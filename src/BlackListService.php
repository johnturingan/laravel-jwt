<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 30/05/2018
 * Time: 6:59 PM
 */

namespace Snp\JWT;

use Snp\JWT\Infra\Storage\Repository as Storage;

/**
 * Class BlackListService
 * @package Snp\JWT
 */
class BlackListService
{

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var int
     */
    private $refreshTTL = 20160;

    /**
     * @var int
     */
    private $blackListGracePeriod = 0;


    /**
     * BlackListService constructor.
     * @param Storage $storage
     */
    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Payload $payload
     * @return bool
     */
    public function blacklist(Payload $payload)
    {

        $exp = Utils::timestamp($payload->get('exp'));

        $refreshExp = Utils::timestamp($payload->get('iat'))->addMinutes($this->refreshTTL);

        // there is no need to add the token to the blacklist
        // if the token has already expired AND the refresh_ttl
        // has gone by
        if ($exp->isPast() && $refreshExp->isPast()) {
            return false;
        }

        // Set the cache entry's lifetime to be equal to the amount
        // of refreshable time it has remaining (which is the larger
        // of `exp` and `iat+refresh_ttl`), rounded up a minute
        $cacheLifetime = $exp->max($refreshExp)->addMinute()->diffInMinutes();

        $this->storage->add($payload->get('jti'), [
            't' => Utils::now()->timestamp
        ], $cacheLifetime);

        return true;

    }

    /**
     * Determine whether the token has been blacklisted.
     *
     * @param $claims_array
     * @return bool
     */
    public function verify($claims_array)
    {

        if ($this->blackListGracePeriod < 1) {

            return $this->storage->has($claims_array['jti']);

        }

        $black = $this->storage->get($claims_array['jti']);

        if ($black) {

            return Utils::timestamp((int)$black['t'])->addSeconds($this->blackListGracePeriod)->isPast();

        }

        return false;
    }

    /**
     * Remove the token (jti claim) from the blacklist.
     *
     * @param $claims_array
     * @return bool
     */
    public function remove($claims_array)
    {
        return $this->storage->destroy($claims_array['jti']);
    }

    /**
     * Set the refresh time limit.
     *
     * @param  int
     *
     * @return $this
     */
    public function setRefreshTTL($ttl)
    {
        $this->refreshTTL = (int) $ttl;

        return $this;
    }

    /**
     * Set the Grace Period for checking blacklisted Token
     *
     * @param $value
     * @return $this
     */
    public function setBlackListGracePeriod ($value)
    {

        $this->blackListGracePeriod = (int) $value;

        return $this;

    }

}