<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 30/05/2018
 * Time: 9:44 AM
 */

namespace Snp\JWT\Encryption;

use Snp\JWT\Exceptions\JWTException;
use Snp\JWT\Exceptions\TokenInvalidException;
use Snp\JWT\Payload;
use Snp\JWT\Token;

/**
 * Interface IEncryption
 * @package Snp\JWT\Encryption
 */
interface JOSE
{

    /**
     * Encode Payload to JSON Web Token
     *
     * @param Payload $payload
     * @return string
     * @throws JWTException
     */
    public function encode (Payload $payload);

    /**
     * Decode a JSON Web Token.
     *
     * @param $token
     * @return array
     * @throws TokenInvalidException
     */
    public function decode(Token $token);

}