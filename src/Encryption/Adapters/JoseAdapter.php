<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 10:25 AM
 */

namespace Snp\JWT\Encryption\Adapters;

/**
 * Class Jose
 * @package Snp\JWT\Encryption\Adapters
 */
abstract class JoseAdapter
{

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $algo;

    /**
     * @param string  $secret
     * @param string  $algo
     */
    public function __construct($secret, $algo = 'HS256')
    {
        $this->secret = $secret;
        $this->algo = $algo;
    }

    /**
     * Set the algorithm used to sign the token.
     *
     * @param  string  $algo
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
    public function getAlgo()
    {
        return $this->algo;
    }

}