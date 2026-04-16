<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Complete Profile</title>
</head>
<body class="{{ session('theme') == 'dark' ? 'dark-mode' : '' }}">
    <div class="container">
        <div class="form-box">
            <form action="{{ route('web.complete_profile') }}" method="POST">
                @csrf
                <h1>Complete Your Profile</h1>
                <p style="margin-bottom: 20px;">Please fill in your details to complete your profile.</p>

                //Display validation errors if any
                @if ($errors->any())
                    <div style="color: #ff4d4d; font-size: 13px; margin-bottom: 15px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="input-group">
                    <div class="input-box">
                        <input type="text" name="lastname" placeholder="Apellido" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input type="text" name="username" placeholder="Username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                </div>

                <div class="input-box">
                    <input type="text" name="document" placeholder="Documento de Identidad" required>
                    <i class='bx bxs-id-card'></i>
                </div>
                <div class="input-box">
                    <input type="tel" name="phone" placeholder="Teléfono" required>
                    <i class='bx bxs-phone'></i>
                </div>
                
                <button type="submit" class="btn">Complete Profile</button>
            </form> 
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
                <p>We're glad to have you here. 
                    We just need a few additional details to secure your account and give you full access to the system.</p>
            </div>
        </div>
    </div>
    
</body>
</html>