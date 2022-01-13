<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\Auth\ConfirmSignupMail;
use App\Mail\Auth\ForgotPasswordMail;
use App\Models\User;
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
    const ACCESS_USERNAME = ['username', 'password'];
    const ACCESS_EMAIL = ['email', 'password'];

    const MODEL_WITH = ['photo'];
    const PHOTO_PATH = 'photos/users/';
    const PHOTO_SIZES = [[425, 271], [500, 500]];

    public function login()
    {
        if (!$token = JWTAuth::customClaims(['token' => Str::random(60)])->attempt(request()->only(request()->filled(self::ACCESS_USERNAME) ? self::ACCESS_USERNAME : self::ACCESS_EMAIL))) {
            throw new JWTException('User or password do not exist.');
        }

        $user = JWTAuth::user();

        $this->isValidAccess($user);

        $additional = ['collections' => [], 'meta' => ['message' => 'Successfully logged.', 'token' => $this->getFormatTokenJwt($token)]];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function signup(UserRequest $request)
    {
        $user = ((new UserController)->store($request))->resource;

        $additional = ['meta' => ['message' => 'Created successfully, enter your email account to validate the entered data.']];

        $this->sendConfirmMail($user);

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

        $additional = ['collections' => [], 'meta' => ['message' => 'Successfully confirmed.', 'token' => $this->getFormatTokenJwt($token)]];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function me(Request $request)
    {
        $user = ((new UserController)->show(JWTAuth::user()))->resource;

        $additional = [];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function updateMe(UserRequest $request)
    {
        $user = ((new UserController)->update($request, JWTAuth::user()))->resource;

        $additional = ['meta' => ['message' => 'Updated successfully.']];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function logout()
    {
        Auth::logout();

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

        $additional = ['collections' => [], 'meta' => ['message' => 'Password changed.', 'token' => $this->getFormatTokenJwt($token)]];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function refresh()
    {
        $tokenValue = JWTAuth::getToken()->get();

        list($header, $payload, $signature) = explode(".", $tokenValue);
        $payload = json_decode(base64_decode($payload));

        $now = time();
        $exp = $payload->exp;

        if ($exp < $now) {
            $tokenValue = Auth::refresh();
        }

        return response()->json([
            'meta' => array('token' => $this->getFormatTokenJwt($tokenValue)),
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

    protected function getFormatTokenJwt($token)
    {
        return [
            'type' => 'bearer',
            'value' => $token,
            'expires' => JWTAuth::factory()->getTTL() * 60
        ];
    }
}
