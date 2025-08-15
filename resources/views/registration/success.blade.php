{{-- resources/views/registration/success.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <style>
        body {
            background-color: #f8fafc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .success-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 20px;
        }
        .success-card {
            background-color: #ecfdf5;
            border: 1px solid #6ee7b7;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .success-title {
            font-size: 1.6rem;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 12px;
        }
        .success-text {
            font-size: 1rem;
            color: #111827;
            line-height: 1.5;
        }
        .success-note {
            margin-top: 10px;
            color: #065f46;
            font-weight: 600;
        }
        .success-actions {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn-dashboard {
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            background-color: #f3f4f6;
            color: #111827;
            border: 2px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }
        .btn-dashboard:hover {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body>

<div class="success-container">
    <div class="success-card">
        <div class="success-title">✅ Pendaftaran Berhasil</div>
        <div class="success-text">
            Terima kasih, pendaftaran Anda telah kami terima.
            Silakan cek <strong>email</strong> atau <strong>WhatsApp</strong> yang Anda daftarkan
            untuk informasi selanjutnya.
        </div>
        <div class="success-note">
            Catatan: Jika Anda tidak menerima email, periksa folder <em>spam</em> atau hubungi admin.
        </div>
        <div class="success-actions">
            <a href="{{ route('landing') }}" class="btn-dashboard">
                ⬅ Dashboard
            </a>
        </div>
    </div>
</div>

</body>
</html>
