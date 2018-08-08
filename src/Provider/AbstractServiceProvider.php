<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 31/05/2018
 * Time: 5:49 PM
 */

namespace Snp\JWT\Provider;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Snp\JWT\BlackListService;
use Snp\JWT\Claims\Factory as ClaimFactory;
use Snp\JWT\Encryption\JOSE;
use Snp\JWT\Infra\Config\Repository as ConfigRepository;
use Snp\JWT\Infra\Request\IlluminateRequest;
use Snp\JWT\Infra\Request\IRequest;
use Snp\JWT\Infra\Storage\Repository as Storage;
use Snp\JWT\JWT;
use Snp\JWT\JWTInterface;
use Snp\JWT\PayloadFactory;
use Snp\JWT\Validation\IValidator;
use Snp\JWT\Validation\PayloadValidator;

/**
 * Class AbstractServiceProvider
 * @package Snp\JWT\Provider
 */
abstract class AbstractServiceProvider extends IlluminateServiceProvider
{

    private $config_prefix = 'jwt';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__.'/../config/jwt.php', 'jwt'
        );

        $this->app->singleton(
            IRequest::class,
            IlluminateRequest::class
        );

        $this->app->singleton(ConfigRepository::class, function ($app){

            return $app->make(config($this->config_prefix. '.adapters.config'));

        });

        $this->app->singleton(Storage::class, function ($app){

            return $app->make(config($this->config_prefix. '.adapters.storage'));

        });

        $this->app->singleton( JOSE::class, function ($app) {

            return $app->make(config($this->config_prefix . '.adapters.jose'));

        });

        $this->app->singleton( IValidator::class, function  ($app){

            return $app->make(PayloadValidator::class)->setRefreshTTL(config($this->config_prefix . '.refresh_ttl'));

        });

        $this->app->singleton(PayloadFactory::class, function ($app) {

            $payloadFactory = new PayloadFactory(
                $app[ClaimFactory::class],
                $app[IValidator::class],
                $app[IRequest::class]
            );

            return $payloadFactory
                ->setTTL( (int) config($this->config_prefix. '.ttl'))
                ->setNbfGracePeriod((int) config($this->config_prefix. '.grace_period.nbf'))
                ;

        });

        $this->app->singleton(BlackListService::class, function  ($app){

            $service = new BlackListService(
                $app[Storage::class]
            );

            return $service
                ->setRefreshTTL(config($this->config_prefix . '.refresh_ttl'))
                ->setBlackListGracePeriod(config($this->config_prefix . '.grace_period.blacklist'))
                ;

        });

        $this->app->singleton(JWTInterface::class, function ($app) {

            $instance = new JWT(
                $app[IRequest::class],
                $app[PayloadFactory::class],
                $app[BlackListService::class],
                $app[JOSE::class]
            );

            return $instance
                ->enableBlackListing((bool) config($this->config_prefix . '.enable_blacklisting'));

        });

    }

}