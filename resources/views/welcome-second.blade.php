<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taif Welcome</title>

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
            background: url('{{ asset('pics/bg.png') }}') no-repeat;
            background-position: left;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
             background-size: 140% 100%; /* كانت كبيرة واجد */
            opacity: 0.9;
            background-position: right top;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
             padding: 50px 20px 30px; 
        }

    
        .logo {
            width: 450px; 
    margin-bottom: 25px;
            display: block;
           
        }

            .subtitle {
                font-size: 15px;
        line-height: 1.6;
        color: #6b7280;
        max-width: 260px;
        margin-bottom: auto;
            }

        .actions {
            width: 100%;
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .btn {
            height: 60px; 
        border-radius: 14px;
        font-size: 15px;
          font-size: 18px;
         font-weight: 700;
         border: none;
           cursor: pointer;
           text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-primary {
            background: #2f80ed;
            color: #fff;
            box-shadow: 0 6px 14px rgba(47, 128, 237, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
        }

        .btn-outline {
            background: #fff;
            color: #2f80ed;
            border: 1.5px solid #87d7cc;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .btn-outline:hover {
            transform: translateY(-1px);
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
                background-size: 165% 100%;
                background-position: right top;
            }

            .logo {
                width: 230px;
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

            <div class="actions">
        <a href="{{ route('signup.step1') }}" class="btn btn-primary">Sign up</a>
        <a href="{{ route('login.page') }}" class="btn btn-outline">Login</a>
        </div>

        </div>
    </div>

</body>
</html>