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
                        Karyawan
                    </div>
                </div>
                <!--end::Header-->

                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="form-group">
                            @if ($fotolama)
                            @endif
                            @if ($foto && is_object($foto))
                                <img src="{{ $foto->temporaryUrl() }}" width="150">
                                <br>
                            @else
                                @if ($fotolama)
                                    <img src="{{ Storage::url($fotolama) }}" alt="Foto" class="img-fluid"
                                        style="max-width: 100px; max-height: 100px;">
                                    <br>
                                @endif
                            @endif
                            <input type="text" class="form-control form-control-sm" wire:model="nrp_lama">
                            <input type="text" class="form-control form-control-sm" wire:model="nama_lama">
                            <input type="text" class="form-control form-control-sm" wire:model="jabatan_lama">
                            <input type="text" class="form-control form-control-sm" wire:model="departemen_lama">
                            <input type="text" class="form-control form-control-sm" wire:model="id_karyawan">
                            <input type="hidden" class="form-control form-control-sm" wire:model="fotolama">
                            <label for="foto">Foto</label>
                            <input type="file"
                                class="form-control form-control-sm @error('foto') is-invalid @enderror"
                                wire:model="foto">
                            @error('foto')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text"
                                class="form-control form-control-sm @error('nik') is-invalid @enderror" wire:model="nik"
                                placeholder="NIK">
                            @error('nik')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nrp">NRP</label>
                            <input type="text"
                                class="form-control form-control-sm @error('nrp') is-invalid @enderror" wire:model="nrp"
                                placeholder="NRP">
                            @error('nrp')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="doh">Tanggal Masuk</label>
                            <input type="date"
                                class="form-control form-control-sm @error('doh') is-invalid @enderror" wire:model="doh"
                                placeholder="Tanggal Masuk">
                            @error('doh')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date"
                                class="form-control form-control-sm @error('tgl_lahir') is-invalid @enderror"
                                wire:model="tgl_lahir" placeholder="Tanggal Lahir">
                            @error('tgl_lahir')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text"
                                class="form-control form-control-sm @error('nama') is-invalid @enderror"
                                wire:model="nama" placeholder="Nama User">
                            @error('nama')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control form-control-sm @error('jenis_kelamin') is-invalid @enderror"
                                wire:model="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text"
                                class="form-control form-control-sm @error('tempat_lahir') is-invalid @enderror"
                                wire:model="tempat_lahir" placeholder="Tempat Lahir">
                            @error('tempat_lahir')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="agama">Agama</label>
                            <select class="form-control form-control-sm @error('agama') is-invalid @enderror"
                                wire:model="agama">
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('agama')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gol_darah">Golongan Darah</label>
                            <select class="form-control form-control-sm @error('gol_darah') is-invalid @enderror"
                                wire:model="gol_darah">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                            @error('gol_darah')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status_perkawinan">Status Perkawinan</label>
                            <select
                                class="form-control form-control-sm @error('status_perkawinan') is-invalid @enderror"
                                wire:model="status_perkawinan">
                                <option value="">Pilih Status Perkawinan</option>
                                <option value="menikah">Menikah</option>
                                <option value="belum menikah">Belum Menikah</option>
                            </select>
                            @error('status_perkawinan')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="perusahaan">Perusahaan</label>
                            <select class="form-control form-control-sm @error('perusahaan') is-invalid @enderror"
                                wire:model="perusahaan">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($perusahaans as $dataperusahaan)
                                    <option value="{{ $dataperusahaan->nama_perusahaan }}">
                                        {{ $dataperusahaan->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('perusahaan')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kontraktor">Mitra Perusahaan</label>
                            <input type="text"
                                class="form-control form-control-sm @error('kontraktor') is-invalid @enderror"
                                wire:model="kontraktor" placeholder="Mitra Perusahaan">
                            @error('kontraktor')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        @if (auth()->user()->hasRole('superadmin'))
                            <div class="form-group">
                                <label for="dept">Departemen</label>
                                <select class="form-control form-control-sm @error('dept') is-invalid @enderror"
                                    wire:model="dept">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->name_department }}">
                                            {{ $department->name_department }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dept')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @else
                            <div class="form-group">
                                <label for="dept">Departemen</label>
                                <input type="text"
                                    class="form-control form-control-sm @error('dept') is-invalid @enderror"
                                    wire:model="dept" placeholder="dept" readonly>
                                @error('dept')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <select class="form-control form-control-sm @error('jabatan') is-invalid @enderror"
                                wire:model="jabatan">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatans as $datajabatan)
                                    <option value="{{ $datajabatan->nama_jabatan }}">
                                        {{ $datajabatan->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jabatan')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No. HP</label>
                            <input type="text"
                                class="form-control form-control-sm @error('no_hp') is-invalid @enderror"
                                wire:model="no_hp" placeholder="No. HP">
                            @error('no_hp')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text"
                                class="form-control form-control-sm @error('alamat') is-invalid @enderror"
                                wire:model="alamat" placeholder="Alamat">
                            @error('alamat')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="domisili">Domisili</label>
                            <select class="form-control form-control-sm @error('domisili') is-invalid @enderror"
                                wire:model="domisili">
                                <option value="">Pilih Domisili</option>
                                <option value="lokal">Lokal</option>
                                <option value="non lokal">Non Lokal</option>
                            </select>
                            @error('domisili')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control form-control-sm @error('status') is-invalid @enderror"
                                wire:model="status">
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="non aktif">Non Aktif</option>
                            </select>
                            @error('status')
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
