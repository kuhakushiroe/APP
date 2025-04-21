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
                                            <td>{{ $data->tgl_mcu }}</td>
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
                                                        <span class="bi bi-file-earmark-arrow-down"></span> Download
                                                    </a>
                                                </td>
                                                <td>{{ $data->tgl_mcu }}</td>
                                                <td>
                                                    @if (empty($data->mcuStatus))
                                                        @if (empty($data->paramedik))
                                                            @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['paramedik']))
                                                                <button class="btn btn-outline-warning btn-sm"
                                                                    wire:click="verifikasi({{ $data->id_mcu }})">
                                                                    <span class="bi bi-file-check"></span>
                                                                    Verifikasi Paramedik
                                                                </button>
                                                            @endif
                                                        @else
                                                            @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['verifikator']))
                                                                <button class="btn btn-outline-warning btn-sm"
                                                                    wire:click="verifikasi({{ $data->id_mcu }})">
                                                                    <span class="bi bi-file-check"></span>
                                                                    Verifikasi Dokter
                                                                </button>
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
                                                    Hasil:
                                                    <br>
                                                    <b>{{ $data->saran_mcu }}</b>
                                                    <br>
                                                    <small>~{{ $data->keterangan_mcu }}~</small>
                                                    <br>
                                                    <small>~{{ $data->tgl_verifikasi }}~</small><br>
                                                    @if (!empty($data->mcuStatus))
                                                        <a href="cetak-mcu-sub/{{ $data->id_mcu }}" target="_blank"
                                                            class="btn btn-outline-warning btn-sm">
                                                            <span class="bi bi-download"></span>
                                                            Download Verifikasi {{ $data->mcuStatus }}
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $row = 2; @endphp
                                            @foreach ($subItems as $item)
                                                <tr>
                                                    <td>{{ $row++ }}</td>
                                                    <td>
                                                        <a href="{{ Storage::url($item->file_mcu) }}" target="_blank">
                                                            <span class="bi bi-file-earmark-arrow-down"></span> Download
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->tgl_mcu }}</td>
                                                    <td>
                                                        @if (empty($item->status))
                                                            @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['verifikator', 'paramedik']))
                                                                <button class="btn btn-outline-warning btn-sm"
                                                                    wire:click="verifikasi({{ $item->id }})">
                                                                    <span class="bi bi-file-check"></span>
                                                                    @if (auth()->user()->subrole == 'paramedik')
                                                                        Verifikasi Paramedik
                                                                    @else
                                                                        Verifikasi Dokter
                                                                    @endif
                                                                </button>
                                                            @else
                                                                -
                                                            @endif
                                                        @else
                                                            {{ $item->status }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">-</td>
                                                    <td colspan="3">
                                                        Hasil:
                                                        <br>
                                                        <b>{{ $item->saran_mcu }}</b>
                                                        <br>
                                                        <small>~{{ $item->keterangan_mcu }}~</small>
                                                        <br>
                                                        <small>~{{ $item->tgl_verifikasi }}~</small><br>
                                                        @if (!empty($item->status))
                                                            <a href="cetak-mcu-sub/{{ $item->id }}" target="_blank"
                                                                class="btn btn-outline-warning btn-sm">
                                                                <span class="bi bi-download"></span>
                                                                Download Verifikasi {{ $item->status }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($canUpload)
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
