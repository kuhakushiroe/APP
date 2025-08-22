<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <td>: {{ now()->format('d/m/Y') }}</td>
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
                <td>
                    @php
                        $users = DB::table('users')->where('username', $query->verifikator)->first();
                    @endphp
                    {{ $users->name }}
                </td>
            </tr>
            <tr>
                <td>
                    Jenis MCU
                </td>
                <td>:</td>
                <td>
                    <p style="font-family: DejaVu Sans, sans-serif;">
                        Pre Employment
                        @if ($query->jenis_pengajuan_mcu === 'Pre Employment')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                        Annual
                        @if ($query->jenis_pengajuan_mcu === 'Annual')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                        Temporary
                        @if ($query->jenis_pengajuan_mcu === 'Temporary')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                        Khusus
                        @if ($query->jenis_pengajuan_mcu === 'Khusus')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                        Exit MCU
                        @if ($query->jenis_pengajuan_mcu === 'Exit MCU')
                            &#9746;
                        @else
                            &#9744;
                        @endif
                    </p>
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
                    <td>: {{ $query->jenis_kelamin ?: '-' }} /
                        {{ \Carbon\Carbon::parse($query->tgl_lahir)->age }} tahun</td>
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
                    <td>: {{ $query->tredmil_test }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Hasil Follow Up</td>
                    <td>: {{ $query->status }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td> Echocardiography</td>
                    <td>: {{ $query->echocardiography }}</td>
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
                    <td>: Kehilangan Kesadaran : {{ strtoupper($query->kesadaran ?? '-') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td> Epilepsi : {{ strtoupper($query->epilepsi ?? '-') }}</td>
                </tr>
                <tr>
                    <td>2. Tekanan Darah</td>
                    <td>: {{ $query->sistol }} / {{ $query->diastol }} mmhg</td>
                </tr>
                <tr>
                    <td>3. Buta Warna</td>
                    <td>: {{ $query->butawarna }}</td>
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
                    <img src="{{ public_path('storage/' . $query->verifikator . '.jpg') }}" alt=""
                        width="150px">
                    {{-- <p><b>dr. Muhammad Reza Wardana</b></p>
                    <p>No. STR: EQ00000519937393</p> --}}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
