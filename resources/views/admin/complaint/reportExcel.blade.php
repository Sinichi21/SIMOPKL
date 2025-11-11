<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Excel</title>
</head>

<body>
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
                <td>{{ $complaint->awardee->fullname }}</td>
                <td>{{ $complaint->awardee->bpi_number }}</td>
                <td>{{ $complaint->awardee->studyProgram->faculty->name }}</td>
                <td>{{ $complaint->awardee->studyProgram->name }}</td>
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