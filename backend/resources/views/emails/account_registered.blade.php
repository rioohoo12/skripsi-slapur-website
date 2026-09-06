<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Akun Berhasil</title>
</head>
<body>
    <h1>Selamat datang, {{ $user->name }}!</h1>
    <p>Akun Anda telah berhasil didaftarkan di sistem Slapur.</p>
    <p>Gunakan email <strong>{{ $user->email }}</strong> untuk login ke dalam sistem.</p>
    <p>Terima kasih,<br>Tim Slapur</p>
</body>
</html>
