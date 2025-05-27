<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h4 class="card-title">Tambah Versatility</h4>
                </div>
                <div class="card-body">
                    <form action="" wire:submit.prevent="saveVersatility()" class="row">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_pengajuan">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="jenis Pengajuan">Unit</label>
                                @php
                                    $versatility = $this->availableVersatility;

                                @endphp
                                <select
                                    class="form-control form-control-sm @error('id_versatility') is-invalid @enderror"
                                    wire:model="id_versatility">
                                    <option value="">Pilih Versatility</option>
                                    @foreach ($versatility as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->type_versatility }}-{{ $item->versatility }}</option>
                                    @endforeach
                                </select>
                                @error('id_versatility')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="jenis Pengajuan">Klasifikasi</label>
                                <select class="form-control form-control-sm @error('klasifikasi') is-invalid @enderror"
                                    wire:model="klasifikasi">
                                    <option value="">Pilih Klasifikasi</option>
                                    <option value="F">F</option>
                                    <option value="R">R</option>
                                    <option value="T">T</option>
                                    <option value="I">I</option>
                                </select>
                                @error('klasifikasi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group pt-2">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            <button class="btn btn-outline-danger btn-sm" wire:click="close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
