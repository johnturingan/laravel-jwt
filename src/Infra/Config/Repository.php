<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 11:43 AM
 */

namespace Snp\JWT\Infra\Config;


interface Repository
{

    /**
     * @param $key
     * @return string
     */
    public function get($key);

}