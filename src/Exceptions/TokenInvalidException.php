<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snp\JWT\Exceptions;

/**
 * Class TokenInvalidException
 * @package Snp\JWT\Exceptions
 */
class TokenInvalidException extends JWTException
{
    /**
     * @var int
     */
    protected $statusCode = 400;

    /**
     * @var string
     */
    protected $errorCode = 'jwt.1005';
}
