<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">

                    <h4 class="card-title">Tambah Pengajua Kimper</h4>
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

                        <!-- File Upload Fields -->
                        @foreach ([
        'id' => 'ID',
        'kimper_lama' => 'Kimper Lama',
        'request' => 'Form Request',
        'sim' => 'SIM',
        'lpo' => 'LPO',
        'foto' => 'Foto',
        'ktp' => 'KTP',
        'skd' => 'SKD',
        'bpjs_kes' => 'BPJS Kesehatan',
        'bpjs_ker' => 'BPJS Ketenagakerjaan',
    ] as $key => $label)
                            <div class="form-group">
                                <label for="upload_{{ $key }}">{{ $label }}</label>
                                <input type="file"
                                    class="form-control form-control-sm @error('upload.' . $key) is-invalid @enderror"
                                    wire:model="upload.{{ $key }}">
                                @error('upload.' . $key)
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach

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

                        <button class="btn btn-outline-danger btn-sm" wire:click="close">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
