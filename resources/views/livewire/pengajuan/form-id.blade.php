<div>
    <div class="row pt-2">
        <div class="col-md-12">

            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h4 class="card-title">Tambah Pengajuan ID</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="NRP">NRP:</label>
                                <input class="form-control form-control-sm @error('nrp') is-invalid @enderror"
                                    wire:model.live='nrp' placeholder="Masukkan NRP">
                                @error('nrp')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($nrp)
                            <div class="alert alert-info" role="alert">
                                @if ($carifoto)
                                    <h4 class="alert-heading">Detail NRP:</h4>
                                    @if (!empty($carifoto->foto))
                                        <label>Foto:</label>
                                        <br>
                                        <img src="{{ Storage::url($carifoto->foto) }}" alt="Foto" class="img-fluid"
                                            style="max-width: 100px; max-height: 150px;">
                                        <br>
                                    @endif
                                    <li>{{ $nrp }}</li>
                                    <li>{{ $info_nama }} | {{ $info_dept }} / {{ $info_jabatan }}</li>
                                    <li>
                                        EXP MCU :
                                        @if ($info_mcu)
                                            @php $tgl = \Carbon\Carbon::parse($info_mcu); @endphp
                                            @if ($tgl->isPast())
                                                <span style="color: red">
                                                    kadaluarsa sejak {{ $tgl->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span>{{ $tgl->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            <span style="color: red">-belum memiliki-</span>
                                        @endif
                                    </li>
                                    <li>
                                        EXP ID :
                                        @if ($info_id)
                                            @php $tgl = \Carbon\Carbon::parse($info_id); @endphp
                                            @if ($tgl->isPast())
                                                <span style="color: red">
                                                    kadaluarsa sejak {{ $tgl->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span>{{ $tgl->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            <span style="color: red">-belum memiliki-</span>
                                        @endif
                                    </li>
                                    {{-- <li>
                                        EXP KIMPER :
                                        @if ($info_kimper)
                                            @php $tgl = \Carbon\Carbon::parse($info_kimper); @endphp
                                            @if ($tgl->isPast())
                                                <span style="color: red">
                                                    kadaluarsa sejak {{ $tgl->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span>{{ $tgl->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            <span style="color: red">-belum memiliki-</span>
                                        @endif
                                    </li> --}}
                                @else
                                    <h4 class="alert-heading">-Tidak ada data-</h4>
                                @endif
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="form-group">
                                <label for="Jenis Pengajuan">Jenis Pengajuan:</label>
                                <select
                                    class="form-control form-control-sm @error('jenis_pengajuan_id') is-invalid @enderror"
                                    wire:model.live='jenis_pengajuan_id'>
                                    <option value="">Pilih Jenis Pengajuan</option>
                                    <option value="baru">Baru</option>
                                    <option value="perpanjangan">Perpanjangan</option>
                                </select>
                                @error('jenis_pengajuan_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset>Berkas Upload</fieldset>
                            <hr>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="upload_request" class="form-label">Upload Form Request</label>
                                <input
                                    class="form-control form-control-sm @error('upload_request') is-invalid @enderror"
                                    type="file" id="upload_request" wire:model='upload_request'>
                                @error('upload_request')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($jenis_pengajuan_id == 'perpanjangan')
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="upload_id_lama" class="form-label">Upload ID Lama</label>
                                    <input
                                        class="form-control form-control-sm @error('upload_id_lama') is-invalid @enderror"
                                        type="file" id="upload_id_lama" wire:model.live='upload_id_lama'>
                                    @error('upload_id_lama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="upload_foto" class="form-label">Upload FOTO</label>
                                    <input
                                        class="form-control form-control-sm @error('upload_foto') is-invalid @enderror"
                                        type="file" id="upload_foto" wire:model='upload_foto'>
                                    @error('upload_foto')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="upload_ktp" class="form-label">Upload KTP</label>
                                    <input
                                        class="form-control form-control-sm @error('upload_ktp') is-invalid @enderror"
                                        type="file" id="upload_ktp" wire:model='upload_ktp'>
                                    @error('upload_ktp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="upload_skd" class="form-label">Upload SKD</label>
                                    <input
                                        class="form-control form-control-sm @error('upload_skd') is-invalid @enderror"
                                        type="file" id="upload_skd" wire:model='upload_skd'>
                                    @error('upload_skd')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="upload_bpjs" class="form-label">Upload BPJS</label>
                                    <input
                                        class="form-control form-control-sm @error('upload_bpjs') is-invalid @enderror"
                                        type="file" id="upload_bpjs" wire:model='upload_bpjs'>
                                    @error('upload_bpjs')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="form-group">
                                <label for="tgl pengajuan" class="form-label">Tanggal Pengajuan</label>
                                <input class="form-control form-control-sm @error('tgl_pengajuan') is-invalid @enderror"
                                    type="date" wire:model='tgl_pengajuan'>
                                @error('tgl_pengajuan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button wire:click='close' class="btn btn-outline-danger btn-sm">
                                <span class="bi bi-off"></span> Close
                            </button>
                            <button wire:click.prevent='store' type="submit" class="btn btn-outline-success btn-sm">
                                <span class="bi bi-save"></span> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
