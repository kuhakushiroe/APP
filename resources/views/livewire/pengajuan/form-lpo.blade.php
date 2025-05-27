<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h4 class="card-title">Form LPO</h4>
                </div>
                <div class="card-body">
                    <form action="" wire:submit.prevent="saveLPO()" class="row">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_pengajuan">
                        <div class="col-12 pt-2">
                            <fieldset><b>LPO {{ $edit_type_lpo ?? '' }}</b></fieldset>
                            <hr>
                        </div>
                        @php
                            // Ambil semua pengajuan_kimper milik NRP
                            $caripengajuankimper = DB::table('pengajuan_kimper_lpo')
                                ->where('id', $id_pengajuan)
                                ->first();
                            $carinrp = \App\Models\ModelPengajuanKimper::where(
                                'id',
                                $caripengajuankimper->id_pengajuan_kimper,
                            )->first();
                            $pengajuanKimperIds = \App\Models\ModelPengajuanKimper::where('nrp', $carinrp->nrp)->pluck(
                                'id',
                            );

                            // Ambil semua id_versatility yang sudah digunakan oleh NRP
                            $usedVersatilityIds = DB::table('pengajuan_kimper_versatility')
                                ->whereIn('id_pengajuan_kimper', $pengajuanKimperIds)
                                ->pluck('id_versatility')
                                ->toArray();

                            // Group semua Versatility berdasarkan type_versatility
                            $versatilityByType = \App\Models\Versatility::all()->groupBy('type_versatility');

                            $unfulfilledTypes = [];

                            foreach ($versatilityByType as $type => $versatilities) {
                                $versatilityIds = $versatilities->pluck('id')->toArray();

                                // Cek apakah semua unit dari type ini sudah digunakan
                                $unused = array_diff($versatilityIds, $usedVersatilityIds);

                                if (count($unused) > 0) {
                                    $unfulfilledTypes[] = $type;
                                }
                            }

                            // Sekarang filter available types dari yang belum digunakan semua
                            $used = $type_lpo;
                            unset($used[$id_pengajuan]);

                            $available = array_diff($unfulfilledTypes, $used);
                        @endphp

                        <div class="col-6">
                            <div class="form-group">
                                <label for="type_lpo" class="form-label">Upload LPO
                                    [{{ $id_pengajuan }}]</label>
                                <select class="form-control form-control-sm" wire:model.live="edit_type_lpo">
                                    <option value="">-Type / Jenis LPO-</option>
                                    @foreach ($available as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('edit_type_lpo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="upload_lpo" class="form-label">Upload
                                    LPO{{ '[' . $id_pengajuan . ']' }}</label>
                                <input
                                    class="form-control form-control-sm @error('edit_upload_lpo') is-invalid @enderror"
                                    type="file" id="upload_lpo" wire:model='edit_upload_lpo'>
                                @error('edit_upload_lpo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="nilai_1" class="form-label">Nilai Instrument
                                    Panel & Kontrol{{ '[' . $id_pengajuan . ']' }}</label>
                                <input
                                    class="form-control form-control-sm @error('edit_instrumen_panel') is-invalid @enderror"
                                    type="number" id="instrumen_panel" wire:model.live='edit_instrumen_panel'>
                                @error('edit_instrumen_panel')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="safety_operasi" class="form-label">Safety
                                    Operasi{{ '[' . $id_pengajuan . ']' }}</label>
                                <input
                                    class="form-control form-control-sm @error('edit_safety_operasi') is-invalid @enderror"
                                    type="number" id="safety_operasi" wire:model.live='edit_safety_operasi'>
                                @error('edit_safety_operasi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="metode_operasi" class="form-label">Metode dan Teknik
                                    Operasi{{ '[' . $id_pengajuan . ']' }}</label>
                                <input
                                    class="form-control form-control-sm @error('edit_metode_operasi') is-invalid @enderror"
                                    type="number" id="metode_operasi" wire:model.live='edit_metode_operasi'>
                                @error('edit_metode_operasi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="perawatan"
                                    class="form-label">Perawatan{{ '[' . $id_pengajuan . ']' }}</label>
                                <input
                                    class="form-control form-control-sm @error('edit_perawatan') is-invalid @enderror"
                                    type="number" id="perawatan" wire:model.live='edit_perawatan'>
                                @error('edit_perawatan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="nilai_total" class="form-label">Nilai
                                    Total{{ '[' . $id_pengajuan . ']' }}</label>
                                <input class="form-control form-control-sm" type="number" id="nilai_total"
                                    wire:model='edit_nilai_total' readonly>
                            </div>
                        </div>

                        <div class="form-group pt-2">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            <a class="btn btn-outline-danger btn-sm" wire:click="close">Close</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
