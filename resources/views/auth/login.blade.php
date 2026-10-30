<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIPIS - Login</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            font-family: Arial, Helvetica, sans-serif;

            background: #f4f8fb;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #1f2937;
        }


        .login-container {

            width: 900px;

            max-width: 95%;

            min-height: 530px;

            background: #ffffff;

            border-radius: 30px;

            overflow: hidden;

            display: flex;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, 0.12);
        }


        /* =========================
           KIRI
        ========================= */

        .login-left {

            width: 45%;

            background: #198754;

            color: white;

            padding: 50px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            text-align: center;

            position: relative;

            overflow: hidden;
        }


        .login-left::before {

            content: "";

            position: absolute;

            width: 250px;
            height: 250px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.08);

            top: -80px;
            left: -80px;
        }


        .login-left::after {

            content: "";

            position: absolute;

            width: 300px;
            height: 300px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.06);

            bottom: -150px;
            right: -100px;
        }


        .logo-circle {

            width: 180px;
            height: 180px;

            border-radius: 50%;

            background: #198754;

            border: 5px solid rgba(255, 255, 255, 0.9);

            display: flex;

            align-items: center;
            justify-content: center;

            position: relative;

            z-index: 2;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(0, 0, 0, 0.15);
        }


        .logo-circle img {

            width: 125px;
            height: 125px;

            object-fit: contain;

            display: block;
        }


        .login-left h1 {

            margin: 25px 0 10px;

            font-size: 32px;

            letter-spacing: 1px;

            position: relative;

            z-index: 2;
        }


        .login-left p {

            max-width: 330px;

            margin: 0;

            line-height: 1.7;

            font-size: 14px;

            opacity: 0.9;

            position: relative;

            z-index: 2;
        }


        .login-info {

            margin-top: 25px;

            font-size: 12px;

            opacity: 0.8;

            position: relative;

            z-index: 2;
        }


        /* =========================
           KANAN
        ========================= */

        .login-right {

            width: 55%;

            padding: 50px 60px;

            display: flex;

            flex-direction: column;

            justify-content: center;
        }


        .sipika-title {

            margin: 0 0 5px;

            color: #198754;

            font-size: 30px;

            font-weight: 700;

            letter-spacing: 1px;
        }


        .login-right h2 {

            margin: 0;

            font-size: 25px;

            color: #1f2937;
        }


        .login-subtitle {

            margin: 8px 0 25px;

            color: #777;

            font-size: 14px;
        }


        /* =========================
           ALERT
        ========================= */

        .error {

            background: #f8d7da;

            color: #842029;

            padding: 11px 13px;

            border-radius: 8px;

            margin-bottom: 18px;

            font-size: 14px;
        }


        .success {

            background: #d1e7dd;

            color: #0f5132;

            padding: 11px 13px;

            border-radius: 8px;

            margin-bottom: 18px;

            font-size: 14px;
        }


        /* =========================
           FORM
        ========================= */

        .form-group {

            margin-bottom: 20px;
        }


        label {

            display: block;

            margin-bottom: 8px;

            color: #333;

            font-size: 14px;

            font-weight: 600;
        }


        input {

            width: 100%;

            height: 48px;

            padding: 10px 14px;

            border: 1px solid #d5dce2;

            border-radius: 8px;

            outline: none;

            font-size: 14px;

            background: #fff;

            color: #222;

            transition: 0.2s;
        }


        input:focus {

            border-color: #198754;

            box-shadow:
                0 0 0 3px rgba(25, 135, 84, 0.12);
        }


        button {

            width: 100%;

            height: 48px;

            border: none;

            border-radius: 8px;

            background: #198754;

            color: white;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;

            margin-top: 5px;
        }


        button:hover {

            background: #146c43;
        }


        /* =========================
           REGISTER
        ========================= */

        .register-link {

            text-align: center;

            margin-top: 18px;

            font-size: 13px;

            color: #777;
        }


        .register-link a {

            color: #198754;

            text-decoration: none;

            font-weight: 600;
        }


        .register-link a:hover {

            text-decoration: underline;
        }


        /* =========================
           FOOTER
        ========================= */

        .footer {

            text-align: center;

            margin-top: 20px;

            font-size: 12px;

            color: #999;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            .login-container {

                flex-direction: column;

                min-height: auto;

                max-width: 92%;
            }


            .login-left {

                width: 100%;

                padding: 40px 25px;
            }


            .login-right {

                width: 100%;

                padding: 40px 30px;
            }


            .logo-circle {

                width: 150px;
                height: 150px;
            }


            .logo-circle img {

                width: 105px;
                height: 105px;
            }
        }

    </style>

</head>


<body>


<div class="login-container">


    <!-- KIRI -->

    <div class="login-left">

        <div class="logo-circle">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo MI">

        </div>


        <h1>
            MIS Al-Falahiyyah Rajeg
        </h1>


        <p>

            Sistem Pendataan Infaq Siswa
            untuk membantu pengelolaan data infaq
            dan pengeluaran secara lebih mudah dan
            terstruktur.

        </p>


        <div class="login-info">

            Sistem Informasi Pengelolaan Infaq Siswa

        </div>

    </div>


    <!-- KANAN -->

    <div class="login-right">


        <h1 class="sipis-title">
            SIPIS
        </h1>


        <h2>
            Selamat Datang
        </h2>


        <p class="login-subtitle">
            Silakan masuk untuk melanjutkan
        </p>


        <!-- SUCCESS -->

        @if (session('success'))

            <div class="success">

                {{ session('success') }}

            </div>

        @endif


        <!-- ERROR -->

        @if ($errors->any())

            <div class="error">

                {{ $errors->first() }}

            </div>

        @endif


        <!-- FORM -->

        <form
            method="POST"
            action="{{ route('login.submit') }}">

            @csrf


            
               
                <div class="form-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        placeholder="Masukkan username"
                        required
                        autofocus>
                </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    required>

            </div>


            <button type="submit">
                Login
            </button>

        </form>


        <!-- REGISTER -->

        <div class="register-link">

            Belum punya akun?

            <a href="{{ route('register') }}">
                Daftar sekarang
            </a>

        </div>


        <div class="footer">

            © {{ date('Y') }} SIPIS

        </div>


    </div>


</div>


</body>

</html>