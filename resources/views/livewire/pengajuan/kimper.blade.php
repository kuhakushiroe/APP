<div>
    @if ($form)
        @include('livewire.pengajuan.form-kimper')
    @else
        <button wire:click='open' class="btn btn-outline-success btn-sm">
            <span class="bi bi-plus"></span> Kimper
        </button>
    @endif

    @forelse ($kimpers as $data)
        <div class="card">
            <div class="card-header">
                NRP: {{ $data->nrp }}
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <td>Dokumen</td>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Dokumen : {{ $data->jenis_pengajuan_kimper }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-danger">
            <span class="bi bi-info-circle"></span> Tidak ada data
        </div>
    @endforelse
</div>
