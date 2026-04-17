<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Recover Password - ZER0</title>
</head>
<body class="{{ session('theme') == 'dark' ? 'dark-mode' : '' }}">
    
    <div class="container">
        <div class="form-box">
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <h1>Recovery password</h1>
                <p style="margin: 15px 0;">Enter your email and we will send you a link to reset your password.</p>

                @if (session('success'))
                    <div style="color: #4CAF50; font-size: 14px; margin-bottom: 15px;">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div style="color: #ff4d4d; font-size: 14px; margin-bottom: 15px;">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div style="color: #ff4d4d; font-size: 14px; margin-bottom: 15px;">{{ $errors->first() }}</div>
                @endif

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                
                <button type="submit" class="btn">Send Link</button>
                
                <div style="margin-top: 20px;">
                    <a href="{{ route('login') }}" style="color: var(--text-muted); text-decoration: none; font-size: 14.5px;">Back to Login</a>
                </div>
            </form> 
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Do not worry</h1>
                <p>You can recovery your account with some clicks</p>
            </div>
        </div>
    </div>

</body>
</html>