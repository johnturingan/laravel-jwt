<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 30/05/2018
 * Time: 8:57 AM
 */

namespace Snp\JWT\Validation;

/**
 * Interface IValidator
 * @package Snp\JWT\Validation
 */
interface IValidator
{

    /**
     * Do Some check on the value
     *
     * @param $value
     * @throws mixed
     * @return bool
     */
    public function validate($value);

    /**
     * Set the refresh flow flag.
     *
     * @param  bool  $refreshFlow
     * @return $this
     */
    public function setRefreshFlow($refreshFlow = true);
}