<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">
                        Ganti Password
                    </div>
                </div>
                <form wire:submit.prevent="UpdatePassword">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_user">
                            <label for="password">Password</label>
                            <input type="password"
                                class="form-control form-control-sm
                            @error('password') is-invalid @enderror"
                                wire:model="password" placeholder="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group float-right mt-2">
                            <button type="submit" class="btn btn-primary btn-sm">
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
