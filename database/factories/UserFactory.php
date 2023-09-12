<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                'username' => fake()->unique()->userName(),
                'kode' => Str::random(3).'-'.mt_rand(100,999),
                'nama' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'telp' => fake()->unique()->phoneNumber(),
                'alamat' => fake()->address(),
                'status' => 'Aktif',
                'level' => mt_rand(0,3),
                'profile' => 'default',
                'tanggal_lahir' => fake()->dateTimeThisDecade()
        ];
    }
}
