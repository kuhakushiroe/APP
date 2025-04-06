<?php

namespace Database\Seeders;

use App\Models\Departments;
use App\Models\User;
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
        // User::factory(100)->create();

        Departments::insert([
            [
                'name_department' => 'ENGINEERING',
                'description_department' => 'ENGINEERING',
            ],
            [
                'name_department' => 'PRODUKSI',
                'description_department' => 'PRODUKSI',
            ],
            [
                'name_department' => 'FLO',
                'description_department' => 'FLO',
            ],
            [
                'name_department' => 'SHE',
                'description_department' => 'SHE',
            ]
        ]);

        $departments = Departments::pluck('name_department')->toArray();

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
        // Loop untuk membuat 100 data karyawan
        DB::transaction(function () {
            $faker = Faker::create(); // Sesuaikan dengan kebutuhan

            foreach (range(1, 100) as $index) {
                $departments = Departments::pluck('name_department')->toArray();
                // Generate unique NRP & NIK
                $nrp = $faker->unique()->numerify('###########'); // 11 digit angka
                $nik = $faker->unique()->numerify('###########'); // 11 digit angka

                // Pilih subrole secara acak
                $randomSubrole = $departments[array_rand($departments)];

                // Insert data karyawan
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
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert data user terkait
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
