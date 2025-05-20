<?php

namespace App\Livewire\Pengajuan;

use App\Models\Karyawan;
use App\Models\ModelPengajuanID;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Id extends Component
{
    use WithFileUploads, WithPagination, WithoutUrlPagination;
    public $search = '';
    public $form = false;
    public $carikaryawan, $nrp, $nama, $jenis_pengajuan_id;
    public $upload_id_lama, $status_upload_id_lama = [], $catatan_upload_id_lama = [];
    public $upload_request, $status_upload_request = [], $catatan_upload_request = [];
    public $upload_foto, $status_upload_foto = [], $catatan_upload_foto = [];
    public $upload_ktp, $status_upload_ktp = [], $catatan_upload_ktp = [];
    public $upload_skd, $status_upload_skd = [], $catatan_upload_skd = [];
    public $upload_bpjs_kes, $status_upload_bpjs_kes = [], $catatan_upload_bpjs_kes = [];
    public $upload_bpjs_ker, $status_upload_bpjs_ker = [], $catatan_upload_bpjs_ker = [];
    public $upload_spdk, $status_upload_spdk = [], $catatan_upload_spdk = [];
    public $upload_induksi, $status_upload_induksi = [], $catatan_upload_induksi, $tgl_induksi;
    public $status_pengajuan, $tgl_pengajuan, $exp_id;
    public $expired_id = [];
    public $info_nama, $info_dept, $info_jabatan, $info_mcu, $info_id, $info_kimper;

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
            ->first();
        if ($caridatakaryawan) {
            $this->info_nama = $caridatakaryawan->nama;
            $this->info_dept = $caridatakaryawan->dept;
            $this->info_jabatan = $caridatakaryawan->jabatan;
            $this->nrp = $caridatakaryawan->nrp;
            $this->info_mcu = $caridatakaryawan->exp_mcu;
            $this->info_id = $caridatakaryawan->exp_id;
            $this->info_kimper = $caridatakaryawan->exp_kimper;
            $this->jenis_pengajuan_id = ($caridatakaryawan->exp_id === null || $caridatakaryawan->exp_id > now())
                ? 'baru'
                : 'perpanjangan';
        } else {
            $this->info_nama = null;
            $this->info_dept = null;
            $this->info_jabatan = null;
            $this->info_mcu = null;
            $this->info_id = null;
            $this->info_kimper = null;
            $this->jenis_pengajuan_id = null;  // Reset field if NIK is not found
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
            $rules['upload_foto'] = 'required|mimes:jpeg,png,jpg,gif|max:10240';
            $rules['upload_ktp'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_skd'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_bpjs_kes'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_bpjs_ker'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_spdk'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
            $rules['upload_induksi'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:10240';
        }

        $messages = [
            'required' => 'Kolom :attribute harus diisi.',
            'mimes' => 'Format file :attribute harus JPEG, PNG, JPG, GIF, atau PDF.',
            'upload_foto.mimes' => 'Format file Foto harus berupa gambar (JPEG, PNG, JPG, atau GIF).',
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

        $datakaryawan = Karyawan::where('nrp', $this->nrp)->first();
        $folderDept = strtoupper(StR::slug($datakaryawan->dept, '_'));
        $folderKaryawan = strtoupper(Str::slug(
            $datakaryawan->nrp . '-' . $datakaryawan->nama . '-' . $datakaryawan->dept . '-' . $datakaryawan->jabatan,
            '_'
        ));
        $folderPath = $folderDept . '/' . $folderKaryawan . '/PENGAJUAN-ID';

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // 2. Simpan file ke storage/public/[nrp]/ dengan nama khusus
        $requestPath = $this->upload_request->storeAs($folderPath, $folderKaryawan . "-REQUEST-" . time() . ".{$this->upload_request->getClientOriginalExtension()}", 'public');

        // Optional jika perpanjangan
        $idLamaPath = null;
        if ($this->jenis_pengajuan_id === 'perpanjangan' && $this->upload_id_lama) {
            $idLamaPath = $this->upload_id_lama->storeAs($folderPath, $folderKaryawan . "-ID LAMA-" . time() . ".{$this->upload_id_lama->getClientOriginalExtension()}", 'public');
        }
        if ($this->jenis_pengajuan_id === 'baru') {
            $fotoPath = $this->upload_foto->storeAs($folderPath, $folderKaryawan . "-FOTO-" . time() . ".{$this->upload_foto->getClientOriginalExtension()}", 'public');
            $ktpPath = $this->upload_ktp->storeAs($folderPath, $folderKaryawan . "-KTP-" . time() . ".{$this->upload_ktp->getClientOriginalExtension()}", 'public');
            $skdPath = $this->upload_skd->storeAs($folderPath, $folderKaryawan . "-SKD-" . time() . ".{$this->upload_skd->getClientOriginalExtension()}", 'public');
            $bpjsKes = $this->upload_bpjs_kes->storeAs($folderPath, $folderKaryawan . "-BPJS KESEHATAN-" . time() . ".{$this->upload_bpjs_kes->getClientOriginalExtension()}", 'public');
            $bpjsKer = $this->upload_bpjs_ker->storeAs($folderPath, $folderKaryawan . "-BPJS KETENAGAKERJAAN-" . time() . ".{$this->upload_bpjs_ker->getClientOriginalExtension()}", 'public');
            $spdkPath = $this->upload_spdk->storeAs($folderPath, $folderKaryawan . "-INDUKSI-" . time() . ".{$this->upload_spdk->getClientOriginalExtension()}", 'public');
            $induksiPath = $this->upload_induksi->storeAs($folderPath, $folderKaryawan . "-INDUKSI-" . time() . ".{$this->upload_induksi->getClientOriginalExtension()}", 'public');
        }

        if ($this->jenis_pengajuan_id === 'baru') {
            $pengajuan->update([
                'upload_request' => $requestPath,
                'upload_foto' => $fotoPath,
                'upload_ktp' => $ktpPath,
                'upload_skd' => $skdPath,
                'upload_bpjs_kes' => $bpjsKes,
                'upload_bpjs_ker' => $bpjsKer,
                'upload_id_lama' => $idLamaPath,
                'upload_induksi' => $induksiPath,
                'upload_spdk' => $spdkPath,
            ]);
        }
        if ($this->jenis_pengajuan_id === 'perpanjangan') {
            $caridatalama = ModelPengajuanID::where('nrp', $this->nrp)->orderBy('created_at', 'desc')->first();
            //$requestPath = $caridatalama->upload_request ?? null;
            $fotoPath = $caridatalama->upload_foto ?? null;
            $ktpPath = $caridatalama->upload_ktp ?? null;
            $skdPath = $caridatalama->upload_skd ?? null;
            $bpjsKes = $caridatalama->upload_bpjs_kes ?? null;
            $bpjsKer = $caridatalama->upload_bpjs_ker ?? null;
            $spdkPath = $caridatalama->upload_spdk ?? null;
            $induksiPath = $caridatalama->upload_induksi ?? null;
            $pengajuan->update([
                'upload_request' => $requestPath,
                'upload_foto' => $fotoPath,
                'upload_ktp' => $ktpPath,
                'upload_skd' => $skdPath,
                'upload_bpjs_kes' => $bpjsKes,
                'upload_bpjs_ker' => $bpjsKer,
                'upload_id_lama' => $idLamaPath,
                'upload_spdk' => $spdkPath,
                'upload_induksi' => $induksiPath,
            ]);
        }

        $infoKaryawan = getInfoKaryawanByNrp($this->nrp);
        $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan ID*\n\n\n*$this->jenis_pengajuan_id*\n\n\n$infoKaryawan\n\n\n";
        // 3. Update data pengajuan dengan path file

        $this->reset();

        //function Proses kirim pesan
        $info = getUserInfo();
        foreach ($info['nomorAdmins'] as $i => $nomor) {
            pesan($nomor, $pesanText, $info['token']);
            if ($i < count($info['nomorAdmins']) - 1) {
                sleep(1);
            }
        }


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
        if ($pengajuan->status_upload_id_lama == '0') {
            $this->validate([
                'upload_id_lama' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $idLamaPath = $this->upload_id_lama->storeAs($folderPath, $folderKaryawan . "-ID LAMA-REVISI-" . time() . ".{$this->upload_id_lama->getClientOriginalExtension()}", 'public');
            $data['upload_id_lama'] = $idLamaPath;
            $data['status_upload_id_lama'] = NULL;
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
        if ($pengajuan->status_upload_spdk == '0') {
            $this->validate([
                'upload_spdk' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $spdkPath = $this->upload_spdk->storeAs($folderPath, $folderKaryawan . "-INDUKSI-" . time() . ".{$this->upload_spdk->getClientOriginalExtension()}", 'public');
            $data['upload_spdk'] = $spdkPath;
            $data['status_upload_spdk'] = NULL;
        }
        if ($pengajuan->status_upload_induksi == '0') {
            $this->validate([
                'upload_induksi' => 'required|file|mimes:pdf,jpg,png|max:10240'
            ]);
            $induksiPath = $this->upload_induksi->storeAs($folderPath, $folderKaryawan . "-INDUKSI-" . time() . ".{$this->upload_induksi->getClientOriginalExtension()}", 'public');
            $data['upload_induksi'] = $induksiPath;
            $data['status_upload_induksi'] = NULL;
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
            'status_upload_request'     => $this->status_upload_request[$id] ?? $pengajuan->status_upload_request,
            'status_upload_id_lama'     => $this->status_upload_id_lama[$id] ?? $pengajuan->status_upload_id_lama,
            'status_upload_foto'        => $this->status_upload_foto[$id] ?? $pengajuan->status_upload_foto,
            'status_upload_ktp'         => $this->status_upload_ktp[$id] ?? $pengajuan->status_upload_ktp,
            'status_upload_skd'         => $this->status_upload_skd[$id] ?? $pengajuan->status_upload_skd,
            'status_upload_bpjs_kes'    => $this->status_upload_bpjs_kes[$id] ?? $pengajuan->status_upload_bpjs_kes,
            'status_upload_bpjs_ker'    => $this->status_upload_bpjs_ker[$id] ?? $pengajuan->status_upload_bpjs_ker,
            'status_upload_induksi'     => $this->status_upload_induksi[$id] ?? $pengajuan->status_upload_induksi,
            'status_upload_spdk'        => $this->status_upload_spdk[$id] ?? $pengajuan->status_upload_spdk,

            'catatan_upload_request'    => $this->catatan_upload_request[$id] ?? null,
            'catatan_upload_id_lama'    => $this->catatan_upload_id_lama[$id] ?? null,
            'catatan_upload_foto'       => $this->catatan_upload_foto[$id] ?? null,
            'catatan_upload_ktp'        => $this->catatan_upload_ktp[$id] ?? null,
            'catatan_upload_skd'        => $this->catatan_upload_skd[$id] ?? null,
            'catatan_upload_bpjs_kes'   => $this->catatan_upload_bpjs_kes[$id] ?? null,
            'catatan_upload_bpjs_ker'   => $this->catatan_upload_bpjs_ker[$id] ?? null,
            'catatan_upload_induksi'    => $this->catatan_upload_induksi[$id] ?? null,
            'catatan_upload_spdk'       => $this->catatan_upload_spdk[$id] ?? null,
        ]);

        $infoKaryawan = getInfoKaryawanByNrp($this->nrp);
        $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan ID*\n\n\n*$this->jenis_pengajuan_id*\n\n\n$infoKaryawan\n\n\n";
        // 3. Update data pengajuan dengan path file

        //function Proses kirim pesan
        $info = getUserInfo();

        $pesanText = "📢 *MIFA-TEST NOTIF - Pengajuan ID*\n\n";
        $pesanText .= "*Jenis Pengajuan:* $this->jenis_pengajuan_id\n\n";
        $pesanText .= "*Info Karyawan:*\n$infoKaryawan\n\n";

        // Siapkan daftar status upload sesuai jenis pengajuan
        $statusList = [
            'Request'              => ['status' => $this->status_upload_request[$id] ?? $pengajuan->status_upload_request, 'catatan' => $this->status_upload_request[$id] ?? null],
            'Foto'              => ['status' => $this->status_upload_foto[$id] ?? $pengajuan->status_upload_foto, 'catatan' => $this->catatan_upload_foto[$id] ?? null],
            'KTP'               => ['status' => $this->status_upload_ktp[$id] ?? $pengajuan->status_upload_ktp, 'catatan' => $this->catatan_upload_ktp[$id] ?? null],
            'SKD'               => ['status' => $this->status_upload_skd[$id] ?? $pengajuan->status_upload_skd, 'catatan' => $this->catatan_upload_skd[$id] ?? null],
            'BPJS Kesehatan'    => ['status' => $this->status_upload_bpjs_kes[$id] ?? $pengajuan->status_upload_bpjs_kes, 'catatan' => $this->catatan_upload_bpjs_kes[$id] ?? null],
            'BPJS Ketenagakerjaan' => ['status' => $this->status_upload_bpjs_ker[$id] ?? $pengajuan->status_upload_bpjs_ker, 'catatan' => $this->catatan_upload_bpjs_ker[$id] ?? null],
            'Induksi'           => ['status' => $this->status_upload_induksi[$id] ?? $pengajuan->status_upload_induksi, 'catatan' => $this->catatan_upload_induksi[$id] ?? null],
            'SPDK'              => ['status' => $this->status_upload_spdk[$id] ?? $pengajuan->status_upload_spdk, 'catatan' => $this->catatan_upload_spdk[$id] ?? null],
        ];

        // Tambahkan "ID Lama" jika jenis pengajuan adalah Perpanjangan
        if (strtolower($this->jenis_pengajuan_id) === 'perpanjangan') {
            $statusList = ['ID Lama' => ['status' => $this->status_upload_id_lama[$id] ?? $pengajuan->status_upload_id_lama, 'catatan' => $this->catatan_upload_id_lama[$id] ?? null]] + $statusList;
        }

        $pesanText .= "*Hasil Verifikasi Upload:*\n";

        foreach ($statusList as $item => $data) {
            $statusStr = $data['status'] == 1 ? '✅ Diterima' : '❌ Ditolak';
            $catatanStr = $data['catatan'] ? "Catatan: {$data['catatan']}" : '';
            $pesanText .= "• *$item*: $statusStr" . ($catatanStr ? "\n  $catatanStr" : '') . "\n";
        }

        foreach ($info['nomorAdmins'] as $i => $nomor) {
            pesan($nomor, $pesanText, $info['token']);
            if ($i < count($info['nomorAdmins']) - 1) {
                sleep(1);
            }
        }

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Kirim Verifikasi Dokumen',
            position: 'center',
            confirm: true,
            redirect: '/pengajuan-id',
        );
        return;
    }

    public function prosesCetak($id)
    {
        $this->validate([
            "expired_id.$id" => 'required|date|after_or_equal:today',
            //"tgl_induksi.$id" => 'required',
        ], [
            "expired_id.$id.required" => 'Tanggal expired wajib diisi.',
            "expired_id.$id.after_or_equal" => 'Tanggal tidak boleh di masa lalu.',
            //"tgl_induksi.$id.required" => 'Tanggal expired wajib diisi.',
        ]);

        $pengajuan = ModelPengajuanID::findOrFail($id);
        $pengajuan->update([
            'status_pengajuan' => '1',
            'exp_id' => $this->expired_id[$id],
            //'tgl_induksi' => $this->tgl_induksi[$id],
        ]);
        Karyawan::where('nrp', $pengajuan->nrp)->update([
            'exp_id' => $this->expired_id[$id],
            //'tgl_induksi' => $this->tgl_induksi[$id],
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
            text: 'Proses Cetak Kartu ID Berhasil',
            position: 'center',
            confirm: true,
            tab: true,
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
            //$this->tgl_induksi[$item->id] = Carbon::now();
        }
    }
    public function render()
    {
        $this->tgl_induksi = Carbon::now()->format('Y-m-d');
        $this->tgl_pengajuan = Carbon::now()->format('Y-m-d');

        $carifoto = Karyawan::where('nrp', $this->nrp)
            ->where('status', 'aktif')
            ->first();
        if (in_array(auth()->user()->role, ['superadmin', 'she'])) {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->where('pengajuan_id.status_pengajuan', '!=', '2')
                ->orderBy('pengajuan_id.created_at', 'desc')
                ->paginate(10);
        } else if (auth()->user()->role === 'karyawan') {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->where('pengajuan_id.nrp', auth()->user()->username)
                ->where('pengajuan_id.status_pengajuan', '!=', '2')
                ->orderBy('pengajuan_id.created_at', 'desc')
                ->paginate(10);
        } else {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('pengajuan_id.status_pengajuan', '!=', '2')
                ->orderBy('pengajuan_id.created_at', 'desc')
                ->paginate(10);
        }
        foreach ($pengajuanid as $item) {
            $id = $item->id_pengajuan;
            $this->catatan_upload_request[$id] = $item->catatan_upload_request;
            $this->catatan_upload_foto[$id] = $item->catatan_upload_foto;
            $this->catatan_upload_ktp[$id] = $item->catatan_upload_ktp;
            $this->catatan_upload_skd[$id] = $item->catatan_upload_skd;
            $this->catatan_upload_bpjs_kes[$id] = $item->catatan_upload_bpjs_kes;
            $this->catatan_upload_bpjs_ker[$id] = $item->catatan_upload_bpjs_ker;
            $this->catatan_upload_induksi[$id] = $item->catatan_upload_induksi;
            $this->catatan_upload_spdk[$id] = $item->catatan_upload_spdk;

            $this->status_upload_request[$id] = $item->status_upload_request;
            $this->status_upload_foto[$id] = $item->status_upload_foto;
            $this->status_upload_ktp[$id] = $item->status_upload_ktp;
            $this->status_upload_skd[$id] = $item->status_upload_skd;
            $this->status_upload_bpjs_kes[$id] = $item->status_upload_bpjs_kes;
            $this->status_upload_bpjs_ker[$id] = $item->status_upload_bpjs_ker;
            $this->status_upload_induksi[$id] = $item->status_upload_induksi;
            $this->status_upload_spdk[$id] = $item->status_upload_spdk;

            // Khusus ID Lama hanya jika jenis pengajuan perpanjangan
            if ($item->jenis_pengajuan_id === 'perpanjangan') {
                $this->catatan_upload_id_lama[$id] = $item->catatan_upload_id_lama;
                $this->status_upload_id_lama[$id] = $item->status_upload_id_lama;
            }
        }
        return view('livewire.pengajuan.id', compact('pengajuanid', 'carifoto'));
    }
}
