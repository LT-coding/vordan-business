{{-- resources/views/emails/user_password.blade.php --}}

<p>Hello {{ $user->email }},</p>

<p>Your account password has been set to: <strong>{{ $password }}</strong></p>

@if(count($businesses) > 0)
    <p>Your account is linked to the following businesses:</p>
    <ul>
        @foreach($businesses as $business)
            <li>{{ $business->company_name }}</li>
        @endforeach
    </ul>
@else
    <p>Your account is not currently linked to any businesses.</p>
@endif

<p>You can log in and change your password after verification.</p>

<p>Regards,<br> Your Application Team</p>