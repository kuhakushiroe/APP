<div>
    @if ($form)
        @include('livewire.pengajuan.form-id')
    @else
        @hasAnyRole(['superadmin', 'admin'])
            <button wire:click='open' class="btn btn-outline-success btn-sm">
                <span class="bi bi-plus"></span> ID
            </button>
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
                                            Rejected
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
                                File:
                                <a href="{{ asset('storage/' . $pengajuan->upload_request) }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    <span class="bi bi-file-earmark-pdf"></span>
                                    Form Request
                                </a>
                                @if ($pengajuan->jenis_pengajuan_id === 'perpanjangan')
                                    <a href="{{ asset('storage/' . $pengajuan->upload_id_lama) }}" target="_blank"
                                        class="btn btn-primary btn-sm">
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
                                <a href="{{ asset('storage/' . $pengajuan->upload_bpjs_kes) }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    <span class="bi bi-file-earmark-pdf"></span>
                                    Bpjs
                                </a>
                            </div>
                            @if (
                                ($pengajuan->status_upload_request == '0' ||
                                    $pengajuan->status_upload_id_lama == '0' ||
                                    $pengajuan->status_upload_foto == '0' ||
                                    $pengajuan->status_upload_ktp == '0' ||
                                    $pengajuan->status_upload_skd == '0' ||
                                    $pengajuan->status_upload_bpjs_kes == '0') &&
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
                                        @if ($pengajuan->status_upload_id_lama == '0')
                                            <div class="col-12">
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
                                                    <label for="upload_bpjs_kes" class="form-label">Upload BPJS</label>
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
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <span class="bi bi-save"></span> Simpan
                                        </button>
                                    </form>
                                </div>
                            @endif
                            @php
                                $isIncomplete =
                                    in_array(null, [
                                        $pengajuan->status_upload_request,
                                        $pengajuan->status_upload_id_lama,
                                        $pengajuan->status_upload_foto,
                                        $pengajuan->status_upload_ktp,
                                        $pengajuan->status_upload_skd,
                                        $pengajuan->status_upload_bpjs_kes,
                                    ]) ||
                                    in_array(0, [
                                        $pengajuan->status_upload_request,
                                        $pengajuan->status_upload_id_lama,
                                        $pengajuan->status_upload_foto,
                                        $pengajuan->status_upload_ktp,
                                        $pengajuan->status_upload_skd,
                                        $pengajuan->status_upload_bpjs_kes,
                                    ]);
                            @endphp
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
                                                @if ($pengajuan->status_upload_request == null)
                                                    <tr>
                                                        <td>Form Request</td>
                                                        <td><input type="radio" wire:model="status_upload_request"
                                                                value="1" class="form-check-input"></td>
                                                        <td><input type="radio" wire:model="status_upload_request"
                                                                value="0" class="form-check-input"></td>
                                                        <td>
                                                            <textarea wire:model="catatan_upload_request" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                @endif

                                                @if ($pengajuan->jenis_pengajuan_id === 'perpanjangan')
                                                    @if ($pengajuan->status_upload_id_lama == null)
                                                        <tr>
                                                            <td>ID Lama</td>
                                                            <td><input type="radio"
                                                                    wire:model="status_upload_id_lama" value="1"
                                                                    class="form-check-input"></td>
                                                            <td><input type="radio"
                                                                    wire:model="status_upload_id_lama" value="0"
                                                                    class="form-check-input"></td>
                                                            <td>
                                                                <textarea wire:model="catatan_upload_id_lama" class="form-control"></textarea>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                @if ($pengajuan->status_upload_foto == null)
                                                    <tr>
                                                        <td>Foto</td>
                                                        <td><input type="radio" wire:model="status_upload_foto"
                                                                value="1" class="form-check-input"></td>
                                                        <td><input type="radio" wire:model="status_upload_foto"
                                                                value="0" class="form-check-input"></td>
                                                        <td>
                                                            <textarea wire:model="catatan_upload_foto" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($pengajuan->status_upload_ktp == null)
                                                    <tr>
                                                        <td>KTP</td>
                                                        <td><input type="radio" wire:model="status_upload_ktp"
                                                                value="1" class="form-check-input"></td>
                                                        <td><input type="radio" wire:model="status_upload_ktp"
                                                                value="0" class="form-check-input"></td>
                                                        <td>
                                                            <textarea wire:model="catatan_upload_ktp" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($pengajuan->status_upload_skd == null)
                                                    <tr>
                                                        <td>SKD</td>
                                                        <td><input type="radio" wire:model="status_upload_skd"
                                                                value="1" class="form-check-input"></td>
                                                        <td><input type="radio" wire:model="status_upload_skd"
                                                                value="0" class="form-check-input"></td>
                                                        <td>
                                                            <textarea wire:model="catatan_upload_skd" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($pengajuan->status_upload_bpjs_kes == null)
                                                    <tr>
                                                        <td>BPJS</td>
                                                        <td><input type="radio" wire:model="status_upload_bpjs_kes"
                                                                value="1" class="form-check-input"></td>
                                                        <td><input type="radio" wire:model="status_upload_bpjs_kes"
                                                                value="0" class="form-check-input"></td>
                                                        <td>
                                                            <textarea wire:model="catatan_upload_bpjs_kes" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            wire:click.prevent="verifikasiUpload('{{ $pengajuan->id_pengajuan }}')">
                                            <i class="bi bi-send"></i> Kirim
                                        </button>
                                    </form>
                                </div>
                            @else
                                @if ($pengajuan->status_pengajuan == 1)
                                    <a target="_blank"
                                        wire:click.prevent="updateCetak('{{ $pengajuan->id_pengajuan }}')"
                                        class="btn btn-sm btn-outline-danger">
                                        <span class="bi bi-printer"></span>
                                        Cetak
                                    </a>
                                @else
                                    <form wire:submit.prevent="prosesCetak('{{ $pengajuan->id_pengajuan }}')">
                                        <label>Tgl Induksi</label>
                                        <input type="date" class="form-control form-control-sm"
                                            wire:model="tgl_induksi.{{ $pengajuan->id_pengajuan }}">
                                        @error("tgl_induksi.{$pengajuan->id_pengajuan}")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
