<?php

namespace App\Livewire\Mcu;

use App\Exports\mcuExport;
use App\Jobs\SendNotifMcu;
use App\Models\cutiDokter;
use App\Models\FollowupMcu;
use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\AssignOp\Mod;

class Mcu extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    public $search = '';
    public $searchtgl_awal;
    public $searchtgl_akhir;
    protected $updatesQueryString = ['search'];
    public $form = false;
    public $form_multi = false;
    public $forms = [];
    public $formVerifikasi = false;
    public $formUpload = false;
    public $id_mcu, $sub_id, $proveder, $nrp, $nama, $tgl_mcu, $gol_darah, $jenis_kelamin, $file_mcu, $jenis_pengajuan_mcu;
    public $no_dokumen, $status = NULL, $keterangan_mcu, $saran_mcu, $tgl_verifikasi, $exp_mcu;
    public $riwayat_rokok, $BB, $TB, $LP, $BMI, $Laseq, $reqtal_touche, $sistol, $diastol, $OD_jauh, $OS_jauh, $OD_dekat, $OS_dekat, $butawarna, $gdp, $hba1c, $gd_2_jpp, $ureum, $creatine, $asamurat, $sgot, $sgpt, $hbsag, $anti_hbs, $kolesterol, $hdl, $ldl, $tg, $darah_rutin, $napza, $urin, $ekg, $rontgen, $audiometri, $spirometri, $tredmil_test, $echocardiography, $widal_test, $routin_feces, $kultur_feces;
    public $kesadaran, $epilepsi;
    public $jabatan;
    public $followups = [
        ['keterangan' => '', 'saran' => '']
    ];
    public $upload_hasil_mcu;
    public $caridatakaryawan = [];
    public $carikaryawan = [];
    public $status_file_mcu = [];
    public $catatan_file_mcu = [];
    public $paramedik, $paramedik_status, $paramedik_catatan;
    protected $listeners = ['delete'];
    public $isOn = false;

    #[Title('MCU')]
    public function toggleCuti()
    {
        $cuti = cutiDokter::first();

        if ($cuti) {
            // Kalau sudah ada → toggle
            $newStatus = $cuti->status === 'on' ? 'off' : 'on';
            $cuti->update(['status' => $newStatus]);

            $this->isOn = $newStatus === 'on';
        } else {
            // Kalau belum ada → buat baru dengan default on
            $cuti = cutiDokter::create(['status' => 'on']);
            $this->isOn = true;
        }
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Tombol Cuti Dokter ' . ($this->isOn ? 'ON' : 'OFF'),
            position: 'center',
            confirm: true,
            redirect: '/mcu',
        );
    }
    public function addFollowup()
    {
        $this->followups[] = ['keterangan' => '', 'saran' => ''];
    }

    public function removeFollowup($index)
    {
        unset($this->followups[$index]);
        $this->followups = array_values($this->followups); // reset index array
    }
    public function updatedNrp($value)
    {
        $caridatakaryawan = Karyawan::where('nrp', $value)
            ->where('status', 'aktif')
            ->first();
        if ($caridatakaryawan) {
            $this->nama = $caridatakaryawan->nama;
            $this->gol_darah = $caridatakaryawan->gol_darah;
            $this->jenis_kelamin = $caridatakaryawan->jenis_kelamin;
            $this->jabatan = $caridatakaryawan->jabatan;
        } else {
            $this->nama = null;
            $this->gol_darah = null;
            $this->jenis_kelamin = null;
            $this->jabatan = null; // Reset field if NIK is not found
        }
    }
    public function updatedForms($value, $key)
    {
        // Contoh $key = "0.nrp", kita pisahkan index dan field
        [$index, $field] = explode('.', $key);

        if ($field === 'nrp') {
            $caridatakaryawan = Karyawan::where('nrp', $value)
                ->where('status', 'aktif')
                ->first();

            if ($caridatakaryawan) {
                $this->forms[$index]['nama'] = $caridatakaryawan->nama;
                $this->forms[$index]['gol_darah'] = $caridatakaryawan->gol_darah;
                $this->forms[$index]['jenis_kelamin'] = $caridatakaryawan->jenis_kelamin;
                // 🔥 cek jabatan untuk jenis pengajuan khusus
                $khususList = ['Paramedic', 'Wellder', 'Pramusaji', 'ERT', 'FODP', 'FMDP', 'PLDP'];
                if (in_array($caridatakaryawan->jabatan, $khususList)) {
                    $this->forms[$index]['jenis_pengajuan_mcu'] = 'Khusus';
                }
            } else {
                $this->forms[$index]['nama'] = null;
                $this->forms[$index]['gol_darah'] = null;
                $this->forms[$index]['jenis_kelamin'] = null;
                $this->forms[$index]['jenis_pengajuan_mcu'] = null;
            }
        }
    }

    public function open()
    {
        $this->form = true;
    }
    //MULTI MCU
    public function open_multi()
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->forms = [$this->emptyForm()];
        $this->form_multi = true;
    }

    public function addFormMcu()
    {
        $this->forms[] = $this->emptyForm();
    }

    public function removeFormMcu($index)
    {
        unset($this->forms[$index]);
        $this->forms = array_values($this->forms); // reset indeks array
    }

    private function emptyForm()
    {
        return [
            'nrp' => '',
            'nama' => '',
            'jenis_pengajuan_mcu' => '',
            'file_mcu' => null,
            'jenis_kelamin' => '',
            'gol_darah' => '',
            'proveder' => '',
            'tgl_mcu' => '',
        ];
    }
    public function storeMulti()
    {
        // Validasi seluruh array forms
        // $validatedData = Validator::make(
        //     ['forms' => $this->forms],
        //     [
        //         'forms.*.jenis_pengajuan_mcu' => 'required',
        //         'forms.*.proveder' => 'required',
        //         'forms.*.nrp' => [
        //             'required',
        //             Rule::exists('karyawans', 'nrp')->where(function ($query) {
        //                 $query->where('status', 'aktif');
        //             }),
        //         ],
        //         'forms.*.tgl_mcu' => 'required|date',
        //         'forms.*.file_mcu' => 'required|file|mimes:pdf|max:10240',
        //     ],
        //     [
        //         'forms.*.proveder.required' => 'Proveder harus diisi.',
        //         'forms.*.nrp.required' => 'NRP harus diisi.',
        //         'forms.*.nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
        //         'forms.*.tgl_mcu.required' => 'Tanggal MCU harus diisi.',
        //         'forms.*.tgl_mcu.date' => 'Tanggal MCU harus format tanggal valid.',
        //         'forms.*.file_mcu.required' => 'File MCU harus diunggah.',
        //         'forms.*.file_mcu.file' => 'File harus berupa file.',
        //         'forms.*.file_mcu.mimes' => 'Format file harus PDF.',
        //         'forms.*.file_mcu.max' => 'Ukuran file maksimal 10MB.',
        //     ]
        // )->validate();

        $validator = Validator::make(
            ['forms' => $this->forms],
            [
                'forms.*.jenis_pengajuan_mcu' => 'required',
                'forms.*.proveder' => 'required',
                'forms.*.nrp' => [
                    'required',
                    Rule::exists('karyawans', 'nrp')->where(fn($q) => $q->where('status', 'aktif')),
                ],
                'forms.*.tgl_mcu' => 'required|date',
                'forms.*.file_mcu' => 'required|file|mimes:pdf|max:10240',
            ],
            [
                'forms.*.proveder.required' => 'Proveder harus diisi.',
                'forms.*.nrp.required' => 'NRP harus diisi.',
                'forms.*.nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
                'forms.*.tgl_mcu.required' => 'Tanggal MCU harus diisi.',
                'forms.*.tgl_mcu.date' => 'Tanggal MCU harus format tanggal valid.',
                'forms.*.file_mcu.required' => 'File MCU harus diunggah.',
                'forms.*.file_mcu.file' => 'File harus berupa file.',
                'forms.*.file_mcu.mimes' => 'Format file harus PDF.',
                'forms.*.file_mcu.max' => 'Ukuran file maksimal 10MB.',
            ]
        );

        $validator->after(function ($validator) {
            // 🔸 Cek NRP duplikat dalam 1 kali input
            $nrps = array_column($this->forms, 'nrp');
            $duplicates = array_diff_assoc($nrps, array_unique($nrps));

            if (!empty($duplicates)) {
                foreach ($duplicates as $i => $dupNrp) {
                    $validator->errors()->add("forms.$i.nrp", "NRP {$dupNrp} tidak boleh diinput lebih dari sekali dalam satu pengajuan.");
                }
            }

            // 🔸 Cek status pengajuan existing
            foreach ($this->forms as $i => $form) {
                $cek = ModelsMcu::where('id_karyawan', $form['nrp'])
                    ->where('status_', '!=', 'close')
                    ->first();

                if ($cek) {
                    if ($cek->status === 'follow up' && empty($form['sub_id'] ?? null)) {
                        $validator->errors()->add("forms.$i.nrp", "NRP {$form['nrp']} masih follow up, hanya boleh input sebagai sub (sub_id wajib).");
                    }

                    if ($cek->status !== 'follow up') {
                        $validator->errors()->add("forms.$i.nrp", "NRP {$form['nrp']} masih ada pengajuan yang belum close.");
                    }
                }
            }
        });

        $validator->validate();

        $info = getUserInfo();
        $token = $info['token'];
        $namaUser = $info['nama'];
        $nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);

        foreach ($this->forms as $index => $form) {
            $datakaryawan = Karyawan::where('nrp', $form['nrp'])->first();

            $folderDept = strtoupper(Str::slug($datakaryawan->dept, '_'));
            $folderKaryawan = strtoupper(Str::slug(
                $datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan,
                '_'
            ));
            $folderPath = $folderDept . '/' . $folderKaryawan . '/MCU';

            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            $uploadedFile = $form['file_mcu'];
            $filePath = $uploadedFile->storeAs(
                $folderPath,
                $folderKaryawan . "-MCU-" . time() . ".{$uploadedFile->getClientOriginalExtension()}",
                'public'
            );

            ModelsMcu::create([
                'id_karyawan' => $form['nrp'],
                'jenis_pengajuan_mcu' => $form['jenis_pengajuan_mcu'],
                'proveder' => $form['proveder'],
                'tgl_mcu' => $form['tgl_mcu'],
                'gol_darah' => $form['gol_darah'] ?: null,
                'file_mcu' => $filePath,
                'status_file_mcu' => null,
                'status' => null,
            ]);

            // Kirim notifikasi tiap form
            $infoKaryawan = getInfoKaryawanByNrp($form['nrp']);
            $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n*$infoKaryawan*";
            dispatch(new SendNotifMcu($pesanText, $nomorGabungan, $token, $namaUser));
            $dept = getInfoKaryawanByNrpDept($form['nrp']);
            $deptMap = [
                'HC' => $info['adminHC'],
                'ENG' => $info['adminENG'],
                'PRO' => $info['adminPRO'],
                'PLT' => $info['adminPLT'],
                'COE' => $info['adminCOE'],
            ];
            if (isset($deptMap[$dept])) {
                dispatch(new SendNotifMcu($pesanText, $deptMap[$dept], $token, $namaUser));
            }
        }

        // Reset seluruh form
        $this->forms = [$this->emptyForm()];

        // Feedback ke user
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Semua pengajuan MCU berhasil disimpan.',
            position: 'center',
            confirm: true,
            redirect: '/mcu',
        );
    }
    //MULTI MCU
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
        $this->form_multi = false;
        $this->form = false;
        $this->reset();
    }

    // Fungsi untuk memuat data dari database
    public function store()
    {
        // Validate the form inputs
        // $this->validate([
        //     'jenis_pengajuan_mcu' => 'required',
        //     'proveder' => 'required',
        //     'nrp' => [
        //         'required',
        //         Rule::exists('karyawans', 'nrp')->where(function ($query) {
        //             $query->where('status', 'aktif');
        //         }),
        //     ],
        //     'tgl_mcu' => 'required|date',
        //     //'gol_darah' => 'required',
        //     'file_mcu' => 'required|file|mimes:pdf|max:10240', // max 10MB
        // ], [
        //     'proveder.required' => 'Proveder harus diisi.',
        //     'nrp.required' => 'NRP harus diisi.',
        //     'nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
        //     'tgl_mcu.required' => 'Tanggal MCU harus diisi.',
        //     'tgl_mcu.date' => 'Tanggal MCU harus dalam format yang valid.',
        //     //'gol_darah.required' => 'Golongan Darah harus diisi.',
        //     'file_mcu.required' => 'File MCU harus diunggah.',
        //     'file_mcu.file' => 'File harus berupa file.',
        //     'file_mcu.mimes' => 'Format file harus PDF.',
        //     'file_mcu.max' => 'Ukuran file maksimal 10MB.',
        // ]);

        $validator = Validator::make(
            [
                'jenis_pengajuan_mcu' => $this->jenis_pengajuan_mcu,
                'proveder' => $this->proveder,
                'nrp' => $this->nrp,
                'tgl_mcu' => $this->tgl_mcu,
                'file_mcu' => $this->file_mcu,
            ],
            [
                'jenis_pengajuan_mcu' => 'required',
                'proveder' => 'required',
                'nrp' => [
                    'required',
                    Rule::exists('karyawans', 'nrp')->where(fn($q) => $q->where('status', 'aktif')),
                ],
                'tgl_mcu' => 'required|date',
                'file_mcu' => 'required|file|mimes:pdf|max:10240',
            ],
            [
                'proveder.required' => 'Proveder harus diisi.',
                'nrp.required' => 'NRP harus diisi.',
                'nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
                'tgl_mcu.required' => 'Tanggal MCU harus diisi.',
                'tgl_mcu.date' => 'Tanggal MCU harus format tanggal valid.',
                'file_mcu.required' => 'File MCU harus diunggah.',
                'file_mcu.file' => 'File harus berupa file.',
                'file_mcu.mimes' => 'Format file harus PDF.',
                'file_mcu.max' => 'Ukuran file maksimal 10MB.',
            ]
        );

        $validator->after(function ($validator) {
            $cek = ModelsMcu::where('id_karyawan', $this->nrp)
                ->where('status_', '!=', 'close')
                ->first();

            if ($cek) {
                if ($cek->status === 'follow up' && empty($this->sub_id)) {
                    $validator->errors()->add('nrp', "NRP {$this->nrp} masih follow up, hanya boleh input sebagai sub (sub_id wajib).");
                }

                if ($cek->status !== 'follow up') {
                    $validator->errors()->add('nrp', "NRP {$this->nrp} masih ada pengajuan yang belum close.");
                }
            }
        });

        $validator->validate();

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
                    'jenis_pengajuan_mcu' => $this->jenis_pengajuan_mcu,
                    'proveder' => $this->proveder,
                    'tgl_mcu' => $this->tgl_mcu,
                    'gol_darah' => $this->gol_darah ?: null,
                    'file_mcu' => $filePath,
                    'status_file_mcu' => NULL,
                    'status' => $this->status,
                    //'tgl_verifikasi' => $this->tgl_verifikasi, // Store the file path, not the file object
                ]
            );
            $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n*Upload File Revisi*";
        } else {
            ModelsMcu::updateOrCreate(
                ['id' => $this->id_mcu], // Assuming id_mcu is the primary key
                [
                    'id_karyawan' => $this->nrp,
                    'jenis_pengajuan_mcu' => $this->jenis_pengajuan_mcu,
                    'proveder' => $this->proveder,
                    'tgl_mcu' => $this->tgl_mcu,
                    'gol_darah' => $this->gol_darah ?: null,
                    'file_mcu' => $filePath,
                    'status' => $this->status,
                    'status_file_mcu' => NULL,
                    //'tgl_verifikasi' => $this->tgl_verifikasi, // Store the file path, not the file object
                ]
            );
            $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*";
        }
        $info = getUserInfo(); // ambil data user saat dispatch, di konteks request HTTP (user pasti ada)
        $nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);
        $token = $info['token'];
        $namaUser = $info['nama'];
        dispatch(new SendNotifMcu($pesanText, $nomorGabungan, $token, $namaUser));
        $dept = getInfoKaryawanByNrpDept($this->nrp);
        $deptMap = [
            'HC' => $info['adminHC'],
            'ENG' => $info['adminENG'],
            'PRO' => $info['adminPRO'],
            'PLT' => $info['adminPLT'],
            'COE' => $info['adminCOE'],
        ];
        if (isset($deptMap[$dept])) {
            dispatch(new SendNotifMcu($pesanText, $deptMap[$dept], $token, $namaUser));
        }

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
                $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n*$infoKaryawan*\n Status File MCU: *Diterima* Lanjut Proses Penginputan Data Medis";
            } else {
                $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n*$infoKaryawan*\n Status File MCU: *Ditolak - $catatan*";
            }
            //$nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);
            $nomorGabungan = array_merge($info['nomorAdmins']);
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
        $this->reqtal_touche = $carimcu->reqtal_touche ?: null;
        $this->sistol = $carimcu->sistol;
        $this->diastol = $carimcu->diastol;
        $this->OD_jauh = $carimcu->OD_jauh;
        $this->OS_jauh = $carimcu->OS_jauh;
        $this->OD_dekat = $carimcu->OD_dekat;
        $this->OS_dekat = $carimcu->OS_dekat;
        $this->butawarna = $carimcu->butawarna ?: null;
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
        $this->tredmil_test = $carimcu->tredmil_test ?: null;
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
                'reqtal_touche' => $this->reqtal_touche ?: null,
                'sistol' => $this->sistol,
                'diastol' => $this->diastol,
                'OD_jauh' => $this->OD_jauh,
                'OS_jauh' => $this->OS_jauh,
                'OD_dekat' => $this->OD_dekat,
                'OS_dekat' => $this->OS_dekat,
                'butawarna' => $this->butawarna ?: null,
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
                'tredmil_test' => $this->tredmil_test ?: null,
                'widal_test' => $this->widal_test,
                'routin_feces' => $this->routin_feces,
                'kultur_feces' => $this->kultur_feces,
            ]
        );

        $info = getUserInfo(); // ambil data user saat dispatch, di konteks request HTTP (user pasti ada)
        $infoKaryawan = getInfoKaryawanByNrp($this->nrp);

        $pesanText = "📢 *MIFA-NOTIF - Follow Up MCU*\n\n\n*$infoKaryawan*\n";
        $nomorGabungan = array_merge($info['nomorAdmins'], $info['nomorParamedik']);
        $token = $info['token'];
        $namaUser = $info['nama'];
        dispatch(new SendNotifMcu($pesanText, $nomorGabungan, $token, $namaUser));
        $dept = getInfoKaryawanByNrpDept($this->nrp);
        $deptMap = [
            'HC' => $info['adminHC'],
            'ENG' => $info['adminENG'],
            'PRO' => $info['adminPRO'],
            'PLT' => $info['adminPLT'],
            'COE' => $info['adminCOE'],
        ];
        if (isset($deptMap[$dept])) {
            dispatch(new SendNotifMcu($pesanText, $deptMap[$dept], $token, $namaUser));
        }

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
        $this->jenis_pengajuan_mcu = $carimcu->jenis_pengajuan_mcu;
        $this->nrp = $carimcu->id_karyawan;
        $this->no_dokumen = $carimcu->no_dokumen;
        $this->tgl_mcu = $carimcu->tgl_mcu;
        $this->nama = $carimcu->nama;
        $this->gol_darah = $carimcu->mcuGolDarah ?: null;
        $this->file_mcu = $carimcu->file_mcu;
        $this->status = $carimcu->mcuStatus;
        $this->id_mcu = $id_mcu;
        $this->riwayat_rokok = $carimcu->riwayat_rokok;
        $this->BB = $carimcu->BB;
        $this->TB = $carimcu->TB;
        $this->LP = $carimcu->LP;
        //$this->BMI = $carimcu->BMI;
        $this->Laseq = $carimcu->Laseq;
        $this->reqtal_touche = $carimcu->reqtal_touche ?: null;
        $this->sistol = $carimcu->sistol;
        $this->diastol = $carimcu->diastol;
        $this->OD_jauh = $carimcu->OD_jauh;
        $this->OS_jauh = $carimcu->OS_jauh;
        $this->OD_dekat = $carimcu->OD_dekat;
        $this->OS_dekat = $carimcu->OS_dekat;
        $this->butawarna = $carimcu->butawarna ?: null;
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
        $this->tredmil_test = $carimcu->tredmil_test ?: null;
        $this->echocardiography = $carimcu->echocardiography;
        $this->widal_test = $carimcu->widal_test;
        $this->routin_feces = $carimcu->routin_feces;
        $this->kultur_feces = $carimcu->kultur_feces;
        $this->kesadaran = $carimcu->kesadaran;
        $this->epilepsi = $carimcu->epilepsi;
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
                $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Inputan data MCU: *ditolak* \nKeterangan: *$this->paramedik_catatan*\n";

                $mcu->update([
                    'tgl_verifikasi' => $this->tgl_verifikasi,
                    'verifikator' => auth()->user()->username,
                    'paramedik' => NULL,
                    'paramedik_status' => $this->paramedik_status,
                    'paramedik_catatan' => $this->paramedik_catatan
                ]);
            } else {
                $this->validate(
                    [
                        'status' => 'required',
                        'tgl_verifikasi' => 'required',
                        'upload_hasil_mcu' => 'nullable|mimes:jpg,png,jpeg,pdf|max:10240',
                    ],
                    [
                        'status.required' => 'Status harus diisi.',
                        'tgl_verifikasi.required' => 'Tanggal Verifikasi harus diisi.',
                        'upload_hasil_mcu.mimes' => 'Format file harus jpg, png, jpeg, pdf.',
                        'upload_hasil_mcu.max' => 'Ukuran file maksimal 10MB.',
                    ]
                );

                $datakaryawan = Karyawan::where('nrp', $this->nrp)->first();
                $folderDept = strtoupper(Str::slug($datakaryawan->dept, '_'));
                $folderKaryawan = strtoupper(Str::slug(
                    $datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan,
                    '_'
                ));
                $folderPath = $folderDept . '/' . $folderKaryawan . '/MCU/TEMUAN';

                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }

                // Handle the file upload for 'file_mcu'
                if ($this->upload_hasil_mcu) {
                    $filePath = $this->upload_hasil_mcu->storeAs($folderPath, $folderKaryawan . "-TEMUAN-MCU-" . time() . ".{$this->upload_hasil_mcu->getClientOriginalExtension()}", 'public');
                } else {
                    $filePath = null; // Handle the case where no file is uploaded (optional)
                }

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
                        'paramedik_catatan' => $this->paramedik_catatan,
                        'upload_hasil_mcu' => $filePath
                    ]);
                    Karyawan::where('nrp', $mcu->id_karyawan)
                        ->update(['exp_mcu' => $this->exp_mcu]);
                    if ($indukmcu) {
                        $indukmcu->update([
                            'status_' => 'close',
                        ]);
                    }
                    $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Hasil MCU: *$this->status*\n";
                } else {
                    if ($this->status == 'FOLLOW UP') {
                        foreach ($this->followups as $item) {
                            FollowupMcu::create([
                                'id_mcu'              => $this->id_mcu,
                                'keterangan_followup' => $item['keterangan'],
                                'saran_followup'      => $item['saran'],
                            ]);
                        }
                    }
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
                            'paramedik_catatan' => $this->paramedik_catatan,
                            'upload_hasil_mcu' => $filePath
                        ]);
                        $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Hasil MCU: *$this->status*\n";
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
                            'paramedik_catatan' => $this->paramedik_catatan,
                            'upload_hasil_mcu' => $filePath
                        ]);
                        $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n Hasil MCU: *$this->status*\n";
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
                    'reqtal_touche' => 'nullable|in:Ditemukan,Tidak Ditemukan',
                ],
                [
                    'no_dokumen.required' => 'No Dokumen harus diisi.',
                    'reqtal_touche.in' => 'Pilihan salah.',
                ]
            );
            if (empty($mcu->paramedik_catatan)) {
                $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n *Paramedik Input Data Medis* \n";
            } else {
                $pesanText = "📢 *MIFA-NOTIF - Pengajuan MCU*\n\n\n*$infoKaryawan*\n *Paramedik Input Data Medis*\n";
            }

            $mcu->update([
                'no_dokumen' => $this->no_dokumen,
                'gol_darah' => $this->gol_darah ?: null,
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
                'reqtal_touche' => $this->reqtal_touche ?: null,
                'sistol' => $this->sistol,
                'diastol' => $this->diastol,
                'OD_jauh' => $this->OD_jauh,
                'OS_jauh' => $this->OS_jauh,
                'OD_dekat' => $this->OD_dekat,
                'OS_dekat' => $this->OS_dekat,
                'butawarna' => $this->butawarna ?: null,
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
                'tredmil_test' => $this->tredmil_test ?: null,
                'widal_test' => $this->widal_test,
                'echocardiography' => $this->echocardiography,
                'routin_feces' => $this->routin_feces,
                'kultur_feces' => $this->kultur_feces,
                'saran_mcu' => $this->saran_mcu,
                'paramedik' => auth()->user()->username,
                'paramedik_catatan' => $this->paramedik_catatan,
                'paramedik_status' => NULL,
                'kesadaran' => $this->kesadaran,
                'epilepsi' => $this->epilepsi,
            ]);
            $updateKaryawan = Karyawan::where('nrp', $nrp)->first();
            $updateKaryawan->update([
                'jenis_kelamin' => $this->jenis_kelamin,
                'gol_darah' => $this->gol_darah ?: null,
            ]);
            $nomorDokter = array_merge($info['nomorDokter']);
            dispatch(new SendNotifMcu($pesanText, $nomorDokter, $token, $namaUser));
        }
        $nomorAdmin = array_merge($info['nomorAdmins']);
        dispatch(new SendNotifMcu($pesanText, $nomorAdmin, $token, $namaUser));
        $dept = getInfoKaryawanByNrpDept($nrp);
        $deptMap = [
            'HC' => $info['adminHC'],
            'ENG' => $info['adminENG'],
            'PRO' => $info['adminPRO'],
            'PLT' => $info['adminPLT'],
            'COE' => $info['adminCOE'],
        ];
        if (isset($deptMap[$dept])) {
            dispatch(new SendNotifMcu($pesanText, $deptMap[$dept], $token, $namaUser));
        }

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
    public function exportExcel()
    {
        $filename = 'Hasil_MCU_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new mcuExport, $filename);
    }
    public function mount()
    {
        $this->forms[] = $this->emptyForm();
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
        $cuti = cutiDokter::first();
        $this->isOn = $cuti && $cuti->status === 'on';
    }
    public function deleteConfirm($id)
    {
        $this->dispatch(
            'confirm',
            id: $id
        );
    }
    public function delete(int $id)
    {
        $mcu = ModelsMcu::find($id);

        Log::info('Delete MCU triggered', [
            'clicked_id' => $id,
            'mcu' => $mcu,
        ]);

        if ($mcu) {
            if ($mcu->sub_id) {
                // Log::info('Data yang diklik adalah anak', [
                //     'sub_id' => $mcu->sub_id,
                // ]);

                // yang diklik anak → hapus induknya & semua anak
                $deletedInduk = ModelsMcu::where('id', $mcu->sub_id)->delete();
                $deletedAnak = ModelsMcu::where('sub_id', $mcu->sub_id)->delete();

                // Log::info('Delete result (anak case)', [
                //     'deleted_induk' => $deletedInduk,
                //     'deleted_anak' => $deletedAnak,
                // ]);
            } else {
                // Log::info('Data yang diklik adalah induk', [
                //     'id' => $id,
                // ]);

                // yang diklik induk → hapus induk & semua anak
                $deletedInduk = ModelsMcu::where('id', $id)->delete();
                $deletedAnak = ModelsMcu::where('sub_id', $id)->delete();

                // Log::info('Delete result (induk case)', [
                //     'deleted_induk' => $deletedInduk,
                //     'deleted_anak' => $deletedAnak,
                // ]);
            }
        } else {
            Log::warning('MCU tidak ditemukan', ['id' => $id]);
        }

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Hapus Data',
            position: 'center',
            confirm: true,
            redirect: '/mcu',
        );
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
            $prioritasIDs = $carimcu->pluck('sub_id')->filter()->unique()->toArray();

            // Cari induk yang punya anak dengan paramedik null
            if (auth()->user()->subrole === 'verifikator') {
                $indukPrioritas = ModelsMcu::whereNotNull('paramedik')
                    ->whereNull('status')
                    ->get()
                    ->map(function ($item) {
                        return $item->sub_id ?? $item->id; // kalau sub_id null, ambil id
                    })
                    ->unique()
                    ->toArray();
                // dd($indukPrioritas);
            } else if (auth()->user()->role === 'superadmin') {
                $indukPrioritas = ModelsMcu::whereNotNull('status')
                    ->get()
                    ->map(function ($item) {
                        return $item->sub_id ?? $item->id; // kalau sub_id null, ambil id
                    })
                    ->unique()
                    ->toArray();
            } else {
                $indukPrioritas = ModelsMcu::whereNull('paramedik')
                    ->get()
                    ->map(function ($item) {
                        return $item->sub_id ?? $item->id; // kalau sub_id null, ambil id
                    })
                    ->unique()
                    ->toArray();
            }

            if (auth()->user()->subrole === 'verifikator') {
                $cuti_dokter = cutiDokter::where('status', 'on')->first();
                if ($cuti_dokter) {
                    $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
                        ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                        ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                        ->where('mcu.status_', '=', "open")
                        ->whereNull('mcu.sub_id')
                        ->when($this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                            $query->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir]);
                        })
                        ->when($this->searchtgl_awal && !$this->searchtgl_akhir, function ($query) {
                            $query->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal);
                        })
                        ->when(!$this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                            $query->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir);
                        })
                        ->when(!empty($indukPrioritas), function ($query) use ($indukPrioritas) {
                            $ids = implode(',', $indukPrioritas);
                            return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                            //return $query->wherein('mcu.id', $indukPrioritas);
                        })
                        ->orderBy('mcu.tgl_mcu', 'asc')
                        ->paginate(10)
                        ->withQueryString();
                } else {
                    $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
                        ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                        ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                        ->where('mcu.status_', '=', "open")
                        ->whereNull('mcu.sub_id')
                        ->when($this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                            $query->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir]);
                        })
                        ->when($this->searchtgl_awal && !$this->searchtgl_akhir, function ($query) {
                            $query->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal);
                        })
                        ->when(!$this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                            $query->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir);
                        })
                        ->when(!empty($indukPrioritas), function ($query) use ($indukPrioritas) {
                            //$ids = implode(',', $indukPrioritas);
                            //return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                            return $query->wherein('mcu.id', $indukPrioritas);
                        })
                        // ->when(!empty($prioritasIDs), function ($query) use ($prioritasIDs) {
                        //     $ids = implode(',', $prioritasIDs);
                        //     return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                        // })
                        //batas kunci untuk dokter
                        ->where(function ($q) {
                            $q->whereNull('mcu.verifikator')
                                ->orWhere('mcu.verifikator', auth()->user()->username);
                        })
                        //batas kunci untuk dokter
                        ->orderBy('mcu.tgl_mcu', 'asc')
                        ->paginate(10)
                        ->withQueryString();
                }
            } else if (auth()->user()->subrole === 'paramedik') {
                $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
                    ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                    ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                    ->where('mcu.status_', '=', "open")
                    ->whereNull('mcu.sub_id')
                    ->when($this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                        $query->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir]);
                    })
                    ->when($this->searchtgl_awal && !$this->searchtgl_akhir, function ($query) {
                        $query->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal);
                    })
                    ->when(!$this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                        $query->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir);
                    })
                    ->when(!empty($indukPrioritas), function ($query) use ($indukPrioritas) {
                        //$ids = implode(',', $indukPrioritas);
                        //return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                        return $query->wherein('mcu.id', $indukPrioritas);
                    })
                    // ->when(!empty($prioritasIDs), function ($query) use ($prioritasIDs) {
                    //     $ids = implode(',', $prioritasIDs);
                    //     return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                    // })
                    // ->orderBy('mcu.tgl_mcu', 'desc')
                    ->paginate(10)
                    ->withQueryString();
            } else {
                $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
                    ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                    ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                    ->where('mcu.status_', '=', "open")
                    ->whereNull('mcu.sub_id')
                    ->when($this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                        $query->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir]);
                    })
                    ->when($this->searchtgl_awal && !$this->searchtgl_akhir, function ($query) {
                        $query->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal);
                    })
                    ->when(!$this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                        $query->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir);
                    })
                    ->when(!empty($indukPrioritas), function ($query) use ($indukPrioritas) {
                        $ids = implode(',', $indukPrioritas);
                        return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                        //return $query->wherein('mcu.id', $indukPrioritas);
                    })
                    // ->when(!empty($prioritasIDs), function ($query) use ($prioritasIDs) {
                    //     $ids = implode(',', $prioritasIDs);
                    //     return $query->orderByRaw("FIELD(mcu.id, $ids) DESC");
                    // })
                    // ->orderBy('mcu.tgl_mcu', 'desc')
                    ->paginate(10)
                    ->withQueryString();
            }
        } else {
            $indukPrioritas = ModelsMcu::whereNull('sub_id')
                ->where('status_', 'open')
                ->where('status', 'FOLLOW UP')
                ->pluck('id')
                ->toArray();

            $subPrioritas = ModelsMcu::whereIn('sub_id', $indukPrioritas)
                ->where('status_', 'open')
                ->pluck('sub_id')
                ->toArray();

            $indukDitolak = ModelsMcu::whereNull('sub_id')
                ->where('status_file_mcu', '0')
                ->pluck('id')
                ->toArray();

            $subDitolak = ModelsMcu::where('status_file_mcu', '0')
                ->pluck('sub_id')
                ->toArray();

            // cari irisan
            $duplikat    = array_intersect($indukPrioritas, $subPrioritas);
            $indukBersih = array_diff($indukPrioritas, $duplikat);
            $subBersih   = array_diff($subPrioritas, $duplikat);

            // gabungkan semua hasil (pastikan integer dan unik)
            $hasilAkhir = array_values(array_unique(array_map('intval', array_merge(
                $indukBersih,
                $subBersih,
                $indukDitolak,
                $subDitolak
            ))));

            // --- query utama ---
            $mcus = ModelsMcu::select(
                'mcu.*',
                'karyawans.*',
                'mcu.status as mcuStatus',
                'mcu.id as id_mcu'
            )
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
                ->where('mcu.status_', '=', "open")
                ->whereNull('mcu.sub_id')
                ->when($this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                    $query->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir]);
                })
                ->when($this->searchtgl_awal && !$this->searchtgl_akhir, function ($query) {
                    $query->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal);
                })
                ->when(!$this->searchtgl_awal && $this->searchtgl_akhir, function ($query) {
                    $query->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir);
                })
                ->where('karyawans.dept', auth()->user()->subrole);

            // --- sorting ---
            if (!empty($hasilAkhir)) {
                // ada prioritas → urutkan sesuai FIELD
                $ids = implode(',', $hasilAkhir);
                $mcus = $mcus->orderByRaw("FIELD(mcu.id, $ids) DESC")
                    ->orderBy('mcu.tgl_mcu', 'desc');
            } else {
                // tidak ada prioritas → urut tanggal saja
                $mcus = $mcus->orderBy('mcu.tgl_mcu', 'desc');
            }

            // --- paginate ---
            $mcus = $mcus->paginate(10)->withQueryString();
        }
        // if (in_array(auth()->user()->role, ['superadmin', 'dokter', 'she'])) {
        //     $carimcu = ModelsMcu::select('sub_id', 'verifikator')
        //         ->whereNull('verifikator')
        //         ->get();
        //     $prioritasIDs = $carimcu->pluck('sub_id')->filter()->unique()->toArray();

        //     Cari induk yang punya anak dengan paramedik null
        //     if (auth()->user()->subrole === 'verifikator') {
        //         $indukPrioritas = ModelsMcu::whereNull('verifikator')
        //             ->whereNotNull('verifikator')
        //             ->whereNotNull('paramedik')
        //             ->whereNull('status')
        //             ->get()
        //             ->map(fn($item) => $item->sub_id ?? $item->id)
        //             ->unique()
        //             ->toArray();
        //         dd($indukPrioritas);
        //     } else if (auth()->user()->subrole === 'paramedik') {
        //         $indukPrioritas = ModelsMcu::whereNull('paramedik')
        //             ->whereNull('status_file_mcu')
        //             ->get()
        //             ->map(fn($item) => $item->sub_id ?? $item->id)
        //             ->unique()
        //             ->toArray();
        //     } else if (auth()->user()->role === 'superadmin') {
        //         $indukPrioritas = ModelsMcu::whereNotNull('status')
        //             ->get()
        //             ->map(fn($item) => $item->sub_id ?? $item->id)
        //             ->unique()
        //             ->toArray();
        //     } else {
        //         $indukPrioritas = ModelsMcu::whereNull('paramedik')
        //             ->whereNull('verifikator')
        //             ->get()
        //             ->map(fn($item) => $item->sub_id ?? $item->id)
        //             ->unique()
        //             ->toArray();
        //     }

        //     if (auth()->user()->subrole === 'verifikator') {
        //         $cuti_dokter = cutiDokter::where('status', 'on')->first();

        //         $query = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
        //             ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
        //             ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
        //             ->where('mcu.status_', '=', "open")
        //             ->whereNotNull('paramedik')
        //             ->whereNull('verifikator')
        //             ->whereNull('mcu.sub_id');
        //         $query->when(
        //             $this->searchtgl_awal && $this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir])
        //         );
        //         $query->when(
        //             $this->searchtgl_awal && !$this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal)
        //         );
        //         $query->when(
        //             !$this->searchtgl_awal && $this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir)
        //         );
        //         $query->when(!empty($indukPrioritas), function ($q) use ($indukPrioritas) {
        //             $ids = implode(',', $indukPrioritas);
        //             return $q->orderByRaw("FIELD(mcu.id, $ids) DESC");
        //             return $q->whereIn('mcu.id', $indukPrioritas)
        //                 ->orderBy('mcu.tgl_mcu', 'asc');
        //         });
        //         dd($indukPrioritas);

        //         if (!$cuti_dokter) {
        //             $query->where(function ($q) {
        //                 $q->whereNull('mcu.verifikator')
        //                     ->orWhere('mcu.verifikator', auth()->user()->username);
        //             });
        //         }

        //         $total = $query->count();
        //         $perPage = max(1, ceil($total / 2)); // biar aman, minimal 1
        //         $mcus = $query->orderBy('mcu.tgl_mcu', 'asc')
        //             ->paginate(200)
        //             ->withQueryString();
        //     } else if (auth()->user()->subrole === 'paramedik') {
        //         $cuti_dokter = cutiDokter::where('status', 'on')->first();

        //         $query = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
        //             ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
        //             ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
        //             ->where('mcu.status_', '=', "open")
        //             ->whereNull('paramedik')
        //             ->whereNull('mcu.sub_id')
        //             ->orderBy('mcu.tgl_mcu', 'asc');

        //         $query->when(
        //             $this->searchtgl_awal && $this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir])
        //         );
        //         $query->when(
        //             $this->searchtgl_awal && !$this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal)
        //         );
        //         $query->when(
        //             !$this->searchtgl_awal && $this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir)
        //         );
        //         $query->when(!empty($indukPrioritas), function ($q) use ($indukPrioritas) {
        //             $ids = implode(',', $indukPrioritas);
        //             return $q->orderByRaw("FIELD(mcu.id, $ids) DESC");
        //         });

        //         if (!$cuti_dokter) {
        //             $query->where(function ($q) {
        //                 $q->whereNull('mcu.verifikator')
        //                     ->orWhere('mcu.verifikator', auth()->user()->username);
        //             });
        //         }

        //         $total = $query->count();
        //         $perPage = max(1, ceil($total / 2)); // biar aman, minimal 1
        //         $mcus = $query->orderBy('mcu.tgl_mcu', 'asc')
        //             ->paginate($perPage)
        //             ->withQueryString();
        //     } else {
        //         $query = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
        //             ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
        //             ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
        //             ->where('mcu.status_', '=', "open")
        //             ->whereNull('mcu.sub_id');

        //         $query->when(
        //             $this->searchtgl_awal && $this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir])
        //         );
        //         $query->when(
        //             $this->searchtgl_awal && !$this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal)
        //         );
        //         $query->when(
        //             !$this->searchtgl_awal && $this->searchtgl_akhir,
        //             fn($q) =>
        //             $q->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir)
        //         );
        //         $query->when(!empty($indukPrioritas), function ($q) use ($indukPrioritas) {
        //             $ids = implode(',', $indukPrioritas);
        //             return $q->orderByRaw("FIELD(mcu.id, $ids) DESC");
        //         });

        //         $total = $query->count();
        //         $perPage = max(1, ceil($total / 2));
        //         $mcus = $query->orderBy('mcu.tgl_mcu', 'asc')
        //             ->paginate($perPage)
        //             ->withQueryString();
        //     }
        // } else {
        //     $indukPrioritas = ModelsMcu::whereNotNull('status')
        //         ->get()
        //         ->map(fn($item) => $item->sub_id ?? $item->id)
        //         ->unique()
        //         ->toArray();

        //     $query = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu')
        //         ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
        //         ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
        //         ->where('mcu.status_', '=', "open")
        //         ->whereNull('mcu.sub_id')
        //         ->where('karyawans.dept', auth()->user()->subrole);

        //     $query->when(
        //         $this->searchtgl_awal && $this->searchtgl_akhir,
        //         fn($q) =>
        //         $q->whereBetween('mcu.tgl_mcu', [$this->searchtgl_awal, $this->searchtgl_akhir])
        //     );
        //     $query->when(
        //         $this->searchtgl_awal && !$this->searchtgl_akhir,
        //         fn($q) =>
        //         $q->whereDate('mcu.tgl_mcu', '>=', $this->searchtgl_awal)
        //     );
        //     $query->when(
        //         !$this->searchtgl_awal && $this->searchtgl_akhir,
        //         fn($q) =>
        //         $q->whereDate('mcu.tgl_mcu', '<=', $this->searchtgl_akhir)
        //     );
        //     $query->when(!empty($indukPrioritas), function ($q) use ($indukPrioritas) {
        //         $ids = implode(',', $indukPrioritas);
        //         return $q->orderByRaw("FIELD(mcu.id, $ids) DESC");
        //     });

        //     $total = $query->count();
        //     $perPage = max(1, ceil($total / 2));
        //     $mcus = $query->orderBy('mcu.tgl_mcu', 'desc')
        //         ->paginate($perPage)
        //         ->withQueryString();
        // }

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
