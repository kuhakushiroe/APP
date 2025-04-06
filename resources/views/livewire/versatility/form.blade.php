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
                        Versatility
                    </div>
                </div>
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_versatility">
                            <label for="versatility">Nama</label>
                            <input type="text"
                                class="form-control form-control-sm @error('versatility') is-invalid @enderror"
                                wire:model="versatility" placeholder="Nama versatility">
                            @error('versatility')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Keterangan</label>
                            <input type="text"
                                class="form-control form-control-sm @error('description') is-invalid @enderror"
                                wire:model="description" placeholder="description">
                            @error('description')
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
