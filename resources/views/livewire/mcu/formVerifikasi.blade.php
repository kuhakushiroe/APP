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
                        Verifikasi MCU {{ $this->nama }}
                    </div>
                </div>
                <form wire:submit.prevent="storeVerifikasi">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control form-control-sm" wire:model="id_mcu" readonly>
                            <input type="hidden" class="form-control form-control-sm" wire:model="nrp" readonly>
                            <div class="form-group">
                                <label for="no_dokumen">No Dokumen MCU</label>
                                <input type="text"
                                    class="form-control form-control-sm @error('no_dokumen') is-invalid @enderror"
                                    wire:model="no_dokumen" placeholder="No Dokumen">
                                @error('no_dokumen')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="" wire:model.live="status"
                                    class="form-control form-control-sm @error('status') is-invalid @enderror">
                                    <option value="">Pilih Status</option>
                                    <option value="FIT">FIT</option>
                                    <option value="FIT WITH NOTE">FIT WITH NOTE</option>
                                    <option value="FOLLOW UP">FOLLOW UP</option>
                                    <option value="TEMPORARY UNFIT">TEMPORARY UNFIT</option>
                                    <option value="UNFIT">UNFIT</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            @if ($status === 'FIT')
                                <div class="form-group">
                                    <label for="exp_mcu">Exp MCU</label>
                                    <input type="date"
                                        class="form-control form-control-sm @error('exp_mcu') is-invalid @enderror"
                                        wire:model="exp_mcu">
                                    @error('exp_mcu')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            @elseif ($status && $status !== 'FIT')
                                <div class="form-group">
                                    <label for="keterangan_mcu">Keterangan / Catatan</label>
                                    <textarea class="form-control form-control-sm @error('keterangan_mcu') is-invalid @enderror" wire:model="catatan_mcu"></textarea>
                                    @error('keterangan_mcu')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="saran_mcu">Saran</label>
                                    <textarea class="form-control form-control-sm @error('saran_mcu') is-invalid @enderror" wire:model="saran_mcu"></textarea>
                                    @error('saran_mcu')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="tgl_verifikasi">Tanggal Verifikasi</label>
                                <input type="date"
                                    class="form-control form-control-sm @error('tgl_verifikasi') is-invalid @enderror"
                                    wire:model="tgl_verifikasi">
                                @error('tgl_verifikasi')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group pt-2">
                                <button type="submit" class="btn btn-primary btn-sm float-right mt-2">
                                    <span class="bi bi-save"></span>
                                    &nbsp;Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Quick Example-->
        </div>
    </div>
</div>
