<div>
    @if ($form)
        @include('livewire.mcu.form')
    @elseif ($formVerifikasi)
        @include('livewire.mcu.formVerifikasi')
    @elseif ($formUpload)
        @include('livewire.mcu.formUpload')
    @else
        <div class="row">
            <div class="col-md-3">
                @hasAnyRole(['admin', 'superadmin'])
                    <button class="btn btn-dark btn-sm" wire:click="open">
                        <span class="bi bi-plus-square"></span>
                        &nbsp;Mcu
                    </button>
                @endhasanyrole
            </div>
            <div class="col-md-6">
                &nbsp;
            </div>
            <div class="col-md-3">
                <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model.live="search">
            </div>
        </div>
        <div class="row pt-2">
            <div class="col">
                @forelse ($mcus as $data)
                    @php
                        // Ambil induk (parent item)
                        $subItems = DB::table('mcu')
                            ->where('sub_id', $data->id_mcu) // Ambil subitem berdasarkan sub_id induk
                            ->orderBy('tgl_mcu', 'asc') // Mengurutkan berdasarkan tanggal MCU
                            ->get(); // Ambil semua subitem sebagai collection

                        // Cek apakah induk sudah diverifikasi
                        $indukVerified = !empty($data->mcuStatus);

                        // Cek apakah semua subitem sudah diverifikasi (atau tidak ada subitem sama sekali)
                        $allSubVerified = $subItems->every(function ($item) {
                            return !empty($item->status);
                        });

                        // Hanya bisa upload jika induk sudah diverifikasi dan semua subitem juga sudah diverifikasi
                        $canUpload = $indukVerified && $allSubVerified;
                    @endphp
                    <div class="card card-primary card-outline mb-4" wire:poll.5s>
                        <div class="card-header">
                            <div class="card-title">
                                {{ $data->nama }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-2 col-md-2">
                                    @if ($data->foto)
                                        <img src="{{ Storage::url($data->foto) }}" alt="Foto" class="img-fluid"
                                            style="max-width: 100px; max-height: 100px;">
                                    @endif
                                </div>

                                <div class="col-12 col-sm-5 col-md-5">
                                    <table>
                                        <tr>
                                            <td>NRP</td>
                                            <td>:</td>
                                            <td>{{ $data->nrp }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama</td>
                                            <td>:</td>
                                            <td>{{ $data->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>:</td>
                                            <td>{{ $data->jenis_kelamin }}</td>
                                        </tr>
                                        <tr>
                                            <td>Dept/Posisi</td>
                                            <td>:</td>
                                            <td>{{ $data->dept }} / {{ $data->jabatan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Gol Darah</td>
                                            <td>:</td>
                                            <td>{{ $data->gol_darah }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pengajuan</td>
                                            <td>:</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($data->tgl_mcu)->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            <td>{{ $data->status_ }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-5 col-md-5">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File Pengajuan MCU</th>
                                                <th>Tgl MCU</th>
                                                <th>Hasil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <a href="{{ Storage::url($data->file_mcu) }}" target="_blank">
                                                        <span class="bi bi-file-earmark-arrow-down"></span> File MCU
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($data->tgl_mcu)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                </td>
                                                <td>
                                                    @if (empty($data->mcuStatus))
                                                        @if (empty($data->paramedik))
                                                            @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['paramedik']))
                                                                @if ($data->status_file_mcu === null)
                                                                    <form action=""
                                                                        wire:submit.prevent="kirimStatusFileMCU({{ $data->id_mcu }})">
                                                                        <select
                                                                            wire:model.live="status_file_mcu.{{ $data->id_mcu }}"
                                                                            class="form-control form-control-sm @error('status_file_mcu.' . $data->id_mcu) is-invalid @enderror">
                                                                            <option value="">-Pilih Status-
                                                                            </option>
                                                                            <option value="1">Diterima</option>
                                                                            <option value="0">Ditolak</option>
                                                                        </select>
                                                                        @error('status_file_mcu.' . $data->id_mcu)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        @if ($status_file_mcu[$data->id_mcu] == '0')
                                                                            {{-- Memastikan bahwa status sudah "Ditolak" --}}
                                                                            <textarea wire:model="catatan_file_mcu.{{ $data->id_mcu }}" class="form-control form-control-sm "
                                                                                placeholder="Tulis Catatan"></textarea>
                                                                            @error('catatan_file_mcu.' . $data->id_mcu)
                                                                                <span class="invalid-feedback"
                                                                                    role="alert"><strong>{{ $message }}</strong></span>
                                                                            @enderror
                                                                        @endif
                                                                        <div
                                                                            class="d-grid
                                                                        gap-2">
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm">
                                                                                <span class="bi bi-send"></span> Kirim
                                                                            </button>
                                                                        </div>

                                                                    </form>
                                                                @else
                                                                    @if ($data->status_file_mcu == '0')
                                                                        <button class="btn btn-outline-warning btn-sm"
                                                                            disabled>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm"></span>
                                                                            Upload Ulang
                                                                        </button>
                                                                    @elseif ($data->status_file_mcu == '1')
                                                                        <span
                                                                            class="text-danger">{{ $data->paramedik_catatan ?? '' }}</span>
                                                                        <br>
                                                                        <button class="btn btn-outline-warning btn-sm"
                                                                            wire:click="verifikasi({{ $data->id_mcu }})">
                                                                            <span class="bi bi-file-check"></span>
                                                                            Verifikasi Paramedik
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-outline-warning btn-sm"
                                                                            wire:click="verifikasi({{ $data->id_mcu }})">
                                                                            <span class="bi bi-file-check"></span>
                                                                            Verifikasi Paramedik
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                {{-- ADMIN LEVEL --}}
                                                                @if ($data->status_file_mcu == '0')
                                                                    <span class="text-danger">
                                                                        "{{ $data->catatan_file_mcu }}"
                                                                        @hasAnyRole(['admin', 'superadmin'])
                                                                            <button class="btn btn-warning btn-sm"
                                                                                wire:click="edit({{ $data->id_mcu }})">
                                                                                <span class="bi bi-upload"></span>
                                                                                &nbsp;Ulangi Upload Mcu
                                                                            </button>
                                                                        @endhasanyrole
                                                                    </span>
                                                                @else
                                                                    @if ($data->status_file_mcu == '1')
                                                                        <span
                                                                            class="text-danger">{{ $data->paramedik_catatan ?? '' }}</span><br>
                                                                        <button class="btn btn-warning btn-sm"
                                                                            type="button" disabled>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm"
                                                                                aria-hidden="true"></span>
                                                                            <span role="status">Paramedik</span>
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-warning btn-sm"
                                                                            type="button" disabled>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm"
                                                                                aria-hidden="true"></span>
                                                                            <span role="status">Paramedik</span>
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['verifikator']))
                                                                @if ($data->paramedik_status == '1' || $data->paramedik_status == null)
                                                                    <button class="btn btn-outline-warning btn-sm"
                                                                        wire:click="verifikasi({{ $data->id_mcu }})">
                                                                        <span class="bi bi-file-check"></span>
                                                                        Verifikasi Dokter
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-warning btn-sm"
                                                                        type="button" disabled>
                                                                        <span class="spinner-border spinner-border-sm"
                                                                            aria-hidden="true"></span>
                                                                        <span role="status">Paramedik</span>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                @if ($data->paramedik_status == '0')
                                                                    <span class="text-danger">
                                                                        "{{ $data->paramedik_catatan }}"
                                                                    </span>
                                                                    <button class="btn btn-outline-warning btn-sm"
                                                                        wire:click="verifikasi({{ $data->id_mcu }})">
                                                                        <span class="bi bi-file-check"></span>
                                                                        Verifikasi Paramedik
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-warning btn-sm"
                                                                        type="button" disabled>
                                                                        <span class="spinner-border spinner-border-sm"
                                                                            aria-hidden="true"></span>
                                                                        <span role="status">Dokter</span>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{ $data->mcuStatus }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">-</td>
                                                <td colspan="3">
                                                    @if (!empty($data->mcuStatus))
                                                        <div class="mb-2">
                                                            <strong>Hasil:</strong>
                                                        </div>

                                                        <div class="mb-1">
                                                            <b>{{ $data->saran_mcu }}</b>
                                                        </div>

                                                        <div class="mb-1 text-muted">
                                                            <small><i>{{ $data->keterangan_mcu }}</i></small>
                                                        </div>

                                                        <div class="mb-3 text-muted">
                                                            <small><i>{{ $data->tgl_verifikasi }}</i></small>
                                                        </div>

                                                        <div class="mb-2">
                                                            <a href="{{ url('cetak-mcu-sub/' . $data->id_mcu) }}"
                                                                target="_blank" class="btn btn-outline-warning btn-sm">
                                                                <i class="bi bi-download"></i> Download Verifikasi
                                                                {{ $data->mcuStatus }}
                                                            </a>
                                                        </div>

                                                        <div>
                                                            <a href="{{ asset('storage/surat jawaban konsul.docx') }}"
                                                                target="_blank" class="btn btn-outline-warning btn-sm">
                                                                <i class="bi bi-download"></i> Download Surat Jawaban
                                                                Konsul
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>

                                            </tr>
                                            @php $row = 2; @endphp
                                            @foreach ($subItems as $item)
                                                <tr>
                                                    <td>{{ $row++ }}</td>
                                                    <td>
                                                        <a href="{{ Storage::url($item->file_mcu) }}"
                                                            target="_blank">
                                                            <span class="bi bi-file-earmark-arrow-down"></span> File
                                                            MCU
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->tgl_mcu }}</td>
                                                    <td>
                                                        @if (empty($item->status))
                                                            @if (empty($item->paramedik))
                                                                @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['paramedik']))
                                                                    @if ($item->status_file_mcu === null)
                                                                        <form action=""
                                                                            wire:submit.prevent="kirimStatusFileMCU({{ $item->id }})">
                                                                            <select
                                                                                wire:model.live="status_file_mcu.{{ $item->id }}"
                                                                                class="form-control form-control-sm @error('status_file_mcu.' . $item->id) is-invalid @enderror">
                                                                                <option value="">-Pilih Status-
                                                                                </option>
                                                                                <option value="1">Diterima
                                                                                </option>
                                                                                <option value="0">Ditolak</option>
                                                                            </select>
                                                                            @error('status_file_mcu.' . $item->id)
                                                                                <span class="invalid-feedback"
                                                                                    role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                            @if ($status_file_mcu[$item->id] == '0')
                                                                                {{-- Memastikan bahwa status sudah "Ditolak" --}}
                                                                                <textarea wire:model="catatan_file_mcu.{{ $item->id }}" class="form-control form-control-sm "
                                                                                    placeholder="Tulis Catatan"></textarea>
                                                                                @error('catatan_file_mcu.' . $item->id)
                                                                                    <span class="invalid-feedback"
                                                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                                                @enderror
                                                                            @endif
                                                                            <div
                                                                                class="d-grid
                                                                        gap-2">
                                                                                <button type="submit"
                                                                                    class="btn btn-danger btn-sm">
                                                                                    <span class="bi bi-send"></span>
                                                                                    Kirim
                                                                                </button>
                                                                            </div>

                                                                        </form>
                                                                    @else
                                                                        @if ($item->status_file_mcu == '0')
                                                                            <button
                                                                                class="btn btn-outline-warning btn-sm"
                                                                                disabled>
                                                                                <span
                                                                                    class="spinner-border spinner-border-sm"></span>
                                                                                Upload Ulang
                                                                            </button>
                                                                        @elseif ($item->status_file_mcu == '1')
                                                                            <span
                                                                                class="text-danger">{{ $item->paramedik_catatan ?? '' }}</span><br>
                                                                            <button
                                                                                class="btn btn-outline-warning btn-sm"
                                                                                wire:click="verifikasi({{ $item->id }})">
                                                                                <span class="bi bi-file-check"></span>
                                                                                Verifikasi Paramedik
                                                                            </button>
                                                                        @else
                                                                            <button
                                                                                class="btn btn-outline-warning btn-sm"
                                                                                wire:click="verifikasi({{ $item->id }})">
                                                                                <span class="bi bi-file-check"></span>
                                                                                Verifikasi Paramedik
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    {{-- ADMIN LEVEL --}}
                                                                    @if ($item->status_file_mcu == '0')
                                                                        <span class="text-danger">
                                                                            "{{ $item->catatan_file_mcu }}"
                                                                            @hasAnyRole(['admin', 'superadmin'])
                                                                                <button class="btn btn-warning btn-sm"
                                                                                    wire:click="edit({{ $item->id }})">
                                                                                    <span class="bi bi-upload"></span>
                                                                                    &nbsp;Ulangi Upload Mcu
                                                                                </button>
                                                                            @endhasanyrole
                                                                        </span>
                                                                    @else
                                                                        @if ($item->status_file_mcu == '1')
                                                                            <span
                                                                                class="text-danger">{{ $item->paramedik_catatan ?? '' }}</span><br>
                                                                            <button class="btn btn-warning btn-sm"
                                                                                type="button" disabled>
                                                                                <span
                                                                                    class="spinner-border spinner-border-sm"
                                                                                    aria-hidden="true"></span>
                                                                                <span role="status">Paramedik</span>
                                                                            </button>
                                                                        @else
                                                                            <button class="btn btn-warning btn-sm"
                                                                                type="button" disabled>
                                                                                <span
                                                                                    class="spinner-border spinner-border-sm"
                                                                                    aria-hidden="true"></span>
                                                                                <span role="status">Paramedik</span>
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['verifikator']))
                                                                    @if ($item->paramedik_status == '1' || $item->paramedik_status == null)
                                                                        <button class="btn btn-outline-warning btn-sm"
                                                                            wire:click="verifikasi({{ $item->id }})">
                                                                            <span class="bi bi-file-check"></span>
                                                                            Verifikasi Dokter
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-warning btn-sm"
                                                                            type="button" disabled>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm"
                                                                                aria-hidden="true"></span>
                                                                            <span role="status">Paramedik</span>
                                                                        </button>
                                                                    @endif
                                                                @else
                                                                    @if ($item->paramedik_status == '0')
                                                                        <span class="text-danger">
                                                                            "{{ $item->paramedik_catatan }}"
                                                                        </span><br>
                                                                        <button class="btn btn-outline-warning btn-sm"
                                                                            wire:click="verifikasi({{ $item->id }})">
                                                                            <span class="bi bi-file-check"></span>
                                                                            Verifikasi Paramedik
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-warning btn-sm"
                                                                            type="button" disabled>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm"
                                                                                aria-hidden="true"></span>
                                                                            <span role="status">Dokter</span>
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @else
                                                            {{ $item->status }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">-</td>
                                                    <td colspan="3">
                                                        @if (!empty($item->status))
                                                            <div class="mb-2">
                                                                <strong>Hasil:</strong>
                                                            </div>

                                                            <div class="mb-1">
                                                                <b>{{ $item->saran_mcu }}</b>
                                                            </div>

                                                            <div class="mb-1 text-muted">
                                                                <small><i>{{ $item->keterangan_mcu }}</i></small>
                                                            </div>

                                                            <div class="mb-3 text-muted">
                                                                <small><i>{{ \Carbon\Carbon::parse($item->tgl_verifikasi)->locale('id')->isoFormat('D MMMM YYYY') }}</i></small>
                                                            </div>

                                                            <div class="mb-2">
                                                                <a href="{{ url('cetak-mcu-sub/' . $item->id) }}"
                                                                    target="_blank"
                                                                    class="btn btn-outline-warning btn-sm">
                                                                    <i class="bi bi-download"></i> Download Verifikasi
                                                                    {{ $item->status }}
                                                                </a>
                                                            </div>

                                                            <div>
                                                                <a href="{{ asset('storage/surat jawaban konsul.docx') }}"
                                                                    target="_blank"
                                                                    class="btn btn-outline-warning btn-sm">
                                                                    <i class="bi bi-download"></i> Download Surat
                                                                    Jawaban Konsul
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($canUpload && in_array(auth()->user()->role, ['admin', 'superadmin']))
                                                <tr>
                                                    <td colspan="4" class="text-end">
                                                        @hasAnyRole(['admin', 'superadmin'])
                                                            <button class="btn btn-outline-danger btn-sm"
                                                                wire:click="uploadMCU({{ $data->id_mcu }})">
                                                                <span class="bi bi-plus"></span>
                                                                Upload MCU
                                                            </button>
                                                        @endhasanyrole
                                                    </td>
                                                </tr>
                                            @else
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-danger">
                        <span class="bi bi-exclamation-circle"></span>
                        &nbsp;No data
                    </div>
                @endforelse
            </div>
        </div>
        {{ $mcus->appends(['search' => $search])->links() }}
    @endif
</div>
