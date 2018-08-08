<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 11:42 AM
 */

namespace Snp\JWT\Infra\Config;

use Illuminate\Contracts\Config\Repository as IlluminateConfigRepository;

/**
 * Class IlluminateConfig
 * @package Snp\JWT\Infra\Config
 */
class IlluminateConfig implements Repository
{

    /**
     * @var IlluminateConfigRepository
     */
    private $config;

    /**
     * IlluminateConfig constructor.
     * @param IlluminateConfigRepository $config
     */
    function __construct(IlluminateConfigRepository $config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return $this->config->get("jwt.$key");
    }
}