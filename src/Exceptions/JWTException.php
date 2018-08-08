<?php

namespace Snp\JWT\Exceptions;

/**
 * Class JWTException
 * @package Snp\JWT\Exceptions
 */
class JWTException extends \Exception
{
    /**
     * @var int
     */
    protected $statusCode = 500;

    /**
     * @var string
     */
    protected $errorCode = 'jwt.1001';

    /**
     * @param string  $message
     * @param int $statusCode
     */
    public function __construct($message = 'unknown error occurred in processing JWT Auth request', $statusCode = null)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode ?? $this->statusCode;

    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return int the status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }


    /**
     * Get Error Code
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
