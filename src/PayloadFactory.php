<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 4:46 PM
 */

namespace Snp\JWT;


use Snp\JWT\Claims\Claim;
use Snp\JWT\Claims\Collection;
use Snp\JWT\Claims\Factory as ClaimFactory;
use Snp\JWT\Infra\Request\IRequest;
use Snp\JWT\Validation\IValidator as PayloadValidator;

/**
 * Class PayloadFactory
 * @package Snp\JWT
 */
class PayloadFactory
{

    /**
     * @var ClaimFactory
     */
    private $claimFactory;

    /**
     * @var PayloadValidator
     */
    private $payloadValidator;

    /**
     * @var IRequest
     */
    private $request;

    /**
     * @var int
     */
    protected $ttl = 60;

    /**
     * @var int
     */
    protected $nbfGracePeriod = 0;

    /**
     * @var bool
     */
    protected $refreshFlow = false;

    /**
     * @var bool
     */
    protected $skipValidation = false;

    /**
     * @var array
     */
    protected $required_claims = Claim::REQUIRED_CLAIMS;


    /**
     * @var array
     */
    protected $claims = [];


    /**
     * PayloadFactory constructor.
     * @param ClaimFactory $claimFactory
     * @param PayloadValidator $payloadValidator
     * @param IRequest $request
     */
    function __construct(ClaimFactory $claimFactory,
                         PayloadValidator $payloadValidator,
                         IRequest $request)
    {
        $this->request = $request;
        $this->claimFactory = $claimFactory;
        $this->payloadValidator = $payloadValidator;
    }

    /**
     * Make Payload
     *
     * @param array $claims
     * @return Payload
     */
    public function make (array $claims = [])
    {

        $this->mergeClaims($claims);

        $collection = new Collection();

        foreach ($this->claims as $key => $value) {

            $collection->add($this->claimFactory->make($key, $value));

        }

        return new Payload($collection,
            $this->payloadValidator,
            $this->refreshFlow,
            $this->skipValidation
        );

    }

    /**
     * Whether or not to skip validation
     * @return $this
     */
    public function skipValidation ()
    {
        $this->skipValidation = true;

        return $this;
    }

    /**
     * @param $claims
     * @return array
     */
    protected function mergeClaims ($claims)
    {
        $this->addClaims(array_diff_key($claims, $this->required_claims));

        foreach ($this->required_claims as $claim) {

            if (! array_key_exists($claim, $claims)) {

                $this->addClaim($claim, $this->$claim());

            }

        }

        return $this->claims;
    }

    /**
     * Add an array of claims to the Payload.
     *
     * @param  array  $claims
     * @return $this
     */
    public function addClaims(array $claims)
    {
        foreach ($claims as $name => $value) {
            $this->addClaim($name, $value);
        }

        return $this;
    }

    /**
     * Add a claim to the Payload.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @return $this
     */
    public function addClaim($name, $value)
    {
        $this->claims[$name] = $value;

        return $this;
    }

    /**
     * Set sub if not provided
     *
     * @return string
     */
    protected function sub()
    {
        return $this->request->url();
    }

    /**
     * Set the Issuer (iss) claim.
     *
     * @return string
     */
    public function iss()
    {
        return $this->request->url();
    }

    /**
     * Set the Issued At (iat) claim.
     *
     * @return int
     */
    public function iat()
    {
        return Utils::now()->timestamp;
    }

    /**
     * Set the Expiration (exp) claim.
     *
     * @return int
     */
    public function exp()
    {
        return Utils::now()->addMinutes($this->ttl)->timestamp;
    }

    /**
     * Set the Not Before (nbf) claim.
     *
     * @return int
     */
    public function nbf()
    {
        return Utils::now()->timestamp -
            (int) $this->nbfGracePeriod;
    }

    /**
     * Set a unique id (jti) for the token.
     *
     * @return string
     */
    protected function jti()
    {
        $sub =  $this->claims['sub'] ?? '';
        $nbf = $this->claims['nbf'] ?? '';

        return sha1(sprintf('jti.%s.%s', $sub, $nbf));
    }


    /**
     * Set the token ttl (in minutes).
     *
     * @param  int  $ttl
     * @return $this
     */
    public function setTTL($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get the token ttl.
     *
     * @return int
     */
    public function getTTL()
    {
        return $this->ttl;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setNbfGracePeriod ($value)
    {
        $this->nbfGracePeriod = $value;

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setRefreshFlow($value = true)
    {

        $this->refreshFlow = $value;

        return $this;

    }

}