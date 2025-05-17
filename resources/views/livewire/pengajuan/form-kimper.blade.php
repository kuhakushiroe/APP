<form action="" wire:submit.prevent="store">
    <input type="hidden" class="form-control form-control-sm" wire:model="id_pengajuan">
    <input type="hidden" class="form-control form-control-sm" wire:model="id_karyawan">
    <div class="col-12">
        <div class="form-group">
            <label for="NRP">NRP:</label>
            <input class="form-control form-control-sm @error('nrp') is-invalid @enderror" wire:model.live='nrp'
                placeholder="Masukkan NRP">
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
    <label for="jenis Pengajuan">Jenis Pengajuan</label>
    <select class="form-control form-control-sm" wire:model="jenis_pengajuan_kimper">
        <option value="">Pilih Pengajuan</option>
        <option value="baru">Baru</option>
        <option value="perpanjangan">Perpanjang</option>
        <option value="penambahan">Penambahan Versatility</option>
    </select>
    <label for="id">ID</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_id">
    <label for="id">Kimper Lama</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_kimper_lama">
    <label for="id">Form Request</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_request">
    <label for="Upload Sim">Sim</label>
    <select name="jenis_sim" id="" class="form-control form-control-sm" wire:model="jenis_sim">
        <option value="">Pilih Sim</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="B1">B1</option>
        <option value="B1 UMUM">B1 UMUM</option>
        <option value="B2">B2</option>
        <option value="B2 UMUM">B2 UMUM</option>
    </select>
    <input type="file" class="form-control form-control-sm" wire:model="upload_sim">
    <label for="upload LPO">LPO</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_lpo">
    <label for="upload Foto">Foto</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_foto">
    <label for="upload KTP">KTP</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_ktp">
    <label for="upload SKD">SKD</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_skd">
    <label for="bpjs kesehatan">Bpjs Kesehatan</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_bpjs_kes">
    <label for="bpjs ketenagakerjaan">Bpjs Ketenagakerjaan</label>
    <input type="file" class="form-control form-control-sm" wire:model="upload_bpjs_ker">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
