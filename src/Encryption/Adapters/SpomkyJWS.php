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
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Snp\JWT\Encryption\JOSE;
use Snp\JWT\Exceptions\JWTException;
use Snp\JWT\Exceptions\TokenInvalidException;
use Snp\JWT\Infra\Config\Repository as Config;
use Snp\JWT\Payload;
use Snp\JWT\Token;

/**
 * Class SpomkyJWS
 * @package Snp\JWT\Encryption\Adapters
 */
class SpomkyJWS extends JoseAdapter implements JOSE
{

    /**
     * JoseAdapter constructor.
     * @param Config $config
     */
    function __construct(Config $config)
    {

        $this->secret = $config->get('signature_secret');
        $this->key = $config->get('signature_key');
        $this->algo = $config->get('signature_algo');

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

        $claims = json_encode($payload->toArray());

        // We instantiate our JWS Builder.
        $builder = new JWSBuilder($this->makeAlgorithmManager($this->algo));

        try {

            $jws = $builder
                ->create()                               // We want to create a new JWS
                ->withPayload($claims)                  // We set the payload
                ->addSignature($this->buildJWK(), [
                    'alg' => $this->algo,
                    'zip' => 'DEF'

                ]) // We add a signature with a simple protected header
                ->build();


            $serializer = new CompactSerializer(); // The serializer

            return $serializer->serialize($jws, 0);

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

            // We instantiate our JWS Verifier.
            $verifier = new JWSVerifier($this->makeAlgorithmManager($this->algo));

            $serializerManager = new JWSSerializerManager([
                new CompactSerializer(),
            ]);

            // We try to load the token.
            $jws = $serializerManager->unserialize($token->get());

            // We verify the signature. This method does NOT check the header.
            // The arguments are:
            // - The JWS object,
            // - The key,
            // - The index of the signature to check. See
            $isVerified = $verifier->verifyWithKey($jws, $this->buildJWK(), 0);

            if ($isVerified) {

                return json_decode($jws->getPayload(), true);
            }

            throw new Exception('Decode unsuccessful');

        } catch (Exception $e) {

            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage());
        }

    }

}