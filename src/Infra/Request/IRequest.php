<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 6:43 PM
 */

namespace Snp\JWT\Infra\Request;

/**
 * Interface IRequest
 * @package Snp\JWT\Infra\Request
 */
interface IRequest
{

    /**
     * @return string
     */
    public function url();

    /**
     * Get Header by Name
     *
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function headers($name, $default = null);

    /**
     * @param null $key
     * @param null $default
     * @return mixed
     */
    public function query($key = null, $default = null);
}