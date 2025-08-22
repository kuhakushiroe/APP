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
                    <form wire:submit.prevent="storeVerifikasi" class="g-3 row">
                        <input type="hidden" class="form-control form-control-sm" wire:model.live="id_mcu" readonly>
                        <input type="hidden" class="form-control form-control-sm" wire:model.live="nrp" readonly>
                        <div class="card text-start">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="card-title">No Dokumen MCU</h4>
                                        <input type="text"
                                            class="form-control form-control-sm @error('no_dokumen') is-invalid @enderror"
                                            wire:model.live="no_dokumen" placeholder="No Dokumen"
                                            @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        @error('no_dokumen')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="jenis_pengajuan_mcu">Jenis Pengajuan MCU</label>
                                        <input type="text"
                                            class="form-control form-control-sm @error('jenis_pengajuan_mcu') is-invalid @enderror"
                                            wire:model.live="jenis_pengajuan_mcu" placeholder="Jenis Pengajuan MCU"
                                            @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        @error('jenis_pengajuan_mcu')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card text-start">
                            <div class="card-body">
                                <h4 class="card-title">Riwayat Rokok</h4>
                                <label for="riwayat_rokok"></label>
                                <select
                                    class="form-control form-control-sm
                @error('riwayat_rokok') is-invalid @enderror
                @if ($riwayat_rokok === 'Ya') border-danger text-danger @endif"
                                    wire:model.live="riwayat_rokok" id="riwayat_rokok"
                                    @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    <option value="">-Pilih Riwayat Rokok-</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                                @error('riwayat_rokok')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Cek Fisik</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="gol_darah">Gol Darah:</label>
                                        <select
                                            class="form-control form-control-sm @error('gol_darah') is-invalid @enderror"
                                            id="gol_darah" wire:model.live="gol_darah"
                                            @if (auth()->user()->subrole === 'verifikator') disabled @endif>
                                            <option value="">-Pilih Gol Darah-</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                        @error('gol_darah')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <label for="BB">BB</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('BB') is-invalid @enderror"
                                        wire:model.live="BB"
                                        placeholder="BB"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('BB')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-md-4 ">
                                    <label for="TB">TB</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('TB') is-invalid @enderror"
                                        wire:model.live="TB"
                                        placeholder="TB"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('TB')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-md-4 ">
                                    <label for="LP">LP</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('LP') is-invalid @enderror"
                                        wire:model.live="LP"
                                        placeholder="LP"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('LP')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="BMI">BMI</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm
                                            @error('BMI') is-invalid @enderror
                                            @if ($BMI !== null) @if ($BMI < 30 || $BMI > 39.9)
                                                    is-invalid
                                                @elseif ($BMI >= 30 && $BMI <= 39.9)
                                                    is-valid @endif
                                            @endif"
                                        wire:model="BMI" placeholder="BMI"
                                        @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('BMI')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-md-4 ">
                                    <label for="Laseq">Laseq</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('Laseq') is-invalid @enderror"
                                        wire:model.live="Laseq"
                                        placeholder="Laseq"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('Laseq')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-md-4 ">
                                    <label for="reqtal_touche">Reqtal Touche</label>
                                    <select type="text"
                                        class="form-control form-control-sm @error('reqtal_touche') is-invalid @enderror
                                        @if ($reqtal_touche === 'Ditemukan') border-danger text-danger @endif"
                                        wire:model.live="reqtal_touche"
                                        placeholder="Reqtal Touche"@if (auth()->user()->subrole === 'verifikator') disabled @endif>
                                        <option value="">-Pilih Reqtal Touche-</option>
                                        <option value="Ditemukan">Ditemukan</option>
                                        <option value="Tidak Ditemukan">Tidak Ditemukan</option>
                                    </select>
                                    @error('reqtal_touche')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Tekanan darah --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Tekanan Darah</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-6">
                                    <label for="sistol">Sistol</label>
                                    <input type="number"
                                        class="form-control form-control-sm @error('sistol') is-invalid @enderror @if ($sistol > 100) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="sistol"
                                        placeholder="Sistol"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('sistol')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="diastol">Diastol</label>
                                    <input type="number"
                                        class="form-control form-control-sm @error('diastol') is-invalid @enderror @if ($diastol > 150) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="diastol"
                                        placeholder="Diastol"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('diastol')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Pemeriksaan Mata Jauh --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Pemeriksaan Mata</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-6">
                                    <label for="OD_jauh">OD Jauh</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('OD_jauh') is-invalid @enderror"
                                        wire:model.live="OD_jauh"
                                        placeholder="OD Jauh"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('OD_jauh')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label for="OS_jauh">OS Jauh</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('OS_jauh') is-invalid @enderror"
                                        wire:model.live="OS_jauh"
                                        placeholder="OS Jauh"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('OS_jauh')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="OD_dekat">OD Dekat</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('OD_dekat') is-invalid @enderror"
                                        wire:model.live="OD_dekat"
                                        placeholder="OD Dekat"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('OD_dekat')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label for="OS_dekat">OS Dekat</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('OS_dekat') is-invalid @enderror"
                                        wire:model.live="OS_dekat"
                                        placeholder="OS Dekat"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('OS_dekat')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="butawarna">Buta Warna</label>
                                    <select
                                        class="form-control form-control-sm @error('butawarna') is-invalid @enderror @if ($butawarna === 'none') border-success text-success @elseif ($butawarna === 'parsial') border-warning text-warning @elseif ($butawarna === 'total') border-danger text-danger @else
                                            border-success text-success @endif"
                                        wire:model.live="butawarna"
                                        placeholder="Buta Warna"@if (auth()->user()->subrole === 'verifikator') disabled @endif>
                                        <option value="">Pilih Buta Warna</option>
                                        <option value="none">Tidak Ada</option>
                                        <option value="parsial">Parsial</option>
                                        <option value="total">Total</option>
                                    </select>
                                    @error('butawarna')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Diabetes --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Diabetes</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-4">
                                    <label for="gdp">GDP</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('gdp') is-invalid @enderror
                                        @if ($gdp < 100 || $gdp > 125) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="gdp"
                                        placeholder="GDP"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('gdp')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="gd_2_jpp">GD 2 JPP</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('gd_2_jpp') is-invalid @enderror"
                                        wire:model.live="gd_2_jpp"
                                        placeholder="GD 2 JPP"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('gd_2_jpp')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="hba1c">HbA1c</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('hba1c') is-invalid @enderror
                                        @if ($hba1c < 5.7 || $hba1c > 6.4) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="hba1c" placeholder="Hba1c"
                                        @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('hba1c')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- GINJAL --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Fungsi Ginjal</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-4">
                                    <label for="ureum">Ureum</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('ureum') is-invalid @enderror
                                        @if ($ureum > 12) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="ureum"
                                        placeholder="Ureum"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('ureum')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="creatine">Creatine</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('creatine') is-invalid @enderror
                                        @if ($creatine < 1.3 || $creatine > 1.4) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="creatine"
                                        placeholder="Creatine"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('creatine')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="asamurat">Asam Urat</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('asamurat') is-invalid @enderror
                                        @if ($asamurat < 8) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="asamurat"
                                        placeholder="Asam Urat"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('asamurat')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Fungsi Hati --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Fungsi Hati</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-4">
                                    <label for="sgot">SGOT</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('sgot') is-invalid @enderror
                                        @if ($sgot > 80) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="sgot"
                                        placeholder="SGOT"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('sgot')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="sgpt">SGPT</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('sgpt') is-invalid @enderror
                                        @if ($sgpt > 100 || $sgpt < 51) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="sgpt"
                                        placeholder="SGPT"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('sgpt')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="hbsag">HBsAg</label>
                                    <select
                                        class="form-control form-control-sm @error('hbsag') is-invalid @enderror
                                        @if ($hbsag === 'Positif') border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="hbsag" @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        <option value="">Pilih HBsAg</option>
                                        <option value="Positif">Positif</option>
                                        <option value="Negatif">Negatif</option>
                                    </select>
                                    @error('hbsag')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Hepatitis --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Hepatitis</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-12">
                                    <label for="anti_hbs">Anti HBs</label>
                                    <input type="number"
                                        class="form-control form-control-sm @error('anti_hbs') is-invalid @enderror"
                                        wire:model.live="anti_hbs"
                                        placeholder="Anti HBs"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('anti_hbs')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Panel LIPID --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Panel LIPID</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-3">
                                    <label for="kolesterol">Kolesterol</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('kolesterol') is-invalid @enderror
                                        @if ($kolesterol > 200) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="kolesterol"
                                        placeholder="Kolesterol"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('kolesterol')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-3">
                                    <label for="hdl">HDL</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('hdl') is-invalid @enderror
                                        @if ($hdl < 35 || $hdl > 55) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="hdl"
                                        placeholder="HDL"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('hdl')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-3">
                                    <label for="ldl">LDL</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('ldl') is-invalid @enderror
                                        @if ($ldl > 160) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="ldl"
                                        placeholder="LDL"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('ldl')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-3">
                                    <label for="tg">TG</label>
                                    <input type="number" step="any"
                                        class="form-control form-control-sm @error('tg') is-invalid @enderror
                                        @if ($tg > 200) border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="tg"
                                        placeholder="TG"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('tg')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Darah RUTIN --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Darah RUTIN</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-12">
                                    <label for="darah_rutin">Darah Rutin</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('darah_rutin') is-invalid @enderror"
                                        wire:model.live="darah_rutin"
                                        placeholder="Darah Rutin"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('darah_rutin')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="napza">NAPZA</label>
                                    <select
                                        class="form-control form-control-sm @error('napza') is-invalid @enderror
                                        @if ($napza === 'Positif') border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="napza"
                                        placeholder="NAPZA"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        <option value="">Pilih NAPZA</option>
                                        <option value="Positif">Positif</option>
                                        <option value="Negatif">Negatif</option>
                                    </select>
                                    @error('napza')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="urin">Urin</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('urin') is-invalid @enderror"
                                        wire:model.live="urin"
                                        placeholder="Urin"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('urin')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="ekg">EKG</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('ekg') is-invalid @enderror"
                                        wire:model.live="ekg"
                                        placeholder="EKG"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('ekg')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="rontgen">Rontgen</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('rontgen') is-invalid @enderror"
                                        wire:model.live="rontgen"
                                        placeholder="Rontgen"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('rontgen')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="audiometri">Audiometri</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('audiometri') is-invalid @enderror"
                                        wire:model.live="audiometri"
                                        placeholder="Audiometri"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('audiometri')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="spirometri">Spirometri</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('spirometri') is-invalid @enderror"
                                        wire:model.live="spirometri"
                                        placeholder="Spirometri"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('spirometri')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="tredmil_test">Treadmill Test</label>
                                    <select
                                        class="form-control form-control-sm @error('tredmil_test') is-invalid @enderror
                                        @if ($tredmil_test === 'Positive Iskemik Respon') border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="tredmil_test"
                                        placeholder="Treadmill Test"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        <option value="">Pilih Hasil</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Normal Iskemik Respon">Normal Iskemik Respon</option>
                                        <option value="Positive Iskemik Respon">Positive Iskemik Respon</option>
                                    </select>
                                    @error('tredmil_test')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="echocardiography">Echocardiography</label>
                                    <select
                                        class="form-control form-control-sm @error('echocardiography') is-invalid @enderror
                                        @if ($echocardiography === 'Abnormal') border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="echocardiography"
                                        placeholder="Treadmill Test"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        <option value="">Pilih Hasil</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Abnormal">Abnormal</option>
                                    </select>
                                    @error('echocardiography')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="widal_test">Widal Test</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('widal_test') is-invalid @enderror"
                                        wire:model.live="widal_test"
                                        placeholder="Widal Test"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('widal_test')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="routin_feces">Routin Feces</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('routin_feces') is-invalid @enderror"
                                        wire:model.live="routin_feces"
                                        placeholder="Routin Feces"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('routin_feces')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="kultur_feces">Kultur Feces</label>
                                    <input type="text"
                                        class="form-control form-control-sm @error('kultur_feces') is-invalid @enderror"
                                        wire:model.live="kultur_feces"
                                        placeholder="Kultur Feces"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                    @error('kultur_feces')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        {{-- Riwayat Penyakit --}}
                        <div class="card text-start">
                            <div class="card-header">
                                <h4 class="card-title">Riwayat Penyakit</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm-6">
                                    <label for="kesadaran">Kehilangan Kesadaran</label>
                                    <select
                                        class="form-control form-control-sm @error('kesadaran') is-invalid @enderror
                                        @if ($kesadaran === 'yes') border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="kesadaran"
                                        placeholder="Kehilangan Kesadaran"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        <option value="">Pilih Option</option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>
                                    </select>
                                    @error('kesadaran')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="epilepsi">Epilepsi</label>
                                    <select
                                        class="form-control form-control-sm @error('epilepsi') is-invalid @enderror
                                        @if ($epilepsi === 'yes') border-danger text-danger @else border-success text-success @endif"
                                        wire:model.live="epilepsi"
                                        placeholder="Epilepsi"@if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        <option value="">Pilih Option</option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>
                                    </select>
                                    @error('epilepsi')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->role === 'dokter' && in_array(auth()->user()->subrole, ['verifikator']))
                            <div class="col-sm">
                                <label for="paramedik_status">Validasi</label>
                                <select wire:model.live="paramedik_status"
                                    class="form-control form-control-sm @error('paramedik_status') is-invalid @enderror">
                                    <option value="">Pilih Status</option>
                                    <option value="0">Tolak</option>
                                    <option value="1">Terima</option>
                                </select>
                                @error('paramedik_status')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            @if ($paramedik_status == null)
                            @elseif($paramedik_status == 0)
                                <div class="col-sm">
                                    <label for="paramedik_catatan">Keterangan</label>
                                    <textarea class="form-control form-control-sm @error('paramedik_catatan') is-invalid @enderror"
                                        wire:model.live='paramedik_catatan' placeholder="Keterangan Di Tolak"></textarea>
                                    @error('paramedik_catatan')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            @else
                                <div class="col-12">
                                    <fieldset>Kesimpulan</fieldset>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="jenis_pengajuan_mcu">Jenis Pengajuan MCU</label>
                                        <input type="text"
                                            class="form-control form-control-sm @error('jenis_pengajuan_mcu') is-invalid @enderror"
                                            wire:model.live="jenis_pengajuan_mcu" placeholder="Jenis Pengajuan MCU"
                                            @if (auth()->user()->subrole === 'verifikator') readonly @endif>
                                        @error('jenis_pengajuan_mcu')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="status">Status</label>
                                    <select name="" wire:model.live="status"
                                        class="form-control form-control-sm @error('status') is-invalid @enderror">
                                        <option value="">Pilih Status</option>
                                        <option value="FIT">FIT</option>
                                        {{-- <option value="FIT WITH NOTE">FIT WITH NOTE</option> --}}
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
                                        wire:model.live="tgl_verifikasi">
                                    @error('tgl_verifikasi')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                @if ($status == 'FOLLOW UP')
                                    @foreach ($followups as $index => $item)
                                        <div class="col-md-12 mb-2">
                                            <label>Keterangan Follow Up {{ $index + 1 }}:</label>
                                            <textarea class="form-control form-control-sm @error("followups.$index.keterangan") is-invalid @enderror"
                                                wire:model.live="followups.{{ $index }}.keterangan"></textarea>
                                            @error("followups.$index.keterangan")
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <label>Saran & Tindakan {{ $index + 1 }}:</label>
                                            <textarea class="form-control form-control-sm @error("followups.$index.saran") is-invalid @enderror"
                                                wire:model.live="followups.{{ $index }}.saran"></textarea>
                                            @error("followups.$index.saran")
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <button type="button" class="btn btn-danger btn-sm mb-3"
                                            wire:click="removeFollowup({{ $index }})">Hapus</button>
                                        <hr>
                                    @endforeach

                                    <button type="button" class="btn btn-success btn-sm" wire:click="addFollowup">+
                                        Tambah Follow Up</button>
                                @endif
                                @if ($status)
                                    <div class="col-sm">
                                        <label for="exp_mcu">Exp MCU</label>
                                        <input type="date"
                                            class="form-control form-control-sm @error('exp_mcu') is-invalid @enderror"
                                            wire:model.live="exp_mcu">
                                        @error('exp_mcu')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    @if ($status == 'FIT')
                                        <div class="col-md-12">
                                            <label for="keterangan_mcu">Keterangan / Hasil / Catatan / Temuan:</label>
                                            <textarea class="form-control form-control-sm @error('keterangan_mcu') is-invalid @enderror"
                                                wire:model.live="keterangan_mcu"></textarea>
                                            @error('keterangan_mcu')
                                                <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label for="saran_mcu">Saran & tindakan yang harus dilakukan:</label>
                                            <textarea class="form-control form-control-sm @error('saran_mcu') is-invalid @enderror" wire:model.live="saran_mcu"></textarea>
                                            @error('saran_mcu')
                                                <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <label for="upload_hasil_mcu">Upload Hasil MCU / Temuan:</label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('upload_hasil_mcu') is-invalid @enderror"
                                            wire:model.live="upload_hasil_mcu"></input>
                                        @error('upload_hasil_mcu')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                @endif
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
