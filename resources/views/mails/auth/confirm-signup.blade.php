@component('mail::message')
# Hello!

Please click the button bellow to verify your email address.<br>

@component('mail::button', ['url' => url('http://localhost:4200/home/auth/confirm-signup?email='.$user->email.'&token='.$token)])
Verify Email Address
@endcomponent

If you did not create and account, further action is required.<br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
