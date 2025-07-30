<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER KIMPER</title>
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
        <table width="100%" border="1" style="text-align: center">
            <tr bgcolor="#92D050">
                <td width="5%" rowspan="2">No</td>
                <td rowspan="2">Nama</td>
                <td rowspan="2">SN</td>
                <td rowspan="2">Perusahaan</td>
                <td rowspan="2">Departemen</td>
                <td rowspan="2">Jabatan</td>
                <td rowspan="2">Unit/Kendaraan yang Dioperasikan</td>
                <td rowspan="2">Permit</td>
                <td colspan="3">SIM Polisi</td>
                <td rowspan="2">KATEGORI KIMPER</td>
            </tr>
            <tr bgcolor="#92D050">
                <td>Jenis</td>
                <td>Masa Berlaku</td>
                <td>Nomor</td>
            </tr>
            @forelse ($data as $kimper => $data1)
                <tr bgcolor="#BFBFBF">
                    <td>{{ $kimper + 1 }}</td>
                    <td>{{ $data1->nama }}</td>
                    <td>{{ $data1->nrp }}</td>
                    <td>{{ $data1->perusahaan }}</td>
                    <td>{{ $data1->dept }}</td>
                    <td>{{ $data1->jabatan }}</td>
                    <td>{{ $data1->unit }}</td>
                    <td>{{ $data1->permit }}</td>
                    <td>{{ $data1->jenis }}</td>
                    <td></td>
                    <td>11</td>
                    <td>12</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12">
                        Tidak Ada Data
                    </td>
                </tr>
            @endforelse
            <tr bgcolor="#BFBFBF">
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
            </tr>
        </table>
        <div style="page-break-before: always;"></div>
        <table width="100%" border="1" style="text-align: center">
            <tr bgcolor="#92D050">
                <td width="5%" rowspan="2">No</td>
                <td rowspan="2">Nama</td>
                <td rowspan="2">SN</td>
                <td rowspan="2">PAS PHOTO</td>
                <td rowspan="2">SIM POLISI</td>
                <td>KIMPER</td>
                <td rowspan="2">ID CARD</td>
            </tr>
            <tr bgcolor="#92D050">
                <td>DEPAN & BELAKANG</td>
            </tr>
            @forelse ($data as $data2 => $item)
                <tr bgcolor="grey">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->nrp }}</td>
                    <td>{{ $item->upload_foto }}</td>
                    <td>{{ $item->upload_sim }}</td>
                    <td>{{ $item->upload_kimper_lama }}</td>
                    <td></td>
                    <td>{{ $item->upload_id }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        -Tidak Ada Data-
                    </td>
                </tr>
            @endforelse
            <tr bgcolor="#BFBFBF">
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
            </tr>


        </table>
    </div>
</body>

</html>
