<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 6:49 PM
 */

namespace Snp\JWT\Claims;

/**
 * Class Factory
 * @package Snp\JWT\Claims
 */
class Factory
{

    /**
     * @var string
     */
    private $ns = 'Snp\JWT\Claims\\';

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function make($name, $value)
    {

        $class = $this->ns . ucfirst($name);

        if (class_exists($class)) {

            return new $class($value);

        }

        return new Custom($name, $value);

    }
}