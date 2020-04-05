<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 10:24 AM
 */

namespace Snp\JWT\Encryption\Adapters;

use Exception;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\Serializer\CompactSerializer;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Snp\JWT\Encryption\JOSE;
use Snp\JWT\Exceptions\JWTException;
use Snp\JWT\Exceptions\TokenInvalidException;
use Snp\JWT\Infra\Config\Repository as Config;
use Snp\JWT\Payload;
use Snp\JWT\Token;

/**
 * Class SpomkyJWE
 * @package Snp\JWT\Encryption\Adapters
 */
class SpomkyJWE extends JoseAdapter implements JOSE
{

    /**
     * JoseAdapter constructor.
     * @param Config $config
     */
    function __construct(Config $config)
    {

        $this->key = $config->get('signature_key');
        $this->secret = $config->get('encrypt_secret');
        $this->algo = $config->get('encrypt_content_algo');
        $this->key_algo = $config->get('encrypt_key_algo');

    }

    /**
     * @return array
     */
    protected function buildManagers ()
    {

        // The key encryption algorithm manager with the A256KW algorithm.
        $keyEncryptionAlgorithmManager = $this->makeAlgorithmManager($this->key_algo);


        // The content encryption algorithm manager with the A256CBC-HS256 algorithm.
        $contentEncryptionAlgorithmManager = $this->makeAlgorithmManager($this->algo);

        // The compression method manager with the DEF (Deflate) method.
        $compressionMethodManager = new CompressionMethodManager([
            new Deflate(),
        ]);

        return [
            'KEAM' => $keyEncryptionAlgorithmManager,
            'CEAM' => $contentEncryptionAlgorithmManager,
            'CMM' => $compressionMethodManager
        ];
    }

    /**
     * Build signature key
     *
     * @return JWK
     */
    private function buildJWK ()
    {

        return new JWK([
            'kty' => 'oct',
            'kid' => $this->secret,
            'k' => $this->key,
        ]);

    }

    /**
     * Encode Payload to JSON Web Token
     *
     * @param Payload $payload
     * @return string
     * @throws JWTException
     */
    public function encode (Payload $payload)
    {

        $claims = $payload->toArray();

        try {

            $managers = $this->buildManagers();

            // We instantiate our JWE Builder.
            $builder = new JWEBuilder(
                $managers['KEAM'],
                $managers['CEAM'],
                $managers['CMM']
            );


            $jwe = $builder
                ->create()              // We want to create a new JWE
                ->withPayload(json_encode($claims)) // We set the payload
                ->withSharedProtectedHeader([
                    'alg' => $this->key_algo,        // Key Encryption Algorithm
                    'enc' => $this->algo, // Content Encryption Algorithm
                    'zip' => 'DEF'            // We enable the compression (irrelevant as the payload is small, just for the example).
                ])
                ->addRecipient($this->buildJWK())    // We add a recipient (a shared key or public key).
                ->build();

            $serializer = new CompactSerializer(); // The serializer

            return $serializer->serialize($jwe, 0);

        } catch (Exception $e) {

            throw new JWTException('Could not create token: '.$e->getMessage());
        }


    }

    /**
     * Decode a JSON Web Token.
     *
     * @param $token
     * @return mixed
     * @throws TokenInvalidException
     */
    public function decode(Token $token)
    {
        try {

            $managers = $this->buildManagers();

            // We instantiate our JWE Builder.
            $decrypter = new JWEDecrypter(
                $managers['KEAM'],
                $managers['CEAM'],
                $managers['CMM']
            );

            // The serializer manager. We only use the JWE Compact Serialization Mode.
            $serializerManager = new JWESerializerManager([
                new CompactSerializer(),
            ]);

            // We try to load the token.
            $jwe = $serializerManager->unserialize($token->get());

            // We decrypt the token. This method does NOT check the header.
            $success = $decrypter->decryptUsingKey($jwe, $this->buildJWK(), 0);

            if ($success) {

                return json_decode($jwe->getPayload(), true);
            }

            throw new Exception('Decryption unsuccessful');

        } catch (Exception $e) {

            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage());
        }


    }

}