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
                        Edit Tanggal MCU
                    </div>
                </div>
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_mcu">
                            <input type="hidden" class="form-control form-control-sm" wire:model="nrp">
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
                            <label for="tgl_verifikasi">Tanggal Verifikasi</label>
                            <input type="date"
                                class="form-control form-control-sm @error('tgl_verifikasi') is-invalid @enderror"
                                wire:model="tgl_verifikasi" placeholder="Tanggal MCU">
                            @error('tgl_verifikasi')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exp_mcu">Exp MCU</label>
                            <input type="date"
                                class="form-control form-control-sm @error('exp_mcu') is-invalid @enderror"
                                wire:model="exp_mcu" placeholder="Tanggal MCU">
                            @error('exp_mcus')
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
