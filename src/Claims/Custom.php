<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 6:53 PM
 */

namespace Snp\JWT\Claims;


class Custom extends Claim
{

    /**
     * @var string
     */
    protected $name;

    /**
     * Custom constructor.
     * @param $name
     * @param $value
     */
    function __construct($name, $value)
    {

        $this->name = $name;

        parent::__construct($value);

    }

    /**
     * No need to validate Custom
     *
     * @param $value
     * @return bool
     */
    protected function validate ($value)
    {
        return true;
    }

}