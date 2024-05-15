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

        \App\Models\Role::create(['roleName' => 'Apoteker']);
        \App\Models\Role::create(['roleName' => 'Dokter']);
        \App\Models\Role::create(['roleName' => 'Pasien']);
        \App\Models\Role::create(['roleName' => 'Kasir']);
        \App\Models\Role::create(['roleName' => 'Administrator']);

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
            'profile' => 'default',
            'tanggal_lahir' => fake()->dateTimeThisDecade(),
            'kategoriDokter' => 'Umum',
            'jamPraktek' => json_encode([now(), now()->addDay()])
        ]);
        User::create([
            'username' => Str::slug(fake()->unique()->userName()),
            'kode' => 'AP-'.mt_rand(100,999),
            'nama' => fake()->name(),
            'gender' => 0,
            'email' => 'admintest123@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'roleId' => 4,
            'profile' => 'default',
            'tanggal_lahir' => fake()->dateTimeThisDecade()
        ]);

        \App\Models\Pasien::create([
            'username' => Str::slug(fake()->unique()->userName()),
            'kode' => 'PAS-'.mt_rand(100,999),
            'no_rekam_medis' => mt_rand(000001, 999999),
            'nama' => fake()->name(),
            'gender' => 0,
            'email' => 'admintest123@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'roleId' => 4,
            'profile' => 'default',
            'tanggal_lahir' => fake()->dateTimeThisDecade()
        ]);


    }
}
