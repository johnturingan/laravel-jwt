<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:17 AM
 */

namespace Snp\JWT\Claims;

/**
 * Class Nbf
 * @meaning Not Before Time
 * @package Snp\JWT\Claims
 */
class Nbf extends Claim
{

    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'nbf';

    /**
     * Validate the not before claim.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function validate($value)
    {
        return is_numeric($value);
    }
}