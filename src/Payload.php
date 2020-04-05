<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 25/05/2018
 * Time: 4:56 PM
 */

namespace Snp\JWT;

use Snp\JWT\Claims\Claim;
use Snp\JWT\Claims\Collection as ClaimCollection;
use Snp\JWT\Validation\IValidator;


/**
 * Class Payload
 * @package Snp\JWT
 */
class Payload
{

    /**
     * @var $claims ClaimCollection
     */
    private $claims;

    /**
     * Payload constructor.
     * @param ClaimCollection $claims
     * @param IValidator $validator
     * @param bool $refreshFlow
     * @param bool $skipValidation
     */
    function __construct(ClaimCollection $claims,
                         IValidator $validator,
                         bool $refreshFlow,
                         bool $skipValidation = false)
    {

        $this->claims = $claims;

        if (! $skipValidation) {

            $validator->setRefreshFlow($refreshFlow)
                ->validate($this);
        }


    }

    /**
     * Get Value based on key
     *
     * @param $claim
     * @return mixed
     */
    public function get($claim)
    {
        $arr = $this->toArray();

        return $arr[$claim] ?? '';
    }

    /**
     * Get the array of claims.
     *
     * @return array
     */
    public function toArray()
    {
        $results = [];

        $claims = $this->claims->all();

        /**
         * @var $claim Claim
         */
        foreach ($claims as $claim) {

            $results[$claim->getName()] = $claim->getValue();
        }

        return $results;
    }
}