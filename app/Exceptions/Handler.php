<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //
        // });

        // $this->renderable((function (ModelNotFoundException $e, $request) {
        //     return response()->json(['message' => 'demo'], $e->getCode());
        // }));

        /**
         * rcastro
         * ValidationException
         */
        $this->renderable(function (ValidationException $e, $request) {
            // return response()->json(['message' => $e->getMessage(), 'errors' => $e->validator->getMessageBag(), 'request' => request()->input(), 'method' => request()->method()], 422);
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->validator->getMessageBag()], 422);
        });

        /**
         * rcastro
         * JWT auth
         */
        $this->renderable(function (TokenInvalidException $e, $request) {
            return response()->json(['message' => 'Invalid token'], 401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return response()->json(['message' => 'Token has Expired'], 401);
        });
        $this->renderable(function (TokenBlacklistedException $e, $request) {
            return response()->json(['message' => 'The token has been blacklisted'], 401);
        });
        $this->renderable(function (JWTException $e, $request) {
            // return response()->json(['message' => $e->getMessage(), 'request' => request()->only(['email', 'password'])], 401);
            return response()->json(['message' => $e->getMessage()], 401);
        });

        /**
         * rcastro
         * HttpException
         */
        $this->renderable(function (HttpException $e, $request) {
            /**
             * 401: unauthorized
             * 404: not found
             */
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        });

        /**
         * rcastro
         * GENERAL
         */
        $this->renderable(function (Exception $e, $request) {
            // $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        });
    }
}
