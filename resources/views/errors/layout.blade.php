<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} — GoToEat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9ff;
            color: #0b1c30;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .container { text-align: center; max-width: 480px; }
        .icon-wrap {
            width: 80px; height: 80px; border-radius: 20px;
            background: #fff7ed; border: 2px solid #fed7aa;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .material-symbols-outlined { font-size: 40px; color: #f97316; }
        .code {
            font-family: 'Sora', sans-serif;
            font-size: 80px; font-weight: 800; line-height: 1;
            color: #f97316; margin-bottom: 8px;
        }
        h1 { font-family: 'Sora', sans-serif; font-size: 24px; font-weight: 700; margin-bottom: 12px; }
        p  { font-size: 15px; color: #584237; line-height: 1.6; margin-bottom: 28px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 28px; background: #f97316; color: #fff;
            border-radius: 12px; font-weight: 700; font-size: 14px;
            text-decoration: none; transition: opacity .15s;
        }
        .btn:hover { opacity: .9; }
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 28px; background: transparent; color: #584237;
            border: 2px solid #e0c0b1; border-radius: 12px;
            font-weight: 600; font-size: 14px;
            text-decoration: none; margin-left: 12px; transition: background .15s;
        }
        .btn-ghost:hover { background: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrap">
            <span class="material-symbols-outlined">{{ $icon }}</span>
        </div>
        <div class="code">{{ $code }}</div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a href="{{ url('/') }}" class="btn">
            <span class="material-symbols-outlined" style="font-size:18px">home</span>
            Volver al inicio
        </a>
        <a href="javascript:history.back()" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px">arrow_back</span>
            Regresar
        </a>
    </div>
</body>
</html>
