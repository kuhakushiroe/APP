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
                        User
                    </div>
                </div>
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_user">
                            <label for="name">Name</label>
                            <input type="text"
                                class="form-control form-control-sm
                    @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Nama User">
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        @if (empty($id_user))
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text"
                                    class="form-control form-control-sm @error('username') is-invalid @enderror"
                                    wire:model="username" placeholder="Username">
                                @error('username')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="role">Role / Level</label>
                            <select class="form-control form-control-sm @error('role') is-invalid @enderror"
                                wire:model.live="role">
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="dokter">Dokter</option>
                                <option value="she">SHE</option>
                                <option value="pimpinan">Pimpinan Dept</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        @if ($role === 'admin')
                            <div class="form-group">
                                <label for="subrole">Sub Role</label>
                                <select class="form-control form-control-sm @error('subrole') is-invalid @enderror"
                                    wire:model.live="subrole">
                                    <option value="">Pilih Sub Role</option>
                                    @forelse ($departments as $data)
                                        <option value="{{ $data->name_department }}">{{ $data->name_department }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('subrole')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @endif
                        @if (empty($id_user))
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password"
                                    class="form-control form-control-sm
                    @error('password') is-invalid @enderror"
                                    wire:model="password" placeholder="password">
                                @error('password')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email"
                                class="form-control form-control-sm
                    @error('email') is-invalid @enderror"
                                wire:model="email" placeholder="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="wa">No. Handphone</label>
                            <input type="wa"
                                class="form-control form-control-sm
                    @error('wa') is-invalid @enderror"
                                wire:model="wa" placeholder="wa">
                            @error('wa')
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
