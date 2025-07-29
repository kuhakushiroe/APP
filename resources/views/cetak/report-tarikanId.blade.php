<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=" UTF-8">
    <title>Pengajuan Kartu Identitas</title>
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

        .table-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .header-row {
            background-color: #92D050;
        }

        .table-kiri {
            width: 45%;
        }

        .table-kanan {
            width: 45%;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">

        <table width="100%" border="1">
            <tr>
                <td align="center" rowspan="4" style="padding: 0 1 0 1 " width="20%">
                    <img src="MIFA.png" width="70%">
                </td>
                <td rowspan="2" width="60%" align="center">FORMULIR</td>
                <td width="10%">Nomor</td>
                <td width="10%">
                    -Nomor-
                </td>
            </tr>
            <tr>
                <td>Tanggal Terbit</td>
                <td>
                    <p><strong>Periode Laporan:</strong>
                        {{ \Carbon\Carbon::parse($date1)->format('d-m-Y') }}
                        s/d
                        {{ \Carbon\Carbon::parse($date2)->format('d-m-Y') }}
                    </p>
                </td>
            </tr>
            <tr>
                <td rowspan="2" align="center"> PENGAJUAN KARTU IDENTITAS DIRI (ID CARD) KARYAWAN </td>
                <td>Revisi</td>
                <td></td>

            </tr>
            <tr>
                <td>Halaman</td>
                <td></td>

            </tr>
            <!-- <tr>
            <td align="center" rowspan="4" style="padding: 0 1 0 1" width="20%">
                <img src="{{ public_path('storage/LOGO AMM 01.png') }}" width="60px" alt="">
            </td>
            <td align="center" style="font-size: 14pt;font-weight: bold" rowspan="2" width="40%">SURAT KETERANGAN
                LAIK DOKTER</td>
            <td>PENGAJUAN KARTU IDENTITAS DIRI (ID CARD) KARYAWAN</td>
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
        </tr> -->
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
            @forelse ($data as $dataId => $item1)
                <tr>
                    <td>{{ $dataId + 1 }}</td>
                    <td>{{ $item1->nama }}</td>
                    <td>{{ $item1->nik }}</td>
                    <td>{{ $item1->nrp }}</td>
                    <td>{{ $item1->jabatan }}</td>
                    <td>{{ $item1->dept }}</td>
                    <td>{{ $item1->doh }}</td>
                    <td></td>
                    <td>{{ $item1->perusahaan }}</td>
                    <td>{{ $item1->jenis_pengajuan_id }}</td>
                    <td>{{ $item1->status_pengajuan }}</td>
                    <td>1</td>
                </tr>
            @empty
                <p>Tidak Ada Data</p>
            @endforelse
        </table>

        <br>

        <div class="table-container">
            <div class="table_kiri">
                <table width="25%" style="border: 1px solid black; border-collapse: collapse;" border="1">
                    <tr>
                        <td>Nama PJO / PIC</td>
                        <td>SEPTIAN</td>
                    </tr>
                    <tr>
                        <td>Nomor Handphone PJO / PIC</td>
                        <td>085954590940</td>
                    </tr>
                </table>
            </div>

            <div class="table-kanan">
                <table width="45%" style="border: 1px solid black; border-collapse: collapse;" border="1">
                    <tr>
                        <td>Diajukan Oleh,</td>
                        <td>Mengetahui</td>
                        <td>Diproses Oleh</td>
                    </tr>
                    <tr>
                        <td height="10%">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Yudhi Wahyudiana</td>
                        <td>Hadi Firmasnsyah</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>SHE PT TKS</td>
                        <td>Kepala Teknik Tambang</td>
                        <td>HR Site</td>
                    </tr>
                </table>
            </div>
        </div>


        <br>

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
        <br>
        <br>

        <div>
            <table width="25%" style="border: 1px solid black; border-collapse: collapse; page-break-before: always;"
                border="1">
                <tr>
                    <td align="center">No</td>
                    <td align="center">Nama</td>
                    <td align="center">Pas Photo</td>
                    <td align="center">KTP</td>
                    <td align="center">Surat Kesehatan</td>
                </tr>
                @forelse ($data as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->upload_foto }}</td>
                        <td>{{ $item->upload_ktp }}</td>
                        <td>{{ $item->upload_skd }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada Data</td>
                    </tr>
                @endforelse
            </table>
        </div>

    </div>
</body>

</html>
