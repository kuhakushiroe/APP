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
            padding: 0px 0px 0px 0px;
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
            text-align: center;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
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
    @php
        $pengajuanKimper = DB::table('pengajuan_kimper')->where('nrp', $id)->first();
    @endphp
    <div class="container">
        <div class="card">
            <div class="card-header">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 40%; padding: 10 10 10 10;">
                            <img src="{{ public_path('/storage/MIFA.png') }}" class="logo1">
                        </td>
                        <td style="width: 60%; text-align: center;">
                            <table style="text-align: center;" border="1">
                                <tr>
                                    <td>KIMPER</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 4pt;">KARTU IZIN MENGEMUDI PERUSAHAAN</td>
                                </tr>
                            </table>
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
                <div
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: rgba(0, 0, 0, 0.2); font-weight: bold; pointer-events: none;">
                    <p
                        style="font-family: 'Oswald', sans-serif; font-size: 60px; transform: scaleY(5.0); display: inline-block;">
                        KONTRAKTOR
                    </p>
                </div>
                <p style="font-size: 8pt; padding-top:30px;">
                    <b>{{ Str::upper($karyawans->nama ?? 'Nama Karyawan') }}</b>
                </p>
                <p style="font-size: 8pt; padding-top:10px; padding-bottom:180px;">
                    <b>{{ $karyawans->jabatan ?? 'Jabatan' }}</b>
                </p>
                <table style="width: 100%">
                    <tr>
                        <td>
                            @php
                                $carifoto = DB::table('pengajuan_kimper')
                                    ->where('nrp', $karyawans->nrp)
                                    ->orderByDesc('id')
                                    ->first();

                                $uploadFoto = $carifoto?->upload_foto;
                                $path = $uploadFoto ? public_path('storage/' . $uploadFoto) : null;
                            @endphp

                            @if ($path && file_exists($path))
                                <img src="{{ $path }}" class="profile-pic">
                            @else
                                <img src="{{ public_path('storage/fotos/default.jpg') }}" class="profile-pic">
                            @endif
                        </td>
                        <td style="vertical-align: bottom;">
                            <table style="text-align: center;">
                                <tr>
                                    <td colspan="4">
                                        <div class="div" style="border: 1px solid black;">
                                            F
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        MFA-AMM-1131
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p style="font-size: 8pt;">Berlaku Sampai:</p>
                                        <p style="font-size: 8pt;">
                                            <b>{{ \Carbon\Carbon::parse($karyawans->exp_id ?? '')->locale('id')->isoFormat('D MMMM Y') }}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: white;border: 1px solid black;">1</td>
                                    <td style="background-color: yellow;border: 1px solid black;">2</td>
                                    <td style="background-color: green;border: 1px solid black;">3</td>
                                    <td style="background-color: red;border: 1px solid black;">4</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div style="margin: 0 0 0 0;">
                <table width="100%"
                    style="font-family: Arial, Helvetica, sans-serif;font-size:7pt; font-weight: bold;">

                    <tr>
                        <td width="40%">
                            SIM POLISI
                        </td>
                        <td width="1%">:</td>
                        <td width="49%">{{ $pengajuanKimper->jenis_sim ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>
                            NOMOR SIM
                        </td>
                        <td>:</td>
                        <td>{{ $pengajuanKimper->no_sim ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>
                            MASA BERLAKU
                        </td>
                        <td>:</td>
                        <td>{{ $pengajuanKimper->exp_sim ?? '' }}</td>
                    </tr>
                </table>
                <table width="100%" style="font-family: Arial, Helvetica, sans-serif;font-size:8pt;">
                    <tr style="background-color: black;">
                        <td style="font-weight: bold;color: white;" width="80%">
                            MIFA Permit
                        </td>
                        <td width="10%"
                            style="background-color: white; border: 1px solid black; text-align: center;">F</td>
                        <td width="10%"
                            style="background-color: white; border: 1px solid black; text-align: center;">R</td>
                        <td width="10%"
                            style="background-color: white; border: 1px solid black; text-align: center;">I</td>
                        <td width="10%"
                            style="background-color: white; border: 1px solid black; text-align: center;">P</td>
                    </tr>
                    <div
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: rgba(0, 0, 0, 0.2); font-weight: bold; pointer-events: none;">
                        <p
                            style="font-family: 'Oswald', sans-serif; font-size: 60px; transform: scaleY(5.0); display: inline-block;">
                            KONTRAKTOR
                        </p>
                    </div>
                    @php
                        $versatilities = DB::table('pengajuan_kimper')
                            ->where('nrp', $id)
                            ->join(
                                'pengajuan_kimper_versatility',
                                'pengajuan_kimper.id',
                                '=',
                                'pengajuan_kimper_versatility.id_pengajuan_kimper',
                            )
                            ->join('versatility', 'pengajuan_kimper_versatility.id_versatility', '=', 'versatility.id')
                            ->select(
                                'versatility.id',
                                'versatility.versatility as namaVersatility',
                                'versatility.description',
                                'pengajuan_kimper_versatility.klasifikasi',
                            )
                            ->distinct()
                            ->get();
                        $access = [];
                    @endphp
                    @foreach ($versatilities as $item)
                        <tr style="font-family: Arial, Helvetica, sans-serif;font-size:5pt;">
                            <td>{{ $item->namaVersatility }}</td>
                            <td style="text-align: center;">
                                @if ($item->klasifikasi == 'F')
                                    v
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ($item->klasifikasi == 'R')
                                    v
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ($item->klasifikasi == 'I')
                                    v
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ($item->klasifikasi == 'P')
                                    v
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @for ($i = $versatilities->count(); $i <= 20 - $versatilities->count(); $i++)
                        @php
                            $access[$i] = rand(1, 4);
                        @endphp
                        <tr style="font-family: Arial, Helvetica, sans-serif;font-size:5pt;">
                            <td>{{ $i }}</td>
                            <td style="text-align: center;">
                                -
                            </td>
                            <td style="text-align: center;">
                                -
                            </td>
                            <td style="text-align: center;">
                                -
                            </td>
                            <td style="text-align: center;">
                                -
                            </td>
                        </tr>
                    @endfor
                </table>
            </div>
            <div class="card-footer">
                <table width="100%" style="text-align: center;">
                    <tr>
                        <td style="background-color:
                    green;text-align: center;font-weight: bold;font-size: 8pt;padding: 5px 0px 5px 0px;"
                            colspan="2">
                            FULL ACCESS</td>
                    </tr>
                    <tr>
                        <td>Meulaboh</td>
                        <td>, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 5pt;">Kepala Teknik Tambang</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 5pt;">PT. MIFA BERSAUDARA</td>
                    </tr>
                    <tr>
                        <td colspan="2"><img src="{{ public_path('/storage/TTD.png') }}" alt="Signature"
                                style="transform: scaleY(5.0); display: inline-block; width: 100px;"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 5pt;">HADI FIRMANSAH</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
