<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 11:42 AM
 */

namespace Snp\JWT\Infra\Storage;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * Class IlluminateStorage
 * @package Snp\JWT\Infra\Storage
 */
class IlluminateStorage implements Repository
{

    /**
     * @var $cache CacheRepository
     */
    protected $cache;

    /**
     * IlluminateStorage constructor.
     * @param CacheRepository $cache
     */
    function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Add a new item into storage.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $minutes
     * @return void
     */
    public function add($key, $value, $minutes)
    {
        $this->cache->put($key, $value, $minutes);
    }

    /**
     * Check whether a key exists in storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return $this->cache->has($key);
    }

    /**
     * Get entry from the Storage
     *
     * @param  string  $key
     * @return bool
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * Remove an item from storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function destroy($key)
    {
        return $this->cache->forget($key);
    }

    /**
     * Remove all items associated with the tag.
     *
     * @return void
     */
    public function flush()
    {
        $this->cache->flush();
    }

}