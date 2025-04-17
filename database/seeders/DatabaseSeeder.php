<?php

namespace Database\Seeders;

use App\Models\Departments;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Departments
        Departments::insert([
            ['name_department' => 'ENGINEERING', 'description_department' => 'ENGINEERING'],
            ['name_department' => 'PRODUKSI', 'description_department' => 'PRODUKSI'],
            ['name_department' => 'FLO', 'description_department' => 'FLO'],
            ['name_department' => 'SHE', 'description_department' => 'SHE'],
        ]);

        // User init
        User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'subrole' => 'SHE',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'Superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'role' => 'superadmin',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'dokter 1',
            'username' => 'dokter',
            'email' => 'dokter1@example.com',
            'role' => 'dokter',
            'subrole' => 'verifikator',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'dokter 2',
            'username' => 'dokter2',
            'email' => 'dokter2@example.com',
            'role' => 'dokter',
            'subrole' => 'skd',
            'password' => Hash::make('password'),
        ]);

        // Helper untuk tanggal expired (bisa 1 tahun ke depan atau belakang)
        function randomExpireDate()
        {
            $direction = rand(0, 1) ? 'add' : 'sub';
            return Carbon::now()->$direction('1 year')->format('Y-m-d');
        }

        // Array kendaraan untuk versatility
        $kendaraanList = [
            'HINO 500',
            'SCANIA P380',
            'MERCY 2528 RMC',
            'QUESTER',
            'IVECO 6824',
            'D85 SS',
            'D155 A',
            'D375 A',
            'PC200',
            'PC200 LA',
            'PC200 DF',
            'PC300',
            'PC400',
            'PC500',
            'PC850',
            'PC1250',
            'PC2000',
            'CAT 395 DL',
            'HD 465',
            'HD 785',
            'CAT 777/E'
        ];

        // Insert 100 karyawan
        DB::transaction(function () use ($kendaraanList) {
            $faker = Faker::create();
            $departments = Departments::pluck('name_department')->toArray();

            foreach (range(1, 100) as $index) {
                // Unique NIK dan NRP
                $nrp = $faker->unique()->numerify('###########');
                $nik = $faker->unique()->numerify('###########');

                // Subrole acak dari daftar departemen
                $randomSubrole = $departments[array_rand($departments)];

                // Ambil 3-5 kendaraan acak, pisahkan dengan koma
                $selectedKendaraan = collect($kendaraanList)->random(rand(3, 5))->implode(', ');

                // Data karyawan
                DB::table('karyawans')->insert([
                    'nik' => $nik,
                    'nrp' => $nrp,
                    'doh' => $faker->date('Y-m-d'),
                    'tgl_lahir' => $faker->date('Y-m-d'),
                    'nama' => $faker->name,
                    'jenis_kelamin' => $faker->randomElement(['laki-laki', 'perempuan']),
                    'tempat_lahir' => $faker->city,
                    'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                    'gol_darah' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                    'status_perkawinan' => $faker->randomElement(['menikah', 'belum menikah']),
                    'perusahaan' => $faker->company,
                    'kontraktor' => $faker->company,
                    'dept' => $randomSubrole,
                    'jabatan' => $faker->jobTitle,
                    'no_hp' => $faker->phoneNumber,
                    'alamat' => $faker->address,
                    'domisili' => $faker->randomElement(['lokal', 'non lokal']),
                    'status' => $faker->randomElement(['aktif', 'non aktif']),
                    'versatility' => $selectedKendaraan,
                    'exp_id' => randomExpireDate(),
                    'exp_kimper' => randomExpireDate(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Data user terkait
                User::create([
                    'name' => $faker->name,
                    'username' => $nrp,
                    'email' => $faker->unique()->safeEmail,
                    'role' => 'karyawan',
                    'subrole' => $randomSubrole,
                    'password' => Hash::make('password'),
                ]);
            }
        });
    }
}
