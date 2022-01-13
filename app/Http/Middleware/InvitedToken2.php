<?php

namespace App\Http\Middleware;

use App\Traits\JwtUtils;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class InvitedToken2 extends BaseMiddleware
{
    use JwtUtils;

    public function handle(Request $request, Closure $next)
    {
        try {
            if ($token = JWTAuth::getToken()) {
                JWTAuth::setToken($token)->checkOrFail();
                return $next($request);
            }
            $token = $this->newToken();
        } catch (TokenExpiredException $e) {
            $token = Auth::refresh();
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responseHandle($request, $next, $token);
    }

    protected function responseHandle($request, $next, $token = null)
    {
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $response = $next($request);

        $bodyContent = (array) json_decode($response->getContent(), true);

        $content = $this->array_merge_recursive_distinct($bodyContent, [
            'meta' => array('token' => $this->getFormatInvitedToken($token))
        ]);

        $response->headers->set('Content-Type', 'application/json');

        return $response->setContent(json_encode($content));
    }

    protected function getFormatInvitedToken($token)
    {
        return [
            "type" => "bearer",
            "value" => $token,
            "expires" => JWTAuth::setToken($token)->factory()->getTTL() * 60
        ];
    }

    function array_merge_recursive_distinct()
    {
        $arrays = func_get_args();
        $base = array_shift($arrays);
        if (!is_array($base)) {
            $base = empty($base) ? array() : array($base);
        }
        foreach ($arrays as $append) {
            if (!is_array($append)) {
                $append = array($append);
            }
            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) and !is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (is_array($value) or is_array($base[$key])) {
                    $base[$key] = array_merge_recursive_distinct($base[$key], $append[$key]);
                } else {
                    if (is_numeric($key)) {
                        if (!in_array($value, $base)) {
                            $base[] = $value;
                        }
                    } else {
                        $base[$key] = $value;
                    }
                }
            }
        }
        return $base;
    }
}
