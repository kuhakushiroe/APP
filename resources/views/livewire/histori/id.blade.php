<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>NRP</th>
                                <th>NAMA</th>
                                <th>JENIS</th>
                                <th>FILE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuanid as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->nrp }}</td>
                                    <td>{{ $pengajuan->nama }}</td>
                                    <td>{{ $pengajuan->jenis_pengajuan_id }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $pengajuan->upload_request) }}" target="_blank"
                                            class="btn btn-primary btn-sm">
                                            <span class="bi bi-file-earmark-pdf"></span>
                                            Form Request
                                        </a>
                                        @if ($pengajuan->jenis_pengajuan_id === 'perpanjangan')
                                            <a href="{{ asset('storage/' . $pengajuan->upload_id_lama) }}"
                                                target="_blank" class="btn btn-primary btn-sm">
                                                <span class="bi bi-file-earmark-pdf"></span>
                                                ID Lama
                                            </a>
                                        @endif
                                        <a href="{{ asset('storage/' . $pengajuan->upload_foto) }}" target="_blank"
                                            class="btn btn-primary btn-sm">
                                            <span class="bi bi-file-earmark-pdf"></span>
                                            Foto
                                        </a>
                                        <a href="{{ asset('storage/' . $pengajuan->upload_ktp) }}" target="_blank"
                                            class="btn btn-primary btn-sm">
                                            <span class="bi bi-file-earmark-pdf"></span>
                                            Ktp
                                        </a>
                                        <a href="{{ asset('storage/' . $pengajuan->upload_skd) }}" target="_blank"
                                            class="btn btn-primary btn-sm">
                                            <span class="bi bi-file-earmark-pdf"></span>
                                            Skd
                                        </a>
                                        <a href="{{ asset('storage/' . $pengajuan->upload_bpjs) }}" target="_blank"
                                            class="btn btn-primary btn-sm">
                                            <span class="bi bi-file-earmark-pdf"></span>
                                            Bpjs
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
