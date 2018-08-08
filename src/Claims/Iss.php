<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:18 AM
 */

namespace Snp\JWT\Claims;

/**
 * Class Iss
 * @meaning Issuer
 * @package Snp\JWT\Claims
 */
class Iss extends Claim
{

    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'iss';
}