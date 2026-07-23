<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Email</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.1);">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#2563eb; padding:24px 32px;">
                            <p style="margin:0; color:#ffffff; font-size:16px; font-weight:bold;">
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px 0; font-size:15px; color:#111827;">
                                Yth. {{ $userName }},
                            </p>

                            <p style="margin:0 0 24px 0; font-size:14px; color:#4b5563; line-height:1.6;">
                                Terima kasih telah mendaftar. Untuk menyelesaikan proses pendaftaran akun Anda,
                                mohon masukkan kode verifikasi (OTP) berikut pada halaman verifikasi:
                            </p>

                            <div style="text-align:center; margin:32px 0;">
                                <span style="display:inline-block; padding:14px 32px; background-color:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; font-size:28px; font-weight:bold; letter-spacing:8px; color:#1d4ed8;">
                                    {{ $otpCode }}
                                </span>
                            </div>

                            <p style="margin:0 0 8px 0; font-size:13px; color:#6b7280; line-height:1.6;">
                                Kode ini berlaku selama <strong>10 menit</strong> sejak email ini dikirim.
                                Jangan bagikan kode ini kepada siapa pun, termasuk pihak yang mengatasnamakan
                                admin atau pengelola sistem.
                            </p>

                            <p style="margin:24px 0 0 0; font-size:13px; color:#6b7280; line-height:1.6;">
                                Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px; background-color:#f9fafb; border-top:1px solid #e5e7eb;">
                            <p style="margin:0; font-size:12px; color:#9ca3af; text-align:center;">
                                Email ini dikirim otomatis oleh sistem {{ config('app.name') }}. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>