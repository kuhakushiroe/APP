<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">
                        <button class="btn btn-outline-danger btn-sm" wire:click="close">
                            <span class="bi bi-arrow-left"></span>
                        </button>
                        Karyawan
                    </div>
                </div>
                <div class="form-group">
                    <div class="container">
                        <div class="row align-items-start">
                            <div class="col">
                                <table class="table">
                                    <thead class="table-info">
                                        <tr>
                                            <th colspan="2">Data Pribadi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>NIK</th>
                                            <td>{{ $dataKaryawan['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>{{ $dataKaryawan['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tempat Tanggal Lahir</th>
                                            <td>{{ $dataKaryawan['tgl_lahir'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td>{{ $dataKaryawan['jenis_kelamin'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Agama</th>
                                            <td>{{ $dataKaryawan['agama'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Golongan Darah</th>
                                            <td>{{ $dataKaryawan['gol_darah'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status Perkawinan</th>
                                            <td>{{ $dataKaryawan['status_perkawinan'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $dataKaryawan['alamat'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Domisili</th>
                                            <td>{{ $dataKaryawan['domisili'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>No Handphone</th>
                                            <td>{{ $dataKaryawan['no_hp'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <table class="table">
                                    <thead class="table-info">
                                        <tr>
                                            <th colspan="2">Data Pekerjaan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Date of Hire</th>
                                            <td>{{ $dataKaryawan['doh'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Perusahaan</th>
                                            <td>{{ $dataKaryawan['perusahaan'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kontraktor</th>
                                            <td>{{ $dataKaryawan['kontraktor'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Departemen</th>
                                            <td>{{ $dataKaryawan['dept'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <td>{{ $dataKaryawan['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>{{ $dataKaryawan['status'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <table class="table">
                                    <thead class="table-info">
                                        <tr>
                                            <th>
                                                Foto
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                FOTO
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="container">
                    <div class="row align-items-start">
                        <div class="col">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th colspan="2">Masa Aktif :</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Expired ID</th>
                                        <td>{{ $dataKaryawan['exp_mcu'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expired Kimper</th>
                                        <td>{{ $dataKaryawan['exp_kimper'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expired Simpo</th>
                                        <td>{{ $dataKaryawan['exp_simpol'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expired Medical Check Up</th>
                                        <td>{{ $dataKaryawan['exp_mcu'] }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th colspan="2">Data Pekerjaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Date of Hire</th>
                                        <td>{{ $dataKaryawan['doh'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Perusahaan</th>
                                        <td>{{ $dataKaryawan['perusahaan'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kontraktor</th>
                                        <td>{{ $dataKaryawan['kontraktor'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Departemen</th>
                                        <td>{{ $dataKaryawan['dept'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jabatan</th>
                                        <td>{{ $dataKaryawan['jabatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $dataKaryawan['status'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th>
                                            Foto
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            FOTO
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <!--end::Quick Example-->
        </div>
    </div>
</div>
