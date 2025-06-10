<?php

namespace Database\Seeders;

use App\Models\Departments;
use App\Models\Jabatan;
use App\Models\Mcu;
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
            ['name_department' => 'FALOG', 'description_department' => 'FALOG'],
            ['name_department' => 'SHE', 'description_department' => 'SHE'],
            ['name_department' => 'COE', 'description_department' => 'COE'],
            ['name_department' => 'PLANT', 'description_department' => 'PLANT'],
            ['name_department' => 'HCGA', 'description_department' => 'HCGA'],
        ]);

        //Jabatan
        Jabatan::insert([
            ['nama_jabatan' => 'Kepala Divisi', 'keterangan_jabatan' => 'KADIV'],
            ['nama_jabatan' => 'Manager', 'keterangan_jabatan' => 'MNG'],
            ['nama_jabatan' => 'Supervisor', 'keterangan_jabatan' => 'SPV'],
            ['nama_jabatan' => 'Staff', 'keterangan_jabatan' => 'STF'],
            ['nama_jabatan' => 'Verifikator', 'keterangan_jabatan' => 'VER'],
            ['nama_jabatan' => 'Auditor', 'keterangan_jabatan' => 'AUD'],
            ['nama_jabatan' => 'Dokter', 'keterangan_jabatan' => 'DOK'],
            ['nama_jabatan' => 'Asisten Dokter', 'keterangan_jabatan' => 'ADOK'],

        ]);

        // User init
        User::create([
            'name' => 'SHE',
            'username' => 'she',
            'email' => 'she@example.com',
            'role' => 'she',
            'subrole' => '',
            'password' => Hash::make('password'),
        ]);
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
            'subrole' => 'verifikator',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'paramedik',
            'username' => 'paramedik',
            'email' => 'paramedik@example.com',
            'role' => 'dokter',
            'subrole' => 'paramedik',
            'password' => Hash::make('password'),
        ]);

        // Helper untuk tanggal expired (bisa 1 tahun ke depan atau belakang)
        function randomExpireDate()
        {
            //$direction = rand(0, 1) ? 'add' : 'sub';
            $direction = rand(0, 1) ? 'add' : 'add';
            return Carbon::now()->$direction('1 year')->format('Y-m-d');
        }



        // Array kendaraan untuk versatility
        // $kendaraanList = [
        //     'LV',
        //     'ELF',
        //     'BUS',
        //     'CRANE',
        //     'LOADER KOM 480 500',
        //     'SANY STC 750',
        //     'SANY STC 60T',
        //     'ISUZU ELF HYVA/HB60E2',
        //     'SCANIA P380 CRANE',
        //     'NISSAN',
        //     'FUSO',
        //     'TADANO 50 T',
        //     'BOMAG 211 D 40',
        //     'SAKAI SV 526',
        //     'HINO 260 Ti LUBE',
        //     'HINO 260 Ti CRANE',
        //     'MERCY 2528 RMC LUBE',
        //     'SCANIA P380 WATER',
        //     'MERCY 2528 RMC WATER',
        //     'QUESTER',
        //     'IVECO 6824',
        //     'MERCY 2528 RMC FUEL',
        //     'HINO 500 WATER',
        //     'HINO 500 LUBE',
        //     'HINO 500 FUEL',
        //     'HINO 500 CRANE',
        //     'D 85 SS',
        //     'D 155 A',
        //     'D 375 A',
        //     'PC 200',
        //     'PC 200 LA',
        //     'PC 200 DF',
        //     'PC 300',
        //     'PC 400',
        //     'PC 500',
        //     'PC 850',
        //     'PC1250',
        //     'PC2000',
        //     'CAT 395 DL',
        //     'HD 465',
        //     'HD 785',
        //     'CAT 777E',
        //     'ARTICULATED DT A 40 G',
        //     'DT VOLVO FMX 400(OB)',
        //     'DT VOLVO FMX 440(COAL)',
        //     'DT SCANIA P 360 (OB)',
        //     'DT SCANIA P460 (COAL)',
        //     'DT SCANIA P 410 (COAL)',
        //     'DT NISSAN CWB',
        //     'DT MERCY 4040 K',
        //     'DT MERCY 4845 K',
        //     'DT QUESTER DUMP TRUCK',
        //     'MG 511',
        //     'MG 705 A',
        //     'MG 825 A',
        //     'MG 755-5R',
        //     'Forklift FD 50X',
        //     'MERLO 27-10',
        //     'MANITOU MHT-X 860L',
        //     'DT QUESTER OTHERS',
        //     'LOWBOY SCANIA R580',
        //     'DRILLING MACHINE 254 KS',
        //     'DRILLING MACHINE 245KS',
        // ];
        $kendaraanList = [
            ['type_versatility' => 'Bulldozer', 'lama' => 'D 155 A', 'revisi' => 'D155A'],
            ['type_versatility' => 'Bulldozer', 'lama' => 'D 85 SS', 'revisi' => 'D85ESS'],
            ['type_versatility' => 'Compactor', 'lama' => 'CM SAKAI SV526D', 'revisi' => 'CM SV526D'],
            ['type_versatility' => 'Compactor', 'lama' => 'CM SAKAI SV700', 'revisi' => 'CM SV700TF'],
            ['type_versatility' => 'Crane Truck', 'lama' => 'CT MERCY 2528', 'revisi' => 'CT A2528'],
            ['type_versatility' => 'Crane Truck', 'lama' => 'TD.01 GR500', 'revisi' => 'CT GR500'],
            ['type_versatility' => 'Elf', 'lama' => 'ELF', 'revisi' => 'ELF'],
            ['type_versatility' => 'Excavator', 'lama' => 'PC210', 'revisi' => 'EX PC210'],
            ['type_versatility' => 'Excavator', 'lama' => 'PC210 LA', 'revisi' => 'EX PC210 LA'],
            ['type_versatility' => 'Excavator', 'lama' => 'PC1250', 'revisi' => 'EX PC1250'],
            ['type_versatility' => 'Excavator', 'lama' => 'PC2000', 'revisi' => 'EX PC2000'],
            ['type_versatility' => 'Excavator', 'lama' => 'PC500', 'revisi' => 'EX PC500'],
            ['type_versatility' => 'Excavator', 'lama' => 'EX395', 'revisi' => 'EX Cat395'],
            ['type_versatility' => 'Excavator Drag Flow', 'lama' => 'PC200DF', 'revisi' => 'DF PC210'],
            ['type_versatility' => 'Excavator', 'lama' => 'EX 320D LA', 'revisi' => 'EX 320 LA'],
            ['type_versatility' => 'Forklift', 'lama' => 'FD 50', 'revisi' => 'FL FD50'],
            ['type_versatility' => 'Fuel Truck', 'lama' => 'FT AXOR 2528', 'revisi' => 'FT A2528'],
            ['type_versatility' => 'Heavy Dump Truck', 'lama' => 'HD785', 'revisi' => 'HD785'],
            ['type_versatility' => 'Heavy Dump Truck', 'lama' => 'CAT777', 'revisi' => 'Cat777'],
            ['type_versatility' => 'Light Dump Truck', 'lama' => 'DT SCANIA P410', 'revisi' => 'DT P410'],
            ['type_versatility' => 'Light Dump Truck', 'lama' => 'DT MERCY 2528', 'revisi' => 'DT A2528'],
            ['type_versatility' => 'Light Dump Truck', 'lama' => 'DT MERCY 4845', 'revisi' => 'DT A4845'],
            ['type_versatility' => 'Light Dump Truck', 'lama' => 'DT FMX400', 'revisi' => 'DT FMX400'],
            ['type_versatility' => 'Light Dump Truck', 'lama' => 'DT IVECO 440', 'revisi' => 'DT IVC440'],
            ['type_versatility' => 'Light Vehice', 'lama' => '', 'revisi' => 'LV'],
            ['type_versatility' => 'Light Vehice', 'lama' => '', 'revisi' => 'LV Ambulance'],
            ['type_versatility' => 'Lube Truck', 'lama' => 'LT MERCY 2528', 'revisi' => 'LT A2528'],
            ['type_versatility' => 'Motor Grader', 'lama' => 'GD755-5R', 'revisi' => 'GD755'],
            ['type_versatility' => 'Motor Grader', 'lama' => 'GD825', 'revisi' => 'GD825'],
            ['type_versatility' => 'Prime Mover', 'lama' => 'DT IVECO 6454 ( SINGLE TRAILER )', 'revisi' => 'PM-STT IVC6454'],
            ['type_versatility' => 'Prime Mover', 'lama' => 'DT IVECO 6454 ( DOUBLE TRAILER )', 'revisi' => 'PM-DTT IVC6454'],
            ['type_versatility' => 'Prime Mover', 'lama' => 'MB4058S ( DOUBLE TRAILER )', 'revisi' => 'PM-DTT A4058S'],
            ['type_versatility' => 'Tele handler', 'lama' => 'MANITOU', 'revisi' => 'TH Manitou'],
            ['type_versatility' => 'Telehandler', 'lama' => 'DIECI SAMSON', 'revisi' => 'TH Dieci Samson'],
            ['type_versatility' => 'Telehandler', 'lama' => 'DIECI HERCULES', 'revisi' => 'TH Dieci Hercules'],
            ['type_versatility' => 'Truck Support', 'lama' => 'TS Mitsubishi', 'revisi' => 'TS Canter'],
            ['type_versatility' => 'Water Truck', 'lama' => 'WT AXOR 2528', 'revisi' => 'WT A2528'],
            ['type_versatility' => 'Water Truck', 'lama' => 'WT CAT773', 'revisi' => 'WT Cat773'],
            ['type_versatility' => 'Wheel Loader', 'lama' => 'WL 988K', 'revisi' => 'WL Cat988K'],
            ['type_versatility' => 'Wheel Loader', 'lama' => 'WL 992K', 'revisi' => 'WL Cat992K'],
            ['type_versatility' => 'Wheel Loader', 'lama' => 'WA 900', 'revisi' => 'WL WA900'],
        ];
        foreach ($kendaraanList as $item) {
            DB::table('versatility')->insert([
                'type_versatility' => $item['type_versatility'],
                'versatility' => $item['revisi'],
                'code_versatility' => $item['revisi'],
            ]);
        }

        // Insert 100 karyawan
        DB::transaction(function () use ($kendaraanList) {
            $faker = Faker::create('id_ID');
            $departments = Departments::pluck('name_department')->toArray();

            foreach (range(1, 100) as $index) {
                // Unique NIK dan NRP
                $nrp = $faker->unique()->numerify('###########');
                $nik = $faker->unique()->numerify('###########');

                // Subrole acak dari daftar departemen
                $randomSubrole = $departments[array_rand($departments)];

                // Ambil 3-5 kendaraan acak, pisahkan dengan koma
                $selectedKendaraan = collect($kendaraanList)->pluck('revisi')->random(rand(3, 5))->implode(', ');

                // Data karyawan
                $rawJabatan = $faker->jobTitle;
                $cleanJabatan = preg_replace('/[^a-zA-Z0-9\s]/', '', $rawJabatan);
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
                    'jabatan' => $cleanJabatan,
                    'no_hp' => $faker->phoneNumber,
                    'alamat' => $faker->address,
                    'domisili' => $faker->randomElement(['lokal', 'non lokal']),
                    'status' => $faker->randomElement(['aktif', 'non aktif']),
                    'versatility' => $selectedKendaraan,
                    'exp_id' => randomExpireDate(),
                    'exp_kimper' => randomExpireDate(),
                    'exp_mcu' => randomExpireDate(),
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


        $verifikators = User::where('subrole', 'verifikator')->pluck('username')->toArray();
        $nrps = DB::table('karyawans')->pluck('nrp')->toArray();
        $faker = Faker::create('id_ID');

        $mcuRecords = [];
        $idCounter = 1;
        $followUpMap = [];

        for ($i = 0; $i < 300; $i++) {
            $id_karyawan = $nrps[array_rand($nrps)];
            $status = $faker->randomElement(['FIT', 'UNFIT', 'TEMPORARY UNFIT', 'FOLLOW UP']);

            $record = [
                'id' => $idCounter,
                'sub_id' => null,
                'id_karyawan' => $id_karyawan,
                'status' => $status,
                'tgl_mcu' => $faker->dateTimeBetween('-1 days', 'now'),
                'exp_mcu' => in_array($status, ['FIT', 'UNFIT', 'TEMPORARY UNFIT']) ? $faker->dateTimeBetween('+30 days', '+60 days') : null,
                'tgl_verifikasi' => $faker->dateTimeBetween('-1 days', 'now'),
                'verifikator' => $verifikators[array_rand($verifikators)],
                'status_' => $status === 'FOLLOW UP' ? 'open' : 'close',
                'gdp' => rand(80, 100),
                'gd_2_jpp' => rand(100, 110),
            ];

            $mcuRecords[] = $record;

            if ($status === 'FOLLOW UP') {
                $followUpMap[] = [
                    'id' => $idCounter,
                    'id_karyawan' => $id_karyawan,
                ];
            }

            $idCounter++;
        }

        // Tambahkan hasil verifikasi untuk FOLLOW UP
        foreach ($followUpMap as $follow) {
            $status = $faker->randomElement(['FIT', 'UNFIT', 'TEMPORARY UNFIT', 'FOLLOW UP']);

            $record = [
                'id' => $idCounter,
                'sub_id' => $follow['id'],
                'id_karyawan' => $follow['id_karyawan'],
                'status' => $status,
                'tgl_mcu' => $faker->dateTimeBetween('now', '+2 days'),
                'exp_mcu' => in_array($status, ['FIT', 'UNFIT', 'TEMPORARY UNFIT']) ? $faker->dateTimeBetween('+30 days', '+60 days') : null,
                'tgl_verifikasi' => $faker->dateTimeBetween('now', '+1 days'),
                'verifikator' => $verifikators[array_rand($verifikators)],
                'status_' => $status === 'FOLLOW UP' ? 'open' : 'close',
                'gdp' => rand(80, 100),
                'gd_2_jpp' => rand(100, 110),
            ];

            $mcuRecords[] = $record;

            // Tutup record awal jika hasil akhir bukan FOLLOW UP
            if ($status !== 'FOLLOW UP') {
                foreach ($mcuRecords as &$mcu) {
                    if ($mcu['id'] === $follow['id']) {
                        $mcu['status_'] = 'close';
                        break;
                    }
                }
            }

            $idCounter++;
        }

        DB::table('mcu')->insert($mcuRecords);
    }
}
