<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
</head>
<body>

    <h2>Verify Your Email</h2>

    <p>We sent a verification code to your email.</p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p style="color: red;">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('verify.email.submit') }}">
        @csrf

        <input
            type="text"
            name="code"
            placeholder="Enter 6 digit code"
            maxlength="6"
            required
        >

        <br><br>

        <button type="submit">Verify</button>
    </form>

    <br>

    <form method="POST" action="{{ route('verify.email.resend') }}">
        @csrf
        <button type="submit">Resend Code</button>
    </form>

</body>
</html>