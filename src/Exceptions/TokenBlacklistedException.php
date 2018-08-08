<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 31/05/2018
 * Time: 10:05 AM
 */

namespace Snp\JWT\Exceptions;

/**
 * Class TokenBlacklistedException
 * @package Snp\JWT\Exceptions
 */
class TokenBlacklistedException extends JWTException
{
    /**
     * @var int
     */
    protected $statusCode = 401;

    /**
     * @var string
     */
    protected $errorCode = 'jwt.1003';
}
