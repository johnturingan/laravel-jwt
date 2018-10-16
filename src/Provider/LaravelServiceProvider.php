<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 30/05/2018
 * Time: 9:34 AM
 */

namespace Snp\JWT\Provider;



/**
 * Class ServiceProvider
 * @package Snp\JWT\Provider
 */
class LaravelServiceProvider extends AbstractServiceProvider
{

    private $config_prefix = 'jwt';

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

        // setup publishing of config
        $this->publishes([
            __DIR__.'/../config/jwt.php' => config_path($this->config_prefix . '.php'),
        ], 'config');

    }



}