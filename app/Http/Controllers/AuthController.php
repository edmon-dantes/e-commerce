<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\Auth\ConfirmSignupMail;
use App\Mail\Auth\ForgotPasswordMail;
use App\Models\User;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    const MODEL_WITH = ['picture'];

    public function login(LoginRequest $request)
    {
        if (!$token = JWTAuth::attempt($request['data'])) {
            throw new JWTException('User or password do not exist.');
        }

        $user = JWTAuth::user();

        // $this->isValidAccess($user);

        $additional = ['collections' => [], 'meta' => ['message' => 'Successfully logged.', 'token' => $this->getJwtToken($token)]];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function signup(UserRequest $request, UsersService $service)
    {
        $user = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created, enter your email account to validate the entered data.']];

        // $this->sendConfirmMail($user);

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function confirmSignup(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!$data = DB::table('password_resets')->where(['email' => $request->query('email'), 'token' => $request->query('token')])->first()) {
                throw new NotFoundHttpException('Token does not exist.');
            }

            $user = User::where(['email' => $data->email])->firstOrFail();
            $user->email_verified_at = Carbon::now();
            $user->status = 1;
            $user->save();
            DB::table('password_resets')->where(['email' => $user->email])->delete();

            $token = JWTAuth::customClaims(['token' => Str::random(60)])->fromUser($user);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['collections' => [], 'meta' => ['message' => 'Successfully confirmed.', 'token' => $this->getJwtToken($token)]];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function me(Request $request)
    {
        $additional = ['collections' => []];

        return (new UserResource(JWTAuth::user()->load(self::MODEL_WITH)))->additional($additional);
    }

    public function updateMe(UserRequest $request, UsersService $service)
    {
        $user = $service->update($request, JWTAuth::user());

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function logout()
    {
        try {
            Auth::logout();
        } catch (Throwable $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return response()->json([
            'meta' => [
                'message' => 'Successfully logged out.',
            ],
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where(['email' => $request->input('email')])->firstOrFail();

        $this->isValidAccess($user);

        Mail::to($user->email)->send(new ForgotPasswordMail($user, $this->getAccessToken($user->email)));

        return response()->json(['meta' => ['message' => 'Reset E-mail is send succesfully, please check your inbox.']]);
    }

    public function resetPassword(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!$data = DB::table('password_resets')->where(['email' => $request->input('email'), 'token' => $request->query('token')])->first()) {
                throw new NotFoundHttpException('Token does not exist.');
            }

            $user = User::where(['email' => $data->email])->firstOrFail();
            $user->update(['password' => $request->input('password')]);

            DB::table('password_resets')->where(['email' => $user->email])->delete();

            $token = JWTAuth::customClaims(['token' => Str::random(60)])->fromUser($user);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['collections' => [], 'meta' => ['message' => 'Password changed.', 'token' => $this->getJwtToken($token)]];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
        } catch (Throwable $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return response()->json([
            'meta' => array('token' => $this->getJwtToken($token)),
        ]);
    }

    protected function sendConfirmMail(User $user): void
    {
        Mail::to($user->email)->send(new ConfirmSignupMail($user, $this->getAccessToken($user->email)));
    }

    protected function isValidAccess(User $user): void
    {
        if (is_null($user->email_verified_at)) {
            $this->sendConfirmMail($user);
            throw new AccessDeniedHttpException('Requires email account confirmation.');
        }

        if ($user->status === 0) {
            throw new AccessDeniedHttpException('Disabled account.');
        }
    }

    protected function getAccessToken($email): string
    {
        $row = DB::table('password_resets')->where(['email' => $email]);
        if (!$data = $row->first()) {
            return $this->getGeneratedToken($email);
        }

        if (Carbon::parse($data->created_at)->addSeconds(config('auth.passwords.users.expire') * 60)->isPast()) {
            $row->delete();
            return $this->getGeneratedToken($email);
        }

        return $data->token;
    }

    protected function getGeneratedToken($email): string
    {
        $token = Str::random(60);
        DB::table('password_resets')->insert(['email' => $email, 'token' => $token, 'created_at' => Carbon::now()]);
        return  $token;
    }

    protected function getJwtToken($token)
    {
        return [
            'type' => 'bearer',
            'value' => $token,
            'expires' => JWTAuth::factory()->getTTL() * 60
        ];
    }
}
