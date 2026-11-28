<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi email — Kawan Tenang</title>
</head>
<body style="margin:0;background:#e9e7e1;color:#131619;font-family:Arial,Helvetica,sans-serif;">
    <div style="padding:32px 16px;">
        <div style="max-width:580px;margin:0 auto;background:#faf7ef;border:2px solid #15181b;box-shadow:5px 5px 0 #15181b;">
            <div style="padding:28px 32px;background:#15181b;color:#faf7ef;">
                <div style="font-size:24px;font-weight:700;letter-spacing:-.03em;">Kawan Tenang</div>
                <div style="margin-top:7px;color:#c9f24d;font-size:11px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;">Ruang aman untuk bercerita</div>
            </div>

            <div style="padding:36px 32px;">
                <div style="display:inline-block;padding:7px 11px;border-radius:999px;background:#c9f24d;color:#161b08;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Satu langkah lagi</div>
                <h1 style="margin:20px 0 12px;font-size:28px;line-height:1.2;letter-spacing:-.03em;">Halo, {{ $user->name }}.</h1>
                <p style="margin:0;color:#5c615f;font-size:16px;line-height:1.7;">Terima kasih sudah bergabung dengan Kawan Tenang. Konfirmasi alamat email kamu agar akunmu tetap aman dan siap dipakai.</p>

                <div style="margin:28px 0;">
                    <a href="{{ $url }}" style="display:inline-block;padding:14px 21px;border:2px solid #15181b;border-radius:999px;background:#c9f24d;color:#161b08;font-size:14px;font-weight:700;text-decoration:none;">Verifikasi email</a>
                </div>

                <p style="margin:0;color:#6f7678;font-size:13px;line-height:1.7;">Link ini berlaku selama {{ $expires }} menit. Kalau kamu tidak merasa membuat akun Kawan Tenang, email ini boleh diabaikan.</p>
                <p style="margin:24px 0 0;color:#6f7678;font-size:12px;line-height:1.7;word-break:break-all;">Jika tombol tidak bisa diklik, salin link ini ke browser:<br><a href="{{ $url }}" style="color:#2f7d5c;">{{ $url }}</a></p>
            </div>

            <div style="padding:18px 32px;border-top:1px solid #d8d8ce;color:#6f7678;font-size:11px;line-height:1.6;">Kawan Tenang · Bercerita tanpa takut dikenali.</div>
        </div>
    </div>
</body>
</html>
