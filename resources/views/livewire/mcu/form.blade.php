<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">
                        <button class="btn btn-outline-danger btn-sm" wire:click="close">
                            <span class="bi bi-arrow-left"></span>
                        </button>
                        @if ($id_mcu)
                            Pengajuan Ulang MCU
                        @else
                            Pengajuan MCU
                        @endif
                    </div>
                </div>
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_mcu">
                            @if ($carifoto)
                                @if (!empty($carifoto->foto))
                                    <label>Foto:</label>
                                    <br>
                                    <img src="{{ Storage::url($carifoto->foto) }}" alt="Foto" class="img-fluid"
                                        style="max-width: 100px; max-height: 150px;">
                                @else
                                @endif
                                <br>
                            @endif
                            <label for="browser">NRP:</label>
                            <input class="form-control form-control-sm @error('nrp') is-invalid @enderror"
                                list="browsers" name="browser" id="browser" wire:model.live='nrp'
                                oninput="updateInput(this)" placeholder="Masukkan NRP">
                            <datalist id="browsers">
                                @foreach ($carikaryawan as $datakaryawan)
                                    <option value="{{ $datakaryawan->nrp }}-{{ $datakaryawan->nama }}">
                                @endforeach
                            </datalist>
                            @error('nrp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama:</label>
                            <input class="form-control form-control-sm" list="browsers" wire:model='nama' readonly>
                        </div>
                        <div class="form-group">
                            <label for="jenis_pengajuan_mcu">Jenis Pengajuan MCU</label>
                            <select
                                class="form-control form-control-sm @error('jenis_pengajuan_mcu') is-invalid @enderror"
                                wire:model="jenis_pengajuan_mcu" placeholder="Keterangan Perusahaan">
                                <option value="">Pilih Jenis Pengajuan MCU</option>
                                <option value="Pre Employeed MCU">Pre Employeed MCU</option>
                                <option value="Annual MCU">Annual MCU</option>
                                <option value="MCU Khusus">MCU Khusus</option>
                                <option value="Exit MCU">Exit MCU</option>
                            </select>
                            @error('jenis_pengajuan_mcu')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="file_mcu">File MCU</label>
                            <input type="file"
                                class="form-control form-control-sm @error('file_mcu') is-invalid @enderror"
                                wire:model="file_mcu" placeholder="Keterangan Perusahaan">
                            @error('file_mcu')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin:</label>
                            <select class="form-control form-control-sm @error('jenis_kelamin') is-invalid @enderror"
                                id="jenis_kelamin" wire:model.live="jenis_kelamin">
                                <option value="">-Pilih Jenis Kelamin-</option>
                                <option value="laki-laki">Laki - Laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{-- <label for="gol_darah">Gol Darah:</label> --}}
                            <input type="hidden"
                                class="form-control form-control-sm @error('gol_darah') is-invalid @enderror"
                                wire:model="gol_darah">
                            @error('gol_darah')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="proveder">Proveder</label>
                            <input type="text"
                                class="form-control form-control-sm @error('proveder') is-invalid @enderror"
                                wire:model="proveder" placeholder="Proveder">
                            @error('proveder')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_mcu">Tanggal MCU</label>
                            <input type="date"
                                class="form-control form-control-sm @error('tgl_mcu') is-invalid @enderror"
                                wire:model="tgl_mcu" placeholder="Tanggal MCU">
                            @error('tgl_mcu')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right mt-2"
                                wire:loading.attr="disabled" wire:target="file_mcu">
                                <span class="bi bi-save"></span>
                                &nbsp;Save
                            </button>
                        </div>

                    </div>
                </form>
            </div>
            <!--end::Quick Example-->
        </div>
    </div>
</div>
