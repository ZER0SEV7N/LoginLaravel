<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Reset Password - ZER0</title>
</head>
<body class="{{ session('theme') == 'dark' ? 'dark-mode' : '' }}">
    
    <div class="container">
        <div class="form-box">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <h1>Reset Password</h1>
                <p style="margin: 15px 0;">Create a new secure password.</p>

                @if ($errors->any())
                    <div style="color: #ff4d4d; font-size: 13px; margin-bottom: 15px;">{{ $errors->first() }}</div>
                @endif

                <input type="hidden" name="token" value="{{ request()->query('token') }}">
                <input type="hidden" name="email" value="{{ request()->query('email') }}">

                <div class="input-box">
                    <input type="password" name="password" placeholder="New Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
                    <i class='bx bxs-check-shield'></i>
                </div>
                
                <button type="submit" class="btn">Save Password</button>
            </form> 
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Almost Ready</h1>
                <p>Make sure to use a password that you don't use on other websites.</p>
            </div>
        </div>
    </div>

</body>
</html>