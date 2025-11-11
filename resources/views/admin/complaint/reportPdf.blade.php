<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .report-header {
            text-align: left;
            margin-bottom: 20px;
        }

        .report-header img {
            width: 5rem;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="report-header">
        <div>
            <img src="img/logo-bpi.png" alt="Logo BPI">
        </div>
        <div>
            <h3>Report Pengaduan</h3>
            <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('d-m-Y') }} - {{
                \Carbon\Carbon::parse($dateTo)->format('d-m-Y') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Timestamp</th>
                <th>Nama</th>
                <th>No Induk BPI</th>
                <th>Fakultas</th>
                <th>Program Studi</th>
                <th>Jenis Pengaduan</th>
                <th>Deskripsi Pengaduan</th>
                <th>Bukti Dokumen</th>
                <th>Alamat Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($complaints as $complaint)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $complaint->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $complaint->fullname }}</td>
                <td>{{ $complaint->bpi_number }}</td>
                <td>{{ $complaint->faculty }}</td>
                <td>{{ $complaint->study_program }}</td>
                <td>{{ $complaint->complaintType->title }}</td>
                <td>{{ $complaint->content }}</td>
                <td>
                    <ul>
                        @foreach ($complaint->complaintMedias as $media)
                        <li>
                            <a href="{{asset('storage/'.$media->url)}}">media {{$loop->iteration}}</a>
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    {{ $complaint->awardee->user->email }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>