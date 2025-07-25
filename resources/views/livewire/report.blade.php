<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card text-start">
                <div class="card-header">
                    <h4 class="card-title">MCU</h4>
                </div>
                <div class="card-body">
                    <form class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" wire:model.live="date_mcu1">
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" wire:model.live="date_mcu2">
                        </div>
                        <div class="col-md-2 text-md-start text-end">
                            <a class="btn btn-success btn-sm"
                                href="{{ route('report-mcu', ['date_mcu1' => $date_mcu1, 'date_mcu2' => $date_mcu2]) }}"
                                target="_blank" rel="noopener noreferrer">
                                <span class="bi bi-download"></span> Download
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 pt-2">
            <div class="card text-start">
                <div class="card-header">
                    <h4 class="card-title">ID</h4>
                </div>
                <div class="card-body">
                    <form class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" wire:model.live="date_id1">
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" wire:model.live="date_id2">
                        </div>
                        <div class="col-md-4 text-md-start text-end">
                            <a class="btn btn-success btn-sm"
                                href="{{ route('cetak-reportId', ['date_id1' => $date_id1, 'date_id2' => $date_id2]) }}"
                                target="_blank" rel="noopener noreferrer">
                                <span class="bi bi-download"></span> Download
                            </a>
                            {{-- <button type="submit" class="btn btn-success btn-sm" wire:click="reportID">
                                <span class="bi bi-download"></span> Download
                            </button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 pt-2">
            <div class="card text-start">
                <div class="card-header">
                    <h4 class="card-title">KIMPER</h4>
                </div>
                <div class="card-body">
                    <form class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" wire:model.live="date_kimper1">
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" wire:model.live="date_kimper2">
                        </div>
                        <div class="col-md-4 text-md-start text-end">
                            <button type="submit" class="btn btn-success btn-sm" wire:click="reportKIMPER">
                                <span class="bi bi-download"></span> Download
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
