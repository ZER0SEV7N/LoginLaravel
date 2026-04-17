<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Dashboard - ZER0</title>
</head>
<body class="{{ session('theme') == 'dark' ? 'dark-mode' : '' }}">
    <div class="container" style="display: flex; justify-content: center; align-items: center; padding: 40px; text-align: center;">
        
        <div style="width: 100%; max-width: 400px;">
            <i class='bx bx-user-circle' style="font-size: 100px; color: var(--primary-color); margin-bottom: 20px;"></i>
            
            <h1 style="margin-bottom: 10px;">Welcome, {{ auth()->user()->name }}!</h1>
            
            <div style="background: var(--input-bg); padding: 15px; border-radius: 8px; margin-bottom: 30px; text-align: left;">
                <p><strong><i class='bx bx-envelope'></i> Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong><i class='bx bx-user'></i> Username:</strong> {{ auth()->user()->username ?? 'No definido' }}</p>
                <p><strong><i class='bx bx-id-card'></i> Documento:</strong> {{ auth()->user()->document ?? 'No definido' }}</p>
            </div>

            <form action="{{ route('web.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn" style="background: #ff4d4d;">Logout</button>
            </form>
        </div>

    </div>

</body>
<script src="app.js"></script>
</html>