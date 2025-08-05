<div>
    @if ($form)
        @include('livewire.karyawan.form')
    @elseif ($lihatdetail)
        @include('livewire.karyawan.detail')
    @elseif ($perubahan)
        @include('livewire.karyawan.perubahan')
    @else
        <div class="row pt-2">
            <div class="col-md-12">
                @hasAnyRole(['superadmin', 'admin'])
                    <button class="btn btn-dark btn-sm" wire:click="open">
                        <span class="bi bi-plus-square"></span>
                        &nbsp;Karyawan
                    </button>
                    @hasAnyRole(['superadmin'])
                        <button class="btn btn-success btn-sm" wire:click="export">
                            <span class="bi bi-download"></span>
                            &nbsp;Export
                        </button>

                        @if ($formImport)
                        @else
                            <button class="btn btn-warning btn-sm" wire:click="openImport">
                                <span class="bi bi-upload"></span>
                                &nbsp;Import
                            </button>
                        @endif
                        <a href="{{ route('try-export') }}" class="btn btn-success btn-sm">
                            <span class="bi bi-download"></span>
                            &nbsp;Export Model Cheklist
                        </a>
                        @if ($formImportCek)
                        @else
                            <button class="btn btn-warning btn-sm" wire:click="openImportCek">
                                <span class="bi bi-upload"></span>
                                &nbsp;Import By Cek
                            </button>
                        @endif
                    @endhasAnyRole
                @endhasAnyRole
            </div>
            <div class="col-md-6 pt-2">
                @if ($formImport)
                    <form wire:submit.prevent="import">
                        <div class="input-group">
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                wire:model="file">
                            <button class="btn btn-outline-secondary" type="submit">Upload</button>
                            <button class="btn btn-outline-danger" type="reset"
                                wire:click.prevent="closeImport">Batal</button>
                            @error('file')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </form>
                @endif
                @if ($formImportCek)
                    <form wire:submit.prevent="importCek">
                        <div class="input-group">
                            <input type="file" class="form-control @error('fileCek') is-invalid @enderror"
                                wire:model="fileCek">
                            <button class="btn btn-outline-secondary" type="submit">Upload</button>
                            <button class="btn btn-outline-danger" type="reset"
                                wire:click.prevent="closeImportCek">Batal</button>
                            @error('fileCek')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </form>
                @endif
            </div>
            <div class="col-md-12">
                <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model.live="search">
            </div>
        </div>
        <!-- resources/views/datakaryawans/index.blade.php -->
        <div class="table table-responsive pt-2">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th>NIK</th>
                        <th>NRP</th>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th>No HP</th>
                        <th>Domisili</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawans as $index => $datakaryawan)
                        <tr @if ($datakaryawan->status == 'non aktif') class="table-danger" @endif>
                            <td>
                                @if ($datakaryawan->status == 'aktif')
                                    @php
                                        $cariperubahan = DB::table('log_karyawan')
                                            ->where('id_karyawan', $datakaryawan->id)
                                            ->get();
                                    @endphp
                                    @if ($cariperubahan->count() > 0)
                                        <button class="btn btn-outline-warning btn-sm"
                                            wire:click="openPerubahan({{ $datakaryawan->id }})">
                                            <span class="bi bi-exclamation-diamond"></span>
                                        </button>
                                    @endif
                                    @php
                                        $cariusers = DB::table('users')->where('username', $datakaryawan->nrp)->first();
                                    @endphp
                                    @hasAnyRole(['superadmin'])
                                        @if (!$cariusers && $datakaryawan->status == 'aktif' && $datakaryawan->status_karyawan !== 'TEMPORARY')
                                            <button class="btn btn-outline-success btn-sm"
                                                wire:click="buatakun('{{ $datakaryawan->nrp }}')">
                                                <span class="bi bi-key"></span><span class="bi bi-person"></span>
                                            </button>
                                        @endif
                                    @endhasAnyRole
                                    <button class="btn btn-outline-info btn-sm"
                                        wire:click="detail({{ $datakaryawan->id }})">
                                        <span class="bi bi-eye"></span>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm"
                                        wire:click="edit({{ $datakaryawan->id }})">
                                        <span class="bi bi-pencil"></span>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm"
                                        wire:click="aktif({{ $datakaryawan->id }})">
                                        <span class="bi bi-trash"></span>
                                    </button>
                                @else
                                    <button class="btn btn-outline-success btn-sm"
                                        wire:click="nonAktif({{ $datakaryawan->id }})">
                                        <span class="bi bi-repeat"></span>
                                    </button>
                                @endif
                            </td>
                            <td>
                                {{ $datakaryawan->status }}<br>
                                {{ $datakaryawan->status_karyawan }}
                            </td>
                            <td>
                                @if ($datakaryawan->foto)
                                    {{-- <img src="{{ Storage::disk('s3')->temporaryUrl($datakaryawan->foto, now()->addMinutes(10)) }}"
                                        alt="Foto" class="img-fluid" style="max-width: 100px; max-height: 100px;"> --}}
                                    <img src="{{ Storage::url($datakaryawan->foto) }}" alt="Foto" class="img-fluid"
                                        style="max-width: 100px; max-height: 100px;">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $datakaryawan->nik }}</td>
                            <td>{{ $datakaryawan->nrp }}</td>
                            <td>{{ $datakaryawan->nama }}</td>
                            <td>{{ $datakaryawan->perusahaan }}</td>
                            <td>{{ $datakaryawan->dept }}</td>
                            <td>{{ $datakaryawan->jabatan }}</td>
                            <td>{{ $datakaryawan->no_hp }}</td>
                            <td>{{ $datakaryawan->domisili }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">
                                <div class="alert alert-danger">
                                    <span class="bi bi-exclamation-circle"></span>
                                    &nbsp;No data
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $karyawans->appends(['search' => $search])->links() }}
    @endif
</div>
