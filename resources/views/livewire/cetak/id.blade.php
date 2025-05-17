<div>
    <!-- resources/views/datakaryawans/index.blade.php -->
    <div class="table table-responsive pt-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="12">
                        <input type="text" class="form-control form-control-sm" wire:model.live="search"
                            placeholder="Search">
                    </td>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>NIK</th>
                    <th>NRP</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Perusahaan</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>No HP</th>
                    <th>Domisili</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($karyawans as $index => $datakaryawan)
                    <tr @if ($datakaryawan->status == 'non aktif') class="table-danger" @endif>
                        <td>
                            @php
                                $caripengajuan = DB::table('pengajuan_id')->where('nrp', $datakaryawan->nrp)->first();
                                if ($caripengajuan) {
                                    $cetak = $caripengajuan->status_pengajuan;
                                    $exp_id = $caripengajuan->exp_id;
                                } else {
                                    $cetak = '0';
                                    $exp_id = null;
                                }
                            @endphp
                            @if ($cetak === '2' && $exp_id > NOW() && $exp_id !== null)
                                @if ($datakaryawan->status == 'aktif' && $datakaryawan->exp_id > NOW())
                                    <a href="cetak-kartu/{{ $datakaryawan->nrp }}" target="_blank"
                                        class="btn btn-primary btn-sm" rel="noopener noreferrer">
                                        <span class="bi bi-printer"></span> Cetak
                                    </a>
                                @else
                                    -
                                @endif
                            @else
                                <a href="#" target="_blank" class="btn btn-secondary btn-sm disabled"
                                    rel="noopener noreferrer">
                                    <span class="bi bi-printer"></span> Cetak
                                </a>
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
                        <td>{{ $datakaryawan->perusahaan }}</td>
                        <td>{{ $datakaryawan->dept }}</td>
                        <td>{{ $datakaryawan->jabatan }}</td>
                        <td>{{ $datakaryawan->no_hp }}</td>
                        <td>{{ $datakaryawan->domisili }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">
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
