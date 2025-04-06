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
                        Jabatan
                    </div>
                </div>
                <!--end::Header-->

                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_jabatan">
                            <label for="nama_jabatan">Nama</label>
                            <input type="text"
                                class="form-control form-control-sm
                            @error('nama_jabatan') is-invalid @enderror"
                                wire:model="nama_jabatan" placeholder="Nama Jabatan">
                            @error('nama_jabatan')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text"
                                class="form-control form-control-sm
                            @error('keterangan_jabatan') is-invalid @enderror"
                                wire:model="keterangan_jabatan" placeholder="Keterangan Jabatan">
                            @error('keterangan_jabatan')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right mt-2">
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
