<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 25/05/2018
 * Time: 6:32 PM
 */

namespace Snp\JWT\Claims;

use JsonSerializable;
use Snp\JWT\Exceptions\ClaimInvalidException;

/**
 * Class Collection
 * @package Snp\JWT\Claims
 */
class Collection implements JsonSerializable
{

    /**
     * Array collection of Claims
     * @var array
     */
    private $claims = [];


    /**
     * @param $claim
     * @return $this
     * @throws ClaimInvalidException
     */
    public function add ($claim)
    {

        $add = function ($claim) {

            if (! ($claim instanceof Claim)) {

                throw new ClaimInvalidException('Claim is not an instance of Snp\JWT\Claims\Claim');

            }

            $this->claims[] = $claim;

        };

        if (is_array($claim)) {

            foreach ($claim as $c) {

                $add($c);

            }

        } else {

            $add($claim);

        }

        return $this;
    }

    /**
     * Get Array of Claims
     * @return array
     */
    public function all()
    {
        return $this->claims;
    }

    /**
     * Get Array Representation of Object
     * @return array
     */
    public function toArray()
    {

        $claim_array = [];

        /**
         * @var $claim Claim
         */
        foreach ($this->claims as $claim) {

            $claim_array[] = [
                'name' => $claim->getName(),
                'value' => $claim->getValue()
            ];
        }

        return $claim_array;
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return json_encode($this->claims);
    }
}