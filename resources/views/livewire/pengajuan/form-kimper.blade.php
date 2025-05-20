<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">

                    <h4 class="card-title">Tambah Pengajuan Kimper</h4>
                </div>
                <div class="card-body">
                    <form action="" wire:submit.prevent="store">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_pengajuan">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_karyawan">
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
                                    <li>
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
                                    </li>
                                @else
                                    <h4 class="alert-heading">-Tidak ada data-</h4>
                                @endif
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="jenis Pengajuan">Jenis Pengajuan</label>
                            <select
                                class="form-control form-control-sm @error('jenis_pengajuan_kimper') is-invalid @enderror"
                                wire:model="jenis_pengajuan_kimper">
                                <option value="">Pilih Pengajuan</option>
                                <option value="baru">Baru</option>
                                <option value="perpanjangan">Perpanjang</option>
                                <option value="penambahan">Penambahan Versatility</option>
                            </select>
                            @error('jenis_pengajuan_kimper')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_id" class="form-label">Upload ID</label>
                            <input class="form-control form-control-sm @error('upload_id') is-invalid @enderror"
                                type="file" id="upload_id" wire:model.live='upload_id'>
                            @error('upload_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_kimper_lama" class="form-label">Upload Kimper Lama</label>
                            <input
                                class="form-control form-control-sm @error('upload_kimper_lama') is-invalid @enderror"
                                type="file" id="upload_kimper_lama" wire:model.live='upload_kimper_lama'>
                            @error('upload_kimper_lama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_request" class="form-label">Upload Form Request</label>
                            <input class="form-control form-control-sm @error('upload_request') is-invalid @enderror"
                                type="file" id="upload_request" wire:model.live='upload_request'>
                            @error('upload_request')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_sim" class="form-label">Upload SIM</label>
                            <input class="form-control form-control-sm @error('upload_sim') is-invalid @enderror"
                                type="file" id="upload_sim" wire:model.live='upload_sim'>
                            @error('upload_sim')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Jenis SIM -->
                        <div class="form-group">
                            <label for="jenis_sim">Jenis SIM</label>
                            <select name="jenis_sim"
                                class="form-control form-control-sm @error('jenis_sim') is-invalid @enderror"
                                wire:model="jenis_sim">
                                <option value="">Pilih Sim</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="B1">B1</option>
                                <option value="B1 UMUM">B1 UMUM</option>
                                <option value="B2">B2</option>
                                <option value="B2 UMUM">B2 UMUM</option>
                            </select>
                            @error('jenis_sim')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_sertifikat" class="form-label">Upload Sertifikat</label>
                            <input class="form-control form-control-sm @error('upload_sertifikat') is-invalid @enderror"
                                type="file" id="upload_sertifikat" wire:model.live='upload_sertifikat'>
                            @error('upload_sertifikat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_lpo" class="form-label">Upload LPO</label>
                            <input class="form-control form-control-sm @error('upload_lpo') is-invalid @enderror"
                                type="file" id="upload_lpo" wire:model.live='upload_lpo'>
                            @error('upload_lpo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_foto" class="form-label">Upload Foto</label>
                            <input class="form-control form-control-sm @error('upload_foto') is-invalid @enderror"
                                type="file" id="upload_foto" wire:model.live='upload_foto'>
                            @error('upload_foto')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_ktp" class="form-label">Upload KTP</label>
                            <input class="form-control form-control-sm @error('upload_ktp') is-invalid @enderror"
                                type="file" id="upload_ktp" wire:model.live='upload_ktp'>
                            @error('upload_ktp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_skd" class="form-label">Upload SKD</label>
                            <input class="form-control form-control-sm @error('upload_skd') is-invalid @enderror"
                                type="file" id="upload_skd" wire:model.live='upload_skd'>
                            @error('upload_skd')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_bpjs_kes" class="form-label">Upload BPJS Kesehatan</label>
                            <input class="form-control form-control-sm @error('upload_bpjs_kes') is-invalid @enderror"
                                type="file" id="upload_bpjs_kes" wire:model.live='upload_bpjs_kes'>
                            @error('upload_bpjs_kes')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upload_bpjs_ker" class="form-label">Upload BPJS Ketenagakerjaan</label>
                            <input class="form-control form-control-sm @error('upload_bpjs_ker') is-invalid @enderror"
                                type="file" id="upload_bpjs_ker" wire:model.live='upload_bpjs_ker'>
                            @error('upload_bpjs_ker')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group pt-2">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            <button class="btn btn-outline-danger btn-sm" wire:click="close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
