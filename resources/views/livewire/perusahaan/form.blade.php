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
                        Perusahaan
                    </div>
                </div>
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_perusahaan">
                            <label for="nama_perusahaan">Nama</label>
                            <input type="text"
                                class="form-control form-control-sm @error('nama_perusahaan') is-invalid @enderror"
                                wire:model="nama_perusahaan" placeholder="Nama Perusahaan">
                            @error('nama_perusahaan')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text"
                                class="form-control form-control-sm @error('keterangan_perusahaan') is-invalid @enderror"
                                wire:model="keterangan_perusahaan" placeholder="Keterangan Perusahaan">
                            @error('keterangan_perusahaan')
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
