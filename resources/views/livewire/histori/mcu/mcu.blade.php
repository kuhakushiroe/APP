<div>
    @if ($editform)
        @include('livewire.histori.mcu.form-edit')
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
                                        <a class="btn btn-outline-primary btn-sm" href="{{ Storage::url($data->file_mcu) }}"
                                            target="_blank">
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
                                        <a href="cetak-laik/{{ $data->id_mcu }}" class="btn btn-outline-success btn-sm"
                                            target="_blank">
                                            <span class="bi bi-download"></span>
                                            Download Surat Laik Kerja {{ $data->mcuStatus }}
                                        </a>
                                        <a href="cetak-skd/{{ $data->id_mcu }}" class="btn btn-outline-success btn-sm"
                                            target="_blank">
                                            <span class="bi bi-download"></span>
                                            Download SKD {{ $data->mcuStatus }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($data->exp_mcu)->locale('id')->translatedFormat('l, d F Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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
