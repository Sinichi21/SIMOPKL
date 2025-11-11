<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>BPI UI - User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }

        .button-primary {
            background-color: #fbbf24;
            border-bottom: 8px solid #fbbf24;
            border-left: 18px solid #fbbf24;
            border-right: 18px solid #fbbf24;
            border-top: 8px solid #fbbf24;
            color: #fff;
            display: inline-block;
            overflow: hidden;
            text-decoration: none;
            border-radius: 4px;
        }

        .button-primary:hover, .button-primary:active {
            background-color: #f59e0b;
            border-color: #f59e0b;
        }
    </style>
</head>
<body style="background-color: #ffffff; color: #718096; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
        <tr>
            <td align="center" style="padding: 25px 0; text-align: center;">
                <a href="{{ route('admin.index') }}" style="display: inline-block;">
                    <img src="https://bpiui.com/assets/logo-bpi.png" alt="BPI UI Logo" style="width: 100px; height: 100px;">
                </a>
            </td>
        </tr>
        <tr>
            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="background-color: #edf2f7; margin: 0; padding: 0; width: 100%; border: hidden !important;">
                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; border-radius: 2px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; width: 570px;">
                    <tr>
                        <td class="content-cell" style="padding: 32px;">
                            <h1 style="color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">Pendaftaran Pengguna Baru</h1>
                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">Halo Admin,</p>
                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                Seorang pengguna baru telah mendaftar dengan informasi berikut:
                            </p>
                            <ul style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                <li><strong>Nama :</strong> {{ $fullname }}</li>
                                <li><strong>Email :</strong> {{ $email }}</li>
                                <li><strong>Jenjang :</strong> {{ $degree }}</li>
                                <li><strong>Fakultas :</strong> {{ $facultyName }}</li>
                                <li><strong>Program Studi :</strong> {{ $studyProgramName }}</li>
                            </ul>
                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                Silakan tinjau dan setujui pendaftaran pengguna ini melalui halaman admin jika diperlukan.
                            </p>
                            <a href="{{ route('admin.index') }}" class="button-primary" style="text-decoration: none;">Buka Halaman Admin</a>
                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify; margin-top: 20px;">
                                Terima kasih,<br>BPI UI
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                    <tr>
                        <td class="content-cell" align="center" style="padding: 32px;">
                            <p style="color: #b0adc5; font-size: 12px; text-align: center;">&copy; {{ date('Y') }} BPI UI. Hak Cipta Dilindungi.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
