<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 9:56 AM
 */

namespace Snp\JWT\Middleware;

/**
 * Class RefreshTokenOnExpiration
 * @package Snp\JWT\Middleware
 */
class RefreshTokenOnExpiration
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {



        $response = $next($request);

    }
}