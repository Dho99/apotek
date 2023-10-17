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
        User::factory()->create([
            'username' => Str::slug(fake()->unique()->name()),
            'kode' => 'DOK-'.mt_rand(100,999),
            'nama' => fake()->name(),
            'email' => 'doktertest@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'level' => 0,
            'profile' => 'default',
            'tanggal_lahir' => fake()->dateTimeThisDecade()
        ]);
        User::create([
            'username' => Str::slug(fake()->unique()->userName()),
            'kode' => 'AP-'.mt_rand(100,999),
            'nama' => fake()->name(),
            'email' => 'apotekertest123@example.org',
            'password' => bcrypt('password'),
            'telp' => fake()->unique()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'Aktif',
            'level' => 1,
            'profile' => 'default',
            'tanggal_lahir' => fake()->dateTimeThisDecade()
        ]);

    }
}
