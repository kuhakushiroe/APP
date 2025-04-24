<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIT Worker Permit</title>
    <style>
        @font-face {
            font-family: FontBaru;
            src: url('storage/fonts/trebucbd.ttf') format(TrueType);
        }

        @page {
            margin: 0px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .card {
            width: 5.5cm;
            height: 9cm;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 10px 0px 10px;
            background-color: gray;
        }

        .logo1 {
            width: 100%;
        }

        .logo2 {
            width: 100%;
        }

        .card-title h1 {
            margin: 0;
            font-size: 1.2rem;
        }

        .card-title p {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        .card-subtitle p {
            margin: 2px 0;
            font-size: 14pt;
            color: white;
            padding: 5px 0;
        }

        .card-body {
            text-align: center;
        }

        .profile-pic {
            width: 120px;
            height: 150px;
            object-fit: cover;
        }

        .card-body h2 {
            margin: 10px 0 5px;
            font-size: 1rem;
        }

        .card-body p {
            margin: 3px 0;
        }

        .id-number {
            font-size: 10pt;
            font-weight: bold;
        }

        .card-footer {
            text-align: left;
            font-size: 0.7rem;
        }

        .rules {
            padding-left: 10px;
            margin-bottom: 10px;
        }

        .rules p {
            font-weight: bold;
        }

        .rules ol {
            font-size: 7pt;
            padding-left: 20px;
            margin: 5px 0;
        }

        .rules li {
            margin-bottom: 5px;
        }

        .emergency {
            padding-left: 10px;
            margin-top: 90px;
        }

        .emergency-number {
            font-weight: bold;
            font-style: italic;
            font-size: 12pt;
            margin-top: -20px;
            margin-left: 25px;
            font-weight: bold;
            color: red;
        }

        .emergency-channel {
            font-size: 10pt;
            margin-top: -10px;
            font-weight: bold;
            color: red;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; padding: 10 10 10 10;">
                            <img src="{{ public_path('/storage/MIFA.png') }}" class="logo1">
                        </td>
                        <td>
                            <div style="border: 1 solid black; width:80%;text-align:center;">
                                ID CARD
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-title" style="padding-top:80px;">
                <p>
                    <b>PT ANTAREJA MAHADA MAKMUR</b>
                </p>
            </div>
            <div class="card-body">
                <p style="font-size: 8pt; padding-top:30px;">
                    <b>{{ Str::upper($karyawans->nama ?? 'Nama Karyawan') }}</b>
                </p>
                <p style="font-size: 8pt; padding-top:10px; padding-bottom:200px;">
                    <b>{{ $karyawans->jabatan ?? 'Jabatan' }}</b>
                </p>
                <table style="width: 100%">
                    <tr>
                        <td>
                            @if (!empty($karyawans->foto))
                                <img src="{{ public_path('/storage/' . $karyawans->foto) }}" class="profile-pic">
                            @else
                                <img src="{{ public_path('/storage/fotos/6408045201970004wHqpIXoAJOqwyVXNrU0IJsVewkhMrNJxgxm0jbAa.jpg') }}"
                                    class="profile-pic">
                            @endif
                        </td>
                        <td style="vertical-align: bottom;">
                            <p style="font-size: 8pt;">Berlaku Sampai:</p>
                            <p style="font-size: 8pt;">
                                <b>{{ \Carbon\Carbon::parse($karyawans->exp_id ?? '')->locale('id')->isoFormat('D MMMM Y') }}</b>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div style="margin: 10 0 10 0;">
                <table width="100%" style="font-family: Arial, Helvetica, sans-serif;font-size:8pt;">
                    <tr>
                        <td style="background-color: green;padding: 5 5 5 5;text-align:center;">
                            KETENTUAN KARTU ID
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <div class="rules" style="margin-left: 10px;margin-right: 10px;">
                    <ol style="font-size:6pt;">
                        <li>KARTU INI DIGUNAKAN SEBAGAI TANDA BAHWA ANDA DIBERIKAN IZIN UNTUK MEMASUKI AREA OPERASIONAL
                            PT MIFA BERSAUDARA</li>
                        <li>KARTU ID WAJIB DIPAKAI SETIAP BERADA DI AREA OPERASIONAL PT MIFA BERSAUDARA</li>
                        <li>SELAMA BERADA DI AREA PT MIFA BERSAUDARA ANDA WAJIB MENGIKUTI SELURUH ATURAN KESELAMATAN DAN
                            KESEHATAN KERJA SERTA LINGKUNGAN</li>
                        <li>HARAP MENJAGA KARTU ID INI</li>
                        <li>BILA TERDAPAT KEADAAN DAARURAT, HUBUNGI OSC (ON SCENE COMMANDER) DI AREA ANDA MELALUI
                            CHANNEL RADIO 108 ATAU DI NOMOR 08116729220</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
