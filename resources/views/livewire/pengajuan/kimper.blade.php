<div>
    @if ($form)
        @include('livewire.pengajuan.form-kimper')
    @else
        <button wire:click='open' class="btn btn-outline-success btn-sm">
            <span class="bi bi-plus"></span> Kimper
        </button>
        <div class="row pt-2">
            <div class="col-md-12">
                <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model.live="search">
            </div>
        </div>
        @forelse ($kimpers as $data)
            <div class="row pt-2">
                <div class="col-md-12">
                    {{-- @forelse ($pengajuanKimper as $dataKimper) --}}
                    <div class="card card-primary card-outline mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td width="20%">
                                    <b>Jenis</b>
                                </td>
                                <td>
                                    {{ $data->jenis_pengajuan_kimper }}
                                </td>
                            </tr>
                            <tr>
                                <td width="10%">
                                    <b>NRP</b>
                                </td>
                                <td>
                                    {{ $data->nrp }}
                                </td>
                            </tr>
                            <tr>
                                <td width="10%">
                                    <b>Nama</b>
                                </td>
                                <td {{ $data->nama }}> <b>{{ $data->dept }} -
                                        {{ $data->jabatan }}</b>
                                </td>
                            </tr>
                            <tr width="10%">
                                <td><b>Tanggal Pengajuan</b></td>
                                <td>{{ \Carbon\Carbon::parse($data->tgl_pengajuan)->locale('id')->translatedFormat('l, d F Y') }}
                                </td>
                            </tr>
                            <tr width="10%">
                                <td><b>Status</b></td>
                                <td>
                                    @if ($data->status_pengajuan == '0')
                                        Pending
                                    @elseif ($data->status_pengajuan == '1')
                                        Approved
                                    @elseif ($data->status_pengajuan == '2')
                                        Rejected
                                    @else
                                        <button class="btn btn-primary btn-sm" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                            <span role="status">Waiting...</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <div class="alert alert-primary mt-3">
                            <div class="row g-2">
                                @php
                                    $files = [
                                        'Form Request' => $data->upload_request,
                                        'Foto' => $data->upload_foto,
                                        'KTP' => $data->upload_ktp,
                                        'SKD' => $data->upload_skd,
                                        'BPJS Kesehatan' => $data->upload_bpjs_kes,
                                        'BPJS Ketenagakerjaan' => $data->upload_bpjs_ker,
                                        'Kimper Lama' => $data->upload_kimper_lama,
                                        'Jenis Sim' => $data->upload_jenis_sim,
                                    ];

                                    if ($data->jenis_pengajuan_id === 'perpanjangan') {
                                        $files = ['ID Lama' => $data->upload_id_lama] + $files;
                                    }
                                @endphp

                                @foreach ($files as $label => $file)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        @if ($file && ($label !== 'Foto' || cekFile('/' . $file)))
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-file-earmark-pdf"></i> {{ $label }}
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-danger btn-sm w-100 disabled">
                                                <i class="bi bi-file-earmark-pdf"></i> {{ $label }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-danger">
                <span class="bi bi-info-circle"></span> Tidak ada data
            </div>
        @endforelse
    @endif
</div>
