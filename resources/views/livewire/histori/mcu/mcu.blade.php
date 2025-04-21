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
                    <th>Nama</th>
                    <th>Dept / Posisi</th>
                    <th>Hasil</th>
                    <th>Exp MCU</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historimcus as $data)
                    <tr>
                        <td>
                            {{ $data->nama }}
                        </td>
                        <td>{{ $data->dept }} / {{ $data->jabatan }}</td>
                        <td>
                            @if ($data->mcuStatus == 'FIT' || $data->mcuStatus == 'FIT WITH NOTE')
                                <a href="cetak-mcu/{{ $data->id_mcu }}" target="_blank"
                                    class="btn btn-outline-warning btn-sm">
                                    <span class="bi bi-download"></span>
                                    Download Verifikasi {{ $data->mcuStatus }}
                                </a>
                                <a href="cetak-skd/{{ $data->id_mcu }}" class="btn btn-outline-success btn-sm"
                                    target="_blank">
                                    <span class="bi bi-download"></span>
                                    Download SKD {{ $data->mcuStatus }}
                                </a>
                            @endif
                        </td>
                        <td>
                            {{ $data->exp_mcu }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
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
