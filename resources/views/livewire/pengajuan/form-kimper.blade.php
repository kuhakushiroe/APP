<div>
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h4 class="card-title">Tambah Pengajuan Kimper</h4>
                </div>
                <div class="card-body">
                    <form action="" wire:submit.prevent="store" class="row">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_pengajuan">
                        <input type="hidden" class="form-control form-control-sm" wire:model="id_karyawan">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="NRP">NRP:</label>
                                <input class="form-control form-control-sm @error('nrp') is-invalid @enderror"
                                    wire:model.live='nrp' placeholder="Masukkan NRP">
                                @error('nrp')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($nrp)
                            <div class="alert alert-info" role="alert">
                                @if ($carifoto)
                                    <h4 class="alert-heading">Detail NRP:</h4>
                                    @if (!empty($carifoto->foto))
                                        <label>Foto:</label>
                                        <br>
                                        <img src="{{ Storage::url($carifoto->foto) }}" alt="Foto" class="img-fluid"
                                            style="max-width: 100px; max-height: 150px;">
                                        <br>
                                    @endif
                                    <li>{{ $nrp }}</li>
                                    <li>{{ $info_nama }} | {{ $info_dept }} / {{ $info_jabatan }}</li>
                                    <li>
                                        EXP MCU :
                                        @if ($info_mcu)
                                            @php $tgl = \Carbon\Carbon::parse($info_mcu); @endphp
                                            @if ($tgl->isPast())
                                                <span style="color: red">
                                                    kadaluarsa sejak {{ $tgl->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span>{{ $tgl->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            <span style="color: red">-belum memiliki-</span>
                                        @endif
                                    </li>
                                    <li>
                                        EXP ID :
                                        @if ($info_id)
                                            @php $tgl = \Carbon\Carbon::parse($info_id); @endphp
                                            @if ($tgl->isPast())
                                                <span style="color: red">
                                                    kadaluarsa sejak {{ $tgl->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span>{{ $tgl->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            <span style="color: red">-belum memiliki-</span>
                                        @endif
                                    </li>
                                    <li>
                                        EXP KIMPER :
                                        @if ($info_kimper)
                                            @php $tgl = \Carbon\Carbon::parse($info_kimper); @endphp
                                            @if ($tgl->isPast())
                                                <span style="color: red">
                                                    kadaluarsa sejak {{ $tgl->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span>{{ $tgl->translatedFormat('d F Y') }}</span>
                                            @endif
                                        @else
                                            <span style="color: red">-belum memiliki-</span>
                                        @endif
                                    </li>
                                @else
                                    <h4 class="alert-heading">-Tidak ada data-</h4>
                                @endif
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="form-group">
                                <label for="jenis Pengajuan">Jenis Pengajuan</label>
                                <select
                                    class="form-control form-control-sm @error('jenis_pengajuan_kimper') is-invalid @enderror"
                                    wire:model.live="jenis_pengajuan_kimper">
                                    <option value="">Pilih Pengajuan</option>
                                    <option value="baru">Baru</option>
                                    <option value="perpanjangan">Perpanjang</option>
                                    <option value="penambahan">Penambahan Versatility</option>
                                </select>
                                @error('jenis_pengajuan_kimper')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="@if ($jenis_pengajuan_kimper === '' || $jenis_pengajuan_kimper === null) col-12 @else col-6 @endif">
                            <div class="form-group">
                                <label for="upload_request" class="form-label">Upload Form Request</label>
                                <input
                                    class="form-control form-control-sm @error('upload_request') is-invalid @enderror"
                                    type="file" id="upload_request" wire:model='upload_request'>
                                @error('upload_request')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}

                        @if ($jenis_pengajuan_kimper == 'baru')
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="upload_id" class="form-label">Upload ID Aktif</label>
                                    <input class="form-control form-control-sm @error('upload_id') is-invalid @enderror"
                                        type="file" id="upload_id" wire:model='upload_id'>
                                    @error('upload_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        {{-- <div class="col-6">
                            <div class="form-group">
                                <label for="upload_foto" class="form-label">Upload Foto</label>
                                <input class="form-control form-control-sm @error('upload_foto') is-invalid @enderror"
                                    type="file" id="upload_foto" wire:model='upload_foto'>
                                @error('upload_foto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        {{-- <div class="col-6">
                            <div class="form-group">
                                <label for="upload_ktp" class="form-label">Upload KTP</label>
                                <input class="form-control form-control-sm @error('upload_ktp') is-invalid @enderror"
                                    type="file" id="upload_ktp" wire:model='upload_ktp'>
                                @error('upload_ktp')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        @if ($jenis_pengajuan_kimper == 'perpanjangan' || $jenis_pengajuan_kimper == 'penambahan')
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="upload_kimper_lama" class="form-label">Upload Kimper Lama</label>
                                    <input
                                        class="form-control form-control-sm @error('upload_kimper_lama') is-invalid @enderror"
                                        type="file" id="upload_kimper_lama" wire:model='upload_kimper_lama'>
                                    @error('upload_kimper_lama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-3">
                            <div class="form-group">
                                <label for="jenis_sim">Jenis SIM</label>
                                <select name="jenis_sim"
                                    class="form-control form-control-sm @error('jenis_sim') is-invalid @enderror"
                                    wire:model="jenis_sim">
                                    <option value="">Pilih Sim</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="B1">B1</option>
                                    <option value="B1 UMUM">B1 UMUM</option>
                                    <option value="B2">B2</option>
                                    <option value="B2 UMUM">B2 UMUM</option>
                                </select>
                                @error('jenis_sim')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="no_sim" class="form-label">No Sim</label>
                                <input class="form-control form-control-sm @error('no_sim') is-invalid @enderror"
                                    type="text" id="no_sim" wire:model='no_sim' placeholder="No Sim">
                                @error('no_sim')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="upload_sim" class="form-label">Upload SIM</label>
                                <input class="form-control form-control-sm @error('upload_sim') is-invalid @enderror"
                                    type="file" id="upload_sim" wire:model='upload_sim'>
                                @error('upload_sim')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="exp_sim" class="form-label">Exp Sim</label>
                                <input class="form-control form-control-sm @error('exp_sim') is-invalid @enderror"
                                    type="date" id="exp_sim" wire:model='exp_sim'>
                                @error('exp_sim')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="upload_sertifikat" class="form-label">Upload Sertifikat</label>
                                <input
                                    class="form-control form-control-sm @error('upload_sertifikat') is-invalid @enderror"
                                    type="file" id="upload_sertifikat" wire:model='upload_sertifikat'>
                                @error('upload_sertifikat')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($jenis_pengajuan_kimper === 'baru' || $jenis_pengajuan_kimper === 'penambahan')
                            @for ($i = 1; $i <= $form_lpo; $i++)
                                <div class="col-12 pt-2">
                                    <fieldset><b>LPO {{ $type_lpo[$i] ?? '' }}</b></fieldset>
                                    <hr>
                                </div>
                                @php
                                    // Ambil semua pengajuan_kimper milik NRP
                                    $pengajuanKimperIds = \App\Models\ModelPengajuanKimper::where('nrp', $nrp)->pluck(
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
                                    unset($used[$i]);

                                    $available = array_diff($unfulfilledTypes, $used);
                                @endphp

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="type_lpo" class="form-label">Upload LPO
                                            [{{ $i }}]</label>
                                        <select class="form-control form-control-sm"
                                            wire:model.live="type_lpo.{{ $i }}">
                                            <option value="">-Type / Jenis LPO-</option>
                                            @foreach ($available as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_lpo.' . $i)
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="upload_lpo" class="form-label">Upload
                                            LPO{{ '[' . $i . ']' }}</label>
                                        <input
                                            class="form-control form-control-sm @error('upload_lpo') is-invalid @enderror"
                                            type="file" id="upload_lpo"
                                            wire:model='upload_lpo.{{ $i }}'>
                                        @error('upload_lpo.' . $i)
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="type_point" class="form-label">Point Nilai
                                            [{{ $i }}]</label>
                                        <select class="form-control form-control-sm"
                                            wire:model.live="type_point.{{ $i }}">
                                            <option value="">-Point Nilai-</option>
                                            <option value='4'>4 Point</option>
                                            <option value='5'>5 Point</option>
                                        </select>
                                        @error('type_point.' . $i)
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @if (!empty($type_point[$i]))
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="instrumen_panel" class="form-label">Nilai Instrument
                                                Panel & Kontrol{{ '[' . $i . ']' }}</label>
                                            <input
                                                class="form-control form-control-sm @error('instrumen_panel.' . $i) is-invalid @enderror"
                                                type="number" id="instrumen_panel"
                                                wire:model.live='instrumen_panel.{{ $i }}'
                                                placeholder="Nilai Instrument Panel & Kontrol">
                                            @error('instrumen_panel.' . $i)
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if ($type_point[$i] == 5)
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="main_power" class="form-label">Nilai
                                                    Power Train{{ '[' . $i . ']' }}</label>
                                                <input
                                                    class="form-control form-control-sm @error('main_power.' . $i) is-invalid @enderror"
                                                    type="number" id="main_power"
                                                    wire:model.live='main_power.{{ $i }}'
                                                    placeholder="Nilai Power Train">
                                                @error('main_power.' . $i)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="safety_operasi" class="form-label">Safety
                                                Operasi{{ '[' . $i . ']' }}</label>
                                            <input
                                                class="form-control form-control-sm @error('safety_operasi.' . $i) is-invalid @enderror"
                                                type="number" id="safety_operasi"
                                                wire:model.live='safety_operasi.{{ $i }}'
                                                placeholder="Nilai Safety Operasi">
                                            @error('safety_operasi.' . $i)
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="metode_operasi" class="form-label">Metode dan Teknik
                                                Operasi{{ '[' . $i . ']' }}</label>
                                            <input
                                                class="form-control form-control-sm @error('metode_operasi.' . $i) is-invalid @enderror"
                                                type="number" id="metode_operasi"
                                                wire:model.live='metode_operasi.{{ $i }}'
                                                placeholder="Nilai Metode dan Teknik Operasi">
                                            @error('metode_operasi.' . $i)
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="perawatan"
                                                class="form-label">Perawatan{{ '[' . $i . ']' }}</label>
                                            <input
                                                class="form-control form-control-sm @error('perawatan.' . $i) is-invalid @enderror"
                                                type="number" id="perawatan"
                                                wire:model.live='perawatan.{{ $i }}'
                                                placeholder="Nilai Perawatan">
                                            @error('perawatan.' . $i)
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="nilai_total" class="form-label">Nilai
                                                Total{{ '[' . $i . ']' }}</label>
                                            <input class="form-control form-control-sm" type="number"
                                                id="nilai_total" wire:model='nilai_total.{{ $i }}'
                                                readonly>
                                        </div>
                                    </div>
                                @endif
                            @endfor
                            <div class="col-12 pt-2">
                                <div class="form-group">
                                    <a wire:click="tambahLpo" class="btn btn-success btn-sm">
                                        <i class="bi bi-plus-square"></i> LPO
                                    </a>
                                    @if ($form_lpo > 1)
                                        <a wire:click="kurangLpo" class="btn btn-danger btn-sm">
                                            <i class="bi bi-dash-square"></i> LPO
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- <div class="col-4">
                            <div class="form-group">
                                <label for="upload_skd" class="form-label">Upload SKD</label>
                                <input class="form-control form-control-sm @error('upload_skd') is-invalid @enderror"
                                    type="file" id="upload_skd" wire:model='upload_skd'>
                                @error('upload_skd')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="upload_bpjs_kes" class="form-label">Upload BPJS Kesehatan</label>
                                <input
                                    class="form-control form-control-sm @error('upload_bpjs_kes') is-invalid @enderror"
                                    type="file" id="upload_bpjs_kes" wire:model='upload_bpjs_kes'>
                                @error('upload_bpjs_kes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="upload_bpjs_ker" class="form-label">Upload BPJS Ketenagakerjaan</label>
                                <input
                                    class="form-control form-control-sm @error('upload_bpjs_ker') is-invalid @enderror"
                                    type="file" id="upload_bpjs_ker" wire:model='upload_bpjs_ker'>
                                @error('upload_bpjs_ker')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="form-group pt-2 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            <button class="btn btn-outline-danger btn-sm" wire:click="close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
