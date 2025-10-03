<div>
    @if ($editform)
        @include('livewire.histori.mcu.form-edit')
    @elseif ($editformnilai)
        @include('livewire.histori.mcu.form-editnilai')
    @else
        <div class="row pt-2">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                &nbsp;
            </div>
            <div class="col-md-3">
                <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model.live="search">
            </div>
        </div>
        <div class="table table-responsive pt-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Verifikator</th>
                        <th>NRP</th>
                        <th>Nama</th>
                        <th>Dept / Posisi</th>
                        <th>File MCU</th>
                        <th>Hasil</th>
                        <th>Hasil Temuan</th>
                        <th>Histori</th>
                        <th>Exp MCU</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historimcus as $data)
                        <tr>
                            <td>
                                @hasAnyRole(['dokter'])
                                    @if (Auth()->user()->username === $data->verifikator || Auth()->user()->subrole === 'paramedik')
                                        <button class="btn btn-outline-warning btn-sm"
                                            wire:click="edit({{ $data->id_mcu }})">
                                            <span class="bi bi-pencil"></span> Tanggal
                                        </button>
                                    @endif
                                    @if (Auth()->user()->username === $data->verifikator)
                                        <button class="btn btn-outline-warning btn-sm"
                                            wire:click="editnilai({{ $data->id_mcu }})">
                                            <span class="bi bi-pencil"></span> Nilai
                                        </button>
                                    @endif
                                    @if ($data->mcuStatus === 'UNFIT')
                                        @if (auth()->user()->role === 'superadmin' || auth()->user()->subrole === 'verifikator')
                                            <button class="btn btn-outline-danger btn-sm"
                                                wire:click="deleteConfirm({{ $data->id_mcu }})">
                                                <span class="bi bi-trash"></span> Hapus
                                            </button>
                                        @endif
                                    @endif
                                @endhasAnyRole
                            </td>
                            <td>
                                @php
                                    $verifikator = DB::table('users')->where('username', $data->verifikator)->first();
                                @endphp
                                {{ $verifikator->name }}
                            </td>
                            <td>
                                {{ $data->nrp }}
                            </td>
                            <td>
                                {{ $data->nama }}
                            </td>
                            <td>{{ $data->dept }} / {{ $data->jabatan }}</td>
                            <td>
                                @if (!is_null($data->sub_id))
                                    @php
                                        $subItems = DB::table('mcu')
                                            ->where(function ($query) use ($data) {
                                                $query->where('id', $data->sub_id)->orWhere('sub_id', $data->sub_id);
                                            })
                                            ->whereNull('exp_mcu')
                                            ->orderBy('tgl_mcu', 'asc')
                                            ->get();
                                    @endphp
                                @endif
                                <div class="d-grid gap-2">
                                    @hasAnyRole(['superadmin', 'dokter'])
                                        @if (!is_null($data->sub_id))
                                            @foreach ($subItems as $index => $item)
                                                <a class="btn btn-outline-primary btn-sm"
                                                    href="{{ Storage::url($item->file_mcu) }}" target="_blank">
                                                    <span class="bi bi-file-earmark-arrow-down"></span> File MCU
                                                    {{ $index + 1 }}
                                                </a>
                                            @endforeach
                                        @endif
                                        <a class="btn btn-outline-primary btn-sm"
                                            href="{{ Storage::url($data->file_mcu) }}" target="_blank">
                                            <span class="bi bi-file-earmark-arrow-down"></span> File MCU Final
                                        </a>
                                        {{ $data->mcuStatus }}
                                    @endhasAnyRole
                                </div>
                            </td>
                            <td>
                                <div class="d-grid gap-2">
                                    @if ($data->mcuStatus == 'FIT' || $data->mcuStatus == 'UNFIT' || $data->mcuStatus == 'TEMPORARY UNFIT')
                                        <a href="cetak-mcu/{{ $data->id_mcu }}" target="_blank"
                                            class="btn btn-outline-warning btn-sm">
                                            <span class="bi bi-download"></span>
                                            Download Verifikasi Final {{ $data->mcuStatus }}
                                        </a>
                                        @if (auth()->user()->role !== 'pimpinan')
                                            <a href="cetak-laik/{{ $data->id_mcu }}"
                                                class="btn btn-outline-success btn-sm" target="_blank">
                                                <span class="bi bi-download"></span>
                                                Download Surat Laik Kerja {{ $data->mcuStatus }}
                                            </a>
                                            <a href="cetak-skd/{{ $data->id_mcu }}"
                                                class="btn btn-outline-success btn-sm" target="_blank">
                                                <span class="bi bi-download"></span>
                                                Download SKD {{ $data->mcuStatus }}
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    if (empty($data->sub_id)) {
                                        // INDUK: ambil induk + semua anak
                                        $hasiltemuan = DB::table('mcu')
                                            ->where('id', $data->id_mcu) // induk
                                            ->orderByRaw('CASE WHEN id = ? THEN 0 ELSE 1 END', [$data->id_mcu]) // induk dulu
                                            ->orderBy('tgl_mcu', 'asc')
                                            ->get();
                                    } else {
                                        // ANAK: ambil induk + semua anak dalam grup
                                        $hasiltemuan = DB::table('mcu')
                                            ->where('id', $data->sub_id) // induk
                                            ->orWhere('sub_id', $data->sub_id) // anak-anak
                                            ->orderByRaw('CASE WHEN id = ? THEN 0 ELSE 1 END', [$data->sub_id]) // induk dulu
                                            ->orderBy('tgl_mcu', 'asc')
                                            ->get();
                                    }
                                @endphp
                                @forelse ($hasiltemuan as $datahasiltemuan)
                                    @if (!empty($datahasiltemuan->upload_hasil_mcu))
                                        <a href="{{ Storage::url($datahasiltemuan->upload_hasil_mcu) }}"
                                            class="btn btn-outline-primary btn-sm" target="_blank">FILE HASIL TEMUAN
                                            {{ $datahasiltemuan->status }}</a>
                                    @else
                                        -
                                    @endif
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>
                                @php
                                    if ($data->sub_id === null) {
                                        $historimcu = DB::table('mcu')
                                            ->where('id', $data->id)
                                            ->orderBy('tgl_mcu', 'asc')
                                            ->get();
                                    } else {
                                        $historimcu = DB::table('mcu')
                                            ->where('sub_id', $data->sub_id)
                                            ->orWhere('id', $data->sub_id)
                                            ->orderBy('tgl_mcu', 'asc')
                                            ->get();
                                    }
                                @endphp
                                @forelse ($historimcu as $datahistori)
                                    {{ $datahistori->status }}
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($data->exp_mcu)->locale('id')->translatedFormat('l, d F Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="alert alert-danger">
                                    <span class="bi bi-exclamation-circle"></span>
                                    &nbsp;No data
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $historimcus->links() }}
        </div>
    @endif
</div>
