<?php

namespace App\Livewire\Mcu;

use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
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
    public $no_dokumen, $status = '', $keterangan_mcu, $saran_mcu, $tgl_verifikasi, $exp_mcu;
    public $caridatakaryawan = [];
    public $carikaryawan = [];

    #[Title('MCU')]
    public function updatedNrp($value)
    {
        $caridatakaryawan = Karyawan::where('nrp', $value)
            ->where('status', 'aktif')
            ->first();
        if ($caridatakaryawan) {
            $this->gol_darah = $caridatakaryawan->gol_darah;
            $this->jenis_kelamin = $caridatakaryawan->jenis_kelamin;
        } else {
            $this->gol_darah = null;
            $this->jenis_kelamin = null; // Reset field if NIK is not found
        }
    }
    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
        $this->reset();
    }
    public function mount()
    {
        $this->carikaryawan = Karyawan::select('nrp', 'nama')
            ->where('status', 'aktif')
            ->get();
        $this->tgl_mcu = date('Y-m-d');
        $this->tgl_verifikasi = date('Y-m-d');
    }
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
            'gol_darah' => 'required',
            'file_mcu' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ], [
            'proveder.required' => 'Proveder harus diisi.',
            'nrp.required' => 'NRP harus diisi.',
            'nrp.exists' => 'NRP tidak ditemukan atau karyawan tidak aktif.',
            'tgl_mcu.required' => 'Tanggal MCU harus diisi.',
            'tgl_mcu.date' => 'Tanggal MCU harus dalam format yang valid.',
            'gol_darah.required' => 'Golongan Darah harus diisi.',
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
        ModelsMcu::updateOrCreate(
            ['id' => $this->id_mcu], // Assuming id_mcu is the primary key
            [
                'id_karyawan' => $this->nrp,
                'proveder' => $this->proveder,
                'tgl_mcu' => $this->tgl_mcu,
                'gol_darah' => $this->gol_darah,
                'file_mcu' => $filePath,
                'status' => $this->status,
                'tgl_verifikasi' => $this->tgl_verifikasi, // Store the file path, not the file object
            ]
        );

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
    public function uploadMCU($id_mcu)
    {
        $this->formUpload = true;
        $carimcu = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->where('mcu.id', $id_mcu)->first();
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
            ]
        );

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
    public function verifikasi($id_mcu)
    {
        $this->formVerifikasi = true;
        $carimcu = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->where('mcu.id', $id_mcu)->first();
        $this->nrp = $carimcu->id_karyawan;
        $this->no_dokumen = $carimcu->no_dokumen;
        $this->tgl_mcu = $carimcu->tgl_mcu;
        $this->nama = $carimcu->nama;
        $this->gol_darah = $carimcu->gol_darah;
        $this->file_mcu = $carimcu->file_mcu;
        $this->status = $carimcu->mcuStatus;
        $this->id_mcu = $id_mcu;
        $this->tgl_verifikasi = date('Y-m-d');
        $this->exp_mcu = now()->addMonth(6)->format('Y-m-d');
    }
    public function storeVerifikasi()
    {
        $mcu = ModelsMcu::find($this->id_mcu);
        $this->validate(
            [
                'no_dokumen' => 'required',
                'status' => 'required',
                'tgl_verifikasi' => 'required',
            ],
            [
                'no_dokumen.required' => 'No Dokumen harus diisi.',
                'status.required' => 'Status harus diisi.',
                'tgl_verifikasi.required' => 'Tanggal Verifikasi harus diisi.',
            ]
        );
        $indukmcu = ModelsMcu::where('id', $mcu->sub_id)->where('sub_id', NULL)->first();
        if ($this->status == 'FIT') {
            $mcu->update([
                'no_dokumen' => $this->no_dokumen,
                'status_' => 'close',
                'status' => $this->status,
                'tgl_verifikasi' => $this->tgl_verifikasi,
                'exp_mcu' => $this->exp_mcu, // Use Laravel's `now()` helper for current date
            ]);
            if ($indukmcu) {
                $indukmcu->update([
                    'status_' => 'close',
                ]);
            }
        } else {
            if (empty($mcu->sub_id)) {
                $mcu->update([
                    'no_dokumen' => $this->no_dokumen,
                    'status_' => 'open',
                    'status' => $this->status,
                    'tgl_verifikasi' => $this->tgl_verifikasi, // Use Laravel's `now()` helper for current date
                ]);
            } else {
                $mcu->update([
                    'no_dokumen' => $this->no_dokumen,
                    'status_' => 'close',
                    'status' => $this->status,
                    'tgl_verifikasi' => $this->tgl_verifikasi, // Use Laravel's `now()` helper for current date
                ]);
            }
            if ($indukmcu) {
                $indukmcu->update([
                    'status_' => 'open',
                    'status' => $this->status,
                ]);
            }
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
    public function render()
    {
        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        $mcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.status as mcuStatus')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->whereAny(['karyawans.nrp', 'karyawans.nama'], 'like', '%' . $this->search . '%')
            ->where('mcu.status_', '=', "open")
            ->where('mcu.sub_id', NULL)
            ->orderBy('mcu.tgl_mcu', 'desc')
            ->paginate(10)
            ->withQueryString();
        return view('livewire.mcu.mcu', compact('mcus', 'carifoto'));
    }
}
