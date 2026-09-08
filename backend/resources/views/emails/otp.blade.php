<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Verifikasi Slapur</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 40px 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #1e293b; border-radius: 12px; padding: 32px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { font-size: 24px; color: #38bdf8; margin: 0; }
        .otp-box { font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #f43f5e; text-align: center; background: #0f172a; padding: 16px; border-radius: 8px; border: 1px dashed #f43f5e; margin: 24px 0; }
        .footer { font-size: 12px; color: #94a3b8; text-align: center; margin-top: 24px; border-top: 1px solid #334155; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Portal Slapur</h1>
            <p style="color: #94a3b8;">Verifikasi Pendaftaran Akun</p>
        </div>
        <p>Halo,</p>
        <p>Berikut adalah kode OTP verifikasi Anda untuk menyelesaikan pendaftaran akun di Portal Slapur:</p>
        
        <div class="otp-box">
            {{ $otp }}
        </div>

        <p style="color: #cbd5e1; font-size: 14px;">Kode OTP ini berlaku selama <strong>15 menit</strong>. Jangan bagikan kode ini kepada siapapun.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Portal Slapur. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
