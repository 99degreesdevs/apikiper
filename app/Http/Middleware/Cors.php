<?php

namespace App\Http\Middleware;


use Closure;

class Cors{
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin','*')
            ->header('Access-Control-Allow-Methods','GET,POST,PUT,DELETE,OPTIONS')
            ->header('Access-Control-Allow-Headers','Origin, Content-Type, X-Auth-Token, X-Auth-Token, X-CSRF-TOKEN, Authorization, X-Requested-With,Accept');

    }
}