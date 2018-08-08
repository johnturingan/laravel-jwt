<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 10:24 AM
 */

namespace Snp\JWT\Encryption\Adapters;

use Jose\Factory\JWEFactory;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
use Jose\Loader;
use Jose\Object\JWKInterface;
use Snp\JWT\Encryption\JOSE;
use Snp\JWT\Exceptions\JWTException;
use Snp\JWT\Exceptions\TokenInvalidException;
use Snp\JWT\Infra\Config\Repository as ConfigRepository;
use Snp\JWT\Payload;
use Snp\JWT\Token;

/**
 * Class SpomkyJWE
 * @package Snp\JWT\Encryption\Adapters
 */
class SpomkyJWE extends JoseAdapter implements JOSE
{


    /**
     * @var JWKInterface
     */
    private $signatureKey;

    /**
     * @var JWKInterface
     */
    private $encryptionKey;

    /**
     * Spomky constructor.
     * @param ConfigRepository $configRepository
     */
    function __construct(ConfigRepository $configRepository)
    {

        $sign_secret = $configRepository->get('signature_secret');
        $sign_algo = $configRepository->get('signature_algo');

        $encrypt_secret = $configRepository->get('encrypt_secret');
        $encrypt_algo = $configRepository->get('encrypt_algo');

        parent::__construct($sign_secret, $sign_algo);

        $this->signatureKey = $this->buildSignatureKey(
            $sign_secret,
            $sign_algo
        );

        $this->encryptionKey = $this->buildEncryptionKey(
            $encrypt_secret,
            $encrypt_algo
        );
    }

    /**
     * Build signature key
     *
     * @param $secret
     * @param $algo
     * @return \Jose\Object\JWK|\Jose\Object\JWKInterface|\Jose\Object\JWKSet|\Jose\Object\JWKSetInterface
     */
    private function buildSignatureKey ($secret, $algo)
    {
        return JWKFactory::createFromValues([
            'kty' => 'oct',
            'k'   => $secret,
            'alg' => $algo,
        ]);
    }

    /**
     * Build encryption key
     *
     * @param $secret
     * @param $algo
     * @return \Jose\Object\JWK|\Jose\Object\JWKInterface|\Jose\Object\JWKSet|\Jose\Object\JWKSetInterface
     */
    private function buildEncryptionKey($secret, $algo)
    {
        return JWKFactory::createFromValues([
            'kty' => 'oct',
            'k'   => $secret,
            'alg' => $algo,
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

            $token = JWEFactory::createJWEToCompactJSON(
                $claims,
                $this->encryptionKey,
                [
                    'alg' => 'dir',
                    'enc' => $this->encryptionKey->get('alg'),
                    'zip' => 'DEF'
                ]
            );

            return JWSFactory::createJWSToCompactJSON($token, $this->signatureKey, [
                'alg' => $this->signatureKey->get('alg'),
                'zip' => 'DEF'
            ]);

        } catch (\Exception $e) {

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

            $loader = new Loader();

            $verifiedJWS = $loader->loadAndVerifySignatureUsingKey(
                (string)$token,
                $this->signatureKey,
                [ $this->signatureKey->get('alg') ]
            );

            $jwe = $loader->loadAndDecryptUsingKey(
                $verifiedJWS->getPayload(),
                $this->encryptionKey,
                [ 'dir' ],
                [ $this->encryptionKey->get('alg') ]
            );

        } catch (\Exception $e) {

            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage());
        }

        return $jwe->getPayload();

    }

}