<?php

namespace App\Livewire\Home;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Departments;
use App\Models\Mcu as ModelsMcu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Home extends Component
{
    public $tanggal;
    //public $files = [];
    public function onchangeTanggal()
    {
        $this->tanggal = $this->tanggal; //coba lagi
    }
    public function mount()
    {
        $this->tanggal = date('Y-m-d');
    }
    public function render()
    {
        $departments = Departments::select('name_department')->get();
        $colors = [
            '#FF5733',
            '#33FF57',
            '#3357FF',
            '#F0E68C',
            '#FF8C00',
            '#8A2BE2',
            '#FF1493',
            '#00FA9A',
            '#FFD700',
            '#A52A2A',
            '#20B2AA',
            '#FF6347',
            '#800080',
            '#FFFF00',
            '#FF4500'
        ];
        $employeeCountsAktif = [];
        foreach ($departments as $department) {
            // Count the number of employees in each department
            $employeeCountsAktif[] = Karyawan::where('dept', $department->name_department)->where('status', 'aktif')->count();
        }

        $employeeCountsNonAktif = [];
        foreach ($departments as $department) {
            // Count the number of employees in each department
            $employeeCountsNonAktif[] = Karyawan::where('dept', $department->name_department)->where('status', 'non aktif')->count();
        }

        // Ensure there are enough colors; repeat if needed
        $assignedColors = [];
        foreach ($departments as $index => $department) {
            $assignedColors[] = $colors[$index % count($colors)];  // Use modulus to cycle through colors
        }

        $jumlahKaryawan = Karyawan::all()->count();
        $jumlahKaryawanAktif = Karyawan::where('status', 'aktif')->count();
        $jumlahKaryawanNonAktif = Karyawan::where('status', 'non aktif')->count();

        // Hitung jumlah berdasarkan status (aktif/non aktif)
        $statusCountsKaryawan = Karyawan::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['aktif', 'non aktif'])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Hitung jumlah berdasarkan kombinasi status_karyawan dan status (aktif/non aktif)
        $detailStatusKaryawan = Karyawan::select('status_karyawan', 'status', DB::raw('count(*) as total'))
            ->whereIn('status_karyawan', ['TEMPORARY', 'PERMANEN', 'PKWT'])
            ->whereIn('status', ['aktif', 'non aktif'])
            ->groupBy('status_karyawan', 'status')
            ->get()
            ->groupBy('status_karyawan');

        // Warna untuk status utama
        $colorKaryawan = [
            'aktif' => 'text-bg-success',        // Hijau
            'non aktif' => 'text-bg-danger',     // Merah
        ];

        // Format akhir status aktif/non aktif secara umum
        $finalKaryawanCounts = collect(['aktif', 'non aktif'])
            ->map(function ($status) use ($statusCountsKaryawan, $colorKaryawan) {
                return [
                    'status' => $status,
                    'total' => $statusCountsKaryawan[$status] ?? 0,
                    'color' => $colorKaryawan[$status] ?? 'text-bg-secondary',
                ];
            })
            ->toArray();

        // Format akhir berdasarkan status_karyawan dan status aktif/non aktif
        $finalDetailStatusKaryawan = collect(['TEMPORARY', 'PERMANEN', 'PKWT'])
            ->map(function ($statusKaryawan) use ($detailStatusKaryawan) {
                $data = $detailStatusKaryawan[$statusKaryawan] ?? collect();

                return [
                    'status_karyawan' => $statusKaryawan,
                    'aktif' => $data->firstWhere('status', 'aktif')->total ?? 0,
                    'non_aktif' => $data->firstWhere('status', 'non aktif')->total ?? 0,
                ];
            })
            ->toArray();

        //pakai ini coba untuk mcu dashboard dokter
        //$today = Carbon::today()->toDateString(); // Format: '2025-06-09'
        // $this->tanggal = date('Y-m-d');
        // if ($this->tanggal) {
        //     $today = $this->tanggal;
        // } else {
        //     $today = Carbon::today()->toDateString();
        // }

        // Warna untuk setiap status
        $colorMap = [
            'FIT' => 'text-bg-success',
            'FOLLOW UP' => 'text-bg-warning',
            'UNFIT' => 'text-bg-danger',
            'TEMPORARY UNFIT' => 'text-bg-info',
        ];

        //MCU belum di acc
        $today = $this->tanggal;
        $mcuNoAcc = ModelsMcu::whereNull(['status', 'verifikator'])->count();
        // Ambil data MCU untuk hari ini, dikelompokkan berdasarkan verifikator dan status
        $mcuData = ModelsMcu::select('verifikator', 'status', DB::raw('count(*) as total'))
            ->whereNotNull('verifikator')
            ->whereDate('tgl_verifikasi', $today)
            ->groupBy('verifikator', 'status')
            ->get()
            ->groupBy('verifikator');

        //dd($mcuData);
        // Ambil semua verifikator dengan role dokter dan subrole verifikator
        $verifikators = User::where('role', 'dokter')
            ->where('subrole', 'verifikator')
            ->get()
            ->map(function ($verifikator) use ($mcuData, $colorMap, $today) {
                // Ambil data MCU untuk verifikator ini
                $statusCounts = $mcuData->get($verifikator->username, collect([]))
                    ->pluck('total', 'status')
                    ->toArray();

                // Hitung total MCU untuk verifikator ini pada hari ini
                $jumlahMcu = ModelsMcu::where('verifikator', $verifikator->username)
                    ->whereDate('tgl_verifikasi', $today)
                    ->count();

                // Format status counts dengan semua status (termasuk yang 0)
                $finalMcuCounts = collect(['FIT', 'FOLLOW UP', 'UNFIT', 'TEMPORARY UNFIT'])
                    ->map(function ($status) use ($statusCounts, $colorMap) {
                        return [
                            'status' => $status,
                            'total' => $statusCounts[$status] ?? 0,
                            'color' => $colorMap[$status] ?? 'text-bg-secondary',
                        ];
                    })
                    ->toArray();

                return [
                    'username' => $verifikator->username,
                    'nama' => $verifikator->name,
                    'jumlah_mcu' => $jumlahMcu,
                    'status_counts' => $finalMcuCounts,
                    'status_fit' => $finalMcuCounts[0]['total'],
                    'status_follow_up' => $finalMcuCounts[1]['total'],
                    'status_unfit' => $finalMcuCounts[2]['total'],
                    'status_temporary_unfit' => $finalMcuCounts[3]['total'],
                    'color' => $finalMcuCounts[0]['color'],
                    'status_total' => $finalMcuCounts[0]['total'] + $finalMcuCounts[1]['total'] + $finalMcuCounts[2]['total'] + $finalMcuCounts[3]['total'],

                ];
            })
            ->toArray();

        $totalSemuaStatus = collect($verifikators)->sum('status_total');


        //Rumus Gula Darah di google
        $gulanormal = ModelsMcu::where('gdp', '<', 100)
            ->where('gd_2_jpp', '<', 140)
            ->count();

        $prediabetes = ModelsMcu::where(function ($query) {
            $query->whereBetween('gdp', [100, 125])
                ->orWhereBetween('gd_2_jpp', [140, 199]);
        })->where(function ($query) {
            $query->where('gdp', '<', 126)
                ->orWhereNull('gdp')
                ->where('gd_2_jpp', '<', 200)
                ->orWhereNull('gd_2_jpp');
        })->count();

        $diabetes = ModelsMcu::where(function ($query) {
            $query->where('gdp', '>=', 126)
                ->orWhere('gd_2_jpp', '>=', 200);
        })->count();

        return view('livewire.home.home', [
            'departments' => $departments,
            'assignedColors' => $assignedColors,
            'employeeCountsAktif' => $employeeCountsAktif,
            'employeeCountsNonAktif' => $employeeCountsNonAktif,
            'jumlahKaryawan' => $jumlahKaryawan,
            'jumlahKaryawanAktif' => $jumlahKaryawanAktif,
            'jumlahKaryawanNonAktif' => $jumlahKaryawanNonAktif,
            // 'jumlahMCUFit' => $jumlahMCUFit,
            // 'jumlahMCUFitWithNote' => $jumlahMCUFitWithNote,
            // 'jumlahMCUFollowUp' => $jumlahMCUFollowUp,
            // 'jumlahMCUnfit' => $jumlahMCUnfit,
            //'mcuCounts' => $finalmcuCounts,
            'finalKaryawanCounts' => $finalKaryawanCounts,
            'finalDetailStatusKaryawan' => $finalDetailStatusKaryawan,

            //'dokter' => $dokter,
            //'dokter2' => $dokter2,
            'mcuNoAcc' => $mcuNoAcc,
            'verifikators' => $verifikators,
            'prediabetes' => $prediabetes,
            'diabetes' => $diabetes,
            'gulanormal' => $gulanormal,
            'totalSemuaStatus' => $totalSemuaStatus,
        ]);
    }
}
