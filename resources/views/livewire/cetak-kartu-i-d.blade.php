<div>
    <!-- resources/views/datakaryawans/index.blade.php -->
    <div class="table table-responsive pt-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>NIK</th>
                    <th>NRP</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Agama</th>
                    <th>Gol. Darah</th>
                    <th>Status Perkawinan</th>
                    <th>Perusahaan</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Domisili</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($karyawans as $index => $datakaryawan)
                    <tr @if ($datakaryawan->status == 'non aktif') class="table-danger" @endif>
                        <td>
                            @if ($datakaryawan->status == 'aktif')
                                <a href="cetak-kartu/{{ $datakaryawan->nrp }}" target="_blank"
                                    class="btn btn-warning btn-sm" rel="noopener noreferrer">
                                    <span class="bi bi-printer"></span> Cetak
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $datakaryawan->status }}</td>
                        <td>
                            @if ($datakaryawan->foto)
                                <img src="{{ Storage::url($datakaryawan->foto) }}" alt="Foto" class="img-fluid"
                                    style="max-width: 100px; max-height: 100px;">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $datakaryawan->nik }}</td>
                        <td>{{ $datakaryawan->nrp }}</td>
                        <td>{{ $datakaryawan->nama }}</td>
                        <td>{{ $datakaryawan->jenis_kelamin }}</td>
                        <td>{{ $datakaryawan->tempat_lahir }}</td>
                        <td>{{ $datakaryawan->agama }}</td>
                        <td>{{ $datakaryawan->gol_darah }}</td>
                        <td>{{ $datakaryawan->status_perkawinan }}</td>
                        <td>{{ $datakaryawan->perusahaan }}</td>
                        <td>{{ $datakaryawan->dept }}</td>
                        <td>{{ $datakaryawan->jabatan }}</td>
                        <td>{{ $datakaryawan->no_hp }}</td>
                        <td>{{ $datakaryawan->alamat }}</td>
                        <td>{{ $datakaryawan->domisili }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="16">
                            <div class="alert alert-danger">
                                <span class="bi bi-exclamation-circle"></span>
                                &nbsp;No data
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $karyawans->appends(['search' => $search])->links() }}
</div>
