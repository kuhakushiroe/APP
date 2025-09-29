<?php

namespace App\Livewire\Karyawan;

use App\Models\User;
use App\Models\Jabatan;
use Livewire\Component;
use App\Models\Perusahaan;
use App\Models\Departments;
use App\Models\LogKaryawan;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Imports\KaryawanImport;
use App\Exports\KaryawansExport;
use App\Imports\checklistImport;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Karyawan as ModelsKaryawan;
use App\Models\Mcu;
use App\Models\ModelPengajuanID;
use App\Models\ModelPengajuanKimper;
use App\Models\Versatility;

class Karyawan extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;
    protected $listeners = ['delete'];

    #[Title('Karyawan')]
    public $search = '';
    protected $updatesQueryString = ['search'];
    public $form = false;
    public $formImport = false;
    public $formImportCek = false;
    public $id_karyawan, $foto, $fotolama, $nik, $nrp, $doh, $tgl_lahir, $nama, $jenis_kelamin, $nrp_lama, $departemen_lama, $jabatan_lama, $nama_lama;
    public $tempat_lahir, $agama, $gol_darah, $status_perkawinan, $perusahaan;
    public $kontraktor, $dept, $jabatan, $no_hp, $alamat, $domisili, $status = 'aktif', $status_karyawan, $file, $fileCek;
    public $dataKaryawan = [];
    public $lihatdetail = false;
    public $perubahan = false;
    public function openPerubahan($id_karyawan)
    {
        $this->perubahan = true;
        $this->id_karyawan = $id_karyawan;
    }
    public function open()
    {
        if (auth()->user()->hasRole('admin')) {
            $this->dept = auth()->user()->subrole;
        }
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
        $this->lihatdetail = false;
        $this->perubahan = false;
        $this->reset();
    }
    public function edit($id)
    {
        $karyawans = ModelsKaryawan::find($id);
        $this->id_karyawan = $karyawans->id;
        $this->nrp_lama = $karyawans->nrp;
        $this->nama_lama = $karyawans->nama;
        $this->jabatan_lama = $karyawans->jabatan;
        $this->departemen_lama = $karyawans->dept;
        $this->fotolama = $karyawans->foto;
        $this->nik = $karyawans->nik;
        $this->nrp = $karyawans->nrp;
        $this->doh = $karyawans->doh;
        $this->tgl_lahir = $karyawans->tgl_lahir;
        $this->nama = $karyawans->nama;
        $this->jenis_kelamin = $karyawans->jenis_kelamin;
        $this->tempat_lahir = $karyawans->tempat_lahir;
        $this->agama = $karyawans->agama;
        $this->gol_darah = $karyawans->gol_darah;
        $this->status_perkawinan = $karyawans->status_perkawinan;
        $this->perusahaan = $karyawans->perusahaan;
        $this->kontraktor = $karyawans->kontraktor;
        $this->dept = $karyawans->dept;
        $this->jabatan = $karyawans->jabatan;
        $this->no_hp = $karyawans->no_hp;
        $this->alamat = $karyawans->alamat;
        $this->domisili = $karyawans->domisili;
        $this->status = $karyawans->status;
        $this->status_karyawan = $karyawans->status_karyawan;
        $this->open();
    }
    // Store or update the Karyawan record
    public function store()
    {
        if ($this->id_karyawan) {
            $this->validate([
                'nik' => 'required',
                'nrp' => 'required|unique:karyawans,nrp,' . $this->id_karyawan,
                'nama' => 'required|string|max:255',
                'tgl_lahir' => 'required|date',
                'jenis_kelamin' => 'nullable',
                'tempat_lahir' => 'nullable|string|max:255',
                'agama' => 'required',
                'gol_darah' => 'required',
                'status_perkawinan' => 'required',
                'perusahaan' => 'required|string|max:255',
                'kontraktor' => 'nullable|string|max:255',
                'dept' => 'nullable',
                'jabatan' => 'string|max:255',
                'no_hp' => 'nullable',
                'alamat' => 'nullable|string|max:255',
                'domisili' => 'nullable',
                'status' => 'nullable',
                'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:1024',
                'nrp_lama' => 'nullable',
                'status_karyawan' => 'required',
            ]);
        } else {
            $this->validate([
                'nik' => 'required',
                'nrp' => 'required|unique:karyawans,nrp,' . $this->id_karyawan,
                'nama' => 'required|string|max:255',
                'doh' => 'required|date',
                'tgl_lahir' => 'required|date',
                'jenis_kelamin' => 'nullable',
                'tempat_lahir' => 'nullable|string|max:255',
                'agama' => 'required',
                'gol_darah' => 'required',
                'status_perkawinan' => 'required',
                'perusahaan' => 'required|string|max:255',
                'kontraktor' => 'nullable|string|max:255',
                'dept' => 'nullable',
                'jabatan' => 'string|max:255',
                'no_hp' => 'nullable',
                'alamat' => 'nullable|string|max:255',
                'domisili' => 'nullable',
                'status' => 'nullable',
                'status_karyawan' => 'required',
                'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:1024',
                'nrp_lama' => 'nullable',
            ]);
        }


        // Ensure that NRP is available for folder creation
        if (!$this->nrp) {
            session()->flash('message', 'NRP is required for the folder name.');
            return;
        }

        // Path for the folder: storage/app/public/{NRP}/foto/
        $folderPath = $this->nrp . '/foto';

        // Check if folder exists, if not create it

        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath); // Create the directory if it doesn't exist
        }
        // Handle file upload for 'foto'
        if ($this->foto) {
            // Store the photo in the specific folder
            $fotoPath = $this->foto->store($folderPath, 'public');
        } else {
            if ($this->fotolama) {
                $fotoPath = $this->fotolama;
            } else {
                $fotoPath = null;
            } // Handle if no file is uploaded (optional)
        }

        // if (!Storage::disk('s3')->exists($folderPath)) {
        //     Storage::disk('s3')->makeDirectory($folderPath);
        // }

        // // Handle file upload for 'foto'
        // if ($this->foto) {
        //     // Store the photo in the specific folder using S3 disk
        //     $fotoPath = $this->foto->store($folderPath, 's3');
        // } else {
        //     if ($this->fotolama) {
        //         $fotoPath = $this->fotolama;
        //     } else {
        //         $fotoPath = null;
        //     }
        // }

        // Update or create the Karyawan model


        if ($this->id_karyawan) {
            if ($this->nrp_lama != $this->nrp) {
                LogKaryawan::create(
                    [
                        'id_karyawan' => $this->id_karyawan,
                        'lama' => $this->nrp_lama,
                        'baru' => $this->nrp,
                        'jenis_perubahan' => 'nrp',
                    ]
                );
                ModelPengajuanID::where('nrp', $this->nrp_lama)->update(['nrp' => $this->nrp]);
                ModelPengajuanKimper::where('nrp', $this->nrp_lama)->update(['nrp' => $this->nrp]);
                User::where('username', $this->nrp_lama)->update(['username' => $this->nrp]);
                Mcu::where('id_karyawan', $this->nrp_lama)->update(['id_karyawan' => $this->nrp]);
            }
        }

        if ($this->id_karyawan) {
            if ($this->nama_lama != $this->nama) {
                LogKaryawan::create(
                    [
                        'id_karyawan' => $this->id_karyawan,
                        'lama' => $this->nama_lama,
                        'baru' => $this->nama,
                        'jenis_perubahan' => 'nama',
                    ]
                );
            }
        }

        if ($this->id_karyawan) {
            if ($this->jabatan_lama != $this->jabatan) {
                LogKaryawan::create(
                    [
                        'id_karyawan' => $this->id_karyawan,
                        'lama' => $this->jabatan_lama,
                        'baru' => $this->jabatan,
                        'jenis_perubahan' => 'jabatan',
                    ]
                );
            }
        }

        if ($this->id_karyawan) {
            if ($this->departemen_lama != $this->dept) {
                LogKaryawan::create(
                    [
                        'id_karyawan' => $this->id_karyawan,
                        'lama' => $this->departemen_lama,
                        'baru' => $this->dept,
                        'jenis_perubahan' => 'departemen',
                    ]
                );
            }
        }




        ModelsKaryawan::updateOrCreate(
            ['id' => $this->id_karyawan], // Unique identifier for update
            [
                'foto' => $fotoPath ?? null,
                'nik' => $this->nik,
                'nrp' => $this->nrp,
                'doh' => $this->doh,
                'tgl_lahir' => $this->tgl_lahir,
                'nama' => $this->nama,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'agama' => $this->agama,
                'gol_darah' => $this->gol_darah,
                'status_perkawinan' => $this->status_perkawinan,
                'perusahaan' => $this->perusahaan,
                'kontraktor' => $this->kontraktor,
                'dept' => $this->dept,
                'jabatan' => $this->jabatan,
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
                'domisili' => $this->domisili,
                'status' => $this->status,
                'status_karyawan' => $this->status_karyawan,

            ]
        );
        if ($this->status_karyawan != 'TEMPORARY') {
            User::updateOrCreate(
                ['username' => $this->nrp],
                [
                    'name' => $this->nama,
                    'password' => Hash::make($this->nrp),
                    'role' => 'karyawan',
                    'subrole' => $this->dept,
                    'email' => $this->nrp . '@email.com',
                ]
            );
        }
        // Optionally, reset the form fields after submission
        $jenis = $this->id_karyawan ? 'Edit' : 'Tambah';
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' Karyawan',
            position: 'center',
            confirm: true,
            redirect: '/karyawan',
        );
        $this->reset();
        return;
    }
    public function openImport()
    {
        $this->formImport = true;
    }
    public function closeImport()
    {
        $this->formImport = false;
    }
    public function openImportCek()
    {
        $this->formImportCek = true;
    }
    public function closeImportCek()
    {
        $this->formImportCek = false;
    }
    public function import()
    {
        $excel = app(Excel::class);
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240' // Max file size 10MB
        ]);

        try {
            // Import the Excel file
            $excel->import(new KaryawanImport, $this->file);
            // On success, show success message
            $this->dispatch(
                'alert',
                type: 'success',
                title: 'Berhasil',
                text: 'Berhasil Import Karyawan',
                position: 'center',
                confirm: true,
                redirect: '/karyawan',
            );
            return;
        } catch (\Exception $e) {
            // On error, show error message
            //\Log::error('Import Karyawan Error: ' . $e->getMessage());
            $this->dispatch(
                'alert',
                type: 'error',
                title: 'Gagal',
                text: 'Gagal Import Karyawan',
                position: 'center',
                confirm: true,
                redirect: '/karyawan',
            );
            return;
        }

        // Reset the file input after the import
        $this->reset('file');
    }
    public function importCek()
    {
        $excel = app(Excel::class);
        $this->validate([
            'fileCek' => 'required|file|mimes:xlsx,xls',
        ]);
        try {
            $excel->import(new checklistImport, $this->fileCek);
            $this->dispatch(
                'alert',
                type: 'success',
                title: 'Berhasil',
                text: 'Berhasil Import Karyawan',
                position: 'center',
                confirm: true,
                redirect: '/karyawan',
            );
            return;
        } catch (\Exception $e) {
            $this->dispatch(
                'alert',
                type: 'error',
                title: 'Gagal',
                text: 'Gagal Import Karyawan',
                position: 'center',
                confirm: true,
                redirect: '/karyawan',
            );
            return;
        }
    }
    public function export()
    {
        $excel = app(Excel::class);
        if (auth()->user()->hasRole('superadmin')) {
            return $excel->download(new KaryawansExport, 'karyawans-MIFA-ALL-' . date('Y-m-d') . time() . '.xlsx');
            //return Excel::download(new KaryawansExport, 'karyawans-MIFA' . date('Y-m-d') . time() . '.xlsx');
        }
        if (auth()->user()->hasRole('admin')) {
            return $excel->download(new KaryawansExport, 'karyawans-MIFA-' . auth()->user()->subrole . '-' . date('Y-m-d') . time() . '.xlsx');
            //return Excel::download(new KaryawansExport, 'karyawans-MIFA' . date('Y-m-d') . time() . '.xlsx');
        }
    }
    public function aktif(int $id)
    {
        $karyawan = ModelsKaryawan::where('id', $id);
        $karyawan->update([
            'status' => 'non aktif',
        ]);
        User::where('username', $karyawan->first()->nrp)->delete();
    }
    public function nonAktif(int $id)
    {
        $karyawan = ModelsKaryawan::where('id', $id);
        $karyawan->update([
            'status' => 'aktif',
        ]);
        $user = User::withTrashed()->where('username', $karyawan->first()->nrp);

        if ($user) {
            // Mengembalikan data yang telah di-soft delete
            $user->restore();
        } else {
            // Menangani jika data tidak ditemukan
        }
    }

    public function deleteConfirm($id)
    {
        $this->dispatch(
            'confirm',
            id: $id
        );
    }
    //search on any page
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function detail($id)
    {
        $karyawan = ModelsKaryawan::find($id);
        $this->dataKaryawan = [

            //Data Pribadi dan Data Karyawan
            'nik' => $karyawan->nik,
            'nrp' => $karyawan->nrp,
            'foto' => $karyawan->foto,
            'doh' => $karyawan->doh,
            'tgl_lahir' => $karyawan->tgl_lahir,
            'nama' => $karyawan->nama,
            'jenis_kelamin' => $karyawan->jenis_kelamin,
            'tempat_lahir' => $karyawan->tempat_lahir,
            'agama' => $karyawan->agama,
            'gol_darah' => $karyawan->gol_darah,
            'status_perkawinan' => $karyawan->status_perkawinan,
            'perusahaan' => $karyawan->perusahaan,
            'kontraktor' => $karyawan->kontraktor,
            'dept' => $karyawan->dept,
            'jabatan' => $karyawan->jabatan,
            'no_hp' => $karyawan->no_hp,
            'alamat' => $karyawan->alamat,
            'domisili' => $karyawan->domisili,
            'status' => $karyawan->status,
            'status_karyawan' => $karyawan->status_karyawan,

            //Kimper
            'exp_id' => $karyawan->exp_id,
            'exp_kimper' => $karyawan->exp_kimper,
            'exp_mcu' => $karyawan->exp_mcu,
            'exp_simpol' => $karyawan->exp_simpo,


        ];
        $this->lihatdetail = true;
    }

    public function buatakun($id)
    {
        $karyawan = ModelsKaryawan::where('nrp', $id)->first();
        User::create([
            'name' => $karyawan->nama,
            'username' => $karyawan->nrp,
            'password' => Hash::make($karyawan->nrp),
            'email' => $karyawan->nrp . '@mifashe.id',
            'role' => 'karyawan',
        ]);
    }

    public function render()
    {
        if (in_array(auth()->user()->role, ['superadmin', 'she'])) {
            $karyawans = ModelsKaryawan::whereAny(['nik', 'nrp', 'nama', 'status', 'status_karyawan', 'dept'], 'LIKE', '%' . $this->search . '%')
                ->orderByRaw("status = 'non aktif' ASC")
                ->paginate(10)
                ->withQueryString();
        } else {
            $karyawans = ModelsKaryawan::whereAny(['nik', 'nrp', 'nama', 'status', 'status_karyawan'], 'LIKE', '%' . $this->search . '%')
                ->orderByRaw("status = 'non aktif' ASC")
                ->where('dept', auth()->user()->subrole)
                ->paginate(10)
                ->withQueryString();
        }
        $departments = Departments::all();
        $perusahaans = Perusahaan::all();
        $jabatans = Jabatan::all();
        return view('livewire.karyawan.karyawan', compact('karyawans', 'departments', 'jabatans', 'perusahaans'));
    }
}
