<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=" UTF-8">
    <title>Surat Keterangan Laik Kerja</title>
    <style>
        @page {
            size: A4;
            margin: 0.5cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
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
    <table width="100%" style="border: 1px solid black; border-collapse: collapse;" border="1">
        <tr>
            <td align="center" rowspan="4" style="padding: 0 1 0 1" width="20%">
                <img src="{{ public_path('storage/LOGO AMM 01.png') }}" width="60px" alt="">
            </td>
            <td align="center" style="font-size: 14pt;font-weight: bold" rowspan="4" width="40%">SURAT KETERANGAN
                LAIK KERJA</td>
            <td width="15%">No. Dokumen</td>
            <td width="25%">: AMM-MIFA-F-SHE-77E</td>
        </tr>
        <tr>
            <td>Revisi</td>
            <td>: 00</td>
        </tr>
        <tr>
            <td>Tgl. Efektif</td>
            <td>: 00/00/0000</td>
        </tr>
        <tr>
            <td>Halaman</td>
            <td>: 1 Halaman</td>
        </tr>
    </table>
    <!-- TOP RIGHT BOTTOM LEFT -->
    <div style="margin: 0cm 0cm 0cm 1cm">
        <table width="100%">
            <tr>
                <td style="width: 40%">
                    Dokter Perusahaan
                </td>
                <td style="width: 1%">:</td>
                <td>dr. Muhammad Reza Wardana</td>
            </tr>
            <tr>
                <td>
                    Jenis Pemeriksaan Kesehatan
                </td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <table style="margin-left: 25%;">
                    <tr>
                        <td>
                            <p>MCU Pre-Employment ☐</p>
                        </td>
                        <td>
                            <p>MCU Khusus ☐</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Exit MCU ☐</p>
                        </td>
                        <td>
                            <p>MCU Tahunan ☐</p>
                        </td>
                    </tr>
                </table>
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
                <td>Jenis Kelamain</td>
                <td>:</td>
                <td>{{ $query->jenis_kelamin }}</td>
            </tr>
            <tr>
                <td>Posisi / Jabatan</td>
                <td>:</td>
                <td>{{ $query->jabatan }}</td>
            </tr>
            <tr>
                <td>Karyawan</td>
                <td>:</td>
                <td>{{ $query->dept }}</td>
            </tr>
        </table>
    </div>
    <div style="margin: 0cm 0cm 0cm 1cm">
        Berdasarkan Hasil Pemeriksaan MCU Annual Pada Tanggal {{ $query->created_at }} Dinyatakan {{ $query->status }}
        <br>
        Catatan :
        <div style="padding: 0cm 0cm 0cm 0.6cm">
            <table width="100%">

        </div>
    </div>
    <div style="margin: 0cm 0cm 0cm 1cm">
        Status Kelayakan Kerja Tersebut akan berakhir pada tanggal {{ $query->exp_mcu }},
        Sehingga Perlu dilakukan pemeriksaan MCU berikutnya sebagai dasar perpanjangan status kelaikan kerja.
    </div>

    <div class="text-right mt-5">
        <table width="100%">
            <tr>
                <td width="70%">&nbsp;</td>
                <td width="30%">
                    <p>Meulaboh,
                        {{ \Carbon\Carbon::parse($query->tgl_verifikasi)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                    <p>Dokter Yang Memeriksa</p>
                    <img src="{{ public_path('storage/Dokter 1.jpeg') }}" alt="" width="100px">
                    {{-- <p><b>dr. Muhammad Reza Wardana</b></p>
                    <p>No. STR: EQ00000519937393</p> --}}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
