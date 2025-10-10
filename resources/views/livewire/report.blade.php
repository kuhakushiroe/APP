<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card text-start">
                <div class="card-header">
                    <h4 class="card-title">MCU report</h4>
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
                    <h4 class="card-title">ID Request</h4>
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
                                href="{{ route('cetak-register-id', ['date_id1' => $date_id1, 'date_id2' => $date_id2]) }}"
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
                    <h4 class="card-title">KIMPER Register</h4>
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
                            <a class="btn btn-success btn-sm"
                                href="{{ route('cetak-register-kimper', ['date_kimper1' => $date_kimper1, 'date_kimper2' => $date_kimper2]) }}"
                                target="_blank" rel="noopener noreferrer">
                                <span class="bi bi-download"></span> Download
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
