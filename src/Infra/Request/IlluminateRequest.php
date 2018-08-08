<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 6:43 PM
 */

namespace Snp\JWT\Infra\Request;


use Illuminate\Http\Request;

/**
 * Class IlluminateRequest
 * @package Snp\JWT\Infra\Request
 */
class IlluminateRequest implements IRequest
{


    /**
     * @return string
     */
    public function url()
    {

        try {

            return $this->getIlluminateRequest()->url();

        } catch (\Exception $e) {}

        return '';

    }

    /**
     * Get Header by Name
     *
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function headers($name, $default = null)
    {

        return $this->getIlluminateRequest()->headers->get($name, $default);

    }

    /**
     * @param null $key
     * @param null $default
     * @return mixed
     */
    public function query($key = null, $default = null)
    {
        try {

            return $this->getIlluminateRequest()->query($key, $default);

        } catch (\Exception $e) {}

        return $default;

    }

    private function getIlluminateRequest ()
    {
        return app()->make(Request::class);
    }
}