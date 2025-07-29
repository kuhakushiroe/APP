<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER ID</title>
    <style>
        @page {}

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
        }

        .container {
            width: 277mm;
            /* Lebar A4 landscape (297mm - 20mm margin kiri - 20mm margin kanan) */
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .header-row {
            background-color: #92D050;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <table width="100%" style=" border: 1px solid black; border-collapse: collapse;">
            <tr>
                <td align="center" rowspan="4" style="padding: 0 10 0 10" width="10%"
                    style="border: 1px solid black; border-collapse: collapse;">
                    <img src="{{ public_path('storage/MIFA.png') }}" width="80%">
                </td>
                <td rowspan="2" width="60%" align="center"
                    style=" border: 1px solid black; border-collapse: collapse;"><b>FORMULIR</b></td>
                <td width="5%" style="border-bottom: 1px solid black;">Nomor</td>
                <td width="6%" style="border-bottom: 1px solid black;">
                    : MFA-FM-HRA-051
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid black;">Tanggal Terbit</td>
                <td style="border-bottom: 1px solid black;">
                    {{-- Format Mifa Tidak Boleh di Ubah --}}
                    : 14-01-2019
                </td>
            </tr>
            <tr>
                <td rowspan="2" align="center" style=" border: 1px solid black; border-collapse: collapse;">
                    <b>PENGAJUAN KARTU IDENTITAS DIRI (ID CARD) KARYAWAN</b>
                </td>
                <td style="border-bottom: 1px solid black;">Revisi</td>
                <td style="border-bottom: 1px solid black;">: 001</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid black;">Halaman</td>
                <td style="border-bottom: 1px solid black;">: 01 Dari 01</td>
            </tr>
        </table>
        <br>
        <br>
        <table width="100%" style="border: 1px solid black; border-collapse: collapse;" border="1">
            <tr class="header-row">
                <td rowspan="2"> No. </td>
                <td rowspan="2">Nama Karyawan</td>
                <td rowspan="2">No. Induk Kependudukan</td>
                <td rowspan="2">No. Induk Karyawan</td>
                <td rowspan="2">Posisi / Jabatan</td>
                <td rowspan="2">Departemen / Bagian</td>
                <td colspan="2" align="center">Periode Kerja</td>
                <td rowspan="2">Perusahaan</td>
                <td colspan="2">Status Pengajuan</td>
                <td rowspan="2">Keterangan</td>
            </tr>
            <tr class="header-row">
                <td>Tanggal Masuk</td>
                <td>Tanggal Keluar</td>
                <td>Baru</td>
                <td>Penggantian</td>
            </tr>
            @forelse ($data as $dataId => $data)
                <tr>
                    <td>{{ $dataId + 1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12">-Tidak Ada Data-</td>
                </tr>
            @endforelse
        </table>
        <br>
        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <!-- Tabel Kiri -->
                <td style="vertical-align: top; width: 30%;">
                    <table width="100%" style="border: 1px solid black; border-collapse: collapse;" border="1">
                        <tr>
                            <td>Nama PJO / PIC</td>
                            <td>SEPTIAN</td>
                        </tr>
                        <tr>
                            <td>Nomor Handphone PJO / PIC</td>
                            <td>085954590940</td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td>Catatan</td>
                        </tr>
                        <tr>
                            <td>- Melampirkan Kartu Tanda Penduduk (KTP) masing - masing karyawan</td>
                        </tr>
                        <tr>
                            <td>- Melampirkan Soft Copy Pas Photo Latar Merah masing - masing karyawan </td>
                        </tr>
                        <tr>
                            <td>- Melampirkan Berita Acara (Khusus Penggantian Kartu Karena Rusak)</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top; width: 40%;">
                    &nbsp;
                </td>
                <!-- Tabel Kanan -->
                <td style="vertical-align: top; width: 30%;">
                    <table width="100%" style="border: 1px solid black; border-collapse: collapse;" border="1">
                        <tr>
                            <td align="center" width="33%">Diajukan Oleh,</td>
                            <td align="center" width="33%">Mengetahui</td>
                            <td align="center">Diproses Oleh</td>
                        </tr>
                        <tr>
                            <td height="60">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">Yudhi Wahyudiana</td>
                            <td align="center">Hadi Firmansyah</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="center">SHE PT TKS</td>
                            <td align="center">Kepala Teknik Tambang</td>
                            <td align="center">HR Site</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="page-break-before: always;"></div>
        <table width="100%" border="1">
            <tr>
                <td width="5%">No</td>
                <td>Nama</td>
                <td>Pas Foto</td>
                <td>KTP</td>
                <td>Surat Kesehatan</td>
            </tr>
            <tr>
                <td>-No-</td>
                <td>-database-</td>
                <td>-database-</td>
                <td>-database-</td>
                <td>-database-</td>
            </tr>
        </table>
    </div>

</body>

</html>
