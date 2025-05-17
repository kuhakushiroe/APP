<?php

namespace App\Livewire\Pengajuan;

use App\Models\Karyawan;
use App\Models\ModelPengajuanKimper;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class Kimper extends Component
{
    public $id_pengajuan, $nrp, $jenis_pengajuan_kimper, $jenis_sim, $versatility, $status_pengajuan, $tgl_pengajuan, $exp_kimper;
    public $form = false;
    public $info_nama, $info_dept, $info_jabatan, $info_mcu, $info_id, $info_kimper;
    // Upload files
    public $upload = [
        'id' => null,
        'kimper_lama' => null,
        'request' => null,
        'sim' => null,
        'sertifikat' => null,
        'lpo' => null,
        'foto' => null,
        'ktp' => null,
        'skd' => null,
        'bpjs_kes' => null,
        'bpjs_ker' => null
    ];

    // Status untuk setiap upload
    public $status_upload = [
        'id' => null,
        'kimper_lama' => null,
        'request' => null,
        'sim' => null,
        'sertifikat' => null,
        'lpo' => null,
        'foto' => null,
        'ktp' => null,
        'skd' => null,
        'bpjs_kes' => null,
        'bpjs_ker' => null

    ];

    // Catatan untuk setiap upload
    public $catatan_upload = [
        'id' => null,
        'kimper_lama' => null,
        'request' => null,
        'sim' => null,
        'sertifikat' => null,
        'lpo' => null,
        'foto' => null,
        'ktp' => null,
        'skd' => null,
        'bpjs' => null,
    ];
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

        $this->upload = array_fill_keys([
            'id',
            'kimper_lama',
            'request',
            'sim',
            'sertifikat',
            'lpo',
            'foto',
            'ktp',
            'skd',
            'bpjs_kes',
            'bpjs_ker'
        ], null);
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

        $datakaryawan = Karyawan::where('nrp', $this->nrp)->first();
        $folderDept = strtoupper($datakaryawan->dept);
        $folderKaryawan = strtoupper($datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan);
        // Define the folder path where the file will be stored
        $folderPath = $folderDept . '/' . $folderKaryawan . '/pengajuan-kimper';
        // Check if folder exists, if not, create it
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath); // Create the directory if it doesn't exist
        }

        // Simpan file upload dan ganti nilainya dengan path
        foreach ($this->upload as $key => $file) {
            if ($file) {
                $extension = $file->getClientOriginalExtension();
                $filename = $folderKaryawan . "-{$key}-" . time() . "." . $extension;
                $this->upload[$key] = $file->storeAs($folderPath, $filename, 'public');
            } else {
                $this->upload[$key] = null;
            }
        }

        // Simpan data ke tabel pengajuan
        ModelPengajuanKimper::create([
            'nrp' => $this->nrp,
            'jenis_pengajuan_kimper' => $this->jenis_pengajuan_kimper,
            'jenis_sim' => $this->jenis_sim,
            'versatility' => $this->versatility,
            'status_pengajuan' => 0,
            'tgl_pengajuan' => now(),
            'exp_kimper' => $this->exp_kimper,

            // Uploads
            'upload_id' => $this->upload['id'],
            'upload_kimper_lama' => $this->upload['kimper_lama'],
            'upload_request' => $this->upload['request'],
            'upload_sim' => $this->upload['sim'],
            'upload_sertifikat' => $this->upload['sertifikat'],
            'upload_lpo' => $this->upload['lpo'],
            'upload_foto' => $this->upload['foto'],
            'upload_ktp' => $this->upload['ktp'],
            'upload_skd' => $this->upload['skd'],
            'upload_bpjs_kes' => $this->upload['bpjs_kes'],
            'upload_bpjs_ker' => $this->upload['bpjs_ker'],
        ]);

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Tambah Pengajuan Kimper ' . $this->nrp,
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-kimper',
        );

        $this->resetForm();
    }

    #[Title('Kimper')]
    public function render()
    {
        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        $kimpers = ModelPengajuanKimper::where('status_pengajuan', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.pengajuan.kimper', [
            'kimpers' => $kimpers,
            'carifoto' => $carifoto
        ]);
    }
}
