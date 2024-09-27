<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <title>Página No Encontrada - BlitzVideo API</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .body-404 {
            font-family: 'Nunito', sans-serif;
            background-color: #ffffff;
            color: #001f31;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 100vh !important;
            margin: 0 !important;
        }

        .container-404 {
            text-align: center;
        }

        .logo-404 {
            height: 250px;
            margin-bottom: 20px;
        }

        .title-404 {
            font-size: 2.5rem;
            color: #0eaafd;
        }

        .subtitle {
            font-size: 1.5rem;
            color: #001f31;
        }

        .home-link-404 {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0eaafd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body class="body-404">
    <div class="container-404">
        <img src="{{ asset('img/Blitzvideo.png') }}" alt="BlitzVideo Logo" class="logo-404">
        <div class="title-404">404 - Página No Encontrada</div>
        <div class="subtitle">Lo sentimos, la página que estás buscando no existe.</div>
        <a href="{{ url('/') }}" class="home-link-404 "><i class="fas fa-arrow-left"></i> Volver a la Página
            Principal</a>
    </div>
</body>

</html>
