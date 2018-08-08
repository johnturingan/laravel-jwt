<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 11:43 AM
 */

namespace Snp\JWT\Infra\Storage;

/**
 * Interface Repository
 * @package Snp\JWT\Infra\Storage
 */
interface Repository
{

    /**
     * Add a new item into storage.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $minutes
     * @return void
     */
    public function add($key, $value, $minutes);

    /**
     * Check whether a key exists in storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key);

    /**
     * Get Entry from the storage
     *
     * @param  string  $key
     * @return bool
     */
    public function get($key);

    /**
     * Remove an item from storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function destroy($key);

    /**
     * Remove all items associated with the tag.
     *
     * @return void
     */
    public function flush();

}