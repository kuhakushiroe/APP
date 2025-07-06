<?php

namespace App\Livewire\Mcu;

use App\Jobs\SendNotifMcu;
use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Mcu extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    public $search = '';
    protected $updatesQueryString = ['search'];
    public $form = false;
    public $formVerifikasi = false;
    public $formUpload = false;
    public $id_mcu, $sub_id, $proveder, $nrp, $nama, $tgl_mcu, $gol_darah, $jenis_kelamin, $file_mcu;
    public $no_dokumen, $status = NULL, $keterangan_mcu, $saran_mcu, $tgl_verifikasi, $exp_mcu;
    public $riwayat_rokok, $BB, $TB, $LP, $BMI, $Laseq, $reqtal_touche, $sistol, $diastol, $OD_jauh, $OS_jauh, $OD_dekat, $OS_dekat, $butawarna, $gdp, $hba1c, $gd_2_jpp, $ureum, $creatine, $asamurat, $sgot, $sgpt, $hbsag, $anti_hbs, $kolesterol, $hdl, $ldl, $tg, $darah_rutin, $napza, $urin, $ekg, $rontgen, $audiometri, $spirometri, $tredmil_test, $widal_test, $routin_feces, $kultur_feces;
    public $caridatakaryawan = [];
    public $carikaryawan = [];
    public $status_file_mcu = [];
    public $catatan_file_mcu = [];
    public $paramedik, $paramedik_status, $paramedik_catatan;

    #[Title('MCU')]
    public function updatedNrp($value)
    {
        $caridatakaryawan = Karyawan::where('nrp', $value)
            ->where('status', 'aktif')
            ->first();
        if ($caridatakaryawan) {
            $this->nama = $caridatakaryawan->nama;
            $this->gol_darah = $caridatakaryawan->gol_darah;
            $this->jenis_kelamin = $caridatakaryawan->jenis_kelamin;
        } else {
            $this->nama = null;
            $this->gol_darah = null;
            $this->jenis_kelamin = null; // Reset field if NIK is not found
        }
    }
    public function open()
    {
        $this->form = true;
    }
    public function edit($id_mcu)
    {
        $this->form = true;
        $cariMcu = ModelsMcu::find($id_mcu);
        $this->id_mcu = $cariMcu->id;
        $this->nrp = $cariMcu->id_karyawan;
        $this->proveder = $cariMcu->proveder;
        $this->tgl_mcu = $cariMcu->tgl_mcu;
        $caridatakaryawan = Karyawan::where('nrp', $cariMcu->id_karyawan)
            ->where('status', 'aktif')
            ->first();
        if ($caridatakaryawan) {
            $this->nama = $caridatakaryawan->nama;
            $this->gol_darah = $caridatakaryawan->gol_darah;
            $this->jenis_kelamin = $caridatakaryawan->jenis_kelamin;
        } else {
            $this->nama = null;
            $this->gol_darah = null;
            $this->jenis_kelamin = null; // Reset field if NIK is not found
        }
    }
    public function close()
    {
        $this->form = false;
        $this->reset();
    }

    // Fungsi untuk memuat data dari database
    public function store()
    {
        // Validate the form inputs
        $this->validate([
            'proveder' => 'required',
            'nrp' => [
                'required',
                Rule::exists('karyawans', 'nrp')->where(function ($query) {
                    $query->where('status', 'aktif');
                }),
            ],
            'tgl_mcu' => 'required|date',
            //'gol_darah' => 'required',
            'file_mcu' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ], [
            'proveder.required' => 'Proveder harus diisi.',
            'nrp.required' => 'NRP harus diisi.',
            'nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
            'tgl_mcu.required' => 'Tanggal MCU harus diisi.',
            'tgl_mcu.date' => 'Tanggal MCU harus dalam format yang valid.',
            //'gol_darah.required' => 'Golongan Darah harus diisi.',
            'file_mcu.required' => 'File MCU harus diunggah.',
            'file_mcu.file' => 'File harus berupa file.',
            'file_mcu.mimes' => 'Format file harus PDF.',
            'file_mcu.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $datakaryawan = Karyawan::where('nrp', $this->nrp)->first();
        $folderDept = strtoupper(Str::slug($datakaryawan->dept, '_'));
        $folderKaryawan = strtoupper(Str::slug(
            $datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan,
            '_'
        ));
        $folderPath = $folderDept . '/' . $folderKaryawan . '/MCU';

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Handle the file upload for 'file_mcu'
        if ($this->file_mcu) {
            // Store the file in the specific folder
            if (!empty($this->id_mcu)) {
                $filePath = $this->file_mcu->storeAs($folderPath, $folderKaryawan . "-MCU-REVISI-" . time() . ".{$this->file_mcu->getClientOriginalExtension()}", 'public');
            } else {
                $filePath = $this->file_mcu->storeAs($folderPath, $folderKaryawan . "-MCU-" . time() . ".{$this->file_mcu->getClientOriginalExtension()}", 'public');
            }
            // $filePath = $this->file_mcu->store($folderPath, 'public');
        } else {
            $filePath = null; // Handle the case where no file is uploaded (optional)
        }

        $infoKaryawan = getInfoKaryawanByNrp($this->nrp);
        // Store the file path, not the file object
        if ($this->id_mcu) {
            ModelsMcu::updateOrCreate(
                ['id' => $this->id_mcu], // Assuming id_mcu is the primary key
                [
                    'id_karyawan' => $this->nrp,
                    'proveder' => $this->proveder,
                    'tgl_mcu' => $this->tgl_mcu,
                    'gol_darah' => $this->gol_darah,
                    'file_mcu' => $filePath,
                    'status_file_mcu' => NULL,
                    'status' => $this->status,
                    'tgl_verifikasi' => $this->tgl_verifikasi, // Store the file path, not the file object
                ]
            );
            $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n*Upload File Revisi*";
        } else {
            ModelsMcu::updateOrCreate(
                ['id' => $this->id_mcu], // Assuming id_mcu is the primary key
                [
                    'id_karyawan' => $this->nrp,
                    'proveder' => $this->proveder,
                    'tgl_mcu' => $this->tgl_mcu,
                    'gol_darah' => $this->gol_darah,
                    'file_mcu' => $filePath,
                    'status' => $this->status,
                    'status_file_mcu' => NULL,
                    'tgl_verifikasi' => $this->tgl_verifikasi, // Store the file path, not the file object
                ]
            );
            $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*";
        }
        $info = getUserInfo(); // ambil data user saat dispatch, di konteks request HTTP (user pasti ada)
        $nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);
        $token = $info['token'];
        $namaUser = $info['nama'];
        dispatch(new SendNotifMcu($pesanText, $nomorGabungan, $token, $namaUser));

        // Reset the form fields after save

        // Determine whether it's an edit or a new entry
        $jenis = $this->id_mcu ? 'Edit' : 'Tambah';
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' MCU',
            position: 'center',
            confirm: true,
            redirect: '/mcu',
        );
        return;
    }
    public function kirimStatusFileMCU($id_mcu)
    {
        // Validasi input
        $this->validate([
            "status_file_mcu.$id_mcu" => 'required|in:0,1',  // Status harus 0 (Diterima) atau 1 (Ditolak)
            "catatan_file_mcu.$id_mcu" => 'nullable|string|max:255',  // Catatan harus string, maksimal 255 karakter
        ]);

        // Ambil data status dan catatan berdasarkan ID MCU
        $status = $this->status_file_mcu[$id_mcu] ?? null;
        $catatan = $this->catatan_file_mcu[$id_mcu] ?? null;

        // Validasi tambahan: jika status adalah "Ditolak", pastikan catatan ada
        if ($status == 0 && empty($catatan)) {
            $this->dispatch(
                'alert',
                type: 'error',
                title: 'Error',
                text: 'Error, Catatan harus diisi ketika status diubah menjadi "Ditolak" pada MCU',
                position: 'center',
                confirm: true,
                redirect: '/mcu',
            );
            return;
        }

        // Ambil data MCU berdasarkan ID
        $mcu = ModelsMcu::find($id_mcu);
        $infoKaryawan = getInfoKaryawanByNrp($mcu->id_karyawan);
        if ($mcu) {
            // Update status file MCU dan catatan jika ada
            $mcu->status_file_mcu = $status;
            $mcu->catatan_file_mcu = $catatan;
            // Simpan perubahan
            $mcu->save();

            $info = getUserInfo(); // ambil data user saat dispatch, di konteks request HTTP (user pasti ada)
            if ($status == 1) {
                $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n*$infoKaryawan*\n Status File MCU: *Diterima* Proses Dokter";
            } else {
                $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n*$infoKaryawan*\n Status File MCU: *Ditolak - $catatan*";
            }
            $nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);
            $token = $info['token'];
            $namaUser = $info['nama'];
            dispatch(new SendNotifMcu($pesanText, $nomorGabungan, $token, $namaUser));
            // Reset input setelah kirim

            $this->status_file_mcu[$id_mcu] = '';
            $this->catatan_file_mcu[$id_mcu] = '';

            // Berikan pesan sukses
            $this->dispatch(
                'alert',
                type: 'success',
                title: 'Berhasil',
                text: 'Berhasil mengirim status MCU',
                position: 'center',
                confirm: true,
                redirect: '/mcu',
            );
            return;
        } else {
            $this->dispatch(
                'alert',
                type: 'error',
                title: 'Error',
                text: 'Gagal mengirim MCU tidak ditemukan',
                position: 'center',
                confirm: true,
                redirect: '/mcu',
            );
            return;
        }
    }
    public function uploadMCU($id_mcu)
    {
        $this->formUpload = true;
        $ceksubmcu = ModelsMcu::where('sub_id', $id_mcu)->first();

        if ($ceksubmcu) {
            $carimcu = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->where('mcu.sub_id', $id_mcu)
                ->orderBy('mcu.created_at', 'desc')
                ->first();
        } else {
            $carimcu = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->orderBy('mcu.created_at', 'desc')
                ->where('mcu.id', $id_mcu)->first();
        }
        $this->nrp = $carimcu->id_karyawan;
        $this->sub_id = $carimcu->sub_id ? $carimcu->sub_id : $id_mcu;
        $this->no_dokumen = $carimcu->no_dokumen;
        $this->tgl_mcu = $carimcu->tgl_mcu;
        $this->nama = $carimcu->nama;
        $this->gol_darah = $carimcu->gol_darah;
        $this->file_mcu = $carimcu->file_mcu;
        $this->status = $carimcu->mcuStatus;
        $this->id_mcu = $id_mcu;
        $this->proveder = $carimcu->proveder;

        $this->riwayat_rokok = $carimcu->riwayat_rokok;
        $this->BB = $carimcu->BB;
        $this->TB = $carimcu->TB;
        $this->LP = $carimcu->LP;
        $this->BMI = $carimcu->BMI;
        $this->Laseq = $carimcu->Laseq;
        $this->reqtal_touche = $carimcu->reqtal_touche;
        $this->sistol = $carimcu->sistol;
        $this->diastol = $carimcu->diastol;
        $this->OD_jauh = $carimcu->OD_jauh;
        $this->OS_jauh = $carimcu->OS_jauh;
        $this->OD_dekat = $carimcu->OD_dekat;
        $this->OS_dekat = $carimcu->OS_dekat;
        $this->butawarna = $carimcu->butawarna;
        $this->gdp = $carimcu->gdp;
        $this->gd_2_jpp = $carimcu->gd_2_jpp;
        $this->ureum = $carimcu->ureum;
        $this->creatine = $carimcu->creatine;
        $this->asamurat = $carimcu->asamurat;
        $this->sgot = $carimcu->sgot;
        $this->sgpt = $carimcu->sgpt;
        $this->hbsag = $carimcu->hbsag;
        $this->anti_hbs = $carimcu->anti_hbs;
        $this->kolesterol = $carimcu->kolesterol;
        $this->hdl = $carimcu->hdl;
        $this->ldl = $carimcu->ldl;
        $this->tg = $carimcu->tg;
        $this->darah_rutin = $carimcu->darah_rutin;
        $this->napza = $carimcu->napza;
        $this->urin = $carimcu->urin;
        $this->ekg = $carimcu->ekg;
        $this->rontgen = $carimcu->rontgen;
        $this->audiometri = $carimcu->audiometri;
        $this->spirometri = $carimcu->spirometri;
        $this->tredmil_test = $carimcu->tredmil_test;
        $this->widal_test = $carimcu->widal_test;
        $this->routin_feces = $carimcu->routin_feces;
        $this->kultur_feces = $carimcu->kultur_feces;

        $this->tgl_verifikasi = date('Y-m-d');
        $this->exp_mcu = now()->addMonth(6)->format('Y-m-d');
    }
    public function storeUpload()
    {
        // Validate the form inputs
        $this->validate([
            'proveder' => 'required',
            'nrp' => [
                'required',
                Rule::exists('karyawans', 'nrp')->where(function ($query) {
                    $query->where('status', 'aktif');
                }),
            ],
            'tgl_mcu' => 'required|date',
            'file_mcu' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ], [
            'proveder.required' => 'Proveder harus diisi.',
            'nrp.required' => 'NRP harus diisi.',
            'nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
            'tgl_mcu.required' => 'Tanggal MCU harus diisi.',
            'tgl_mcu.date' => 'Tanggal MCU harus dalam format yang valid.',
            'file_mcu.required' => 'File MCU harus diunggah.',
            'file_mcu.file' => 'File harus berupa file.',
            'file_mcu.mimes' => 'Format file harus PDF.',
            'file_mcu.max' => 'Ukuran file maksimal 10MB.',
        ]);

        // Define the folder path where the file will be stored
        $folderPath = $this->nrp . '/pengajuan-mcu';

        // Check if folder exists, if not, create it
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath); // Create the directory if it doesn't exist
        }

        // Handle the file upload for 'file_mcu'
        if ($this->file_mcu) {
            // Store the file in the specific folder
            $filePath = $this->file_mcu->store($folderPath, 'public');
        } else {
            $filePath = null; // Handle the case where no file is uploaded (optional)
        }

        // Update or create the MCU record
        ModelsMcu::create(
            [
                'id_karyawan' => $this->nrp,
                'no_dokumen' => $this->no_dokumen,
                'sub_id' => $this->sub_id,
                'proveder' => $this->proveder,
                'tgl_mcu' => $this->tgl_mcu,
                'gol_darah' => $this->gol_darah,
                'file_mcu' => $filePath, // Store the file path, not the file object
                'riwayat_rokok' => $this->riwayat_rokok,
                'BB' => $this->BB,
                'TB' => $this->TB,
                'LP' => $this->LP,
                'BMI' => $this->BMI,
                'Laseq' => $this->Laseq,
                'reqtal_touche' => $this->reqtal_touche,
                'sistol' => $this->sistol,
                'diastol' => $this->diastol,
                'OD_jauh' => $this->OD_jauh,
                'OS_jauh' => $this->OS_jauh,
                'OD_dekat' => $this->OD_dekat,
                'OS_dekat' => $this->OS_dekat,
                'butawarna' => $this->butawarna,
                'gdp' => $this->gdp,
                'gd_2_jpp' => $this->gd_2_jpp,
                'ureum' => $this->ureum,
                'creatine' => $this->creatine,
                'asamurat' => $this->asamurat,
                'sgot' => $this->sgot,
                'sgpt' => $this->sgpt,
                'hbsag' => $this->hbsag,
                'anti_hbs' => $this->anti_hbs,
                'kolesterol' => $this->kolesterol,
                'hdl' => $this->hdl,
                'ldl' => $this->ldl,
                'tg' => $this->tg,
                'darah_rutin' => $this->darah_rutin,
                'napza' => $this->napza,
                'urin' => $this->urin,
                'ekg' => $this->ekg,
                'rontgen' => $this->rontgen,
                'audiometri' => $this->audiometri,
                'spirometri' => $this->spirometri,
                'tredmil_test' => $this->tredmil_test,
                'widal_test' => $this->widal_test,
                'routin_feces' => $this->routin_feces,
                'kultur_feces' => $this->kultur_feces,
            ]
        );

        $info = getUserInfo(); // ambil data user saat dispatch, di konteks request HTTP (user pasti ada)
        $infoKaryawan = getInfoKaryawanByNrp($this->nrp);

        $pesanText = "📢 *MIFA-TEST NOTIF - Follow Up MCU*\n\n\n*$infoKaryawan*\n";
        $nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);
        $token = $info['token'];
        $namaUser = $info['nama'];
        dispatch(new SendNotifMcu($pesanText, $nomorGabungan, $token, $namaUser));

        // Reset the form fields after save
        $this->reset();

        // Determine whether it's an edit or a new entry
        $jenis = $this->id_mcu ? 'Edit' : 'Tambah';
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' MCU',
            position: 'center',
            confirm: true,
            redirect: '/mcu',
        );
        return;
    }
    public function updatedBB()
    {
        $this->hitungBMI();
    }

    public function updatedTB()
    {
        $this->hitungBMI();
    }

    private function hitungBMI()
    {
        if (!empty($this->BB) && !empty($this->TB) && $this->TB > 0) {
            $this->BMI =  round($this->BB / pow($this->TB / 100, 2), 2);
        } else {
            $this->BMI = null;
        }
    }
    public function verifikasi($id_mcu)
    {
        $this->formVerifikasi = true;
        $carimcu = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.gol_darah as mcuGolDarah')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->where('mcu.id', $id_mcu)->first();
        $this->nrp = $carimcu->id_karyawan;
        $this->no_dokumen = $carimcu->no_dokumen;
        $this->tgl_mcu = $carimcu->tgl_mcu;
        $this->nama = $carimcu->nama;
        $this->gol_darah = $carimcu->mcuGolDarah;
        $this->file_mcu = $carimcu->file_mcu;
        $this->status = $carimcu->mcuStatus;
        $this->id_mcu = $id_mcu;
        $this->riwayat_rokok = $carimcu->riwayat_rokok;
        $this->BB = $carimcu->BB;
        $this->TB = $carimcu->TB;
        $this->LP = $carimcu->LP;
        //$this->BMI = $carimcu->BMI;
        $this->Laseq = $carimcu->Laseq;
        $this->reqtal_touche = $carimcu->reqtal_touche;
        $this->sistol = $carimcu->sistol;
        $this->diastol = $carimcu->diastol;
        $this->OD_jauh = $carimcu->OD_jauh;
        $this->OS_jauh = $carimcu->OS_jauh;
        $this->OD_dekat = $carimcu->OD_dekat;
        $this->OS_dekat = $carimcu->OS_dekat;
        $this->butawarna = $carimcu->butawarna;
        $this->gdp = $carimcu->gdp;
        $this->gd_2_jpp = $carimcu->gd_2_jpp;
        $this->hba1c = $carimcu->hba1c;
        $this->ureum = $carimcu->ureum;
        $this->creatine = $carimcu->creatine;
        $this->asamurat = $carimcu->asamurat;
        $this->sgot = $carimcu->sgot;
        $this->sgpt = $carimcu->sgpt;
        $this->hbsag = $carimcu->hbsag;
        $this->anti_hbs = $carimcu->anti_hbs;
        $this->kolesterol = $carimcu->kolesterol;
        $this->hdl = $carimcu->hdl;
        $this->ldl = $carimcu->ldl;
        $this->tg = $carimcu->tg;
        $this->darah_rutin = $carimcu->darah_rutin;
        $this->napza = $carimcu->napza;
        $this->urin = $carimcu->urin;
        $this->ekg = $carimcu->ekg;
        $this->rontgen = $carimcu->rontgen;
        $this->audiometri = $carimcu->audiometri;
        $this->spirometri = $carimcu->spirometri;
        $this->tredmil_test = $carimcu->tredmil_test;
        $this->widal_test = $carimcu->widal_test;
        $this->routin_feces = $carimcu->routin_feces;
        $this->kultur_feces = $carimcu->kultur_feces;
        $this->tgl_verifikasi = date('Y-m-d');
        $this->exp_mcu = now()->addMonth(6)->format('Y-m-d');
    }
    public function storeVerifikasi()
    {
        $info = getUserInfo();
        $token = $info['token'];
        $namaUser = $info['nama'];
        $mcu = ModelsMcu::find($this->id_mcu);
        $nrp = $mcu->id_karyawan;
        $infoKaryawan = getInfoKaryawanByNrp($nrp);
        if (auth()->user()->subrole === 'verifikator') {
            $this->validate(
                [
                    'paramedik_status' => 'required',
                ],
                [
                    'paramedik_status.required' => 'Status harus diisi.',
                ]
            );
            if ($this->paramedik_status == 0) {
                $this->validate(
                    [
                        'paramedik_catatan' => 'required',
                    ],
                    [
                        'paramedik_catatan.required' => 'Catatan harus diisi.',
                    ]
                );
                $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Inputan data MCU: *ditolak* \nKeterangan: *$this->paramedik_catatan*\n";

                $mcu->update([
                    'tgl_verifikasi' => $this->tgl_verifikasi,
                    'verifikator' => auth()->user()->username,
                    'paramedik' => NULL,
                    'paramedik_status' => $this->paramedik_status,
                    'paramedik_catatan' => $this->paramedik_catatan
                ]);
                foreach ($info['nomorParamedik'] as $i => $nomor) {
                    pesan($nomor, $pesanText, $info['token']);
                    if ($i < count($info['nomorParamedik']) - 1) {
                        sleep(1);
                    }
                }
            } else {
                $this->validate(
                    [
                        'status' => 'required',
                        'tgl_verifikasi' => 'required',
                    ],
                    [
                        'status.required' => 'Status harus diisi.',
                        'tgl_verifikasi.required' => 'Tanggal Verifikasi harus diisi.',
                    ]
                );
                $indukmcu = ModelsMcu::where('id', $mcu->sub_id)->where('sub_id', NULL)->first();
                if ($this->status == 'FIT' || $this->status == 'FIT WITH NOTE') {
                    $mcu->update([
                        'no_dokumen' => $this->no_dokumen,
                        'status_' => 'close',
                        'status' => $this->status,
                        'tgl_verifikasi' => $this->tgl_verifikasi,
                        'verifikator' => auth()->user()->username,
                        'exp_mcu' => $this->exp_mcu, // Use Laravel's `now()` helper for current date
                        'keterangan_mcu' => $this->keterangan_mcu,
                        'saran_mcu' => $this->saran_mcu,
                        'paramedik_status' => $this->paramedik_status,
                        'paramedik_catatan' => $this->paramedik_catatan
                    ]);
                    Karyawan::where('nrp', $mcu->id_karyawan)
                        ->update(['exp_mcu' => $this->exp_mcu]);
                    if ($indukmcu) {
                        $indukmcu->update([
                            'status_' => 'close',
                        ]);
                    }
                    $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Hasil MCU: *$this->status*\n";
                } else {
                    if (empty($mcu->sub_id)) {

                        $mcu->update([
                            'no_dokumen' => $this->no_dokumen,
                            'status_' => 'open',
                            'status' => $this->status,
                            'tgl_verifikasi' => $this->tgl_verifikasi,
                            'verifikator' => auth()->user()->username, // Use Laravel's `now()` helper for current date
                            'keterangan_mcu' => $this->keterangan_mcu,
                            'saran_mcu' => $this->saran_mcu,
                            'paramedik_status' => $this->paramedik_status,
                            'paramedik_catatan' => $this->paramedik_catatan
                        ]);
                        $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Hasil MCU: *$this->status*\n";
                    } else {
                        $mcu->update([
                            'no_dokumen' => $this->no_dokumen,
                            'status_' => 'close',
                            'status' => $this->status,
                            'tgl_verifikasi' => $this->tgl_verifikasi,
                            'verifikator' => auth()->user()->username, // Use Laravel's `now()` helper for current date
                            'keterangan_mcu' => $this->keterangan_mcu,
                            'saran_mcu' => $this->saran_mcu,
                            'paramedik_status' => $this->paramedik_status,
                            'paramedik_catatan' => $this->paramedik_catatan
                        ]);
                        $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Hasil MCU: *$this->status*\n";
                    }
                    if ($indukmcu) {
                        $indukmcu->update([
                            'status_' => 'open',
                            'status' => $this->status,
                        ]);
                    }
                }
            }
        } else {
            $this->validate(
                [
                    'no_dokumen' => 'required',
                ],
                [
                    'no_dokumen.required' => 'No Dokumen harus diisi.',
                ]
            );
            if (empty($mcu->paramedik_catatan)) {
                $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n *Paramedik Input Hasil MCU* \n";
            } else {
                $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n *Paramedik Input Ulang Hasil MCU*\n";
            }

            $mcu->update([
                'no_dokumen' => $this->no_dokumen,
                'gol_darah' => $this->gol_darah,
                'status_' => 'open',
                'status' => $this->status,
                'tgl_verifikasi' => $this->tgl_verifikasi, // Use Laravel's `now()` helper for current date
                'keterangan_mcu' => $this->keterangan_mcu,
                'riwayat_rokok' => $this->riwayat_rokok,
                'BB' => $this->BB,
                'TB' => $this->TB,
                'LP' => $this->LP,
                'BMI' => $this->BMI,
                'Laseq' => $this->Laseq,
                'reqtal_touche' => $this->reqtal_touche,
                'sistol' => $this->sistol,
                'diastol' => $this->diastol,
                'OD_jauh' => $this->OD_jauh,
                'OS_jauh' => $this->OS_jauh,
                'OD_dekat' => $this->OD_dekat,
                'OS_dekat' => $this->OS_dekat,
                'butawarna' => $this->butawarna,
                'gdp' => $this->gdp,
                'gd_2_jpp' => $this->gd_2_jpp,
                'ureum' => $this->ureum,
                'creatine' => $this->creatine,
                'asamurat' => $this->asamurat,
                'sgot' => $this->sgot,
                'sgpt' => $this->sgpt,
                'hbsag' => $this->hbsag,
                'anti_hbs' => $this->anti_hbs,
                'kolesterol' => $this->kolesterol,
                'hdl' => $this->hdl,
                'ldl' => $this->ldl,
                'tg' => $this->tg,
                'darah_rutin' => $this->darah_rutin,
                'napza' => $this->napza,
                'urin' => $this->urin,
                'ekg' => $this->ekg,
                'rontgen' => $this->rontgen,
                'audiometri' => $this->audiometri,
                'spirometri' => $this->spirometri,
                'tredmil_test' => $this->tredmil_test,
                'widal_test' => $this->widal_test,
                'routin_feces' => $this->routin_feces,
                'kultur_feces' => $this->kultur_feces,
                'saran_mcu' => $this->saran_mcu,
                'paramedik' => auth()->user()->username,
                'paramedik_catatan' => $this->paramedik_catatan,
                'paramedik_status' => NULL,
            ]);
            $updateKaryawan = Karyawan::where('nrp', $nrp)->first();
            $updateKaryawan->update([
                'gol_darah' => $this->gol_darah,
            ]);
            $nomorDokter = array_merge($info['nomorDokter']);
            dispatch(new SendNotifMcu($pesanText, $nomorDokter, $token, $namaUser));
        }
        $nomorAdmin = array_merge($info['nomorAdmins']);
        dispatch(new SendNotifMcu($pesanText, $nomorAdmin, $token, $namaUser));

        $jenis = $this->id_mcu ? 'Verifikasi' : 'Tambah';
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' MCU',
            position: 'center',
            confirm: true,
            redirect: '/mcu',
        );
        return;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function cetak($id)
    {
        $carimcu = ModelsMcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->where('mcu.id', $id)
            ->select('mcu.*', 'karyawans.*', 'mcu.status as status_mcu') // beri alias untuk menghindari bentrok
            ->first();

        if (!$carimcu) {
            abort(404, 'Data MCU tidak ditemukan');
        }

        // Optional: convert ke array untuk amankan karakter
        $data = json_decode(json_encode($carimcu), true);

        $pdf = Pdf::loadView('cetak.mcu', ['data' => $data])
            ->setOption('dpi', 96)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('mcu-' . ($data['nik'] ?? 'unknown') . '-' . date('Y-m-d') . '.pdf');
    }
    public function mount()
    {
        $cariMcu = ModelsMcu::where('paramedik', '=', NULL)->get();
        foreach ($cariMcu as $item) {
            $this->status_file_mcu[$item->id] =  '';  // Menambahkan nilai default
            $this->catatan_file_mcu[$item->id] = '';  // Menambahkan nilai default
        }

        // Mengambil karyawan yang aktif
        $this->carikaryawan = Karyawan::select('nrp', 'nama')
            ->where('status', 'aktif')
            ->get();

        // Menetapkan tanggal
        $this->tgl_mcu = date('Y-m-d');
        $this->tgl_verifikasi = date('Y-m-d');
    }
    public function render()
    {

        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        if (in_array(auth()->user()->role, ['superadmin', 'dokter', 'she'])) {
            $carimcu = ModelsMcu::select('sub_id', 'verifikator')
                ->whereNull('verifikator')
                ->get();

            $prioritasIDs = $carimcu->pluck('sub_id')->filter()->unique()->toArray(); // Ambil nilai sub_id unik dan tidak null

            $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                ->where('mcu.status_', '=', "open")
                ->whereNull('mcu.sub_id')
                ->when(!empty($prioritasIDs), function ($query) use ($prioritasIDs) {
                    $ids = implode(',', $prioritasIDs);
                    return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                })
                ->orderBy('mcu.tgl_mcu', 'desc')
                ->paginate(10)
                ->withQueryString();
        } else {
            $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.status as mcuStatus')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                ->where('mcu.status_', '=', "open")
                ->where('mcu.sub_id', NULL)
                ->where('karyawans.dept', auth()->user()->subrole)
                ->orderBy('mcu.tgl_mcu', 'desc')
                ->paginate(10)
                ->withQueryString();
        }

        foreach ($mcus as $data) {
            if (!isset($this->status_file_mcu[$data->id_mcu])) {
                $this->status_file_mcu[$data->id_mcu] = '';  // Nilai default untuk status
            }
            if (!isset($this->catatan_file_mcu[$data->id_mcu])) {
                $this->catatan_file_mcu[$data->id_mcu] = '';  // Nilai default untuk catatan
            }
            $subItems = ModelsMcu::where('sub_id', $data->id_mcu) // Ambil subitem berdasarkan sub_id induk
                ->orderBy('tgl_mcu', 'asc') // Mengurutkan berdasarkan tanggal MCU
                ->get();
            foreach ($subItems as $subItem) {
                if (!isset($this->status_file_mcu[$subItem->id])) {
                    $this->status_file_mcu[$subItem->id] = '';  // Nilai default untuk status
                }
                if (!isset($this->catatan_file_mcu[$subItem->id])) {
                    $this->catatan_file_mcu[$subItem->id] = '';  // Nilai default untuk catatan
                }
            }
        }
        return view('livewire.mcu.mcu', compact('mcus', 'carifoto'));
    }
}
