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
                <button class="btn btn-dark btn-sm" wire:click="open">
                    <span class="bi bi-plus-square"></span>
                    &nbsp;Mcu
                </button>
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
                        $status = $data->statusMcu;
                        $canUpload = $status !== '';

                    @endphp

                    <div class="card card-primary card-outline mb-4">
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
                                                <th>Tanggal MCU</th>
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
                                                        <button class="btn btn-outline-warning btn-sm"
                                                            wire:click="verifikasi({{ $data->id_mcu }})">
                                                            <span class="bi bi-file-check"></span>
                                                            Verifikasi
                                                        </button>
                                                    @else
                                                        {{ $data->mcuStatus }}
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
                                                            <button class="btn btn-outline-warning btn-sm"
                                                                wire:click="verifikasi({{ $item->id }})">
                                                                <span class="bi bi-file-check"></span>
                                                                Verifikasi
                                                            </button>
                                                        @else
                                                            {{ $item->status }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @php
                                                // Cek apakah status MCU induk sudah diverifikasi
                                                $indukVerified = !empty($data->mcuStatus);

                                                // Cek apakah ada subItem yang sudah diverifikasi
                                                $subItemVerified = $subItems->contains(function ($item) {
                                                    return !empty($item->status);
                                                });
                                            @endphp
                                            @if ($canUpload && ($indukVerified || $subItemVerified))
                                                <tr>
                                                    <td colspan="4" class="text-end">
                                                        <button class="btn btn-outline-success btn-sm"
                                                            wire:click="uploadMCU({{ $data->id_mcu }})">
                                                            <span class="bi bi-plus"></span>
                                                            Upload MCU
                                                        </button>
                                                    </td>
                                                </tr>
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
