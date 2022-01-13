<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Requests\Auth\ConfirmSignupRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\BaseFormRequest;
use App\Http\Resources\UserResource;
use App\Mail\Auth\ConfirmSignupMail;
use App\Mail\Auth\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    const ACCESS_USERNAME = ['username', 'password'];
    const ACCESS_EMAIL = ['email', 'password'];

    protected function getFormatAuthToken($token)
    {
        return [
            "type" => "bearer",
            "value" => $token,
            "expires" => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    public function login()
    {
        if (!$token = JWTAuth::customClaims(['token' => Str::random(60)])->attempt(request()->only(request()->filled(self::ACCESS_USERNAME) ? self::ACCESS_USERNAME : self::ACCESS_EMAIL))) {
            throw new JWTException('User or password do not exist.');
        }

        $user = JWTAuth::user();

        $this->isUserVerified($user);

        $attributes = ['message' => 'Successfully logged.', 'token' => $this->getFormatAuthToken($token)];

        return (new UserResource($user))->additional(['meta' => $attributes]);
    }

    public function signup(BaseFormRequest $baseFormRequest)
    {
        $user = ((new UserController)->store($baseFormRequest))->resource;

        Mail::to($user->email)->send(new ConfirmSignupMail($user, $this->getResetToken($user->email)));

        $attributes = ['message' => 'Created successfully, enter your email account to validate the entered data.'];
        return (new UserResource($user))->additional(['meta' => $attributes]);
    }

    public function confirmSignup(BaseFormRequest $baseFormRequest)
    {
        $request = $baseFormRequest->convertRequest(ConfirmSignupRequest::class);

        if (!$resetToken = $this->getResetSavedToken(['email' => $request->input('email'), 'token' => $request->input('token')], false)) {
            throw new NotFoundHttpException('Token does not exist.');
        }

        $user = User::where(['email' => $request->input('email')])->first();

        DB::beginTransaction();
        try {
            $user->email_verified_at = Carbon::now();
            $user->status = 1;
            $user->save();
            DB::table('password_resets')->where(['email' => $user->email])->delete();

            $token = Auth::login($user);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $attributes = ['message' => 'Confirmed user.', 'token' => $this->getFormatAuthToken($token)];
        return (new UserResource($user))->additional(['meta' => $attributes]);
    }

    public function forgotPassword(BaseFormRequest $baseFormRequest)
    {
        $request = $baseFormRequest->convertRequest(ForgotPasswordRequest::class);

        $user = User::where(['email' => $request->input('email')])->first();

        $this->isUserVerified($user);

        Mail::to($user->email)->send(new ForgotPasswordMail($user, $this->getResetToken($user->email)));

        //* $attributes = ['message' => 'Reset E-mail is send succesfully, please check your inbox.'];
        //* return (new UserResource($user))->additional(['meta' => $attributes]);

        return response()->json([
            'meta' => [
                'message' => 'Reset E-mail is send succesfully, please check your inbox.',
            ],
        ]);
    }

    public function resetPassword(BaseFormRequest $baseFormRequest)
    {
        $request = $baseFormRequest->convertRequest(ResetPasswordRequest::class);

        if (!$resetToken = $this->getResetSavedToken(['email' => $request->input('email'), 'token' => $request->input('token')])) {
            throw new NotFoundHttpException('Token does not exist.');
        }

        $user = User::where(['email' => $request->input('email')])->first();

        DB::beginTransaction();
        try {
            $user->update(['password' => $request->input('password')]);
            DB::table('password_resets')->where(['email' => $user->email])->delete();

            $token = Auth::login($user);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $attributes = ['message' => 'Password changed.', 'token' => $token];
        return (new UserResource($user))->additional(['meta' => $attributes]);
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
            'meta' => array('token' => $this->getFormatAuthToken($tokenValue)),
        ]);
    }

    public function me()
    {
        return (new UserController)->show(JWTAuth::user());
        // return redirect()->action([UserController::class, 'show'], JWTAuth::user());
        // return redirect()->route('users.show', JWTAuth::user());
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

    public function updateMe(BaseFormRequest $baseFormRequest)
    {
        return (new UserController)->update($baseFormRequest, JWTAuth::user());
    }

    protected function isUserVerified(User $user)
    {
        if (is_null($user->email_verified_at)) {
            Mail::to($user->email)->send(new ConfirmSignupMail($user, $this->getResetToken($user->email)));
            throw new AccessDeniedHttpException('Requires email account confirmation.');
        } elseif (!$user->status) {
            throw new AccessDeniedHttpException('Blocked account.');
        }
    }

    protected function getResetSavedToken($conditions, $isPast = true)
    {
        $row = DB::table('password_resets')->where($conditions);
        if (!!$data = $row->first()) {
            if ($isPast && Carbon::parse($data->created_at)->addSeconds(config('auth.passwords.users.expire') * 60)->isPast()) {
                $row->delete();
            } else {
                return $data->token;
            }
        }
        return null;
    }

    protected function getResetToken($email)
    {
        if (!!$token = $this->getResetSavedToken(['email' => $email])) {
            return $token;
        }

        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }
}
