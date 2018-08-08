<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 25/05/2018
 * Time: 6:44 PM
 */

namespace Snp\JWT\Claims;

use Snp\JWT\Exceptions\ClaimInvalidException;

/**
 * Class Claim
 * @package Snp\JWT\Claims
 */
abstract class Claim
{

    const REQUIRED_CLAIMS = ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'];

    /**
     * The claim name.
     *
     * @var string
     */
    protected $name;

    /**
     * The claim value.
     *
     * @var mixed
     */
    private $value;

    /**
     * @param mixed  $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * @param $value
     * @return $this
     * @throws ClaimInvalidException
     */
    public function setValue($value)
    {

        if (! $this->validate($value)) {

            throw new ClaimInvalidException('Invalid value provided for claim "'.$this->getName().'": '.$value);

        }

        $this->value = $value;

        return $this;
    }

    /**
     * Get the claim value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the claim name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    protected function validate ($value)
    {
        return !empty($value);
    }

}