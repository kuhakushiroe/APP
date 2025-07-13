<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Surat Keterangan Laik Kerja</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            background-image: url('storage/BG MCU.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .isi {
            padding-top: 70px;
            text-align: center;
            margin: 80px;
        }
    </style>
</head>

<body>
    <!-- @php
        $query = DB::table('mcu')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->select('karyawans.*', 'mcu.*', 'mcu.id as idPengajuanMcu', 'mcu.created_at as tglPengajuan')
            ->where('mcu.id', $id)
            ->first();
    @endphp -->
    <!-- TOP RIGHT BOTTOM LEFT -->
    <div class="isi">
        <h2>Surat Keterangan Laik Kerja</h2>
        <table width="100%">
            @php
                $users = DB::table('users')->where('username', $query->verifikator)->first();
            @endphp
            <tr>
                <td style="width: 40%">
                    Dokter Perusahaan
                </td>
                <td style="width: 1%">:</td>
                <td>{{ $users->name }}</td>
            </tr>
            <tr>
                <td>
                    Jenis Pemeriksaan Kesehatan
                </td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="25%">
                    MCU Pre-Employment
                </td>
                <td>
                    <p style="font-family: DejaVu Sans, sans-serif;">
                        @if ($query->jenis_pengajuan_mcu === 'Pre Employeed MCU')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                    </p>
                </td>
                <td width="20%">
                    MCU Khusus
                </td>
                <td>
                    <p style="font-family: DejaVu Sans, sans-serif;">
                        @if ($query->jenis_pengajuan_mcu === 'MCU Khusus')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    Exit MCU
                </td>
                <td>
                    <p style="font-family: DejaVu Sans, sans-serif;">
                        @if ($query->jenis_pengajuan_mcu === 'MCU Exit')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                    </p>
                </td>
                <td>
                    MCU Tahunan
                </td>
                <td>
                    <p style="font-family: DejaVu Sans, sans-serif;">
                        @if ($query->jenis_pengajuan_mcu === 'Annual MCU')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                    </p>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $query->nama }}</td>
            </tr>
            <tr>
                @php
                    use Carbon\Carbon;
                    $usia = Carbon::parse($query->tgl_lahir)->age;
                @endphp
                <td>Usia</td>
                <td>:</td>
                <td>{{ $usia }} tahun</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $query->jenis_kelamin ?: '-' }}</td>
            </tr>
            <tr>
                <td>Posisi / Jabatan</td>
                <td>:</td>
                <td>{{ $query->jabatan }}</td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <td>
                    Berdasarkan Hasil Pemeriksaan {{ $query->jenis_pengajuan_mcu }} Pada Tanggal
                    {{ $query->created_at }}
                    Dinyatakan <b>{{ $query->status }}</b>
                </td>
            </tr>
            <tr>
                <td>
                    Catatan : {{ $query->keterangan_mcu }}
                </td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <td>
                    Status Kelayakan Kerja Tersebut akan berakhir pada tanggal {{ $query->exp_mcu }},
                    Sehingga Perlu dilakukan pemeriksaan MCU berikutnya sebagai dasar perpanjangan status kelaikan
                    kerja.
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="60%">&nbsp;</td>
                <td width="40%" align="center">
                    <p>Meulaboh,
                        {{ \Carbon\Carbon::parse($query->tgl_verifikasi)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                    <img src="{{ public_path('storage/' . $query->verifikator . '.jpg') }}" alt=""
                        width="150px">
                    <p>Dokter Perusahaan</p>
                    {{-- <p><b>dr. Muhammad Reza Wardana</b></p>
                    <p>No. STR: EQ00000519937393</p> --}}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
