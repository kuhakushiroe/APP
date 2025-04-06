<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Dokter</title>
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
    @php
        $query = DB::table('mcu')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->select('karyawans.*', 'mcu.*', 'mcu.id as idPengajuanMcu', 'mcu.created_at as tglPengajuan')
            ->where('mcu.id', $id)
            ->first();
    @endphp
    <table width="100%" style="border: 1px solid black; border-collapse: collapse;" border="1">
        <tr>
            <td align="center" rowspan="4" style="padding: 0 1 0 1" width="20%">
                <img src="{{ public_path('storage/LOGO AMM 01.png') }}" width="60px" alt="">
            </td>
            <td align="center" style="font-size: 14pt;font-weight: bold" rowspan="4" width="40%">SURAT KETERANGAN
                DOKTER</td>
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
                    Jenis MCU
                </td>
                <td>:</td>
                <td>-</td>
            </tr>
        </table>
    </div>
    <div style="margin: 0cm 0cm 0cm 1.2cm">
        Dengan ini menyatakan bahwa:
        <div style="padding: 0cm 0cm 0cm 0.6cm">
            <table width="100%">
                <tr>
                    <td width="38%">Nama</td>
                    <td>: {{ $query->nama }}</td>
                </tr>
                <tr>
                    <td>NRP</td>
                    <td>: {{ $query->nrp }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin / Umur</td>
                    <td>: {{ $query->jenis_kelamin }} / {{ \Carbon\Carbon::parse($query->tgl_lahir)->age }} tahun</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: {{ $query->jabatan }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>: {{ $query->dept }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div style="margin: 0cm 0cm 0cm 1.2cm">
        Berikut hasil pemeriksaan Medical Check Up yang dilakukan pada tanggal 9 Januari 2024:
        <div style="padding: 0cm 0cm 0cm 0.6cm">
            <table width="100%">
                <tr>
                    <td width="38%">1. Kadar Gula Darah</td>
                    <td>: 101 mg/dL</td>
                </tr>
                <tr>
                    <td>2. Pemeriksaan Mata</td>
                    <td>: dalam batas normal</td>
                </tr>
                <tr>
                    <td>3. Audiometri</td>
                    <td>: dalam batas normal</td>
                </tr>
                <tr>
                    <td>4. EKG</td>
                    <td>: dalam batas normal</td>
                </tr>
                <tr>
                    <td>5. Treadmill</td>
                    <td>: -</td>
                </tr>
                <tr>
                    <td>Hasil Follow Up</td>
                    <td>: -</td>
                </tr>
                <tr>
                    <td>6. Tinggi Badan</td>
                    <td>: 160,4 cm</td>
                </tr>
                <tr>
                    <td>Berat Badan</td>
                    <td>: 63,1 Kg</td>
                </tr>
                <tr>
                    <td>Tekanan Darah</td>
                    <td>: 108/82 mmHg</td>
                </tr>
                <tr>
                    <td>Buta Warna</td>
                    <td>: Negatif</td>
                </tr>
                <tr>
                    <td>Riwayat Penyakit</td>
                    <td>
                        Kehilangan Kesadaran: TIDAK<br>
                        Epilepsi: TIDAK
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="margin: 0cm 0cm 0cm 1.2cm">
        <p class="mt-3"><b>Berdasarkan hasil pemeriksaan MCU di atas dapat dinyatakan: <u>FIT</u>.</b></p>
        Surat keterangan ini untuk keperluan administrasi pengajuan ID Card & KIMPER berlaku sampai dengan 9
        Januari Atas perhatian dan kerjasamanya saya mengucapkan terima kasih.
    </div>
    <div class="text-right mt-5">
        <table width="100%">
            <tr>
                <td width="70%">&nbsp;</td>
                <td width="30%">
                    <p>Meulaboh, 18 Februari 2024</p>
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
