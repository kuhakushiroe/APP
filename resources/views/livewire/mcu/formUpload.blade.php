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
                        Upload MCU {{ $this->no_dokumen }}
                    </div>
                </div>
                <form wire:submit.prevent="storeUpload">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="sub_id" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="riwayat_rokok"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="BB" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="TB" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="LP" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="BMI" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="Laseq" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="reqtal_touche"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="sistol" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="diastol" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="OD_jauh" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="OS_jauh" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="OD_dekat" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="OS_dekat" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="butawarna" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="gdp" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="gd_2_jpp" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="ureum" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="creatine" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="asamurat" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="sgot" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="sgpt" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="hbsag" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="anti_hbs" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="kolesterol" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="hdl" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="ldl" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="tg" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="darah_rutin"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="napza" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="urin" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="ekg" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="rontgen" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="audiometri"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="spirometri"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="tredmil_test"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="widal_test"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="routin_feces"
                                readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="kultur_feces"
                                readonly>

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
                            <label for="file_mcu">File MCU</label>
                            <input type="file"
                                class="form-control form-control-sm @error('file_mcu') is-invalid @enderror"
                                wire:model="file_mcu" placeholder="Keterangan Perusahaan">
                            @error('file_mcu')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
