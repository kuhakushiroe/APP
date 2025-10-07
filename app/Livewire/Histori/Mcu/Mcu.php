<?php

namespace App\Livewire\Histori\Mcu;

use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Mcu extends Component
{

    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $editform = false;
    public $editformnilai = false;
    public $tgl_mcu, $exp_mcu, $tgl_verifikasi, $nrp, $id_mcu;
    public $riwayat_rokok, $BB, $TB, $LP, $BMI, $Laseq, $reqtal_touche, $sistol, $diastol, $OD_jauh, $OS_jauh, $OD_dekat, $OS_dekat, $butawarna, $gdp, $hba1c, $gd_2_jpp, $ureum, $creatine, $asamurat, $sgot, $sgpt, $hbsag, $anti_hbs, $kolesterol, $hdl, $ldl, $tg, $darah_rutin, $napza, $urin, $ekg, $rontgen, $audiometri, $spirometri, $tredmil_test, $echocardiography, $widal_test, $routin_feces, $kultur_feces;
    public $kesadaran, $epilepsi, $keterangan_mcu, $saran_mcu;
    public $jenis_pengajuan_mcu, $no_dokumen, $nama, $gol_darah, $file_mcu, $status, $sub_id, $proveder, $id_karyawan;
    protected $listeners = ['delete'];
    #[Title('Histori MCU')]
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

        // Log::info('Delete MCU triggered', [
        //     'clicked_id' => $id,
        //     'mcu' => $mcu,
        // ]);

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
            // Log::warning('MCU tidak ditemukan', ['id' => $id]);
        }

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Hapus Data',
            position: 'center',
            confirm: true,
            redirect: '/histori-mcu',
        );
    }
    public function close()
    {
        $this->editform = false;
        $this->editformnilai = false;
        $this->reset();
    }
    public function edit($id)
    {
        $this->editform = true;
        $carimcu = ModelsMcu::find($id);
        $this->id_mcu = $carimcu->id;
        $this->nrp = $carimcu->id_karyawan;
        $this->tgl_mcu = $carimcu->tgl_mcu;
        $this->exp_mcu = $carimcu->exp_mcu;
        $this->tgl_verifikasi = $carimcu->tgl_verifikasi;
    }
    public function editnilai($id_mcu)
    {
        $this->editformnilai = true;
        $carimcu = ModelsMcu::find($id_mcu);
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
        $this->tredmil_test = $carimcu->tredmil_test ?: null;
        $this->echocardiography = $carimcu->echocardiography;
        $this->widal_test = $carimcu->widal_test;
        $this->routin_feces = $carimcu->routin_feces;
        $this->kultur_feces = $carimcu->kultur_feces;
        $this->kesadaran = $carimcu->kesadaran;
        $this->epilepsi = $carimcu->epilepsi;
        $this->keterangan_mcu = $carimcu->keterangan_mcu;
        $this->saran_mcu = $carimcu->saran_mcu;
    }
    public function storenilai()
    {
        ModelsMcu::where('id', $this->id_mcu)->update([
            'no_dokumen' => $this->no_dokumen,
            'gol_darah' => $this->gol_darah ?: null, // Use Laravel's `now()` helper for current date
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
            'tredmil_test' => $this->tredmil_test ?: null,
            'widal_test' => $this->widal_test,
            'echocardiography' => $this->echocardiography,
            'routin_feces' => $this->routin_feces,
            'kultur_feces' => $this->kultur_feces,
            'saran_mcu' => $this->saran_mcu,
            'keterangan_mcu' => $this->keterangan_mcu,
            'kesadaran' => $this->kesadaran,
            'epilepsi' => $this->epilepsi,
        ]);
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Ubah Nilai',
            position: 'center',
            confirm: true,
            redirect: '/histori-mcu',
        );
        $this->reset();
        return;
    }
    public function store()
    {
        ModelsMcu::where('id', $this->id_mcu)->update([
            'tgl_mcu' => $this->tgl_mcu,
            'exp_mcu' => $this->exp_mcu,
            'tgl_verifikasi' => $this->tgl_verifikasi
        ]);
        Karyawan::where('nrp', $this->nrp)->update([
            'exp_mcu' => $this->exp_mcu
        ]);
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Ubah Tanggal',
            position: 'center',
            confirm: true,
            redirect: '/histori-mcu',
        );
        $this->reset();
        return;
    }

    public function render()
    {
        if (in_array(auth()->user()->role, ['superadmin', 'she', 'dokter'])) {
            if (auth()->user()->subrole == 'verifikator') {
                $historimcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.exp_mcu as ExpMcu')
                    ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                    ->whereAny(['karyawans.nrp', 'karyawans.nama', 'karyawans.jabatan', 'karyawans.dept'], 'like', '%' . $this->search . '%')
                    ->where('mcu.status_', '=', "close")
                    ->whereIn('mcu.status', ['FIT', 'UNFIT'])
                    ->where('mcu.verifikator', auth()->user()->username)
                    //->whereNotNull('mcu.exp_mcu')
                    ->where('mcu.tgl_verifikasi', '!=', null)
                    ->orderBy('mcu.tgl_mcu', 'desc')
                    ->paginate(10);
            } else {
                $historimcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.exp_mcu as ExpMcu')
                    ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                    ->whereAny(['karyawans.nrp', 'karyawans.nama', 'karyawans.jabatan', 'karyawans.dept'], 'like', '%' . $this->search . '%')
                    ->where('mcu.status_', '=', "close")
                    ->whereIn('mcu.status', ['FIT', 'UNFIT'])
                    //->whereNotNull('mcu.exp_mcu')
                    ->orderBy('mcu.tgl_mcu', 'desc')
                    ->paginate(10);
            }
        } else {
            $historimcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.exp_mcu as ExpMcu')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->whereAny(['karyawans.nrp', 'karyawans.nama', 'karyawans.jabatan', 'karyawans.dept'], 'like', '%' . $this->search . '%')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('mcu.status_', '=', "close")
                ->whereIn('mcu.status', ['FIT', 'UNFIT'])
                //->whereNotNull('mcu.exp_mcu')
                ->orderBy('mcu.tgl_mcu', 'desc')
                ->paginate(10);
        }
        return view('livewire.histori.mcu.mcu', compact('historimcus'));
    }
}
