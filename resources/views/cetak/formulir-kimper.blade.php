<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Formulir KIMPER</title>
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 190mm;
            margin: auto;
            box-sizing: border-box;
        }

        .center-middle {
            text-align: center;
            vertical-align: middle;
        }

        .header {
            text-align: right;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .photo {
            float: right;
            margin-top: 10px;
            width: 3cm;
            height: 4cm;
            border: 1px solid black;
            text-align: center;
            font-size: 10px;
            box-sizing: border-box;
            margin-left: 10px;
        }

        .box-no-reg {
            margin-top: -105px;
            margin-right: 20px;
            float: right;
            width: 5cm;
            border: 1px dashed black;
            font-size: 10px;
            box-sizing: border-box;
        }

        .box-1 {
            float: left;
            margin-top: 10px;
        }

        .box {
            border: 1px solid black;
            padding: 8px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: bottom;
        }

        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 4px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container">
        <table width="100%" style="margin: 0;padding: 0;font-size: 10px">
            <tr style="border: 1px solid #000;">
                <td rowspan="4" width="20%" style="border: 1px solid #000;" class="center-middle">
                    <img src="{{ public_path('storage/MIFA.png') }}" width="70%">
                </td>
                <td rowspan="2" width="50%" style="border: 1px solid #000;" class="center-middle">
                    <b>
                        FORMULIR
                    </b>
                </td>
                <td width="10%">Nomor</td>
                <td width="1%">:</td>
                <td width="12%">MFA-FM-HSE-059</td>
            </tr>
            <tr style="border: 1px solid #000;">
                <td>Tanggal Terbit</td>
                <td>:</td>
                <td>01-04-2021</td>
            </tr>
            <tr style="border: 1px solid #000;">
                <td rowspan="2" style="border: 1px solid #000;" class="center-middle">
                    <b>
                        PERMOHONAN KIMPER
                    </b>
                </td>
                <td>Revisi</td>
                <td>:</td>
                <td>005</td>
            </tr>
            <tr style="border: 1px solid #000;">
                <td>Halaman</td>
                <td>:</td>
                <td>01 dari 01</td>
            </tr>
        </table>

        <div class="photo">
            @if ($data->upload_foto == null)
                Pas Photo<br>3 x 4 cm<br><br>Layar Merah
            @else
                <img src="{{ public_path('storage/' . $data->upload_foto) }}" style="width: 3cm; height: 4cm"
                    alt="">
            @endif
        </div>

        <div class="box-1">
            <br>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="6"><b>I. DATA PEMOHON *</b></td>
                </tr>
                <tr>
                    <td style="width: 2%;">&nbsp;</td>
                    <td style="width: 3%;">1.</td>
                    <td colspan="2" style="width: 25%;">Nama</td>
                    <td style="width: 3%;">:</td>
                    <td style="width: 67%;">{{ $data->nama }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">2.</td>
                    <td colspan="2">Nomor Induk Karyawan / SN</td>
                    <td>:</td>
                    <td>{{ $data->nik }} / {{ $data->nrp }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">3.</td>
                    <td colspan="2">Perusahaan</td>
                    <td>:</td>
                    <td>{{ $data->perusahaan }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">4.</td>
                    <td colspan="2">Departemen</td>
                    <td>:</td>
                    <td>{{ $data->dept }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">5.</td>
                    <td colspan="2">Jabatan</td>
                    <td>:</td>
                    <td>{{ $data->jabatan }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">6.</td>
                    <td colspan="4">Type KIMPER yang diminta</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td style="font-family: DejaVu Sans, sans-serif;">● Unit / LV </td>
                    <td>:</td>
                    <td>
                        <table width=" 100%">
                            <tr>
                                <td width="20%"><span class="checkbox"></span>Full Access</td>
                                <td><span class="checkbox"></span>Non-Operational Access</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td style="font-family: DejaVu Sans, sans-serif;">● Sepeda Motor</td>
                    <td>:</td>
                    <td>
                        <table width=" 100%">
                            <tr>
                                <td width="20%"><span class="checkbox"></span> Operasional</td>
                                <td><span class="checkbox"></span> Non-Operational</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @php
                    // Ambil data kendaraan dari database
                    $vehicles = DB::table('pengajuan_kimper_versatility')
                        ->join('versatility', 'pengajuan_kimper_versatility.id_versatility', '=', 'versatility.id')
                        ->where('pengajuan_kimper_versatility.id_pengajuan_kimper', $data->id_pengajuan_kimper)
                        ->select(
                            'versatility.type_versatility',
                            'versatility.versatility',
                            'pengajuan_kimper_versatility.klasifikasi',
                        )
                        ->get();

                    // Buat array 12 slot (isi data yang ada, sisanya null)
                    $maxSlot = 12;
                    $display = [];

                    for ($i = 0; $i < $maxSlot; $i++) {
                        $display[$i] = $vehicles[$i] ?? null;
                    }
                @endphp
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%" style="vertical-align: top;">7.</td>
                    <td colspan="2" style="vertical-align: top;">Kendaraan/Unit yang Dioperasikan</td>
                    <td style="vertical-align: top;">:</td>
                    <td>
                        <table>
                            @for ($row = 0; $row < 4; $row++)
                                <tr>
                                    @for ($col = 0; $col < 3; $col++)
                                        @php
                                            $index = $row * 3 + $col;
                                            $item = $display[$index];
                                        @endphp
                                        <td width="30%">
                                            {{ $index + 1 }}.
                                            @if ($item)
                                                {{ $item->versatility }}
                                            @else
                                                __________
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </table>
                    </td>
                </tr>
                {{-- <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">7.</td>
                    <td colspan="2">Kendaraan/Unit yang Dioperasikan</td>
                    <td>:</td>
                    <td>
                        <table>
                            <tr>
                                <td width="20%">1. __________</td>
                                <td width="20%">5. __________</td>
                                <td>9. __________</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td>
                        <table>
                            <tr>
                                <td width="20%">2. __________</td>
                                <td width="20%">6. __________</td>
                                <td>10. __________</td>
                            </tr>
                            <tr>
                                <td>3. __________</td>
                                <td>7. __________</td>
                                <td>11. __________</td>
                            </tr>
                            <tr>
                                <td>4. __________</td>
                                <td>8. __________</td>
                                <td>12. __________</td>
                            </tr>
                        </table>
                    </td>
                </tr> --}}
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">8.</td>
                    <td colspan="2">Jenis SIM</td>
                    <td>:</td>
                    <td>
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if ($data->jenis_sim == 'C')
                                ✔
                            @endif
                        </span> C
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if ($data->jenis_sim == 'A')
                                ✔
                            @endif
                        </span>
                        A
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if ($data->jenis_sim == 'B1' || $data->jenis_sim == 'B1 UMUM')
                                ✔
                            @endif
                        </span>
                        BI Umum
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if ($data->jenis_sim == 'B2' || $data->jenis_sim == 'B2 UMUM')
                                ✔
                            @endif
                        </span>
                        BII Umum
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if ($data->jenis_sim == 'SIO')
                                ✔
                            @endif
                        </span> SIO
                    </td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">9.</td>
                    <td colspan="2">Nomor SIM</td>
                    <td>:</td>
                    <td>{{ $data->no_sim }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">10.</td>
                    <td colspan="2">Masa Berlaku SIM</td>
                    <td>:</td>
                    <td>{{ $data->exp_sim }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">11.</td>
                    <td colspan="2">Status Pengajuan Kimper</td>
                    <td>:</td>
                    <td>
                        <table>
                            <tr>
                                <td width="20%"><span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'baru')
                                            ✔
                                        @endif
                                    </span> Baru</td>
                                <td width="30%"><span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'perpanjangan')
                                            ✔
                                        @endif
                                    </span> Perpanjangan</td>
                                <td><span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'penambahan')
                                            ✔
                                        @endif
                                    </span> Penambahan</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td>
                        <table>
                            <tr>
                                <td width="20%"><span class="checkbox"
                                        style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'hilang')
                                            ✔
                                        @endif
                                    </span> Hilang
                                </td>
                                <td width="30%"><span class="checkbox"
                                        style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'rusak')
                                            ✔
                                        @endif
                                    </span> Rusak
                                </td>
                                <td><span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'pindah')
                                            ✔
                                        @endif
                                    </span>
                                    Pindah
                                    Perusahaan</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                                        @if ($data->jenis_pengajuan_kimper == 'lainnya')
                                            ✔
                                        @endif
                                    </span>
                                    Lainnya:___________________________________
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br>
            @php

            @endphp
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="4"><b>II. REKOMENDASI KESEHATAN **</b></td>
                    <td>:</td>
                    <td>
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if (isset($mcu->jenis_pengajuan_mcu) === 'Pre Employment')
                                ✔
                            @endif
                        </span> Pekerja 6 Bulan
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if (isset($mcu->jenis_pengajuan_mcu) === 'Annual')
                                ✔
                            @endif
                        </span>
                        Pekerja 1 Tahun
                    </td>
                </tr>
                <tr>
                    <td style="width: 2%;">&nbsp;</td>
                    <td style="width: 3%;">a.</td>
                    <td colspan="2" style="width: 25%;">Tekanan Darah</td>
                    <td style="width: 3%;">:</td>
                    <td style="width: 67%;">{{ $mcu->sistol ?? '-' }}/{{ $mcu->diastol ?? '-' }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">b.</td>
                    <td colspan="2">EKG</td>
                    <td>:</td>
                    <td>{{ $mcu->ekg ?? '-' }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">c.</td>
                    <td colspan="2">Penglihatan</td>
                    <td>:</td>
                    <td>OD Jauh:{{ $mcu->OD_jauh ?? '-' }} OD Dekat:{{ $mcu->OD_dekat ?? '-' }} OS
                        Jauh:{{ $mcu->OS_jauh ?? '-' }} OS
                        Dekat:{{ $mcu->OS_dekat ?? '-' }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">d.</td>
                    <td colspan="2">Buta Warna</td>
                    <td>:</td>
                    <td>{{ $mcu->butawarna ?? '-' }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">e.</td>
                    <td colspan="2">Audiometri</td>
                    <td>:</td>
                    <td>{{ $mcu->audiometri ?? '-' }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">f.</td>
                    <td colspan="2">Gula Darah</td>
                    <td>:</td>
                    <td>Gdp:{{ $mcu->gdp ?? '-' }} gd_2_jpp:{{ $mcu->gd_2_jpp ?? '-' }}
                        hba1c:{{ $mcu->hba1c ?? '-' }}</td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">&nbsp;</td>
                    <td colspan="2"><i>Hasil Pemeriksaan</i></td>
                    <td>:</td>
                    <td>
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if (isset($mcu->status) === 'FIT')
                                ✔
                            @endif
                        </span> Fit
                        <span class="checkbox" style="font-family: DejaVu Sans, sans-serif;">
                            @if (isset($mcu->status) === 'UNFIT')
                                ✔
                            @endif
                        </span> Un-Fit
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><b>III. VALIDASI UJI KOMPETENSIs **</b></td>
                    <td width="1%">:</td>
                    <td>
                        <span class="checkbox"></span> Competence <span class="checkbox"></span> Not Yet Competence
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><b>IV. CATATAN QSHE DEPT**</b></td>
                    <td width="1%">:</td>
                    <td>&nbsp;

                    </td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td height="50px">
                        ________________________________________
                    </td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">&nbsp;</td>
                    <td colspan="2">Tanggal Permohonan KIMPER*</td>
                    <td>:</td>
                    <td>&nbsp;

                    </td>
                </tr>
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="1%">&nbsp;</td>
                    <td colspan="2">Tanggal Diterima QSHE ** </td>
                    <td>:</td>
                    <td>&nbsp;

                    </td>
                </tr>
            </table>
            <br>
            <table border="0">
                <tr>
                    <td width="25%" align="center">Diajukan Oleh,</td>
                    <td width="25%" align="center">Diperiksa oleh,</td>
                    <td width="25%" align="center">Disetujui Oleh,</td>
                    <td width="25%" align="center">Disetujui untuk diberikan KIMPER</td>
                </tr>
                <tr>
                    <td align="center" style="padding-top: 60px;">..................................................
                    </td>
                    <td align="center" style="padding-top: 60px;">..................................................
                    </td>
                    <td align="center" style="padding-top: 60px;"><u>Nanang Prambudi</u></td>
                    <td align="center" style="padding-top: 60px;"><u>Hadi Firmansah</u></td>
                </tr>
                <tr>
                    <td align="center">Section/Dept Head ....................</td>
                    <td align="center">QSHE</td>
                    <td align="center">PJO</td>
                    <td align="center">Kepala Teknik Tambang</td>
                </tr>
            </table>

            <div class="box" style="font-size: 7pt">
                <table border="0">
                    <tr>
                        <td width="1%"><b>Note</b></td>
                        <td width="1%">:</td>
                        <td>( Formulir Permohonan KIMPER menggunakan TTD Basah)</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><i>1.</i></td>
                        <td><i>* Diisi oleh Pemohon ** Diisi Oleh QSHE</i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><i>2.</i></td>
                        <td><i>Keterangan yang merupakan pilihan diisi
                                dengan tanda Centang</i><i style="font-family: DejaVu Sans, sans-serif;"> ( √ )</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><i>3.</i></td>
                        <td><i>Melampirkan Fotocopi SIM atau SIO</i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><i>4.</i></td>
                        <td><i>Melampirkan MCU 1Th Terakhir (Pemeriksaan Tekanan Darah, EKG(jantung), Penglihatan, Buta
                                Warna, Audiometri & KGD/gula darah</i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><i>5.</i></td>
                        <td><i>Mengirimkan Softcopy Data Pemohon KIMPER (Pas Photo Layar Merah, SIM Polisi, & KIMPER
                                Lama bagi yang Perpanjangan) ke</i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">&nbsp;</td>
                        <td><i>E-mail : hse.administrator@mifacoal.co.id (Format file Excel diambil pada QSHE Dept</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><i>6.</i></td>
                        <td><i>Melampirkan Surat Keterangan Hilang dari Security PT. Mifa Bersaudara untuk Pengajuan
                                Cetak ulang KIMPER Hilang</i></td>
                    </tr>
                </table>
                <div class="box-no-reg">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 45%;">No. Reg. Kimper</td>
                            <td style="width: 1%">:</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Masa Berlaku</td>
                            <td>:</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
        </div>
    </div>
</body>

</html>
