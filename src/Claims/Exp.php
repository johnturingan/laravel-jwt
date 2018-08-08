<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:17 AM
 */

namespace Snp\JWT\Claims;

/**
 * Class Exp
 * @meaning Expiration
 * @package Snp\JWT\Claims
 */
class Exp extends Claim
{

    /**
     * @var string
     */
    protected $name = 'exp';

    /**
     * @param $value
     * @return bool
     */
    protected function validate ($value)
    {

        return  is_numeric($value);
    }
}