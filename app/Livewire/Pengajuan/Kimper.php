<?php

namespace App\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\ModelPengajuanID;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\ModelPengajuanKimper;
use App\Models\pengajuanVersatility;
use App\Models\Versatility;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithoutUrlPagination;

class Kimper extends Component
{
    use WithFileUploads, WithPagination, WithoutUrlPagination;
    public $id_pengajuan, $nrp, $jenis_pengajuan_kimper, $no_sim, $jenis_sim, $exp_sim, $versatility, $status_pengajuan, $tgl_pengajuan, $exp_kimper;
    public $form = false;
    public $search = '';
    public $catatan_upload_induksi = [], $catatan_upload_id = [], $catatan_upload_kimper_lama = [], $catatan_upload_request = [], $catatan_upload_sim = [], $catatan_upload_sertifikat = [], $catatan_upload_lpo = [], $catatan_upload_foto = [], $catatan_upload_ktp = [], $catatan_upload_skd = [], $catatan_upload_bpjs_kes = [], $catatan_upload_bpjs_ker = [];
    public $status_upload_induksi = [], $status_upload_id = [], $status_upload_kimper_lama = [], $status_upload_request = [], $status_upload_sim = [], $status_upload_sertifikat = [], $status_upload_lpo = [], $status_upload_foto = [], $status_upload_ktp = [], $status_upload_skd = [], $status_upload_bpjs_kes = [], $status_upload_bpjs_ker = [];
    public $upload_id, $upload_kimper_lama, $upload_request, $upload_sim, $upload_sertifikat, $upload_lpo, $upload_foto, $upload_ktp, $upload_skd, $upload_bpjs_kes, $upload_bpjs_ker;
    public $info_nama, $info_dept, $info_jabatan, $info_mcu, $info_id, $info_kimper;
    public $expired_kimper = [];
    public $formVersatility = false;

