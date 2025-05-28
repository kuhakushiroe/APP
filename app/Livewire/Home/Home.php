<?php

namespace App\Livewire\Home;

use App\Models\Departments;
use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use App\Models\User;
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
        $jumlahMCUFit = ModelsMcu::where('status', 'FIT')->count();
        $jumlahMCUFitWithNote = ModelsMcu::where('status', 'FIT WITH NOTE')->count();
        $jumlahMCUFollowUp = ModelsMcu::where('status', 'FOLLOW UP')->count();
        $jumlahMCUnfit = ModelsMcu::where('status', 'UNFIT')->count();
        $dokter = ModelsMcu::where('verifikator', 'dokter')->count();
        $dokter2 = ModelsMcu::where('verifikator', 'dokter2')->count();

        $verifikators = User::where('role', 'dokter')
            ->where('subrole', 'verifikator')
            ->get(['id', 'name']);



        // $mcuCountsByVerifikator = [];
        // foreach ($verifikators as $verifikator) {
        //     $mcuCount = ModelsMcu::where('verifikator_id', $verifikator->id)->count();
        //     $mcuCountsByVerifikator[] = [
        //         'verifikator_name' => $verifikator->name,
        //         'mcu_count' => $mcuCount,
        //     ];
        // }



        return view('livewire.home.home', [
            'departments' => $departments,
            'assignedColors' => $assignedColors,
            'employeeCountsAktif' => $employeeCountsAktif,
            'employeeCountsNonAktif' => $employeeCountsNonAktif,
            'jumlahKaryawan' => $jumlahKaryawan,
            'jumlahKaryawanAktif' => $jumlahKaryawanAktif,
            'jumlahKaryawanNonAktif' => $jumlahKaryawanNonAktif,
            'jumlahMCUFit' => $jumlahMCUFit,
            'jumlahMCUFitWithNote' => $jumlahMCUFitWithNote,
            'jumlahMCUFollowUp' => $jumlahMCUFollowUp,
            'jumlahMCUnfit' => $jumlahMCUnfit,
            'dokter' => $dokter,
            'dokter2' => $dokter2,
            'verifikators' => $verifikators


        ]);
    }
}
