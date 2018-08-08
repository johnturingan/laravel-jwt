<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:24 AM
 */

namespace Snp\JWT\Claims;

/**
 * Class Sub
 * @meaning Subject
 * @package Snp\JWT\Claims
 */
class Sub extends Claim
{

    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'sub';
}