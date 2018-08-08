<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:17 AM
 */

namespace Snp\JWT\Claims;

/**
 * Class Jti
 * @meaning JWT ID
 * @package Snp\JWT\Claims
 */
class Jti extends Claim
{

    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'jti';
}