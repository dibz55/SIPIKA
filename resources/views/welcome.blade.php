<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SIPIKA - MI Al-Falahiyyah</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f5f7f2;
        }

        .landing {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background:
                linear-gradient(
                    135deg,
                    #57cd8e 0%,
                    #287247 55%,
                    #57cd8e  100%
                );
        }

        .landing-card {
            width: 100%;
            max-width: 1000px;
            min-height: 570px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.20);
        }

        .landing-left {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            text-align: center;
            color: white;
            background: #1d5c3a;
        }

        .landing-left::after {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            right: -100px;
            bottom: -100px;
            border-radius: 50%;
            background: rgba(210, 167, 61, 0.18);
        }

        .logo-wrapper {
            width: 145px;
            height: 145px;
            margin-bottom: 25px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 5px solid #d2a73d;
            border-radius: 50%;
            position: relative;
            z-index: 1;
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .landing-left h1 {
            margin: 0;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .landing-left .subtitle {
            margin-top: 10px;
            color: #e5c76e;
            font-size: 18px;
            font-weight: 700;
        }

        .landing-left .description {
            max-width: 390px;
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
            font-size: 15px;
        }

        .landing-right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 55px;
            background: white;
        }

        .landing-right .small-title {
            margin-bottom: 8px;
            color: #1d5c3a;
            font-size: 15px;
            font-weight: 700;
        }

        .landing-right h2 {
            margin: 0;
            color: #222;
            font-size: 32px;
            font-weight: 800;
        }

        .landing-right p {
            margin-top: 14px;
            color: #777;
            line-height: 1.7;
            font-size: 15px;
        }

        .feature-list {
            margin: 25px 0;
            padding: 0;
            list-style: none;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            color: #444;
            font-size: 15px;
            font-weight: 600;
        }

        .feature-list i {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 8px;
            background: #e8f2eb;
            color: #1d5c3a;
        }

        .btn-masuk {
            width: 100%;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            border-radius: 11px;
            background: #1d5c3a;
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            transition: 0.2s;
        }

        .btn-masuk:hover {
            background: #16482d;
            color: white;
            transform: translateY(-1px);
        }

        .footer-text {
            margin-top: 25px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        @media (max-width: 800px) {

            .landing {
                padding: 15px;
            }

            .landing-card {
                grid-template-columns: 1fr;
            }

            .landing-left {
                min-height: 420px;
                padding: 35px 25px;
            }

            .landing-right {
                padding: 35px 25px;
            }

            .landing-left h1 {
                font-size: 34px;
            }

            .logo-wrapper {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>

<body>

<div class="landing">

    <div class="landing-card">

        {{-- BAGIAN KIRI --}}
        <div class="landing-left">

            <div class="logo-wrapper">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo MI Al-Falahiyyah">
            </div>

            <h1>SIPIKA</h1>

            <div class="subtitle">
                Sistem Pendataan Infaq Kelas
            </div>

            <p class="description">
                Sistem informasi untuk membantu pengelolaan,
                pencatatan, dan pemantauan data infaq serta
                pengeluaran secara lebih mudah dan terstruktur.
            </p>

        </div>


        {{-- BAGIAN KANAN --}}
        <div class="landing-right">

            <div class="small-title">
                MIS AL-FALAHIYYAH
            </div>

            <h2>Selamat Datang</h2>

            <p>
                Kelola data keuangan kelas dengan lebih
                sederhana, rapi, dan terorganisir melalui SIPIKA.
            </p>

            <ul class="feature-list">

                <li>
                    <i class="bi bi-wallet2"></i>
                    Pendataan Infaq
                </li>

                <li>
                    <i class="bi bi-receipt"></i>
                    Pencatatan Pengeluaran
                </li>

                <li>
                    <i class="bi bi-bar-chart-line"></i>
                    Laporan Keuangan
                </li>

            </ul>

            <a href="{{ route('login') }}" class="btn-masuk">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke Sistem
            </a>

            <div class="footer-text">
                © {{ date('Y') }} SIPIKA · MIS Al-Falahiyyah
            </div>

        </div>

    </div>

</div>

</body>
</html>