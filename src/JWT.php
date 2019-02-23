<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:41 AM
 */

namespace Snp\JWT;

use Snp\JWT\Encryption\JOSE;
use Snp\JWT\Exceptions\JWTException;
use Snp\JWT\Exceptions\TokenBlacklistedException;
use Snp\JWT\Infra\Request\IRequest;


/**
 * Class JWE
 * @package Snp\JWT
 */
class JWT implements JWTInterface
{


    /**
     * @var IRequest
     */
    private $request;

    /**
     * @var PayloadFactory
     */
    private $payloadFactory;

    /**
     * @var JOSE
     */
    private $jose;

    /**
     * @var BlackListService
     */
    private $blackListService;

    /**
     * @var bool
     */
    private $enableBlackListing = true;

    /**
     * @var bool
     */
    protected $refreshFlow = false;


    /**
     * JWT constructor.
     * @param IRequest $request
     * @param PayloadFactory $payloadFactory
     * @param BlackListService $blackListService
     * @param JOSE $jose
     */
    function __construct(IRequest $request,
                         PayloadFactory $payloadFactory,
                         BlackListService $blackListService,
                         JOSE $jose)
    {
        $this->request = $request;
        $this->payloadFactory = $payloadFactory;
        $this->blackListService = $blackListService;
        $this->jose = $jose;
    }

    /**
     * @param string $method
     * @param string $header
     * @param string $query
     * @return Token
     * @throws JWTException
     */
    public function getToken ($method = 'bearer', $header = 'authorization', $query = 'token')
    {
        if (! $token = $this->parseAuthHeader($header, $method)) {
            if (! $token = $this->request->query($query, false)) {
                throw new JWTException('The token could not be parsed from the request', 400);
            }
        }

        return new Token($token);
    }

    /**
     * Parse token from the authorization header.
     *
     * @param string $header
     * @param string $method
     *
     * @return false|string
     */
    protected function parseAuthHeader($header = 'authorization', $method = 'bearer')
    {
        $header = $this->request->headers($header);

        if (! starts_with(strtolower($header), strtolower($method))) {
            return false;
        }

        return trim(str_ireplace($method, '', $header));
    }


    /**
     * @param array $claims
     * @return Token
     */
    public function createToken (array $claims) : Token
    {

        $payload = $this->payloadFactory->make($claims);

        $token = $this->jose->encode($payload);

        return new Token($token);

    }

    /**
     * @param Token|string $token
     * @return Payload
     * @throws TokenBlacklistedException
     */
    public function decode ($token) : Payload
    {
        $token = $this->resolveToken($token);

        $claims = $this->jose->decode($token);

        if ($this->enableBlackListing && $this->blackListService->verify($claims)) {

            throw new TokenBlacklistedException('The token has been blacklisted');

        }

        return $this->payloadFactory
            ->setRefreshFlow($this->refreshFlow)
            ->make($claims)
            ;
    }

    /**
     * @param Token|string $token
     * @return Payload
     */
    public function getPayload ($token) : Payload
    {

        $token = $this->resolveToken($token);

        $claims = $this->jose->decode($token);

        return $this->payloadFactory
            ->skipValidation()
            ->make($claims);

    }

    /**
     * @param Token|string $token
     * @return array
     */
    public function getPayloadArray ($token) : array
    {

        $token = $this->resolveToken($token);

        return $this->jose->decode($token);

    }

    /**
     * @param Token|string $token
     * @return Token
     */
    public function refresh ($token) : Token
    {

        $token = $this->resolveToken($token);

        $payload = $this
            ->setRefreshFlow()
            ->decode($token);

        if ($this->enableBlackListing) {

            $this->blackListService->blacklist($payload);

        }

        return $this->createToken([
            'iat' => $payload->get('iat')
        ]);

    }

    /**
     * Invalidate a Token by adding it to the blacklist.
     *
     * @param Token|string $token
     * @throws JWTException
     * @return bool
     */
    public function invalidate($token) : bool
    {

        $token = $this->resolveToken($token);

        if (! $this->enableBlackListing) {

            throw new JWTException('You must enable blacklisting to invalidate token');

        }

        return $this->blackListService->blacklist($this->getPayload($token));
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function enableBlackListing(bool $bool)
    {

        $this->enableBlackListing = $bool;

        return $this;
    }

    /**
     * Make sure that token in an instance of Token
     *
     * @param Token|string $token
     * @return Token
     */
    private function resolveToken ($token)
    {

        if (! ($token instanceof Token)) {

            $token = new Token($token);
        }

        return $token;

    }

    /**
     * Set the refresh flow.
     *
     * @param bool $refreshFlow
     * @return $this
     */
    public function setRefreshFlow($refreshFlow = true)
    {
        $this->refreshFlow = $refreshFlow;

        return $this;
    }

}