<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - UPT PTKK Jatim</title>
</head>

  <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .close-btn {
            font-size: 28px;
            font-weight: bold;
            color: #ef4444;
            text-decoration: none;
            margin-right: 10px;
            background: #fee2e2;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .close-btn:hover {
            background: #fecaca;
            transform: rotate(90deg);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-fallback {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            line-height: 1.2;
        }

        .header-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
        }

        .main-content {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }

        .sidebar {
            background: #e0f2fe;
            border-radius: 12px;
            padding: 25px;
            height: fit-content;
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #bae6fd;
        }

        .step:last-child {
            border-bottom: none;
        }

        .step-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .step.active .step-number {
            background: #3b82f6;
            color: white;
        }

        .step:not(.active) .step-number {
            background: #cbd5e1;
            color: #64748b;
        }

        .step-text {
            font-size: 16px;
            font-weight: 500;
        }

        .step.active .step-text {
            color: #0f172a;
        }

        .step:not(.active) .step-text {
            color: #64748b;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="tel"],
        select,
        textarea {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #374151;
            background: white;
            transition: all 0.2s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        input::placeholder,
        textarea::placeholder {
            color: #9ca3af;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .submit-btn {
            background: #3b82f6;
            color: white;
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            float: right;
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .container {
                padding: 15px;
            }

            .header-title {
                font-size: 18px;
            }
        }
    </style>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <!-- Ganti src dengan URL logo UPT PTKK Anda -->
                <img src="./logo-upt-ptkk.png" alt="Logo UPT PTKK"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                <div class="logo-fallback" style="display: none">UPT<br />PTKK</div>
            </div>
            <h1 class="header-title">UPT PTKK Dinas Pendidikan Jawa Timur</h1>
        </header>

        <div class="main-content">
            <aside class="sidebar">
                <h2 class="sidebar-title">Pendaftaran Pelatihan</h2>

                <div class="step completed">
                    <div class="step-number">✓</div>
                    <div class="step-text">Biodata diri</div>
                </div>

                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-text">Biodata Sekolah</div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">Lampiran</div>
                </div>
            </aside>
        </div>

        <main class="content">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
