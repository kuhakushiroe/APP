<div>
    @if ($form)
        @include('livewire.pengajuan.form-id')
    @else
        @hasAnyRole(['superadmin', 'admin'])
            <button wire:click='open' class="btn btn-outline-success btn-sm">
                <span class="bi bi-plus"></span> ID
            </button>
            <div class="row pt-2">
                <div class="col-md-12">
                    <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model.live="search">
                </div>
            </div>
        @endhasanyrole
        <div class="row pt-2">
            <div class="col-md-12">
                @forelse ($pengajuanid as $pengajuan)
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="20%">
                                        <b>Jenis</b>
                                    </td>
                                    <td>
                                        {{ $pengajuan->jenis_pengajuan_id }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <b>NRP</b>
                                    </td>
                                    <td>
                                        {{ $pengajuan->nrp }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <b>Nama</b>
                                    </td>
                                    <td>
                                        {{ $pengajuan->nama }} <b>{{ $pengajuan->dept }} - {{ $pengajuan->jabatan }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <b>Tgl Pengajuan</b>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->locale('id')->translatedFormat('l, d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <b>Status</b>
                                    </td>
                                    <td>
                                        @if ($pengajuan->status_pengajuan == '0')
                                            Pending
                                        @elseif ($pengajuan->status_pengajuan == '1')
                                            Approved
                                        @elseif ($pengajuan->status_pengajuan == '2')
                                            Cetak
                                        @else
                                            <button class="btn btn-primary btn-sm" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm"
                                                    aria-hidden="true"></span>
                                                <span role="status">Waiting...</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <div class="alert alert-primary mt-3">
                                <div class="row g-2">
                                    @php
                                        $files = [
                                            //'Form Request' => $pengajuan->upload_request,
                                            'Foto' => $pengajuan->upload_foto,
                                            'KTP' => $pengajuan->upload_ktp,
                                            'SKD' => $pengajuan->upload_skd,
                                            'BPJS Kesehatan' => $pengajuan->upload_bpjs_kes,
                                            'BPJS Ketenagakerjaan' => $pengajuan->upload_bpjs_ker,
                                            'Induksi' => $pengajuan->upload_induksi,
                                            'SPDK' => $pengajuan->upload_spdk,
                                        ];

                                        if ($pengajuan->jenis_pengajuan_id === 'perpanjangan') {
                                            $files = ['ID Lama' => $pengajuan->upload_id_lama] + $files;
                                        }
                                    @endphp

                                    @foreach ($files as $label => $file)
                                        <div class="col-6 col-md-4 col-lg-3">
                                            @if ($file && ($label !== 'Foto' || cekFile('/' . $file)))
                                                <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                    class="btn btn-primary btn-sm w-100">
                                                    <i class="bi bi-file-earmark-pdf"></i> {{ $label }}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-danger btn-sm w-100 disabled">
                                                    <i class="bi bi-file-earmark-pdf"></i> {{ $label }}
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if (
                                //$pengajuan->status_upload_request == '0' ||
                                ($pengajuan->status_upload_id_lama == '0' ||
                                    $pengajuan->status_upload_foto == '0' ||
                                    $pengajuan->status_upload_ktp == '0' ||
                                    $pengajuan->status_upload_skd == '0' ||
                                    $pengajuan->status_upload_induksi == '0' ||
                                    $pengajuan->status_upload_spdk == '0' ||
                                    $pengajuan->status_upload_bpjs_kes == '0' ||
                                    $pengajuan->status_upload_bpjs_ker == '0') &&
                                    in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <div class="alert alert-warning">
                                    <form wire:submit.prevent="updateUpload({{ $pengajuan->id_pengajuan }})">
                                        <div class="row">
                                            {{-- @if ($pengajuan->status_upload_request == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_request" class="form-label">Upload Form
                                                            Request</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_request') is-invalid @enderror"
                                                            type="file" id="upload_request"
                                                            wire:model='upload_request'>
                                                        @error('upload_request')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_request }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif --}}
                                            @if ($pengajuan->status_upload_id_lama == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_id_lama" class="form-label">Upload ID
                                                            Lama</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_id_lama') is-invalid @enderror"
                                                            type="file" id="upload_id_lama"
                                                            wire:model.live='upload_id_lama'>
                                                        @error('upload_id_lama')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_id_lama }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_foto == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_foto" class="form-label">Upload FOTO</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_foto') is-invalid @enderror"
                                                            type="file" id="upload_foto" wire:model='upload_foto'>
                                                        @error('upload_foto')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_foto }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_ktp == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_ktp" class="form-label">Upload KTP</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_ktp') is-invalid @enderror"
                                                            type="file" id="upload_ktp" wire:model='upload_ktp'>
                                                        @error('upload_ktp')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_ktp }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_skd == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_skd" class="form-label">Upload SKD</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_skd') is-invalid @enderror"
                                                            type="file" id="upload_skd" wire:model='upload_skd'>
                                                        @error('upload_skd')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_skd }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_bpjs_kes == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_bpjs_kes" class="form-label">Upload
                                                            BPJS KESEHATAN</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_bpjs_kes') is-invalid @enderror"
                                                            type="file" id="upload_bpjs_kes"
                                                            wire:model='upload_bpjs_kes'>
                                                        @error('upload_bpjs_kes')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_bpjs_kes }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_bpjs_ker == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_bpjs_ker" class="form-label">Upload
                                                            BPJS KETENAGAKEJAAN</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_bpjs_ker') is-invalid @enderror"
                                                            type="file" id="upload_bpjs_ker"
                                                            wire:model='upload_bpjs_ker'>
                                                        @error('upload_bpjs_ker')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_bpjs_ker }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_spdk == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_spdk" class="form-label">Upload
                                                            SPDK</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_spdk') is-invalid @enderror"
                                                            type="file" id="upload_spdk" wire:model='upload_spdk'>
                                                        @error('upload_spdk')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_spdk }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pengajuan->status_upload_spdk == '0')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="upload_induksi" class="form-label">Upload
                                                            INDUKSI</label>
                                                        <input
                                                            class="form-control form-control-sm @error('upload_induksi') is-invalid @enderror"
                                                            type="file" id="upload_upload_induksi"
                                                            wire:model='upload_induksi'>
                                                        @error('upload_induksi')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="text-danger">
                                                            {{ $pengajuan->catatan_upload_induksi }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-12 pt-2">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <span class="bi bi-save"></span> Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            @php
                                $isIncomplete =
                                    in_array(null, [
                                        //$pengajuan->status_upload_request,
                                        $pengajuan->status_upload_foto,
                                        $pengajuan->status_upload_ktp,
                                        $pengajuan->status_upload_skd,
                                        $pengajuan->status_upload_bpjs_kes,
                                        $pengajuan->status_upload_bpjs_ker,
                                        $pengajuan->status_upload_induksi,
                                        $pengajuan->status_upload_spdk,
                                    ]) ||
                                    in_array(0, [
                                        //$pengajuan->status_upload_request,
                                        $pengajuan->status_upload_foto,
                                        $pengajuan->status_upload_ktp,
                                        $pengajuan->status_upload_skd,
                                        $pengajuan->status_upload_bpjs_kes,
                                        $pengajuan->status_upload_bpjs_ker,
                                        $pengajuan->status_upload_induksi,
                                        $pengajuan->status_upload_spdk,
                                    ]);

                                // Tambahkan pengecekan ID Lama jika jenis pengajuan adalah perpanjangan
                                if ($pengajuan->jenis_pengajuan_id === 'perpanjangan') {
                                    $isIncomplete =
                                        $isIncomplete ||
                                        $pengajuan->status_upload_id_lama === null ||
                                        $pengajuan->status_upload_id_lama === 0;
                                }
                            @endphp
                            @if (
                                //$pengajuan->status_upload_request == '0' ||
                                ($pengajuan->status_upload_id_lama == '0' ||
                                    $pengajuan->status_upload_foto == '0' ||
                                    $pengajuan->status_upload_ktp == '0' ||
                                    $pengajuan->status_upload_skd == '0' ||
                                    $pengajuan->status_upload_induksi == '0' ||
                                    $pengajuan->status_upload_spdk == '0' ||
                                    $pengajuan->status_upload_bpjs_kes == '0' ||
                                    $pengajuan->status_upload_bpjs_ker == '0') &&
                                    in_array(auth()->user()->role, ['superadmin']))
                            @else
                                @if ($isIncomplete && in_array(auth()->user()->role, ['she', 'superadmin']))
                                    <div class="alert alert-warning">
                                        <form>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>File</th>
                                                        <th>OK</th>
                                                        <th>Ulangi</th>
                                                        <th>Catatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $dokumenList = [
                                                            //'upload_request' => 'Form Request',
                                                            'upload_foto' => 'Foto',
                                                            'upload_ktp' => 'KTP',
                                                            'upload_skd' => 'SKD',
                                                            'upload_bpjs_kes' => 'BPJS KESEHATAN',
                                                            'upload_bpjs_ker' => 'BPJS KETENAGAKERJAAN',
                                                            'upload_induksi' => 'INDUKSI',
                                                            'upload_spdk' => 'SPDK',
                                                        ];

                                                        // Tambahkan ID Lama hanya jika perpanjangan
                                                        if ($pengajuan->jenis_pengajuan_id === 'perpanjangan') {
                                                            $dokumenList =
                                                                ['upload_id_lama' => 'ID Lama'] + $dokumenList;
                                                        }
                                                    @endphp

                                                    @foreach ($dokumenList as $field => $label)
                                                        @php
                                                            $statusField = "status_$field";
                                                            $catatanField = "catatan_$field";
                                                            $statusKey = $statusField . '.' . $pengajuan->id_pengajuan;
                                                            $catatanKey =
                                                                $catatanField . '.' . $pengajuan->id_pengajuan;
                                                        @endphp
                                                        @if ($pengajuan->$statusField === null)
                                                            <tr>
                                                                <td>{{ $label }}</td>

                                                                {{-- Radio Button 1 --}}
                                                                <td>
                                                                    <input type="radio"
                                                                        wire:model="{{ $statusField }}.{{ $pengajuan->id_pengajuan }}"
                                                                        value="1" class="form-check-input">
                                                                </td>

                                                                {{-- Radio Button 0 --}}
                                                                <td>
                                                                    <input type="radio"
                                                                        wire:model="{{ $statusField }}.{{ $pengajuan->id_pengajuan }}"
                                                                        value="0" class="form-check-input">
                                                                </td>

                                                                {{-- Textarea --}}
                                                                <td>
                                                                    <textarea wire:model="{{ $catatanField }}.{{ $pengajuan->id_pengajuan }}" class="form-control"></textarea>

                                                                    {{-- Error Message --}}
                                                                    @error($statusKey)
                                                                        <small
                                                                            class="text-danger d-block mt-1">{{ $message }}</small>
                                                                    @enderror
                                                                    @error($catatanKey)
                                                                        <small
                                                                            class="text-danger d-block">{{ $message }}</small>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                wire:click.prevent="verifikasiUpload('{{ $pengajuan->id_pengajuan }}')">
                                                <i class="bi bi-send"></i> Kirim
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    @if (in_array(auth()->user()->role, ['she', 'superadmin']))
                                        @if ($pengajuan->status_pengajuan == 1)
                                            <a target="_blank"
                                                wire:click.prevent="updateCetak('{{ $pengajuan->id_pengajuan }}')"
                                                class="btn btn-sm btn-outline-danger">
                                                <span class="bi bi-printer"></span>
                                                Cetak
                                            </a>
                                        @else
                                            <form wire:submit.prevent="prosesCetak('{{ $pengajuan->id_pengajuan }}')">
                                                <label>Expired ID</label>
                                                <input type="date" class="form-control form-control-sm"
                                                    wire:model="expired_id.{{ $pengajuan->id_pengajuan }}">
                                                @error("expired_id.{$pengajuan->id_pengajuan}")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Lanjut Proses Cetak <span class="bi bi-arrow-right"></span>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-danger">
                        <span class="bi bi-info-circle"></span> Tidak ada data
                    </div>
                @endforelse
                {{ $pengajuanid->links() }}
            </div>
        </div>
    @endif
</div>
