<?php

namespace App\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\ModelPengajuanKimper;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithoutUrlPagination;

class Kimper extends Component
{
    use WithFileUploads, WithPagination, WithoutUrlPagination;
    public $id_pengajuan, $nrp, $jenis_pengajuan_kimper, $jenis_sim, $versatility, $status_pengajuan, $tgl_pengajuan, $exp_kimper;
    public $form = false;
    public $catatan_upload_id = [], $catatan_upload_kimper_lama = [], $catatan_upload_request = [], $catatan_upload_sim = [], $catatan_upload_ertifikat = [], $catatan_upload_lpo = [], $catatan_upload_foto = [], $catatan_upload_ktp = [], $catatan_upload_skd = [], $catatan_upload_bpjs_kes = [], $catatan_upload_bpjs_ker = [];
    public $status_upload_id = [], $status_upload_kimper = [], $status_upload_request = [], $status_upload_sim = [], $status_upload_sertifikat = [], $status_upload_lpo = [], $status_upload_foto = [], $status_upload_ktp = [], $status_upload_skd = [], $status_upload_bpjs_kes = [], $status_upload_bpjs_ker = [];
    public $upload_id, $upload_kimper_lama, $upload_request, $upload_sim, $upload_sertifikat, $upload_lpo, $upload_foto, $upload_ktp, $upload_skd, $upload_bpjs_kes, $upload_bpjs_ker;
    public $info_nama, $info_dept, $info_jabatan, $info_mcu, $info_id, $info_kimper;


    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
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
                'jenis_sim' => 'nullable|string',
            ]
        );

        if ($this->jenis_pengajuan_kimper == 'baru') {
            $rules['upload_foto'] = 'required|mimes:jpeg,png,jpg,gif|max:10240';
            $rules['upload_ktp'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_skd'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_bpjs_kes'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_bpjs_ker'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        if ($this->jenis_pengajuan_kimper == 'perpanjangan') {
            $rules['upload_kimper_lama'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        if ($this->jenis_pengajuan_kimper == "tambah") {
            $rules['jenis_sim'] = 'required';
            $rules['no_sim'] = 'required';
        }

        $messages = [
            'required' => 'Kolom :attribute harus diisi.',
            'mimes' => 'Format file :attribute harus JPEG, PNG, JPG, GIF, atau PDF.',
            'upload_foto.mimes' => 'Format file Foto harus berupa gambar (JPEG, PNG, JPG, atau GIF).',
            'max' => 'Ukuran file :attribute maksimal 10MB.',
        ];
        $this->validate($rules, $messages);

        $pengajuan = ModelPengajuanKimper::create([
            'nrp' => $this->nrp,
            'jenis_pengajuan_kimper' => $this->jenis_pengajuan_kimper,
            'jenis_sim' => $this->jenis_sim,
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


        // Optional jika perpanjangan
        $kimperLamaPath = null;
        if ($this->jenis_pengajuan_kimper === 'perpanjangan' && $this->upload_kimper_lama) {
            $kimperLamaPath = $this->upload_kimper_lama->storeAs($folderPath, $folderKaryawan . "-KIMPER LAMA-" . time() . ".{$this->upload_kimper_lama->getClientOriginalExtension()}", 'public');
        }
        if ($this->jenis_pengajuan_kimper === 'baru') {
            $lpoPath = $this->upload_lpo->storeAs($folderPath, $folderKaryawan . "-LOKASI-" . time() . ".{$this->upload_lpo->getClientOriginalExtension()}", 'public');
            $sertifikatPath = $this->upload_sertifikat->storeAs($folderPath, $folderKaryawan . "-SERTIFIKAT-" . time() . ".{$this->upload_sertifikat->getClientOriginalExtension()}", 'public');
            $simPath = $this->upload_sim->storeAs($folderPath, $folderKaryawan . "-SIM-" . time() . ".{$this->upload_sim->getClientOriginalExtension()}", 'public');
            $idPath = $this->upload_id->storeAs($folderPath, $folderKaryawan . "-ID-" . time() . ".{$this->upload_id->getClientOriginalExtension()}", 'public');
            $fotoPath = $this->upload_foto->storeAs($folderPath, $folderKaryawan . "-FOTO-" . time() . ".{$this->upload_foto->getClientOriginalExtension()}", 'public');
            $ktpPath = $this->upload_ktp->storeAs($folderPath, $folderKaryawan . "-KTP-" . time() . ".{$this->upload_ktp->getClientOriginalExtension()}", 'public');
            $skdPath = $this->upload_skd->storeAs($folderPath, $folderKaryawan . "-SKD-" . time() . ".{$this->upload_skd->getClientOriginalExtension()}", 'public');
            $bpjsKes = $this->upload_bpjs_kes->storeAs($folderPath, $folderKaryawan . "-BPJS KESEHATAN-" . time() . ".{$this->upload_bpjs_kes->getClientOriginalExtension()}", 'public');
            $bpjsKer = $this->upload_bpjs_ker->storeAs($folderPath, $folderKaryawan . "-BPJS KETENAGAKERJAAN-" . time() . ".{$this->upload_bpjs_ker->getClientOriginalExtension()}", 'public');
        }

        if ($this->jenis_pengajuan_kimper === 'baru') {
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
        if ($this->jenis_pengajuan_kimper === 'perpanjangan') {
            $caridatalama = ModelPengajuanKimper::where('nrp', $this->nrp)->orderBy('created_at', 'desc')->first();
            $requestPath = $caridatalama->upload_request ?? null;
            $fotoPath = $caridatalama->upload_foto ?? null;
            $ktpPath = $caridatalama->upload_ktp ?? null;
            $skdPath = $caridatalama->upload_skd ?? null;
            $bpjsKes = $caridatalama->upload_bpjs_kes ?? null;
            $bpjsKer = $caridatalama->upload_bpjs_ker ?? null;
            $kimperLamaPath = $caridatalama->upload_kimper_lama ?? null;
            $lpoPath = $caridatalama->upload_lpo ?? null;
            $sertifikatPath = $caridatalama->upload_sertifikat ?? null;
            $simPath = $caridatalama->upload_sim ?? null;
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

    #[Title('Kimper')]
    public function render()
    {
        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        $kimpers = ModelPengajuanKimper::where('status_pengajuan', '!=', '3')->paginate(10);
        return view('livewire.pengajuan.kimper', [
            'kimpers' => $kimpers,
            'carifoto' => $carifoto
        ]);
    }
}
