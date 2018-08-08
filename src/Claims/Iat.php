<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:18 AM
 */

namespace Snp\JWT\Claims;

/**
 * Class Iat
 * @meaning Issued At
 * @package Snp\JWT\Claims
 */
class Iat extends Claim
{

    /**
     * @var string
     */
    protected $name = 'iat';

    /**
     * @param $value
     * @return bool
     */
    protected function validate ($value)
    {

        return is_numeric($value);
    }
}