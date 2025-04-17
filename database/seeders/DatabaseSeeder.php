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
            'LV',
            'ELF',
            'BUS',
            'CRANE',
            'LOADER KOM 480 500',
            'SANY STC 750',
            'SANY STC 60T',
            'ISUZU ELF HYVA/HB60E2',
            'HINO 260 Ti',
            'HINO 500',
            'SCANIA P380',
            'NISSAN',
            'FUSO',
            'TADANO 50 T',
            'BOMAG 211 D 40',
            'SAKAI SV 526',
            'HINO 260 Ti',
            'MERCY 2528 RMC',
            'HINO 500',
            'HINO 500',
            'SCANIA P380',
            'MERCY 2528 RMC',
            'QUESTER',
            'IVECO 6824',
            'MERCY 2528 RMC',
            'HINO 500',
            'D 85 SS',
            'D 155 A',
            'D 375 A',
            'PC 200',
            'PC 200 LA',
            'PC 200 DF',
            'PC 300',
            'PC 400',
            'PC 500',
            'PC 850',
            'PC1250',
            'PC2000',
            'CAT 395 DL',
            'HD 465',
            'HD 785',
            'CAT 777E',
            'ARTICULATED DT A 40 G',
            'DT VOLVO FMX 400(OB)',
            'DT VOLVO FMX 440(COAL)',
            'DT SCANIA P 360 (OB)',
            'DT SCANIA P460 (COAL)',
            'DT SCANIA P 410 (COAL)',
            'DT NISSAN CWB',
            'DT MERCY 4040 K',
            'DT MERCY 4845 K',
            'DT QUESTER',
            'MG 511',
            'MG 705 A',
            'MG 825 A',
            'MG 755-5R',
            'Forklift FD 50X',
            'MERLO 27-10',
            'MANITOU MHT-X 860L',
            'DT QUESTER',
            'LOWBOY SCANIA R580',
            'DRILLING MACHINE 254 KS',
            'DRILLING MACHINE 245KS',
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
