<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 30/05/2018
 * Time: 9:34 AM
 */

namespace Snp\JWT\Provider;


/**
 * Class LumenServiceProvider
 * @package Snp\JWT\Provider
 */
class LumenServiceProvider extends AbstractServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot Up Provider
     */
    public function boot ()
    {

        $this->app->configure('jwt');

        $path = realpath(__DIR__.'/../config/jwt.php');

        $this->mergeConfigFrom($path, 'jwt');

    }



}