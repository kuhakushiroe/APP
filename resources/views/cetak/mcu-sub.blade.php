<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Verifikasi MCU {{ $id }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            background-image: url('storage/BG MCU.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        table {
            width: 100%;
            padding: 50px;
            font-size: 12pt;
        }

        .kodedoc {
            text-align: left;
            font-size: 0.7rem;
            top: 120px;
            right: 10px;
            position: absolute;
        }

        .isi {
            padding-top: 140px;
            text-align: center;
            margin: 10px;
        }

        .nomor-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            /* Center horizontally */
            top: 180px;
            /* Adjust top position as needed */
        }

        p {
            margin: 0;
        }

        td {}
    </style>
</head>

<body>
    @php
        $query = DB::table('mcu')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->select(
                'karyawans.*',
                'mcu.*',
                'mcu.id as id_mcu',
                'mcu.created_at as tglPengajuan',
                'mcu.status as mcuStatus',
            )
            ->where('mcu.id', $id)
            ->first();
    @endphp
    <div class="kodedoc">
        AMM-BGE-F-SHE-012A
    </div>
    <div class="isi">
        <h2>
            <u>VERIFIKASI MEDICAL CHECK UP</u>
        </h2>
        <table style="width: 100%;position: absolute;top:135px;">
            <tr>
                <td style="text-align: center;">
                    Nomor: <b>{{ $query->no_dokumen ?? '' }}</b>
                </td>
            </tr>
        </table>
        <table style="width:100%;">
            <tr>
                <td colspan="4" style="text-align: left;">Yang bertanda tangan di bawah ini menerangkan bahwa :</td>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 20%;">Nama</td>
                <td style="width: 1px;">:</td>
                <td>{{ $query->nama }}</td>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 10%;">NRP</td>
                <td style="width: 1px;">:</td>
                <td>{{ $query->nrp }}</td>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 10%;">Perusahaan</td>
                <td style="width: 1px;">:</td>
                <td>{{ $query->perusahaan }}</td>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 10%;">Tanggal Lahir</td>
                <td style="width: 1px;">:</td>
                <td>
                    {{ \Carbon\Carbon::parse($query->tgl_lahir)->locale('id')->isoFormat('D MMMM Y') ?? '' }}
                </td>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 10%;">MCU Provider</td>
                <td style="width: 1px;">:</td>
                <td>{{ $query->proveder }}</td>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 10%;">Tanggal MCU</td>
                <td style="width: 1px;">:</td>
                <td>{{ \Carbon\Carbon::parse($query->tgl_mcu ?? '')->locale('id')->isoFormat('D MMMM Y') }}</td>
            </tr>
            </tr>
            <tr style="">
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 10%;">Tanggal Verifikasi</td>
                <td style="width: 1px;">:</td>
                <td>{{ \Carbon\Carbon::parse($query->tgl_verifikasi ?? '')->locale('id')->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left;">
                    Telah dilakukan verikasi medical check up an yang bersangkutan dan dinyatakan :
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="width: 100%;padding: 0px">
                        <tr>
                            <td>
                                @if ($query->status === 'FIT')
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9745;
                                        FIT</p>
                                @else
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9744;
                                        FIT</p>
                                @endif
                            </td>
                            <td>
                                @if ($query->status === 'FOLLOW UP')
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9745;
                                        FOLLOW UP</p>
                                @else
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9744;
                                        FOLLOW UP</p>
                                @endif
                            </td>
                            <td>
                                @if ($query->status === 'FIT WITH NOTE')
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9745;
                                        FIT WITH NOTE</p>
                                @else
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9744;
                                        FIT WITH NOTE</p>
                                @endif
                            </td>
                            <td>
                                @if ($query->status === 'TEMPORARY UNFIT')
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9745;
                                        TEMPORARY UNFIT</p>
                                @else
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9744;
                                        TEMPORARY UNFIT</p>
                                @endif
                            </td>
                            <td>
                                @if ($query->status === 'UNFIT')
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9745;
                                        UNFIT</p>
                                @else
                                    <p style="font-family: DejaVu Sans, sans-serif;">&#9744;
                                        UNFIT</p>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 10%;"><b>Keterangan</b></td>
                <td style="width: 1%;"><b>:</b></td>
                <td colspan="2" style="text-align: left;"></td>
            </tr>
            <tr>
                <td style="width: 10%;">&nbsp;</td>
                <td colspan="3" style="text-align: left;">
                    @php
                        $indukquery = DB::table('mcu')
                            ->where('id', $query->sub_id)
                            ->where('sub_id', null)
                            ->orderBy('tgl_mcu', 'desc')
                            ->first();
                    @endphp
                    @if ($query->sub_id !== null)
                        {{ $query->status . ' , ' . $query->keterangan_mcu }}<br>
                        @php
                            $subquery = DB::table('mcu')
                                ->where('sub_id', $query->sub_id)
                                ->orderBy('tgl_mcu', 'desc')
                                ->get();
                        @endphp
                        @forelse ($subquery as $data)
                            {{ $data->status . ' , ' . $data->keterangan_mcu }}<small>{{ $data->tgl_verifikasi }}</small><br>
                        @empty
                            -
                        @endforelse
                    @else
                        {{ $query->status . ' , ' . $query->keterangan_mcu }}
                        <small>{{ $query->tgl_verifikasi }}</small><br>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 10%;"><b>Saran</b></td>
                <td style="width: 1%;"><b>:</b></td>
                <td colspan="2" style="text-align: left;"></td>
            </tr>
            <tr>
                <td style="width: 10%;">&nbsp;</td>
                <td colspan="3" style="text-align: left;">
                    @if ($query->sub_id !== null)
                        {{ $query->saran_mcu }}<br>
                        @forelse ($subquery as $data)
                            {{ $data->saran_mcu . ' ,' }}<br>
                        @empty
                            -
                        @endforelse
                    @else
                        {{ $query->saran_mcu }}<br>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left;">
                    Demikian hasil verifikasi medical check up ini dibuat untuk
                    dipergunakan sebagaimana mestinya.
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="width: 100%;text-align: center;">
                        <tr>
                            <td style="width: 50%;">&nbsp;</td>
                            <td style="text-align: center;">Meulaboh,
                                {{ \Carbon\Carbon::parse($query->tgl_verifikasi ?? '')->locale('id')->isoFormat('D MMMM Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">&nbsp;</td>
                            <td>Dokter Verifikator MCU PT AMM</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">&nbsp;</td>
                            <td style="text-align: center;">
                                <img src="{{ public_path('storage/Dokter 1.jpeg') }}" alt="" width="100px">
                            </td>
                        </tr>
                        {{-- <tr>
                            <td style="width: 40%;">&nbsp;</td>
                            <td>dr. Yulia Indira Rukmana</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">&nbsp;</td>
                            <td style="font-size: 8pt">SIPD : T-500.16.7.2/1885/DPMPTSP.03</td>
                        </tr> --}}
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
