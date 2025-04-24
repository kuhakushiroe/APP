<?php

namespace App\Livewire\Pengajuan;

use App\Models\Karyawan;
use App\Models\ModelPengajuanID;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Id extends Component
{
    use WithFileUploads, WithPagination, WithoutUrlPagination;
    public $form = false;
    public $carikaryawan, $nrp, $nama, $jenis_pengajuan_id, $upload_id_lama, $status_upload_id_lama, $catatan_upload_id_lama, $upload_request, $status_upload_request, $catatan_upload_request, $upload_foto, $status_upload_foto, $catatan_upload_foto, $upload_ktp, $status_upload_ktp, $catatan_upload_ktp, $upload_skd, $status_upload_skd, $catatan_upload_skd, $upload_bpjs, $status_upload_bpjs, $catatan_upload_bpjs, $status_pengajuan, $tgl_pengajuan, $exp_id;
    public $expired_id = [];
    public $tgl_induksi = [];
    #[Title('ID')]

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
            ->where('exp_mcu', '>', date('Y-m-d'))
            ->first();
        if ($caridatakaryawan) {
            $this->nama = $caridatakaryawan->nama;
            $this->nrp = $caridatakaryawan->nrp;
            $this->jenis_pengajuan_id = ($caridatakaryawan->exp_id === null || $caridatakaryawan->exp_id > now())
                ? 'baru'
                : 'perpanjangan';
        } else {
            $this->nama = null;
            $this->jenis_pengajuan_id = null; // Reset field if NIK is not found
        }
    }
    public function store()
    {
        $rules = [
            'nrp' => [
                'required',
                function ($attribute, $value, $fail) {
                    $karyawan = DB::table('karyawans')->where('nrp', $value)->first();
                    if (!$karyawan) {
                        $fail('NRP tidak ditemukan dalam data karyawan.');
                    } elseif (!$karyawan->exp_mcu) {
                        $fail('Tanggal MCU belum diisi / Belum Memiliki MCU.');
                    } elseif ($karyawan->status === 'non aktif') {
                        $fail('Status Karyawan Non Aktif');
                    } elseif ($karyawan->exp_mcu < now()) {
                        $fail('MCU sudah kadaluarsa.');
                    }
                }
            ],
            'jenis_pengajuan_id' => 'required',
            'tgl_pengajuan' => 'required',
            'upload_request' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240',
        ];

        if ($this->jenis_pengajuan_id === 'perpanjangan') {
            $rules['upload_id_lama'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }
        if ($this->jenis_pengajuan_id === 'baru') {
            $rules['upload_id_lama'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_foto'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_ktp'] =  'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_skd'] =  'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_bpjs'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        $messages = [
            'required' => 'Kolom :attribute harus diisi.',
            'mimes' => 'Format file :attribute harus JPEG, PNG, JPG, GIF, atau PDF.',
            'max' => 'Ukuran file :attribute maksimal 10MB.',
        ];
        $this->validate($rules, $messages);

        $pengajuan = ModelPengajuanID::create([
            'nrp' => $this->nrp,
            'jenis_pengajuan_id' => $this->jenis_pengajuan_id,
            'tgl_pengajuan' => $this->tgl_pengajuan,
            'status_pengajuan' => '0',
        ]);

        $nrp = $this->nrp;
        $idPengajuan = $pengajuan->id;

        $folderPath = $this->nrp . '/pengajuanID';

        // Check if folder exists, if not, create it
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath); // Create the directory if it doesn't exist
        }

        // 2. Simpan file ke storage/public/[nrp]/ dengan nama khusus
        $requestPath = $this->upload_request->storeAs($folderPath, "{$nrp}_request_{$idPengajuan}.{$this->upload_request->getClientOriginalExtension()}", 'public');

        // Optional jika perpanjangan
        $idLamaPath = null;
        if ($this->jenis_pengajuan_id === 'perpanjangan' && $this->upload_id_lama) {
            $idLamaPath = $this->upload_id_lama->storeAs($folderPath, "{$nrp}_id_lama_{$idPengajuan}.{$this->upload_id_lama->getClientOriginalExtension()}", 'public');
        }
        if ($this->jenis_pengajuan_id === 'baru') {
            $fotoPath = $this->upload_foto->storeAs($folderPath, "{$nrp}_foto_{$idPengajuan}.{$this->upload_foto->getClientOriginalExtension()}", 'public');
            $ktpPath = $this->upload_ktp->storeAs($folderPath, "{$nrp}_ktp_{$idPengajuan}.{$this->upload_ktp->getClientOriginalExtension()}", 'public');
            $skdPath = $this->upload_skd->storeAs($folderPath, "{$nrp}_skd_{$idPengajuan}.{$this->upload_skd->getClientOriginalExtension()}", 'public');
            $bpjsPath = $this->upload_bpjs->storeAs($folderPath, "{$nrp}_bpjs_{$idPengajuan}.{$this->upload_bpjs->getClientOriginalExtension()}", 'public');
        }

        if ($this->jenis_pengajuan_id === 'baru') {
            $pengajuan->update([
                'upload_request' => $requestPath,
                'upload_foto' => $fotoPath,
                'upload_ktp' => $ktpPath,
                'upload_skd' => $skdPath,
                'upload_bpjs' => $bpjsPath,
                'upload_id_lama' => $idLamaPath,
            ]);
        }
        if ($this->jenis_pengajuan_id === 'perpanjangan') {
            $caridatalama = ModelPengajuanID::where('nrp', $this->nrp)->orderBy('created_at', 'desc')->first();
            $fotoPath = $caridatalama->upload_foto ?? null;
            $ktpPath = $caridatalama->upload_ktp ?? null;
            $skdPath = $caridatalama->upload_skd ?? null;
            $bpjsPath = $caridatalama->upload_bpjs ?? null;
            $pengajuan->update([
                'upload_request' => $requestPath,
                'upload_foto' => $fotoPath,
                'upload_ktp' => $ktpPath,
                'upload_skd' => $skdPath,
                'upload_bpjs' => $bpjsPath,
                'upload_id_lama' => $idLamaPath,
            ]);
        }
        // 3. Update data pengajuan dengan path file

        $this->reset();
        // Determine whether it's an edit or a new entry
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Tambah Pengajuan ID ' . $this->jenis_pengajuan_id,
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-id',
        );
        return;
    }
    public function updateUpload($id)
    {
        $pengajuan = ModelPengajuanID::findOrFail($id);

        $data = [];

        $nrp = $pengajuan->nrp;
        $idPengajuan = $pengajuan->id;
        $folderPath = $pengajuan->nrp . '/pengajuanID';

        // Check if folder exists, if not, create it
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath); // Create the directory if it doesn't exist
        }

        if ($pengajuan->status_upload_request == '0') {
            $this->validate([
                'upload_request' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $requestPath = $this->upload_request->storeAs($folderPath, "{$nrp}_request_{$idPengajuan}_revisi" . time() . ".{$this->upload_request->getClientOriginalExtension()}", 'public');
            $data['upload_request'] = $requestPath;
            $data['status_upload_request'] = NULL;
        }

        if ($pengajuan->status_upload_id_lama == '0') {
            $this->validate([
                'upload_id_lama' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $idLamaPath = $this->upload_id_lama->storeAs($folderPath, "{$nrp}_id_lama_{$idPengajuan}_revisi" . time() . ".{$this->upload_id_lama->getClientOriginalExtension()}", 'public');
            $data['upload_id_lama'] = $idLamaPath;
            $data['status_upload_id_lama'] = NULL;
        }

        if ($pengajuan->status_upload_foto == '0') {
            $this->validate([
                'upload_foto' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $fotoPath = $this->upload_foto->storeAs($folderPath, "{$nrp}_foto_{$idPengajuan}_revisi" . time() . ".{$this->upload_foto->getClientOriginalExtension()}", 'public');
            $data['upload_foto'] = $fotoPath;
            $data['status_upload_foto'] = NULL;
        }

        if ($pengajuan->status_upload_ktp == '0') {
            $this->validate([
                'upload_ktp' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $ktpPath = $this->upload_ktp->storeAs($folderPath, "{$nrp}_ktp_{$idPengajuan}_revisi" . time() . ".{$this->upload_ktp->getClientOriginalExtension()}", 'public');
            $data['upload_ktp'] = $ktpPath;
            $data['status_upload_ktp'] = NULL;
        }

        if ($pengajuan->status_upload_skd == '0') {
            $this->validate([
                'upload_skd' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $skdPath = $this->upload_skd->storeAs($folderPath, "{$nrp}_skd_{$idPengajuan}_revisi" . time() . ".{$this->upload_skd->getClientOriginalExtension()}", 'public');
            $data['upload_skd'] = $skdPath;
            $data['status_upload_skd'] = NULL;
        }

        if ($pengajuan->status_upload_bpjs == '0') {
            $this->validate([
                'upload_bpjs' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $bpjsPath = $this->upload_bpjs->storeAs($folderPath, "{$nrp}_bpjs_{$idPengajuan}_revisi" . time() . ".{$this->upload_bpjs->getClientOriginalExtension()}", 'public');
            $data['upload_bpjs'] = $bpjsPath;
            $data['status_upload_bpjs'] = NULL;
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
            redirect: '/pengajuan-id',
        );
        return;
    }

    public function verifikasiUpload($id)
    {
        $pengajuan = ModelPengajuanID::findOrFail($id);
        $pengajuan->update([
            'status_upload_request' => $this->status_upload_request ?? '1',
            'status_upload_id_lama' => $this->status_upload_id_lama ?? '1',
            'status_upload_foto' => $this->status_upload_foto ?? '1',
            'status_upload_ktp' => $this->status_upload_ktp ?? '1',
            'status_upload_skd' => $this->status_upload_skd ?? '1',
            'status_upload_bpjs' => $this->status_upload_bpjs ?? '1',
        ]);
        if ($this->catatan_upload_request !== NULL) {
            $pengajuan->update([
                'catatan_upload_request' => $this->catatan_upload_request,
            ]);
        }
        if ($this->catatan_upload_id_lama !== NULL) {
            $pengajuan->update([
                'catatan_upload_id_lama' => $this->catatan_upload_id_lama,
            ]);
        }
        if ($this->catatan_upload_foto !== NULL) {
            $pengajuan->update([
                'catatan_upload_foto' => $this->catatan_upload_foto,
            ]);
        }
        if ($this->catatan_upload_ktp !== NULL) {
            $pengajuan->update([
                'catatan_upload_ktp' => $this->catatan_upload_ktp,
            ]);
        }
        if ($this->catatan_upload_skd !== NULL) {
            $pengajuan->update([
                'catatan_upload_skd' => $this->catatan_upload_skd,
            ]);
        }
        if ($this->catatan_upload_bpjs !== NULL) {
            $pengajuan->update([
                'catatan_upload_bpjs' => $this->catatan_upload_bpjs,
            ]);
        }

        $this->reset();

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Verifikasi dokumen berhasil dikirim',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-id',
        );
    }

    public function prosesCetak($id)
    {
        $this->validate([
            "expired_id.$id" => 'required|date|after_or_equal:today',
            "tgl_induksi.$id" => 'required',
        ], [
            "expired_id.$id.required" => 'Tanggal expired wajib diisi.',
            "expired_id.$id.after_or_equal" => 'Tanggal tidak boleh di masa lalu.',
            "tgl_induksi.$id.required" => 'Tanggal expired wajib diisi.',
        ]);

        $pengajuan = ModelPengajuanID::findOrFail($id);
        $pengajuan->update([
            'status_pengajuan' => '1',
            'exp_id' => $this->expired_id[$id],
            'tgl_induksi' => $this->tgl_induksi[$id],
        ]);
        Karyawan::where('nrp', $pengajuan->nrp)->update([
            'exp_mcu' => $this->expired_id[$id],
            'tgl_induksi' => $this->tgl_induksi[$id],
        ]);

        $this->reset('expired_id', 'tgl_induksi'); // reset hanya properti ini

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Verifikasi dokumen berhasil dikirim',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-id',
        );
    }
    public function updateCetak($id)
    {
        $caripengajuan = ModelPengajuanID::find($id);
        $caripengajuan->update([
            'status_pengajuan' => '2',
        ]);

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Verifikasi dokumen berhasil dikirim',
            position: 'center',
            confirm: true,
            redirect: '/cetak-kartu/' . $caripengajuan->nrp,
        );
    }

    public function mount()
    {
        $this->carikaryawan = Karyawan::select('nrp', 'nama', 'exp_mcu', 'status')
            ->where('status', 'aktif')
            ->where('exp_mcu', '>', date('Y-m-d'))
            ->get();
        $caripengajuanid = ModelPengajuanID::where('status_pengajuan', '!=', '1')->get();
        foreach ($caripengajuanid as $item) {
            $this->expired_id[$item->id] = Carbon::now()->addYear()->format('Y-m-d');
            $this->tgl_induksi[$item->id] = Carbon::now();
        }
    }
    public function render()
    {
        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        if (auth()->user()->role === 'superadmin') {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->where('pengajuan_id.status_pengajuan', '!=', '2')
                ->paginate(10);
        } else if (auth()->user()->role === 'karyawan') {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->where('pengajuan_id.nrp', auth()->user()->username)
                ->where('pengajuan_id.status_pengajuan', '!=', '2')
                ->paginate(10);
        } else {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('pengajuan_id.status_pengajuan', '!=', '2')
                ->paginate(10);
        }
        return view('livewire.pengajuan.id', compact('pengajuanid', 'carifoto'));
    }
}