    public $id_pengajuan_kimper, $id_versatility, $klasifikasi;
    public function openVersatility($id)
    {
        $this->id_pengajuan = $id;
        $this->formVersatility = true;
    }
    public function saveVersatility()
    {
        $this->validate([
            'id_pengajuan' => 'required',
            'id_versatility' => 'required',
            'klasifikasi' => 'required',
        ], [
            'id_pengajuan.required' => 'Pengajuan Kimper harus terisi.',
            'id_versatility.required' => 'Versatility harus dipilih.',
            'klasifikasi.required' => 'Klasifikasi harus diisi.',
        ]);

        pengajuanVersatility::create([
            'id_pengajuan_kimper' => $this->id_pengajuan,
            'id_versatility' => $this->id_versatility,
            'klasifikasi' => $this->klasifikasi,
        ]);

        $infoversa = DB::table('versatility')->where('id', $this->id_versatility)->first();
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil tambah Versatility ' . $infoversa->versatility,
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );
        $this->id_pengajuan = '';
        $this->formVersatility = false;
    }
    public function deleteVersatility($id)
    {
        pengajuanVersatility::where('id', $id)->delete();
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil hapus Versatility',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );
    }
    public function getAvailableVersatilityProperty()
    {
        $pengajuan = ModelPengajuanKimper::find($this->id_pengajuan);
        if (!$pengajuan) return collect();

        $nrp = $pengajuan->nrp;

        // Ambil semua pengajuan milik NRP ini
        $pengajuanIds = ModelPengajuanKimper::where('nrp', $nrp)->pluck('id');

        // Ambil semua versatility yang pernah dipakai oleh NRP ini
        $usedVersatilityIds = DB::table('pengajuan_kimper_versatility')
            ->whereIn('id_pengajuan_kimper', $pengajuanIds)
            ->pluck('id_versatility')
            ->unique();

        // Kembalikan daftar versatility yang belum dipakai oleh NRP ini
        return \App\Models\Versatility::whereNotIn('id', $usedVersatilityIds)->get();
    }

    public function lanjutKimper($id)
    {
        $pengajuan = ModelPengajuanKimper::findOrFail($id);
        $pengajuan->update([
            'versatility' => 'ok',
        ]);
    }
    public function open()
    {
        $this->form = true;
    }

    public function close()
    {
        $this->form = false;
        $this->formVersatility = false;
    }
    public function updatedNrp($value)
    {
        $caridatakaryawan = Karyawan::where('nrp', $value)
            ->where('status', 'aktif')
            ->first();
        if ($caridatakaryawan) {
            $this->info_nama = $caridatakaryawan->nama;
            $this->info_dept = $caridatakaryawan->dept;
            $this->info_jabatan = $caridatakaryawan->jabatan;
            $this->nrp = $caridatakaryawan->nrp;
            $this->info_mcu = $caridatakaryawan->exp_mcu;
            $this->info_id = $caridatakaryawan->exp_id;
            $this->info_kimper = $caridatakaryawan->exp_kimper;
            $this->jenis_pengajuan_kimper = ($caridatakaryawan->exp_kimper === null || $caridatakaryawan->exp_kimper > now())
                ? 'baru'
                : 'perpanjangan';
        } else {
            $this->info_nama = null;
            $this->info_dept = null;
            $this->info_jabatan = null;
            $this->info_mcu = null;
            $this->info_id = null;
            $this->info_kimper = null;
            $this->jenis_pengajuan_kimper = null; // Reset field if NIK is not found
        }
    }
    public function resetForm()
    {
        $this->id_pengajuan = null;
        $this->nrp = null;
        $this->jenis_pengajuan_kimper = null;
        $this->jenis_sim = null;
        $this->versatility = null;
        $this->status_pengajuan = null;
        $this->tgl_pengajuan = null;
        $this->exp_kimper = null;
    }
    public function store()
    {
        // Validasi dasar
        $this->validate(
            [
                'nrp' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $karyawan = Karyawan::where('nrp', $value)->first();
                        if (!$karyawan) {
                            $fail('NRP tidak ditemukan dalam data karyawan.');
                        } elseif (!$karyawan->exp_mcu) {
                            $fail('Tanggal MCU belum diisi / Belum Memiliki MCU.');
                        } elseif ($karyawan->status === 'non aktif') {
                            $fail('Status Karyawan Non Aktif');
                        } elseif ($karyawan->exp_mcu < now()) {
                            $fail('MCU sudah kadaluarsa.');
                        } elseif ($karyawan->exp_id < now() || $karyawan->exp_id === null) {
                            $fail('ID sudah kadaluarsa.');
                        }
                    },
                ],
                'jenis_pengajuan_kimper' => 'required',
                'no_sim' => 'required',
                'jenis_sim' => 'required',
                'exp_sim' => 'required',
                'upload_sim' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240',
                'upload_request' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240',
                'tgl_pengajuan' => 'required',
            ]
        );

        if ($this->jenis_pengajuan_kimper == 'baru') {
            $rules['upload_id'] = 'required|mimes:jpeg,png,jpg,gif|max:10240';
            $rules['upload_lpo'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_sertifikat'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        if ($this->jenis_pengajuan_kimper == 'perpanjangan') {
            $rules['upload_lpo'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_kimper_lama'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_sertifikat'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        if ($this->jenis_pengajuan_kimper == "penambahan") {
            $rules['upload_lpo'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_sertifikat'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        $messages = [
            'required' => 'Kolom :attribute harus diisi.',
            'mimes' => 'Format file :attribute harus JPEG, PNG, JPG, GIF, atau PDF.',
            'upload_foto.mimes' => 'Format file Foto harus berupa gambar (JPEG, PNG, JPG, atau GIF).',
            'max' => 'Ukuran file :attribute maksimal 10MB.',
        ];
        $this->validate($rules, $messages);

        $caridatalama = ModelPengajuanKimper::where('nrp', $this->nrp)->orderBy('created_at', 'desc')->first();

        $pengajuan = ModelPengajuanKimper::create([
            'nrp' => $this->nrp,
            'jenis_pengajuan_kimper' => $this->jenis_pengajuan_kimper,
            'jenis_sim' => $this->jenis_sim,
            'no_sim' => $this->no_sim,
            'exp_sim' => $this->exp_sim,
            'tgl_pengajuan' => $this->tgl_pengajuan,
            'status_pengajuan' => '0',
        ]);


        $datakaryawan = Karyawan::where('nrp', $this->nrp)->first();
        $folderDept = strtoupper($datakaryawan->dept);
        $folderKaryawan = strtoupper($datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan);
        // Define the folder path where the file will be stored
        $folderPath = $folderDept . '/' . $folderKaryawan . '/PENGAJUAN-KIMPER';
        // Check if folder exists, if not, create it
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $requestPath = $this->upload_request->storeAs($folderPath, $folderKaryawan . "-REQUEST-" . time() . ".{$this->upload_request->getClientOriginalExtension()}", 'public');
        $lpoPath = $this->upload_lpo->storeAs($folderPath, $folderKaryawan . "-LPO-" . time() . ".{$this->upload_lpo->getClientOriginalExtension()}", 'public');
        $sertifikatPath = $this->upload_sertifikat->storeAs($folderPath, $folderKaryawan . "-SERTIFIKAT-" . time() . ".{$this->upload_sertifikat->getClientOriginalExtension()}", 'public');
        $simPath = $this->upload_sim->storeAs($folderPath, $folderKaryawan . "-SIM-" . time() . ".{$this->upload_sim->getClientOriginalExtension()}", 'public');

        // Optional jika perpanjangan
        $kimperLamaPath = null;
        if ($this->jenis_pengajuan_kimper === 'perpanjangan' && $this->upload_kimper_lama) {
            $kimperLamaPath = $this->upload_kimper_lama->storeAs($folderPath, $folderKaryawan . "-KIMPER LAMA-" . time() . ".{$this->upload_kimper_lama->getClientOriginalExtension()}", 'public');
        }
        if ($this->jenis_pengajuan_kimper === 'baru') {
            $idPath = $this->upload_id->storeAs($folderPath, $folderKaryawan . "-ID-AKTIF-" . time() . ".{$this->upload_id->getClientOriginalExtension()}", 'public');
        }

        if ($this->jenis_pengajuan_kimper === 'baru') {
            $caridataid = ModelPengajuanID::where('nrp', $this->nrp)->orderBy('created_at', 'desc')->first();
            $pengajuan->update([
                'upload_request' => $requestPath,
                'upload_foto' => $caridataid->upload_foto ?? null,
                'upload_ktp' => $caridataid->upload_ktp ?? null,
                'upload_skd' => $caridataid->upload_skd ?? null,
                'upload_bpjs_kes' => $caridataid->upload_bpjs_kes ?? null,
                'upload_bpjs_ker' => $caridataid->upload_bpjs_ker ?? null,
                'upload_kimper_lama' => $kimperLamaPath,
                'upload_lpo' => $lpoPath,
                'upload_sertifikat' => $sertifikatPath,
                'upload_sim' => $simPath,
                'upload_id' => $idPath
            ]);
        }
        if ($this->jenis_pengajuan_kimper === 'perpanjangan' || $this->jenis_pengajuan_kimper === 'penambahan') {
            $caridataid = ModelPengajuanID::where('nrp', $this->nrp)->orderBy('created_at', 'desc')->first();

            //$requestPath = $caridatalama->upload_request ?? null;

            $fotoPath = $caridataid->upload_foto ?? null;
            $ktpPath = $caridataid->upload_ktp ?? null;
            $skdPath = $caridataid->upload_skd ?? null;
            $bpjsKes = $caridataid->upload_bpjs_kes ?? null;
            $bpjsKer = $caridataid->upload_bpjs_ker ?? null;

            //$kimperLamaPath = $caridatalama->upload_kimper_lama ?? null;
            //$lpoPath = $caridatalama->upload_lpo ?? null;
            //$sertifikatPath = $caridatalama->upload_sertifikat ?? null;
            //$simPath = $caridatalama->upload_sim ?? null;
            $idPath = $caridatalama->upload_id ?? null;

            $pengajuan->update([
                'upload_request' => $requestPath,
                'upload_foto' => $fotoPath,
                'upload_ktp' => $ktpPath,
                'upload_skd' => $skdPath,
                'upload_bpjs_kes' => $bpjsKes,
                'upload_bpjs_ker' => $bpjsKer,
                'upload_kimper_lama' => $kimperLamaPath,
                'upload_lpo' => $lpoPath,
                'upload_sertifikat' => $sertifikatPath,
                'upload_sim' => $simPath,
                'upload_id' => $idPath
            ]);
        }
        // 3. Update data pengajuan dengan path file

        $this->reset();


        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Tambah Pengajuan Kimper ' . $this->nrp,
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );
    }
    public function verifikasiUpload($id)
    {
        $pengajuan = ModelPengajuanKimper::findOrFail($id);

        $pengajuan->update([
            'status_upload_request'     => $this->status_upload_request[$id] ?? $pengajuan->status_upload_request,
            'status_upload_id'          => $this->status_upload_id[$id] ?? $pengajuan->status_upload_id,
            'status_upload_sim'          => $this->status_upload_sim[$id] ?? $pengajuan->status_upload_sim,
            'status_upload_kimper_lama'     => $this->status_upload_kimper_lama[$id] ?? $pengajuan->status_upload_kimper_lama,
            'status_upload_foto'        => $this->status_upload_foto[$id] ?? $pengajuan->status_upload_foto,
            'status_upload_ktp'         => $this->status_upload_ktp[$id] ?? $pengajuan->status_upload_ktp,
            'status_upload_skd'         => $this->status_upload_skd[$id] ?? $pengajuan->status_upload_skd,
            'status_upload_bpjs_kes'    => $this->status_upload_bpjs_kes[$id] ?? $pengajuan->status_upload_bpjs_kes,
            'status_upload_bpjs_ker'    => $this->status_upload_bpjs_ker[$id] ?? $pengajuan->status_upload_bpjs_ker,
            'status_upload_lpo'    => $this->status_upload_lpo[$id] ?? $pengajuan->status_upload_lpo,
            'status_upload_sertifikat'    => $this->status_upload_sertifikat[$id] ?? $pengajuan->status_upload_sertifikat,

            'catatan_upload_request'    => $this->catatan_upload_request[$id] ?? null,
            'catatan_upload_id'         => $this->catatan_upload_id[$id] ?? $pengajuan->catatan_upload_id,
            'catatan_upload_sim'         => $this->catatan_upload_sim[$id] ?? $pengajuan->catatan_upload_sim,
            'catatan_upload_kimper_lama'    => $this->catatan_upload_kimper_lama[$id] ?? null,
            'catatan_upload_foto'       => $this->catatan_upload_foto[$id] ?? null,
            'catatan_upload_ktp'        => $this->catatan_upload_ktp[$id] ?? null,
            'catatan_upload_skd'        => $this->catatan_upload_skd[$id] ?? null,
            'catatan_upload_bpjs_kes'   => $this->catatan_upload_bpjs_kes[$id] ?? null,
            'catatan_upload_bpjs_ker'   => $this->catatan_upload_bpjs_ker[$id] ?? null,
            'catatan_upload_lpo'   => $this->catatan_upload_lpo[$id] ?? null,
            'catatan_upload_sertifikat'   => $this->catatan_upload_sertifikat[$id] ?? null,
        ]);

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Kirim Verifikasi Dokumen',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );
        return;
    }
    public function updateUpload($id)
    {
        $pengajuan = ModelPengajuanKimper::findOrFail($id);

        $data = [];

        $nrp = $pengajuan->nrp;
        $idPengajuan = $pengajuan->id;

        // Check if folder exists, if not, create it
        $datakaryawan = Karyawan::where('nrp', $nrp)->first();
        $folderDept = strtoupper(Str::slug($datakaryawan->dept, '_'));
        $folderKaryawan = strtoupper(Str::slug(
            $datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan,
            '_'
        ));
        $folderPath = $folderDept . '/' . $folderKaryawan . '/PENGAJUAN-ID';

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if ($pengajuan->status_upload_request == '0') {
            $this->validate([
                'upload_request' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $requestPath = $this->upload_request->storeAs($folderPath, $folderKaryawan . "-REQUEST-REVISI-" . time() . ".{$this->upload_request->getClientOriginalExtension()}", 'public');
            $data['upload_request'] = $requestPath;
            $data['status_upload_request'] = NULL;
        }
        if ($pengajuan->status_upload_sim == '0') {
            $this->validate([
                'upload_sim' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $simPath = $this->upload_sim->storeAs($folderPath, $folderKaryawan . "-SIM-REVISI-" . time() . ".{$this->upload_sim->getClientOriginalExtension()}", 'public');
            $data['upload_sim'] = $simPath;
            $data['status_upload_sim'] = NULL;
        }
        if ($pengajuan->status_upload_id == '0') {
            $this->validate([
                'upload_id' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $idPath = $this->upload_id->storeAs($folderPath, $folderKaryawan . "-ID AKTIF-REVISI-" . time() . ".{$this->upload_id->getClientOriginalExtension()}", 'public');
            $data['upload_id'] = $idPath;
            $data['status_upload_id'] = NULL;
        }
        if ($pengajuan->status_upload_kimper_lama == '0') {
            $this->validate([
                'upload_kimper_lama' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $kimperLamaPath = $this->upload_kimper_lama->storeAs($folderPath, $folderKaryawan . "-KIMPER LAMA-REVISI-" . time() . ".{$this->upload_kimper_lama->getClientOriginalExtension()}", 'public');
            $data['upload_kimper_lama'] = $kimperLamaPath;
            $data['status_upload_kimper_lama'] = NULL;
        }
        if ($pengajuan->status_upload_foto == '0') {
            $this->validate([
                'upload_foto' => 'required|file|mimes:jpg,png|max:10240'
            ]);
            $fotoPath = $this->upload_foto->storeAs($folderPath, $folderKaryawan . "-FOTO-REVISI-" . time() . ".{$this->upload_foto->getClientOriginalExtension()}", 'public');
            $data['upload_foto'] = $fotoPath;
            $data['status_upload_foto'] = NULL;
        }
        if ($pengajuan->status_upload_ktp == '0') {
            $this->validate([
                'upload_ktp' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $ktpPath = $this->upload_ktp->storeAs($folderPath, $folderKaryawan . "-KTP-REVISI-" . time() . ".{$this->upload_ktp->getClientOriginalExtension()}", 'public');
            $data['upload_ktp'] = $ktpPath;
            $data['status_upload_ktp'] = NULL;
        }
        if ($pengajuan->status_upload_skd == '0') {
            $this->validate([
                'upload_skd' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $skdPath = $this->upload_skd->storeAs($folderPath, $folderKaryawan . "-SKD-REVISI-" . time() . ".{$this->upload_skd->getClientOriginalExtension()}", 'public');
            $data['upload_skd'] = $skdPath;
            $data['status_upload_skd'] = NULL;
        }
        if ($pengajuan->status_upload_bpjs_kes == '0') {
            $this->validate([
                'upload_bpjs_kes' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $bpjsKes = $this->upload_bpjs_kes->storeAs($folderPath, $folderKaryawan . "-BPJS KESEHATAN-REVISI-" . time() . ".{$this->upload_bpjs_kes->getClientOriginalExtension()}", 'public');
            $data['upload_bpjs_kes'] = $bpjsKes;
            $data['status_upload_bpjs_kes'] = NULL;
        }
        if ($pengajuan->status_upload_bpjs_ker == '0') {
            $this->validate([
                'upload_bpjs_ker' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $bpjsKer = $this->upload_bpjs_ker->storeAs($folderPath, $folderKaryawan . "-BPJS KETENAGAKERJAAN-REVISI-" . time() . ".{$this->upload_bpjs_ker->getClientOriginalExtension()}", 'public');
            $data['upload_bpjs_ker'] = $bpjsKer;
            $data['status_upload_bpjs_ker'] = NULL;
        }
        if ($pengajuan->status_upload_sertifikat == '0') {
            $this->validate([
                'upload_sertifikat' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $sertifikatPath = $this->upload_sertifikat->storeAs($folderPath, $folderKaryawan . "-SERTIFIKAT-REVISI-" . time() . ".{$this->upload_sertifikat->getClientOriginalExtension()}", 'public');
            $data['upload_sertifikat'] = $sertifikatPath;
            $data['status_upload_sertifikat'] = NULL;
        }
        if ($pengajuan->status_upload_lpo == '0') {
            $this->validate([
                'upload_lpo' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $lpoPath = $this->upload_lpo->storeAs($folderPath, $folderKaryawan . "-LPO-REVISI-" . time() . ".{$this->upload_lpo->getClientOriginalExtension()}", 'public');
            $data['upload_lpo'] = $lpoPath;
            $data['status_upload_lpo'] = NULL;
        }

        $pengajuan->update($data);

        $this->reset();
        // Determine whether it's an edit or a new entry
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Upload Ulang',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );
        return;
    }
    public function prosesCetak($id)
    {
        $this->validate([
            "expired_kimper.$id" => 'required|date|after_or_equal:today',
        ], [
            "expired_kimper.$id.required" => 'Tanggal expired wajib diisi.',
            "expired_kimper.$id.after_or_equal" => 'Tanggal tidak boleh di masa lalu.',
        ]);

        $pengajuan = ModelPengajuanKimper::findOrFail($id);
        $pengajuan->update([
            'status_pengajuan' => '1',
            'exp_kimper' => $this->expired_kimper[$id],
        ]);
        Karyawan::where('nrp', $pengajuan->nrp)->update([
            'exp_kimper' => $this->expired_kimper[$id],
        ]);

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Verifikasi dokumen berhasil dikirim',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );
    }
    public function updateCetak($id)
    {
        $caripengajuan = ModelPengajuanKimper::find($id);
        $caripengajuan->update([
            'status_pengajuan' => '2',
        ]);

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Proses Cetak Kartu KIMPER Berhasil',
            position: 'center',
            confirm: true,
            tab: true,
            redirect: '/cetak-kartu-kimper/' . $caripengajuan->nrp,
        );
    }
    public function mount()
    {
        $caripengajuankimper = ModelPengajuanKimper::where('status_pengajuan', '!=', '1')->get();
        foreach ($caripengajuankimper as $item) {
            $this->expired_kimper[$item->id] = Carbon::now()->addYear()->format('Y-m-d');
        }
    }
    #[Title('Kimper')]
    public function render()
    {
        $this->tgl_pengajuan = Carbon::now()->format('Y-m-d');
        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        if (in_array(auth()->user()->role, ['superadmin', 'she'])) {
            $kimpers = DB::table('pengajuan_kimper')
                ->select('pengajuan_kimper.*', 'karyawans.*', 'pengajuan_kimper.id as id_pengajuan', 'pengajuan_kimper.versatility as status_versatility')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_kimper.nrp')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->where('pengajuan_kimper.status_pengajuan', '!=', '2')
                ->orderBy('pengajuan_kimper.created_at', 'desc')
                ->paginate(10);
        } else if (auth()->user()->role === 'karyawan') {
            $kimpers = DB::table('pengajuan_kimper')
                ->select('pengajuan_kimper.*', 'karyawans.*', 'pengajuan_kimper.id as id_pengajuan', 'pengajuan_kimper.versatility as status_versatility')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_kimper.nrp')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->where('pengajuan_kimper.nrp', auth()->user()->username)
                ->where('pengajuan_kimper.status_pengajuan', '!=', '2')
                ->orderBy('pengajuan_kimper.created_at', 'desc')
                ->paginate(10);
        } else {
            $kimpers = DB::table('pengajuan_kimper')
                ->select('pengajuan_kimper.*', 'karyawans.*', 'pengajuan_kimper.id as id_pengajuan', 'pengajuan_kimper.versatility as status_versatility')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_kimper.nrp')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('pengajuan_kimper.status_pengajuan', '!=', '2')
                ->orderBy('pengajuan_kimper.created_at', 'desc')
                ->paginate(10);
        }
        foreach ($kimpers as $item) {
            $id = $item->id_pengajuan;
            $this->catatan_upload_id[$id] = $item->catatan_upload_id;
            $this->catatan_upload_sim[$id] = $item->catatan_upload_sim;
            $this->catatan_upload_request[$id] = $item->catatan_upload_request;
            $this->catatan_upload_foto[$id] = $item->catatan_upload_foto;
            $this->catatan_upload_ktp[$id] = $item->catatan_upload_ktp;
            $this->catatan_upload_skd[$id] = $item->catatan_upload_skd;
            $this->catatan_upload_bpjs_kes[$id] = $item->catatan_upload_bpjs_kes;
            $this->catatan_upload_bpjs_ker[$id] = $item->catatan_upload_bpjs_ker;
            $this->catatan_upload_lpo[$id] = $item->catatan_upload_lpo;
            $this->catatan_upload_sertifikat[$id] = $item->catatan_upload_sertifikat;

            $this->status_upload_id[$id] = $item->status_upload_id ?? '1';
            $this->status_upload_sim[$id] = $item->status_upload_sim  ?? '1';
            $this->status_upload_request[$id] = $item->status_upload_request ?? '1';
            $this->status_upload_foto[$id] = $item->status_upload_foto ?? '1';
            $this->status_upload_ktp[$id] = $item->status_upload_ktp ?? '1';
            $this->status_upload_skd[$id] = $item->status_upload_skd ?? '1';
            $this->status_upload_bpjs_kes[$id] = $item->status_upload_bpjs_kes ?? '1';
            $this->status_upload_bpjs_ker[$id] = $item->status_upload_bpjs_ker ?? '1';
            $this->status_upload_lpo[$id] = $item->status_upload_lpo ?? '1';
            $this->status_upload_sertifikat[$id] = $item->status_upload_sertifikat ?? '1';

            // Khusus ID Lama hanya jika jenis pengajuan perpanjangan
            if ($item->jenis_pengajuan_kimper === 'perpanjangan' || $item->jenis_pengajuan_kimper === 'penambahan') {
                $this->catatan_upload_kimper_lama[$id] = $item->catatan_upload_kimper_lama;
                $this->status_upload_kimper_lama[$id] = $item->status_upload_kimper_lama ?? '1';
            }
        }

        return view('livewire.pengajuan.kimper', [
            'kimpers' => $kimpers,
            'carifoto' => $carifoto
        ]);
    }
}
