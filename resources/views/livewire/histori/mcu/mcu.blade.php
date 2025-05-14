<div>
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
                    <th>NRP</th>
                    <th>Nama</th>
                    <th>Dept / Posisi</th>
                    <th>File MCU</th>
                    <th>Hasil</th>
                    <th>Exp MCU</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historimcus as $data)
                    <tr>
                        <td>
                            {{ $data->nrp }}
                        </td>
                        <td>
                            {{ $data->nama }}
                        </td>
                        <td>{{ $data->dept }} / {{ $data->jabatan }}</td>
                        <td>
                            @php
                                $subItems = DB::table('mcu')
                                    ->whereAny(['id', 'sub_id'], '=', $data->sub_id)
                                    ->where('id', '!=', $data->id_mcu)
                                    ->orderBy('tgl_mcu', 'asc') // Mengurutkan berdasarkan tanggal MCU
                                    ->get();
                            @endphp
                            <div class="d-grid gap-2">
                                @forelse ($subItems as $index => $item)
                                    <a class="btn btn-outline-primary btn-sm" href="{{ Storage::url($item->file_mcu) }}"
                                        target="_blank">
                                        <span class="bi bi-file-earmark-arrow-down"></span> File MCU
                                        {{ $index + 1 }}
                                    </a>
                                @empty
                                @endforelse
                                <a class="btn btn-outline-primary btn-sm" href="{{ Storage::url($data->file_mcu) }}"
                                    target="_blank">
                                    <span class="bi bi-file-earmark-arrow-down"></span> File MCU Final
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="d-grid gap-2">
                                @if ($data->mcuStatus == 'FIT' || $data->mcuStatus == 'FIT WITH NOTE')
                                    <a href="cetak-mcu/{{ $data->id_mcu }}" target="_blank"
                                        class="btn btn-outline-warning btn-sm">
                                        <span class="bi bi-download"></span>
                                        Download Verifikasi Final {{ $data->mcuStatus }}
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
                        <td colspan="5">
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
    {{ $historimcus->links() }}
</div>
