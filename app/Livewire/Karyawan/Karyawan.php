<?php

namespace App\Livewire\Karyawan;

use App\Exports\KaryawansExport;
use App\Imports\checklistImport;
use App\Imports\KaryawanImport;
use App\Models\Departments;
use App\Models\Jabatan;
use App\Models\Karyawan as ModelsKaryawan;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

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
    public $id_karyawan, $foto, $fotolama, $nik, $nrp, $doh, $tgl_lahir, $nama, $jenis_kelamin;
    public $tempat_lahir, $agama, $gol_darah, $status_perkawinan, $perusahaan;
    public $kontraktor, $dept, $jabatan, $no_hp, $alamat, $domisili, $status = 'aktif', $file, $fileCek;
    public $dataKaryawan = [];
    public $lihatdetail = false;
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
        $this->reset();
    }
    public function edit($id)
    {
        $karyawans = ModelsKaryawan::find($id);
        $this->id_karyawan = $karyawans->id;
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
        $this->open();
    }
    // Store or update the Karyawan record
    public function store()
    {
        if ($this->id_karyawan) {
            $this->validate([
                'nik' => 'nullable',
                'nrp' => 'required|unique:karyawans,nrp,' . $this->id_karyawan,
                'nama' => 'required|string|max:255',
                'tgl_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable',
                'tempat_lahir' => 'nullable|string|max:255',
                'agama' => 'nullable',
                'gol_darah' => 'nullable',
                'status_perkawinan' => 'nullable',
                'perusahaan' => 'nullable|string|max:255',
                'kontraktor' => 'nullable|string|max:255',
                'dept' => 'required',
                'jabatan' => 'nullable|string|max:255',
                'no_hp' => 'nullable',
                'alamat' => 'nullable|string|max:255',
                'domisili' => 'nullable',
                'status' => 'nullable',
                'foto' => 'nullable',
            ]);
        } else {
            $this->validate([
                'nik' => 'nullable',
                'nrp' => 'required|unique:karyawans,nrp,' . $this->id_karyawan,
                'nama' => 'required|string|max:255',
                'doh' => 'required|date',
                'tgl_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable',
                'tempat_lahir' => 'nullable|string|max:255',
                'agama' => 'nullable',
                'gol_darah' => 'nullable',
                'status_perkawinan' => 'nullable',
                'perusahaan' => 'nullable|string|max:255',
                'kontraktor' => 'nullable|string|max:255',
                'dept' => 'required',
                'jabatan' => 'nullable|string|max:255',
                'no_hp' => 'nullable',
                'alamat' => 'nullable|string|max:255',
                'domisili' => 'nullable',
                'status' => 'nullable',
                'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:1024',
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
        // Update or create the Karyawan model

        ModelsKaryawan::updateOrCreate(
            ['id' => $this->id_karyawan], // Unique identifier for update
            [
                'foto' => $fotoPath,
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
            ]
        );
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

    public function detail($id){
        $karyawan = ModelsKaryawan::find($id);
        $this->dataKaryawan =[

            //Data Pribadi dan Data Karyawan
            'nik' => $karyawan->nik,
            'nrp' => $karyawan->nrp,
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

            //Kimper
            'exp_id' => $karyawan->exp_id,
            'exp_kimper' => $karyawan->exp_kimper,
            'exp_mcu' => $karyawan->exp_mcu,
            'exp_simpol' => $karyawan->exp_simpo,


        ];
        $this->lihatdetail = true;

    }

    public function render()
    {
        if (in_array(auth()->user()->role, ['superadmin', 'she'])) {
            $karyawans = ModelsKaryawan::whereAny(['nik', 'nrp', 'nama', 'status', 'dept'], 'LIKE', '%' . $this->search . '%')
                ->orderByRaw("status = 'non aktif' ASC")
                ->paginate(10)
                ->withQueryString();
        } else {
            $karyawans = ModelsKaryawan::whereAny(['nik', 'nrp', 'nama', 'status'], 'LIKE', '%' . $this->search . '%')
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
