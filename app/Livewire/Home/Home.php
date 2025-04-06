<?php

namespace App\Livewire\Home;

use App\Models\Departments;
use App\Models\Karyawan;
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

        return view('livewire.home.home', [
            'departments' => $departments,
            'assignedColors' => $assignedColors,
            'employeeCountsAktif' => $employeeCountsAktif,
            'employeeCountsNonAktif' => $employeeCountsNonAktif,
            'jumlahKaryawan' => $jumlahKaryawan,
            'jumlahKaryawanAktif' => $jumlahKaryawanAktif,
            'jumlahKaryawanNonAktif' => $jumlahKaryawanNonAktif
        ]);
    }
}
