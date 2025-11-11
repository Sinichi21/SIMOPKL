<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>BPI UI</title>
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
            text-decoration: none;
            border-radius: 4px;
        }
        .button-primary:hover, .button-primary:active {
            background-color: #f59e0b;
            border-color: #f59e0b;
        }
    </style>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100%;">
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="header" style="padding: 25px 0; text-align: center;">
                            <a href="{{ route('admin.index') }}">
                                <img src="https://bpiui.com/assets/logo-bpi.png" alt="BPI UI Logo" style="width: 100px; height: 100px;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="body" width="100%" style="background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border: 1px solid #e8e5ef; border-radius: 2px; margin: 0 auto; padding: 0; width: 570px;">
                                <tr>
                                    <td class="content-cell" style="padding: 32px;">
                                        <h1 style="color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0;">Notifikasi Thread Baru</h1>

                                        @if($thread->complaint->awardee)
                                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                                <strong>Thread Dibuat oleh :</strong> {{ $thread->complaint->awardee->fullname }}
                                            </p>
                                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                                <strong>Jenis Aduan :</strong> {{ $thread->complaint->complaintType->title }}
                                            </p>
                                            <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                                <strong>Nomor Aduan :</strong> {{ $thread->complaint->bpi_number }}
                                            </p>
                                        @endif

                                        @if($thread->chats->last())
                                            @if($thread->chats->last()->by == 'admin')
                                                <!-- Admin yang menjawab -->
                                                <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                                    <strong>Admin yang menjawab :</strong> {{ $thread->chats->last()->user->email }}
                                                </p>
                                            @elseif($thread->chats->last()->by == 'awardee')
                                                <!-- Awardee yang menjawab -->
                                                <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                                    <strong>Program Studi :</strong>
                                                    {{ $thread->complaint->awardee->studyProgram->name }}
                                                </p>
                                                <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">
                                                    <strong>Fakultas :</strong>
                                                    {{ $thread->complaint->awardee->studyProgram->faculty->name }}
                                                </p>
                                            @endif
                                        @endif
                                        <p style="font-size: 16px; line-height: 1.5em; text-align: justify;">Silakan login ke panel admin untuk melihat detail thread ini.</p>
                                        <a href="{{ route('admin.thread.show', $thread->id) }}" class="button-primary">Lihat Thread</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="text-align: center; width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center" style="padding: 32px;">
                                        <p style="font-size: 12px; color: #b0adc5;">&copy; {{ date('Y') }} BPI UI. Hak Cipta Dilindungi.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
