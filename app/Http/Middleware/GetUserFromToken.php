<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class GetUserFromToken extends BaseMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (!$token = $this->auth->setRequest($request)->getToken()) {
      throw new JWTException('token_not_provided', 400);
    }

    if (!$user = $this->auth->authenticate($token)) {
      throw new JWTException('user_not_found', 401);
    }

    $this->events->fire('tymon.jwt.valid', $user);
    return $next($request);
  }
}
