<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Document</title>
</head>
<body class="{{ session('theme') == 'dark' ? 'dark-mode' : '' }}">
    <div class="container {{ $errors->has('register') ? 'active' : '' }}">
    <div class="form-box login">
        <form action="{{ route('web.login') }}" method="POST">
            @csrf
            <h1>Login ZER0</h1>
            <div class="input-box">
                <input type="text" name="email" placeholder="Username" required>
                <i class='bx bxs-user' ></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="forgot-link">
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>or login with social platforms</p>
            <div class="social-icons">
                <a href="{{ route('social.redirect', 'google') }}"><i class='bx bxl-google' ></i></a>
                <a href="{{ route('social.redirect', 'github') }}"><i class='bx bxl-github' ></i></a>
            </div>
        </form> 
    </div>

    <div class="form-box register">
        <form action="{{ route('web.register') }}" method="POST">
            @csrf
            <h1>Registration ZER0</h1>
            <div class="name-group">
                <div class="input-box">
                    <input type="text" name="name" placeholder="Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="lastname" placeholder="Last Name" required>
                    <i class='bx bxs-user'></i>
                </div>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="text" name="document" placeholder="Document" required>
                    <i class='bx bxs-id-card'></i>
                </div>
                <div class="input-box">
                    <input type="tel" name="phone" placeholder="Phone" required>
                    <i class='bx bxs-phone'></i>
                </div>
            </div>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user' ></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>
            <button type="submit" class="btn">Register</button>
            <p>or login with social platforms</p>
            <div class="social-icons">
                <a href="{{ route('social.redirect', 'google') }}"><i class='bx bxl-google' ></i></a>
                <a href="{{ route('social.redirect', 'github') }}"><i class='bx bxl-github' ></i></a>
            </div>
        </form> 
    </div>

    <div class="toggle-box">
        <div class="toggle-panel toggle-left">
            <h1>Hello, Welcome</h1>
            <p>Don't have an account?</p>
            <button class="btn register-btn">Register</button>
        </div>
         <div class="toggle-panel toggle-right">
            <h1>Welcome Back!</h1>
            <p>Already have an account?</p>
            <button class="btn login-btn">Login</button>
        </div>
    </div>

 </div>
</body>
</html>