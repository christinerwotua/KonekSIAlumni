<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'mahasiswa',
        ];
    }

    /** Akun mahasiswa lengkap dengan profil mahasiswa. */
    public function mahasiswa(): static
    {
        return $this->state(['role' => 'mahasiswa'])->afterCreating(function ($user) {
            $user->mahasiswaProfile()->create([
                'nim' => (string) fake()->unique()->numerify('50262#####'),
                'study_program' => 'Sistem Informasi',
            ]);
        });
    }

    /** Akun alumni lengkap dengan profil alumni. */
    public function alumni(): static
    {
        return $this->state(['role' => 'alumni'])->afterCreating(function ($user) {
            $user->alumniProfile()->create([
                'graduation_year' => 2020,
                'company' => 'PT Contoh',
                'job_title' => 'Data Analyst',
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
