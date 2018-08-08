<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snp\JWT;

use Snp\JWT\Exceptions\TokenInvalidException;


/**
 * Class Token
 * @package Snp\JWT
 */
class Token
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new JSON Web Token.
     *
     * Token constructor.
     * @param $value
     * @throws TokenInvalidException
     */
    public function __construct($value)
    {

        if (count(explode('.', $value)) !== 3) {
            throw new TokenInvalidException('Wrong number of segments');
        }

        $this->value = $value;
    }

    /**
     * Get the token.
     *
     * @return string
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Get the token when casting to string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
