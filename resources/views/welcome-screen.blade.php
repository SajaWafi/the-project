<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taif Splash Screen</title>
    <meta http-equiv="refresh" content="3;url={{ route('welcome.second') }}">


  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #edf1f4;
        font-family: Arial, sans-serif;
        padding: 20px;
    }

    .mobile-screen {
         width: 100%;
    max-width: 360px; /* هذا المهم */
    height: 100vh;
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        background: #f8f8f8;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    /* الخلفية كطبقة مستقلة */
    .mobile-screen::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('{{ asset('images/bg.png') }}');
        background-repeat: no-repeat;
        background-size: 155% 100%;
        background-position: left top;
        opacity: 0.95;
        z-index: 0;
    }

    .content {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 0 28px;
        transform: translateY(-20px);
    }

    .logo {
         width: 450px;
        margin-bottom: 55px;
        display: block;
    }

    .subtitle {
        font-size: 15px;
        line-height: 1.8;
        color: #4b5563;
        max-width: 280px;
    }

    @media (max-width: 480px) {
        body {
            padding: 0;
            background: #fff;
        }

        .mobile-screen {
            width: 100%;
            height: 100vh;
            max-width: 100%;
            max-height: 100vh;
            border-radius: 0;
            box-shadow: none;
        }

        .mobile-screen::before {
            background-size: 160% 100%;
            background-position: left top;
        }

        .content {
            transform: translateY(-10px);
        }

        .logo {
            width: 220px;
            margin-bottom: 48px;
        }
    }
</style>
</head>
<body>
<div class="mobile-screen">
    <div class="content">
        <img src="{{ asset('images/taif-logo.png') }}" alt="Taif Logo" class="logo">

        <p class="subtitle">
            Platform for the Care and Tracking
            <br>
            of Children with Autism
        </p>
    </div>
</div>

</body>
</html>