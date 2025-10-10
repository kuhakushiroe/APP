<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <td colspan="4">
                                    <input type="text" class="form-control form-control-sm" wire:model.live="search"
                                        placeholder="Search">
                                </td>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th>NRP</th>
                                <th>NAMA</th>
                                <th>JENIS</th>
                                <th>FILE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengajuanid as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->nrp }}</td>
                                    <td>{{ $pengajuan->nama }}</td>
                                    <td>{{ $pengajuan->jenis_pengajuan_id }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @php
                                                $dokumenLinks = [
                                                    //'upload_request' => 'Form Request',
                                                    'upload_induksi' => 'Form Induksi',
                                                    'upload_foto' => 'Foto',
                                                    'upload_ktp' => 'KTP',
                                                    'upload_skd' => 'SKD',
                                                    'upload_bpjs_kes' => 'BPJS Kesehatan',
                                                    'upload_bpjs_ker' => 'BPJS Ketenagakerjaan',
                                                    'upload_spdk' => 'SPDK',
                                                    'upload_id' => 'ID',
                                                ];

                                                if ($pengajuan->jenis_pengajuan_id === 'perpanjangan') {
                                                    $dokumenLinks = ['upload_id_lama' => 'ID Lama'] + $dokumenLinks;
                                                }
                                            @endphp

                                            @foreach ($dokumenLinks as $field => $label)
                                                @if (!empty($pengajuan->$field))
                                                    <a href="{{ asset('storage/' . $pengajuan->$field) }}"
                                                        target="_blank" class="btn btn-primary btn-sm">
                                                        <span class="bi bi-file-earmark-pdf"></span>
                                                        {{ $label }}
                                                    </a>
                                                @elseif(!empty($pengajuan->$field))
                                                    <a href="{{ asset('storage/' . $pengajuan->$field) }}"
                                                        target="_blank" class="btn btn-primary btn-sm disabled">
                                                        <span class="bi bi-file-earmark-pdf "></span>
                                                        {{ $label }}
                                                    </a>
                                                @endif
                                            @endforeach
                                            @php
                                                $date_id1 = date('Y-m-d', strtotime($pengajuan->tglaccept));
                                                $date_id2 = date('Y-m-d', strtotime($pengajuan->tglaccept));
                                            @endphp
                                            <div class="col-md-4 text-md-start text-end">
                                                <a class="btn btn-success btn-sm"
                                                    href="{{ route('cetak-register-id', ['date_id1' => $date_id1, 'date_id2' => $date_id2]) }}"
                                                    target="_blank" rel="noopener noreferrer">
                                                    <span class="bi bi-download"></span> Form Request
                                                </a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
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
            </div>
        </div>
    </div>
</div>
