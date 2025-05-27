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
                                <th>Nama</th>
                                <th>Dept / Jabatan</th>
                                <th>Pengajuan</th>
                                <th>Unit</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuankimper as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->nrp }}</td>
                                    <td>{{ $pengajuan->nama }}</td>
                                    <td>{{ $pengajuan->dept }} / {{ $pengajuan->jabatan }}</td>
                                    <td>{{ $pengajuan->jenis_pengajuan_kimper }}</td>
                                    <td>
                                        @php
                                            $unit = DB::table('pengajuan_kimper_versatility')
                                                ->join(
                                                    'versatility',
                                                    'versatility.id',
                                                    '=',
                                                    'pengajuan_kimper_versatility.id_versatility',
                                                )
                                                ->where(
                                                    'pengajuan_kimper_versatility.id_pengajuan_kimper',
                                                    '=',
                                                    $pengajuan->id_pengajuan,
                                                )
                                                ->get();
                                        @endphp
                                        <ul>
                                            @forelse ($unit as $data)
                                                <li>
                                                    {{ $data->type_versatility }}-
                                                    {{ $data->versatility }}
                                                </li>
                                            @empty
                                                -
                                            @endforelse
                                        </ul>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @php
                                                $dokumenLinks = [
                                                    'upload_request' => 'Form Request',
                                                    'upload_foto' => 'Foto',
                                                    'upload_ktp' => 'KTP',
                                                    'upload_skd' => 'SKD',
                                                    'upload_bpjs_kes' => 'BPJS Kesehatan',
                                                    'upload_bpjs_ker' => 'BPJS Ketenagakerjaan',
                                                    'upload_kimper_lama' => 'Kimper Lama',
                                                    'upload_sim' => 'Jenis Sim',
                                                    //'upload_lpo' => 'LPO',
                                                    'upload_sertifikat' => 'Sertifikat',
                                                    'upload_id' => 'ID',
                                                ];

                                                if ($pengajuan->jenis_pengajuan_kimper === 'perpanjangan') {
                                                    $dokumenLinks =
                                                        ['upload_kimper_lama' => 'Kimper Lama'] + $dokumenLinks;
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
                                                $carifilelpo = DB::table('pengajuan_kimper_lpo')
                                                    ->where('id_pengajuan_kimper', '=', $pengajuan->id_pengajuan)
                                                    ->get();
                                            @endphp
                                            @forelse ($carifilelpo as $filelpo)
                                                <a href="{{ asset('storage/' . $filelpo->upload_lpo) }}"
                                                    target="_blank" class="btn btn-primary btn-sm">
                                                    <span class="bi bi-file-earmark-pdf "></span>
                                                    LPO {{ $filelpo->type_lpo }}
                                                </a>
                                            @empty
                                            @endforelse
                                        </div>
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
