<?php

namespace App\Traits;

use Carbon\Carbon;
use Tymon\JWTAuth\Claims\Expiration;
use Tymon\JWTAuth\Claims\IssuedAt;
use Tymon\JWTAuth\Claims\Issuer;
use Tymon\JWTAuth\Claims\JwtId;
use Tymon\JWTAuth\Claims\NotBefore;
use Tymon\JWTAuth\Claims\Subject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

trait JwtUtils
{
    public $token = null;

    protected function getPayload()
    {
        if (!$this->token) {
            $this->token = JWTAuth::getToken();
        }

        $payload = [];
        if (count($token_array = explode(".", $this->token)) === 3) {
            list($header, $sPayload, $signature) = $token_array;
            $payload = (array) json_decode(base64_decode($sPayload));
        }

        $payload = array_merge(['id' => $this->newId()], $payload);

        return (object) $payload;
    }

    protected function getPayloadId()
    {
        return $this->getPayload()->id;
    }

    protected function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    protected function newToken($customClaims = null)
    {
        $customClaims = array_merge([
            'iss' => new Issuer('invited'),
            'iat' => new IssuedAt(Carbon::now('UTC')),
            'exp' => new Expiration(Carbon::now('UTC')->addMinutes(60)), // ->addDays(1)
            'nbf' => new NotBefore(Carbon::now('UTC')),
            'sub' => new Subject('invited'),
            'jti' => new JwtId(md5(microtime(true) . mt_Rand())),
            'id' => $this->newId(),
            'us' => 0,
        ], (array) $customClaims);

        $claims = JWTFactory::customClaims($customClaims);
        $payload = JWTFactory::make($claims);

        return JWTAuth::encode($payload)->get();
    }

    protected function newId()
    {
        return md5(microtime(true) . mt_Rand());
    }
}
