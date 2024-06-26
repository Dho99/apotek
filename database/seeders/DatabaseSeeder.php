<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $roleNames = array('Apoteker','Dokter','Pasien','Kasir','Administrator');
        foreach($roleNames as $role){
            \App\Models\Role::create(
                ['roleName' => $role],
            );
        }


        User::factory()->create([
            'username' => Str::slug(fake()->unique()->name()),
            'kode' => 'DOK-'.mt_rand(100,999),
            'nama' => fake()->name(),
            'gender' => 1,
            'email' => 'doktertest@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'roleId' => 2,
            'profile' => '',
            'tanggal_lahir' => fake()->dateTimeThisDecade(),
            'kategoriDokter' => 'Umum',
            'jamPraktek' => json_encode([
                'start' => now()->format('H:i'),
                'end' => now()->addHours(6)->format('H:i')
            ]),
        ]);
        User::create([
            'username' => Str::slug(fake()->unique()->userName()),
            'kode' => 'AP-'.mt_rand(100,999),
            'nama' => fake()->name(),
            'gender' => 0,
            'email' => 'admintest@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'roleId' => 5,
            'profile' => '',
            'tanggal_lahir' => fake()->dateTimeThisDecade()
        ]);

        \App\Models\Pasien::create([
            'username' => Str::slug(fake()->unique()->userName()),
            'kode' => 'PAS-'.mt_rand(100,999),
            'no_rekam_medis' => mt_rand(000001, 999999),
            'nama' => fake()->name(),
            'gender' => 0,
            'email' => 'pasientest@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'roleId' => 3,
            'profile' => '',
            'tanggal_lahir' => fake()->dateTimeThisDecade()
        ]);


    }
}
