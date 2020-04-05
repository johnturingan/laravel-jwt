<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 30/05/2018
 * Time: 8:57 AM
 */

namespace Snp\JWT\Validation;


use Snp\JWT\Claims\Claim;
use Snp\JWT\Exceptions\TokenExpiredException;
use Snp\JWT\Exceptions\TokenInvalidException;
use Snp\JWT\Payload;
use Snp\JWT\Utils;

/**
 * Class PayloadValidator
 * @package Snp\JWT\Validation
 */
class PayloadValidator implements IValidator
{

    /**
     * @var bool
     */
    protected $refreshFlow = false;

    /**
     * @var int
     */
    protected $refreshTTL = 20160;

    /**
     * Do Some check on the value
     *
     * @param Payload $value
     * @return void
     * @throws TokenExpiredException
     * @throws TokenInvalidException
     */
    public function validate($value)
    {

        $this->validateStructure($value);

        if ($this->refreshFlow) {

            $this->validateRefresh($value->toArray());

        } else {

            $this->validateTimestamps($value->toArray());

        }
    }

    /**
     * Ensure the payload contains the required claims and
     * the claims have the relevant type.
     *
     * @param Payload  $payload
     * @throws TokenInvalidException
     * @return bool
     */
    protected function validateStructure(Payload $payload)
    {
        $payload_array = $payload->toArray();

        if (count(array_diff_key(Claim::REQUIRED_CLAIMS, array_keys($payload_array))) !== 0) {

            throw new TokenInvalidException('JWT payload does not contain the required claims');

        }

        return true;
    }

    /**
     * Validate the payload timestamps.
     *
     * @param array $payload
     * @return bool
     * @throws TokenExpiredException
     * @throws TokenInvalidException
     */
    protected function validateTimestamps(array $payload)
    {
        if (isset($payload['nbf']) && Utils::timestamp($payload['nbf'])->isFuture()) {
            throw new TokenInvalidException('Not Before (nbf) timestamp cannot be in the future', 400);
        }

        if (isset($payload['iat']) && Utils::timestamp($payload['iat'])->isFuture()) {
            throw new TokenInvalidException('Issued At (iat) timestamp cannot be in the future', 400);
        }

        if (Utils::timestamp($payload['exp'])->isPast()) {
            throw new TokenExpiredException('Token has expired');
        }

        return true;
    }

    /**
     * Check the token in the refresh flow context.
     *
     * @param array $payload
     * @return bool
     * @throws TokenExpiredException
     */
    protected function validateRefresh(array $payload)
    {
        if (isset($payload['iat']) && Utils::timestamp($payload['iat'])->addMinutes($this->refreshTTL)->isPast()) {
            throw new TokenExpiredException('Token has expired and can no longer be refreshed', 400);
        }

        return true;
    }

    /**
     * Set the refresh ttl.
     *
     * @param int  $ttl
     * @return $this
     */
    public function setRefreshTTL($ttl)
    {
        $this->refreshTTL = $ttl;

        return $this;
    }

    /**
     * Set the refresh flow flag.
     *
     * @param  bool  $refreshFlow
     * @return $this
     */
    public function setRefreshFlow($refreshFlow = true)
    {
        $this->refreshFlow = $refreshFlow;

        return $this;
    }

}