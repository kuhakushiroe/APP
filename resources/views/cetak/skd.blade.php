<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset="UTF-8">
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
                <td>
                    <table>
                        <tr>
                            <td>
                                <p>Tahunan &#9634;</p>
                            </td>
                            <td>
                                <p>Pre Employeed &#9634;</p>
                            </td>
                            <td>
                                <p>Khusus &#9634;</p>
                            </td>
                        </tr>
                    </table>
                    {{-- <input type="checkbox" id="checkbox1" name="mcu_type" value="type1">
                    <label for="checkbox1" style="margin-right: 20px;">Tahunan</label>
                    <input type="checkbox" id="checkbox2" name="mcu_type" value="type2">
                    <label for="checkbox2" style="margin-right: 20px;">Pre Employeed</label>
                    <input type="checkbox" id="checkbox3" name="mcu_type" value="type3">
                    <label for="checkbox3">Khusus</label> --}}
                </td>
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
        Berikut hasil pemeriksaan Medical Check Up yang dilakukan pada tanggal
        {{ \Carbon\Carbon::parse($query->tgl_mcu)->locale('id')->translatedFormat('d F Y') }}
        <div style="padding: 0cm 0cm 0cm 0.6cm">
            {{ $query->keterangan_mcu }}
        </div>
    </div>
    <div style="margin: 0cm 0cm 0cm 1.2cm">
        {{-- Setelah dilakukan pemeriksaan: --}}
        <div style="padding: 0cm 0cm 0cm 0.6cm">
            <table width="100%">
                <tr>
                    <td width="1">1.</td>
                    <td width="36%">Gula Darah</td>
                    <td>: {{ $query->gdp }} mg/dl </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Pemeriksa Mata</td>
                    <td>: OD : {{ $query->OD_jauh }} , OS : {{ $query->OS_jauh }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Audiometeri</td>
                    <td>: {{ $query->audiometri }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>EKG</td>
                    <td>: {{ $query->ekg }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Treadmill</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Hasil Follow Up</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td></td>
                    <td> Echocardiography</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Tinggi Badan</td>
                    <td>: {{ $query->TB }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Berat Badan</td>
                    <td>: {{ $query->BB }}</td>
                </tr>
            </table>
        </div>

        <div style="padding: 0cm 0cm 0cm 0.6cm">
            {{ $query->saran_mcu }}
        </div>
    </div>
    <div style="margin: 0cm 0cm 0cm 1.2cm">
        Setelah dilakukan pemeriksaan:
        <div style="padding: 0cm 0cm 0cm 0.6cm">
            <table width="100%">
                <tr rowspan="2">
                    <td width="38%">1. Riwayat Penyakit</td>
                    <td>: Kehilangan Kesadaran : Yes / No</td>
                </tr>
                <tr>
                    <td></td>
                    <td> Epilepsi : Yes / No</td>
                </tr>
                <tr>
                    <td>2. Tekanan Darah</td>
                    <td>: {{ $query->sistol }} mmhg</td>
                </tr>
                <tr>
                    <td>3. Buta Warna</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td colspan="2">4. Berdasarkan hasil pemeriksaan MCU diatas dapat dinyatakan
                        <u>{{ $query->status }}</u>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="margin: 0cm 0cm 0cm 1.2cm">
        {{-- <p class="mt-3"><b>Berdasarkan hasil pemeriksaan MCU di atas dapat dinyatakan:
                <u>{{ $query->status }}</u>.</b>
        </p> --}}
        Surat keterangan ini untuk keperluan administrasi pengajuan ID Card & KIMPER berlaku sampai dengan
        {{ \Carbon\Carbon::parse($query->exp_mcu)->locale('id')->translatedFormat('l, d F Y') }} Atas perhatian dan
        kerjasamanya saya mengucapkan terima kasih.
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
