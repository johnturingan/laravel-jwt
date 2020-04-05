<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 10:25 AM
 */

namespace Snp\JWT\Encryption\Adapters;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A128CBCHS256;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A128GCM;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A192CBCHS384;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A192GCM;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256CBCHS512;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
use Jose\Component\Encryption\Algorithm\KeyEncryption\A128GCMKW;
use Jose\Component\Encryption\Algorithm\KeyEncryption\A192GCMKW;
use Jose\Component\Encryption\Algorithm\KeyEncryption\A256GCMKW;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\Algorithm\HS384;
use Jose\Component\Signature\Algorithm\HS512;

/**
 * Class Jose
 * @package Snp\JWT\Encryption\Adapters
 */
abstract class JoseAdapter
{

    /** @var array  */
    protected $supported_algo = [
        'HS256' => HS256::class,
        'HS384' => HS384::class,
        'HS512' => HS512::class,

        'A128GCMKW' => A128GCMKW::class,
        'A192GCMKW' => A192GCMKW::class,
        'A256GCMKW' => A256GCMKW::class,

        'A128GCM' => A128GCM::class,
        'A192GCM' => A192GCM::class,
        'A256GCM' => A256GCM::class,

        'A128CBC-HS256' => A128CBCHS256::class,
        'A192CBC-HS384' => A192CBCHS384::class,
        'A256CBC-HS512' => A256CBCHS512::class,
    ];

    /** @var string */
    protected $secret;

    /** @var string */
    protected $key;

    /** @var string */
    protected $algo;

    /** @var string */
    protected $key_algo;

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return $this
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey(string $key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the algorithm used to sign the token.
     *
     * @param string $algo
     * @return self
     */
    public function setAlgo($algo)
    {
        $this->algo = $algo;

        return $this;
    }

    /**
     * Get the algorithm used to sign the token.
     *
     * @return string
     */
    public function getAlgo(): string
    {
        return $this->algo;
    }

    /**
     * @return string
     */
    public function getKeyAlgo(): string
    {
        return $this->key_algo;
    }

    /**
     * @param string $key_algo
     * @return $this
     */
    public function setKeyAlgo(string $key_algo)
    {
        $this->key_algo = $key_algo;

        return $this;
    }

    /**
     * @param $algo
     * @return AlgorithmManager
     */
    public function makeAlgorithmManager ($algo)
    {
        $class = $this->supported_algo[$algo];

        return new AlgorithmManager([
            new $class()
        ]);
    }

}