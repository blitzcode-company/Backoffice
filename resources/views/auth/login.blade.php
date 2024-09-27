<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blitzvideo - Backoffice</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('img/Blitzvideo.png') }}" alt="Logo" class="logo">
        </div>
        <h3 class="login-text">Inciar sesión</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="ej: dvega"
                    required>
            </div>
            <div class="form-group password-container">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="password"
                    required>
                <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
            <button type="submit" class="btn btn-primary">Acceder</button>
        </form>
        @if ($errors->any())
            <div class="alert alert-danger text-center"
                style="width: 80%; margin: 10px auto; max-width: 400px; padding: 0 1rem;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <footer class="footer">
        <p class="footer-text">Este producto ha sido desarrollado con 💻 y ☕ por <a href="https://blitzcode.com"
                class="footer-link">Blitzcode Company &reg;</a></p>
    </footer>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/togglePasswordVisibility.js') }}"></script>

</body>

</html>
