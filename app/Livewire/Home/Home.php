<?php

namespace App\Livewire\Home;

use App\Models\Departments;
use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
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
        // $jumlahMCUFit = ModelsMcu::where('status', 'FIT')->count();
        // $jumlahMCUFitWithNote = ModelsMcu::where('status', 'FIT WITH NOTE')->count();
        // $jumlahMCUFollowUp = ModelsMcu::where('status', 'FOLLOW UP')->count();
        // $jumlahMCUnfit = ModelsMcu::where('status', 'UNFIT')->count();

        $statusCountsKaryawan = Karyawan::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['aktif', 'non aktif'])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $colorKaryawan = [
            'aktif' => 'text-bg-success',        // Hijau
            'non aktif' => 'text-bg-danger',       // Merah
        ];

        $finalKaryawanCounts = collect(['aktif', 'non aktif'])
            ->map(function ($status) use ($statusCountsKaryawan, $colorKaryawan) {
                return [
                    'status' => $status,
                    'total' => $statusCountsKaryawan[$status] ?? 0,
                    'color' => $colorKaryawan[$status] ?? 'text-bg-secondary',
                ];
            })
            ->toArray();



        $statusCounts = ModelsMcu::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['FIT', 'FIT WITH NOTE', 'FOLLOW UP', 'UNFIT'])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $colorMap = [
            'FIT' => 'text-bg-success',        // Hijau
            'FIT WITH NOTE' => 'text-bg-primary',  // Biru
            'FOLLOW UP' => 'text-bg-warning',  // Kuning
            'UNFIT' => 'text-bg-danger',       // Merah
        ];

        $finalmcuCounts = collect(['FIT', 'FIT WITH NOTE', 'FOLLOW UP', 'UNFIT'])
            ->map(function ($status) use ($statusCounts, $colorMap) {
                return [
                    'status' => $status,
                    'total' => $statusCounts[$status] ?? 0,
                    'color' => $colorMap[$status] ?? 'text-bg-secondary',
                ];
            })
            ->toArray();
        $dokter = ModelsMcu::where('verifikator', 'dokter')->count();
        $dokter2 = ModelsMcu::where('verifikator', 'dokter2')->count();

        $verifikators = User::where('role', 'dokter')
            ->where('subrole', 'verifikator')
            ->get()
            ->map(function ($verifikator) {
                $jumlahMcu = ModelsMcu::where('verifikator', $verifikator->username)->count();
                return [
                    'username' => $verifikator->username,
                    'nama' => $verifikator->name,
                    'jumlah_mcu' => $jumlahMcu,
                ];
            })
            ->toArray();
        // $mcuCountsByVerifikator = [];
        // foreach ($verifikators as $verifikator) {
        //     $mcuCount = ModelsMcu::where('verifikator_id', $verifikator->id)->count();
        //     $mcuCountsByVerifikator[] = [
        //         'verifikator_name' => $verifikator->name,
        //         'mcu_count' => $mcuCount,
        //     ];
        // }
        //temuan
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
            'mcuCounts' => $finalmcuCounts,
            'karyawanCounts' => $finalKaryawanCounts,
            'dokter' => $dokter,
            'dokter2' => $dokter2,
            'verifikators' => $verifikators,
            'prediabetes' => $prediabetes,
            'diabetes' => $diabetes,
            'gulanormal' => $gulanormal
        ]);
    }
}
