@component('mail::message')
# Introduction

Forgot Password.

@component('mail::button', ['url' => url('/api/auth/reset-password?email='.$user->email.'&token='.$token)])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
