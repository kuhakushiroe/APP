<div>
    @if ($form)
        @include('livewire.pengajuan.form-kimper')
    @elseif ($formVersatility)
        @include('livewire.pengajuan.form-versatility')
    @else
        @hasAnyRole(['superadmin', 'admin'])
            <button wire:click='open' class="btn btn-outline-success btn-sm">
                <span class="bi bi-plus"></span> Kimper
            </button>
            <div class="row pt-2">
                <div class="col-md-12">
                    <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model="search">
                </div>
            </div>
        @endhasanyrole
        <div class="row pt-2">
            <div class="col-md-12">
                @forelse ($kimpers as $pengajuan)
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header text-bg-primary">
                            <div class="card-title">
                                Pengajuan Kimper {{ $pengajuan->nrp }}
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="20%">
                                        <b>Jenis</b>
                                    </td>
                                    <td>
                                        {{ $pengajuan->jenis_pengajuan_kimper }}
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
                                    <td>{{ $pengajuan->nama }} <b>{{ $pengajuan->dept }} -
                                            {{ $pengajuan->jabatan }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <b>SIM</b>
                                    </td>
                                    <td>{{ $pengajuan->no_sim }}
                                        <b>
                                            {{ $pengajuan->jenis_sim }} -
                                            {{ \Carbon\Carbon::parse($pengajuan->exp_sim)->locale('id')->translatedFormat('d F Y') }}
                                        </b>
                                    </td>
                                </tr>
                                <tr width="10%">
                                    <td><b>Tanggal Pengajuan</b></td>
                                    <td>{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->locale('id')->translatedFormat('l, d F Y') }}
                                    </td>
                                </tr>
                                <tr width="10%">
                                    <td><b>Status</b></td>
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
                                            'Form Request' => $pengajuan->upload_request,
                                            'ID Aktif' => $pengajuan->upload_id,
                                            'Foto' => $pengajuan->upload_foto,
                                            'KTP' => $pengajuan->upload_ktp,
                                            'SKD' => $pengajuan->upload_skd,
                                            'BPJS Kesehatan' => $pengajuan->upload_bpjs_kes,
                                            'BPJS Ketenagakerjaan' => $pengajuan->upload_bpjs_ker,
                                            'Kimper Lama' => $pengajuan->upload_kimper_lama,
                                            'Sim' => $pengajuan->upload_sim,
                                            'Sertifikat' => $pengajuan->upload_sertifikat,
                                            //'Lpo' => $pengajuan->upload_lpo,
                                        ];

                                        if ($pengajuan->jenis_pengajuan_kimper === 'perpanjangan') {
                                            $files = $files + ['Kimper Lama' => $pengajuan->upload_kimper_lama];
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
                            @php
                                $cariLPO = DB::table('pengajuan_kimper_lpo')
                                    ->where('id_pengajuan_kimper', $pengajuan->id_pengajuan)
                                    ->get();
                            @endphp
                            @if ($cariLPO->count() > 0)
                                <div class="alert alert-secondary mt-3">
                                    <div class="row g-2">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Type</th>
                                                    <th>File</th>
                                                    <th>Nilai 1</th>
                                                    <th>Nilai 2</th>
                                                    <th>Nilai 3</th>
                                                    <th>Nilai 4</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($cariLPO as $key => $lpo)
                                                    <tr>
                                                        <td width="10%">
                                                            <select name="" class="form-control form-control-sm"
                                                                id=""
                                                                wire:model='status_lpo.{{ $lpo->id }}'
                                                                wire:change="updateLPO({{ $lpo->id }})">
                                                                <option value="">-Verifikasi-
                                                                </option>
                                                                <option value="0">Tolak
                                                                </option>
                                                                <option value="1">Terima
                                                                </option>
                                                            </select>
                                                            <a href="#" class="btn btn-primary btn-sm"
                                                                wire:click="editLPO({{ $lpo->id }})">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                            <a href="#" class="btn btn-danger btn-sm"
                                                                wire:click="deleteLPO({{ $lpo->id }})">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </td>
                                                        <td>{{ $lpo->type_lpo }}</td>
                                                        <td>
                                                            <a href="{{ asset('storage/' . $lpo->upload_lpo) }}"
                                                                target="_blank" class="btn btn-primary btn-sm w-100">
                                                                <i class="bi bi-file-earmark-pdf"></i> File LPO
                                                            </a>
                                                        </td>
                                                        <td>{{ $lpo->instrumen_panel }}</td>
                                                        <td>{{ $lpo->safety_operasi }}</td>
                                                        <td>{{ $lpo->metode_operasi }}</td>
                                                        <td>{{ $lpo->perawatan }}</td>
                                                        <td>{{ $lpo->nilai_total }}</td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                <tr>
                                                    <td colspan="8">
                                                        <a href="#" class="btn btn-outline-primary btn-sm"
                                                            wire:click="kunciLpo({{ $pengajuan->id_pengajuan }})">
                                                            <i class="bi bi-lock"></i> Kunci LPO
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if (
                                ($pengajuan->status_upload_request == '0' ||
                                    $pengajuan->status_upload_id == '0' ||
                                    $pengajuan->status_upload_sim == '0' ||
                                    $pengajuan->status_upload_kimper_lama == '0' ||
                                    $pengajuan->status_upload_foto == '0' ||
                                    $pengajuan->status_upload_ktp == '0' ||
                                    $pengajuan->status_upload_skd == '0' ||
                                    //$pengajuan->status_upload_lpo == '0' ||
                                    $pengajuan->status_upload_bpjs_kes == '0' ||
                                    $pengajuan->status_upload_bpjs_ker == '0' ||
                                    $pengajuan->status_upload_sertifikat == '0') &&
                                    in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <div class="alert alert-warning">
                                    <form wire:submit.prevent="updateUpload({{ $pengajuan->id_pengajuan }})">

                                        @if ($pengajuan->status_upload_request == '0')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="upload_request" class="form-label">Upload Form
                                                        Request</label>
                                                    <input
                                                        class="form-control form-control-sm @error('upload_request') is-invalid @enderror"
                                                        type="file" id="upload_request" wire:model='upload_request'>
                                                    @error('upload_request')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="text-danger">
                                                        {{ $pengajuan->catatan_upload_request }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pengajuan->status_upload_id == '0')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="upload_id" class="form-label">Upload ID Aktif</label>
                                                    <input
                                                        class="form-control form-control-sm @error('upload_id') is-invalid @enderror"
                                                        type="file" id="upload_id" wire:model='upload_id'>
                                                    @error('upload_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="text-danger">
                                                        {{ $pengajuan->catatan_upload_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pengajuan->status_upload_sim == '0')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="upload_sim" class="form-label">Upload SIM</label>
                                                    <input
                                                        class="form-control form-control-sm @error('upload_sim') is-invalid @enderror"
                                                        type="file" id="upload_sim" wire:model='upload_sim'>
                                                    @error('upload_sim')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="text-danger">
                                                        {{ $pengajuan->catatan_upload_sim }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pengajuan->status_upload_kimper_lama == '0')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="upload_kimper_lama" class="form-label">Upload ID
                                                        Lama</label>
                                                    <input
                                                        class="form-control form-control-sm @error('upload_kimper_lama') is-invalid @enderror"
                                                        type="file" id="upload_kimper_lama"
                                                        wire:model='upload_kimper_lama'>
                                                    @error('upload_kimper_lama')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="text-danger">
                                                        {{ $pengajuan->catatan_upload_kimper_lama }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($pengajuan->status_upload_foto == '0')
                                            <div class="col-12">
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
                                            <div class="col-12">
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
                                            <div class="col-12">
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
                                            <div class="col-12">
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
                                            <div class="col-12">
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
                                        @if ($pengajuan->status_upload_sertifikat == '0')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="upload_sertifikat" class="form-label">Upload
                                                        Sertifikat</label>
                                                    <input
                                                        class="form-control form-control-sm @error('upload_sertifikat') is-invalid @enderror"
                                                        type="file" id="upload_sertifikat"
                                                        wire:model='upload_sertifikat'>
                                                    @error('upload_sertifikat')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="text-danger">
                                                        {{ $pengajuan->catatan_upload_sertifikat }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- @if ($pengajuan->status_upload_lpo == '0')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="upload_lpo" class="form-label">Upload
                                                        LPO</label>
                                                    <input
                                                        class="form-control form-control-sm @error('upload_lpo') is-invalid @enderror"
                                                        type="file" id="upload_lpo" wire:model='upload_lpo'>
                                                    @error('upload_lpo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="text-danger">
                                                        {{ $pengajuan->catatan_upload_lpo }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif --}}
                                        <div class="col-12 pt-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <span class="bi bi-save"></span> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            @php
                                $isIncomplete =
                                    in_array(null, [
                                        $pengajuan->status_upload_request,
                                        $pengajuan->status_upload_id,
                                        $pengajuan->status_upload_sim,
                                        $pengajuan->status_upload_foto,
                                        $pengajuan->status_upload_ktp,
                                        $pengajuan->status_upload_skd,
                                        $pengajuan->status_upload_bpjs_kes,
                                        $pengajuan->status_upload_bpjs_ker,
                                        //$pengajuan->status_upload_lpo,
                                        $pengajuan->status_upload_sertifikat,
                                    ]) ||
                                    in_array(0, [
                                        $pengajuan->status_upload_request,
                                        $pengajuan->status_upload_id,
                                        $pengajuan->status_upload_sim,
                                        $pengajuan->status_upload_foto,
                                        $pengajuan->status_upload_ktp,
                                        $pengajuan->status_upload_skd,
                                        $pengajuan->status_upload_bpjs_kes,
                                        $pengajuan->status_upload_bpjs_ker,
                                        //$pengajuan->status_upload_lpo,
                                        $pengajuan->status_upload_sertifikat,
                                    ]);

                                // Tambahkan pengecekan ID Lama jika jenis pengajuan adalah perpanjangan
                                if ($pengajuan->jenis_pengajuan_kimper === 'perpanjangan') {
                                    $isIncomplete =
                                        $isIncomplete ||
                                        $pengajuan->status_upload_kimper_lama === null ||
                                        $pengajuan->status_upload_kimper_lama === 0;
                                }
                            @endphp
                            @if (
                                ($pengajuan->status_upload_request == '0' ||
                                    $pengajuan->status_upload_id == '0' ||
                                    $pengajuan->status_upload_kimper_lama == '0' ||
                                    $pengajuan->status_upload_sim == '0' ||
                                    $pengajuan->status_upload_foto == '0' ||
                                    $pengajuan->status_upload_ktp == '0' ||
                                    $pengajuan->status_upload_skd == '0' ||
                                    //$pengajuan->status_upload_lpo == '0' ||
                                    $pengajuan->status_upload_sertifikat == '0' ||
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
                                                            'upload_request' => 'Form Request',
                                                            'upload_id' => 'ID Aktif',
                                                            'upload_sim' => 'SIM',
                                                            'upload_foto' => 'Foto',
                                                            'upload_ktp' => 'KTP',
                                                            'upload_skd' => 'SKD',
                                                            'upload_bpjs_kes' => 'BPJS KESEHATAN',
                                                            'upload_bpjs_ker' => 'BPJS KETENAGAKERJAAN',
                                                            //'upload_lpo' => 'LPO',
                                                            'upload_sertifikat' => 'Sertifikat',
                                                        ];

                                                        // Tambahkan ID Lama hanya jika perpanjangan
                                                        if ($pengajuan->jenis_pengajuan_kimper === 'perpanjangan') {
                                                            $dokumenList =
                                                                ['upload_kimper_lama' => 'ID Lama'] + $dokumenList;
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
                                        @elseif (
                                            $pengajuan->status_pengajuan == 0 &&
                                                ($pengajuan->jenis_pengajuan_kimper === 'penambahan' || $pengajuan->jenis_pengajuan_kimper === 'baru'))
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>Versatility</td>
                                                        <td>Acces</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $kimperversatility = DB::table('pengajuan_kimper_versatility')
                                                            ->join(
                                                                'versatility',
                                                                'versatility.id',
                                                                '=',
                                                                'pengajuan_kimper_versatility.id_versatility',
                                                            )
                                                            ->select(
                                                                'versatility.*',
                                                                'pengajuan_kimper_versatility.*',
                                                                'pengajuan_kimper_versatility.id as idVersatility',
                                                            )
                                                            ->where(
                                                                'pengajuan_kimper_versatility.id_pengajuan_kimper',
                                                                $pengajuan->id_pengajuan,
                                                            )
                                                            ->get();
                                                    @endphp
                                                    @forelse ($kimperversatility as $dataversatility)
                                                        <tr>
                                                            <td>
                                                                <button
                                                                    class="btn btn-outline-danger btn-sm @if ($pengajuan->status_versatility === 'ok') disabled @endif"
                                                                    wire:click="deleteVersatility({{ $dataversatility->idVersatility }})">
                                                                    <span class="bi bi-trash"></span>
                                                                </button>
                                                            </td>
                                                            <td>{{ $dataversatility->versatility }}</td>
                                                            <td>{{ $dataversatility->klasifikasi }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center">
                                                                <div class="alert alert-danger">
                                                                    <button
                                                                        class="btn btn-outline-primary btn-sm @if ($pengajuan->status_versatility === 'ok') disabled @endif"
                                                                        wire:click="openVersatility({{ $pengajuan->id_pengajuan }})">
                                                                        <span class="bi bi-plus"></span> Versatility
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                    @if ($kimperversatility->count() > 0)
                                                        <tr>
                                                            <td colspan="3">
                                                                <button
                                                                    class="btn btn-outline-primary btn-sm @if ($pengajuan->status_versatility === 'ok') disabled @endif"
                                                                    wire:click="openVersatility({{ $pengajuan->id_pengajuan }})">
                                                                    <span class="bi bi-plus"></span> Versatility
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                @if ($pengajuan->status_versatility === 'ok')
                                                                    <form
                                                                        wire:submit.prevent="prosesCetak('{{ $pengajuan->id_pengajuan }}')">
                                                                        <label>Expired Kimper</label>
                                                                        <input type="date"
                                                                            class="form-control form-control-sm"
                                                                            wire:model="expired_kimper.{{ $pengajuan->id_pengajuan }}">
                                                                        @error("expired_kimper.{$pengajuan->id_pengajuan}")
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm">
                                                                            Lanjut Proses Cetak <span
                                                                                class="bi bi-arrow-right"></span>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button
                                                                        wire:click="lanjutKimper({{ $pengajuan->id_pengajuan }})"
                                                                        class="btn btn-outline-danger btn-sm">
                                                                        Lanjut
                                                                        <span class="bi bi-arrow-right"></span>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        @else
                                            @if ($pengajuan->jenis_pengajuan_kimper === 'perpanjangan')
                                                <form
                                                    wire:submit.prevent="prosesCetak('{{ $pengajuan->id_pengajuan }}')">
                                                    <label>Expired Kimper</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        wire:model="expired_kimper.{{ $pengajuan->id_pengajuan }}">
                                                    @error("expired_kimper.{$pengajuan->id_pengajuan}")
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
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-danger">
                        <span class="bi bi-info-circle"></span> Tidak ada data
                    </div>
                @endforelse
                {{ $kimpers->links() }}
            </div>
        </div>
    @endif
</div>
