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
                        Multi MCU
                    </div>
                </div>
                <form wire:submit.prevent="store-multi">
                    <div class="card-body">
                        @foreach ($forms as $index => $form)
                            <div class="row border border-primary rounded p-2 mb-2">
                                <div class="form-group col-md-3">
                                    <label>Jenis Pengajuan MCU:</label>
                                    <select class="form-control form-control-sm"
                                        wire:model="forms.{{ $index }}.jenis_pengajuan_mcu">
                                        <option value="">Pilih</option>
                                        <option value="Pre Employment">Pre Employment</option>
                                        <option value="Annual">Annual</option>
                                        <option value="Temporary">Temporary</option>
                                        <option value="Khusus">Khusus</option>
                                        <option value="Exit MCU">Exit MCU</option>
                                    </select>
                                    @error("forms.$index.jenis_pengajuan_mcu")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nrp_{{ $index }}">NRP:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.live="forms.{{ $index }}.nrp" placeholder="Masukkan NRP">
                                    @error("forms.$index.nrp")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label>File MCU:</label>
                                    <input type="file" class="form-control form-control-sm"
                                        wire:model="forms.{{ $index }}.file_mcu">
                                    @error("forms.$index.file_mcu")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Proveder:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model="forms.{{ $index }}.proveder">
                                    @error("forms.$index.proveder")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Tanggal MCU:</label>
                                    <input type="date" class="form-control form-control-sm"
                                        wire:model="forms.{{ $index }}.tgl_mcu">
                                    @error("forms.$index.tgl_mcu")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 mt-4">
                                    @if ($loop->index > 0)
                                        <button type="button" class="btn btn-danger btn-sm mt-2"
                                            wire:click="removeFormMcu({{ $index }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-sm float-right mt-2"
                                wire:click="addFormMcu">
                                <i class="bi bi-plus"></i> Tambah Form
                            </button>
                            <button type="submit" class="btn btn-success btn-sm float-right mt-2"
                                wire:loading.attr="disabled" wire:target="file_mcu" wire:click="storeMulti">
                                <span class="bi bi-save"></span>
                                &nbsp;Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Quick Example-->
        </div>
    </div>
</div>
