<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 10:07 AM
 */

namespace Snp\JWT;

/**
 * Interface JWT
 * @package Snp\JWT\Encryption
 */
interface JWTInterface
{

    /**
     * @param string $method
     * @param string $header
     * @param string $query
     * @return Token
     */
    public function getToken ($method = 'bearer', $header = 'authorization', $query = 'token');

    /**
     * @param array $claims
     * @return Token
     */
    public function createToken (array $claims) : Token;
    /**
     * @param Token|string $token
     * @return Payload
     */
    public function decode ($token) : Payload;

    /**
     * @param Token|string $token
     * @return Payload
     */
    public function getPayload ($token) : Payload;

    /**
     * @param Token|string $token
     * @return array
     */
    public function getPayloadArray ($token) : array;

    /**
     * @param Token|string $token
     * @return Token
     */
    public function refresh ($token) : Token;

    /**
     * Invalidate a Token by adding it to the blacklist.
     *
     * @param  $token
     * @return bool
     */
    public function invalidate($token) : bool;

    /**
     * @param bool $bool
     * @return $this
     */
    public function enableBlackListing(bool $bool);

    /**
     * Set the refresh flow.
     *
     * @param bool $refreshFlow
     * @return $this
     */
    public function setRefreshFlow($refreshFlow = true);

}