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
                        Log Perubahan Karyawan
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $carikaryawan = DB::table('log_karyawan')->where('id_karyawan', $id_karyawan)->get();
                    @endphp
                    <table class="table table-striped">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 10px" rowspan="2">#</th>
                                <th rowspan="2">Jenis Perubahan</th>
                                <th colspan="2">Data</th>
                                <th rowspan="2">tgl Perubahan</th>
                            </tr>
                            <tr>
                                <th>Lama</th>
                                <th>Baru</th>
                            </tr>

                        </thead>
                        <tbody>
                            @forelse ($carikaryawan as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->jenis_perubahan }}</td>
                                    <td>{{ $data->lama }}</td>
                                    <td>{{ $data->baru }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">-Tidak Ada Data-</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
            <!--end::Quick Example-->
        </div>
    </div>
</div>
