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
                <div class="card-body">
                    <form wire:submit.prevent="storeVerifikasi" class="row g-3">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_mcu" readonly>
                        <input type="hidden" class="form-control form-control-sm" wire:model="nrp" readonly>
                        <div class="col-12">
                            <label for="no_dokumen">No Dokumen MCU</label>
                            <input type="text"
                                class="form-control form-control-sm @error('no_dokumen') is-invalid @enderror"
                                wire:model="no_dokumen" placeholder="No Dokumen">
                            @error('no_dokumen')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="riwayat_rokok">Riwayat Rokok</label>
                            <input type="text"
                                class="form-control form-control-sm @error('riwayat_rokok') is-invalid @enderror"
                                wire:model="riwayat_rokok" placeholder="Riwayat Rokok">
                            @error('riwayat_rokok')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- CEK FISIK --}}
                        <div class="col-12 ">
                            <fieldset>Cek Fisik</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-2">
                            <label for="BB">BB</label>
                            <input type="text" class="form-control form-control-sm @error('BB') is-invalid @enderror"
                                wire:model="BB" placeholder="BB">
                            @error('BB')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-2">
                            <label for="TB">TB</label>
                            <input type="text" class="form-control form-control-sm @error('TB') is-invalid @enderror"
                                wire:model="TB" placeholder="TB">
                            @error('TB')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-2">
                            <label for="LP">LP</label>
                            <input type="text" class="form-control form-control-sm @error('LP') is-invalid @enderror"
                                wire:model="LP" placeholder="LP">
                            @error('LP')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-2">
                            <label for="BMI">BMI</label>
                            <input type="text"
                                class="form-control form-control-sm @error('BMI') is-invalid @enderror" wire:model="BMI"
                                placeholder="BMI">
                            @error('BMI')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-2">
                            <label for="Laseq">Laseq</label>
                            <input type="text"
                                class="form-control form-control-sm @error('Laseq') is-invalid @enderror"
                                wire:model="Laseq" placeholder="Laseq">
                            @error('Laseq')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-2">
                            <label for="reqtal_touche">Reqtal Touche</label>
                            <input type="text"
                                class="form-control form-control-sm @error('reqtal_touche') is-invalid @enderror"
                                wire:model="reqtal_touche" placeholder="Reqtal Touche">
                            @error('reqtal_touche')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Tekanan darah --}}
                        <div class="col-12 ">
                            <fieldset>Tekanan Darah</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-6">
                            <label for="sistol">Sistol</label>
                            <input type="text"
                                class="form-control form-control-sm @error('sistol') is-invalid @enderror"
                                wire:model="sistol" placeholder="Sistol">
                            @error('sistol')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="diastol">Diastol</label>
                            <input type="text"
                                class="form-control form-control-sm @error('diastol') is-invalid @enderror"
                                wire:model="diastol" placeholder="Diastol">
                            @error('diastol')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Pemeriksaan Mata Jauh --}}
                        <div class="col-12 ">
                            <fieldset>Pemeriksaan Mata Jauh</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-6">
                            <label for="OD_jauh">OD Jauh</label>
                            <input type="text"
                                class="form-control form-control-sm @error('OD_jauh') is-invalid @enderror"
                                wire:model="OD_jauh" placeholder="OD Jauh">
                            @error('OD_jauh')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="OS_jauh">OS Jauh</label>
                            <input type="text"
                                class="form-control form-control-sm @error('OS_jauh') is-invalid @enderror"
                                wire:model="OS_jauh" placeholder="OS Jauh">
                            @error('OS_jauh')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Pemeriksaan Mata Dekat --}}
                        <div class="col-12 ">
                            <fieldset>Pemeriksaan Mata Dekat</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-6">
                            <label for="OD_dekat">OD Dekat</label>
                            <input type="text"
                                class="form-control form-control-sm @error('OD_dekat') is-invalid @enderror"
                                wire:model="OD_dekat" placeholder="OD Dekat">
                            @error('OD_dekat')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="OS_dekat">OS Dekat</label>
                            <input type="text"
                                class="form-control form-control-sm @error('OS_dekat') is-invalid @enderror"
                                wire:model="OS_dekat" placeholder="OS Dekat">
                            @error('OS_dekat')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-12 ">
                            <fieldset>Buta Warna</fieldset>
                            <hr>
                        </div>
                        <div class="col-12">
                            <label for="butawarna">Buta Warna</label>
                            <input type="text"
                                class="form-control form-control-sm @error('butawarna') is-invalid @enderror"
                                wire:model="butawarna" placeholder="Buta Warna">
                            @error('butawarna')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <fieldset>Diabetes</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-6">
                            <label for="gdp">GDP</label>
                            <input type="text"
                                class="form-control form-control-sm @error('gdp') is-invalid @enderror"
                                wire:model="gdp" placeholder="GDP">
                            @error('gdp')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="gd_2_jpp">GD 2 JPP</label>
                            <input type="text"
                                class="form-control form-control-sm @error('gd_2_jpp') is-invalid @enderror"
                                wire:model="gd_2_jpp" placeholder="GD 2 JPP">
                            @error('gd_2_jpp')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <fieldset>Fungsi Ginjal</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-4">
                            <label for="ureum">Ureum</label>
                            <input type="text"
                                class="form-control form-control-sm @error('ureum') is-invalid @enderror"
                                wire:model="ureum" placeholder="Ureum">
                            @error('ureum')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label for="creatine">Creatine</label>
                            <input type="text"
                                class="form-control form-control-sm @error('creatine') is-invalid @enderror"
                                wire:model="creatine" placeholder="Creatine">
                            @error('creatine')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label for="asamurat">Asam Urat</label>
                            <input type="text"
                                class="form-control form-control-sm @error('asamurat') is-invalid @enderror"
                                wire:model="asamurat" placeholder="Asam Urat">
                            @error('asamurat')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <fieldset>Fungsi Hati</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-4">
                            <label for="sgot">SGOT</label>
                            <input type="text"
                                class="form-control form-control-sm @error('sgot') is-invalid @enderror"
                                wire:model="sgot" placeholder="SGOT">
                            @error('sgot')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label for="sgpt">SGPT</label>
                            <input type="text"
                                class="form-control form-control-sm @error('sgpt') is-invalid @enderror"
                                wire:model="sgpt" placeholder="SGPT">
                            @error('sgpt')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label for="hbsag">HBsAg</label>
                            <input type="text"
                                class="form-control form-control-sm @error('hbsag') is-invalid @enderror"
                                wire:model="hbsag" placeholder="HBsAg">
                            @error('hbsag')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="anti_hbs">Anti HBs</label>
                            <input type="text"
                                class="form-control form-control-sm @error('anti_hbs') is-invalid @enderror"
                                wire:model="anti_hbs" placeholder="Anti HBs">
                            @error('anti_hbs')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <fieldset>Lemak</fieldset>
                            <hr>
                        </div>
                        <div class="col-sm-3">
                            <label for="kolesterol">Kolesterol</label>
                            <input type="text"
                                class="form-control form-control-sm @error('kolesterol') is-invalid @enderror"
                                wire:model="kolesterol" placeholder="Kolesterol">
                            @error('kolesterol')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-3">
                            <label for="hdl">HDL</label>
                            <input type="text"
                                class="form-control form-control-sm @error('hdl') is-invalid @enderror"
                                wire:model="hdl" placeholder="HDL">
                            @error('hdl')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-3">
                            <label for="ldl">LDL</label>
                            <input type="text"
                                class="form-control form-control-sm @error('ldl') is-invalid @enderror"
                                wire:model="ldl" placeholder="LDL">
                            @error('ldl')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-3">
                            <label for="tg">TG</label>
                            <input type="text"
                                class="form-control form-control-sm @error('tg') is-invalid @enderror"
                                wire:model="tg" placeholder="TG">
                            @error('tg')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="darah_rutin">Darah Rutin</label>
                            <input type="text"
                                class="form-control form-control-sm @error('darah_rutin') is-invalid @enderror"
                                wire:model="darah_rutin" placeholder="Darah Rutin">
                            @error('darah_rutin')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="napza">NAPZA</label>
                            <input type="text"
                                class="form-control form-control-sm @error('napza') is-invalid @enderror"
                                wire:model="napza" placeholder="NAPZA">
                            @error('napza')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="urin">Urin</label>
                            <input type="text"
                                class="form-control form-control-sm @error('urin') is-invalid @enderror"
                                wire:model="urin" placeholder="Urin">
                            @error('urin')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="ekg">EKG</label>
                            <input type="text"
                                class="form-control form-control-sm @error('ekg') is-invalid @enderror"
                                wire:model="ekg" placeholder="EKG">
                            @error('ekg')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="rontgen">Rontgen</label>
                            <input type="text"
                                class="form-control form-control-sm @error('rontgen') is-invalid @enderror"
                                wire:model="rontgen" placeholder="Rontgen">
                            @error('rontgen')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="audiometri">Audiometri</label>
                            <input type="text"
                                class="form-control form-control-sm @error('audiometri') is-invalid @enderror"
                                wire:model="audiometri" placeholder="Audiometri">
                            @error('audiometri')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="spirometri">Spirometri</label>
                            <input type="text"
                                class="form-control form-control-sm @error('spirometri') is-invalid @enderror"
                                wire:model="spirometri" placeholder="Spirometri">
                            @error('spirometri')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="tredmil_test">Treadmill Test</label>
                            <input type="text"
                                class="form-control form-control-sm @error('tredmil_test') is-invalid @enderror"
                                wire:model="tredmil_test" placeholder="Treadmill Test">
                            @error('tredmil_test')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="widal_test">Widal Test</label>
                            <input type="text"
                                class="form-control form-control-sm @error('widal_test') is-invalid @enderror"
                                wire:model="widal_test" placeholder="Widal Test">
                            @error('widal_test')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="routin_feces">Routin Feces</label>
                            <input type="text"
                                class="form-control form-control-sm @error('routin_feces') is-invalid @enderror"
                                wire:model="routin_feces" placeholder="Routin Feces">
                            @error('routin_feces')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="kultur_feces">Kultur Feces</label>
                            <input type="text"
                                class="form-control form-control-sm @error('kultur_feces') is-invalid @enderror"
                                wire:model="kultur_feces" placeholder="Kultur Feces">
                            @error('kultur_feces')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['verifikator']))
                            <div class="col-12">
                                <fieldset>Kesimpulan</fieldset>
                                <hr>
                            </div>
                            <div class="col-sm">
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
                            <div class="col-sm">
                                <label for="tgl_verifikasi">Tanggal Verifikasi</label>
                                <input type="date"
                                    class="form-control form-control-sm @error('tgl_verifikasi') is-invalid @enderror"
                                    wire:model="tgl_verifikasi">
                                @error('tgl_verifikasi')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            @if ($status === 'FIT')
                                <div class="col-sm">
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
                                <div class="col-md-12">
                                    <label for="keterangan_mcu">Keterangan / Catatan / Temuan</label>
                                    <textarea class="form-control form-control-sm @error('keterangan_mcu') is-invalid @enderror"
                                        wire:model="keterangan_mcu"></textarea>
                                    @error('keterangan_mcu')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="saran_mcu">Saran / Tindakan</label>
                                    <textarea class="form-control form-control-sm @error('saran_mcu') is-invalid @enderror" wire:model="saran_mcu"></textarea>
                                    @error('saran_mcu')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            @endif
                        @endif
                        <div class="form-group pt-2">
                            <button type="submit" class="btn btn-primary btn-sm float-right mt-2">
                                <span class="bi bi-save"></span>
                                &nbsp;Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!--end::Quick Example-->
        </div>
    </div>
</div>
