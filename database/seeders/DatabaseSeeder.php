<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createAdministrator();

        $this->call([
            PracticeAreaSeeder::class,
            AttorneySeeder::class,
            ArticleSeeder::class,
            TestimonialSeeder::class,
            ExperienceCaseSeeder::class,
            FaqSeeder::class,
            PageSeeder::class,
            SettingSeeder::class,
        ]);
    }

    /**
     * Crea el usuario del panel con las credenciales de ADMIN_EMAIL / ADMIN_PASSWORD.
     * Sin contraseña definida genera una aleatoria y la muestra una sola vez.
     */
    private function createAdministrator(): void
    {
        $email = config('despacho.admin.email');

        if (User::where('email', $email)->exists()) {
            return;
        }

        $password = config('despacho.admin.password') ?: Str::password(16, symbols: false);

        User::create([
            'name' => config('despacho.admin.name'),
            'email' => $email,
            'password' => $password,
        ]);

        if (! config('despacho.admin.password')) {
            $this->command?->warn("Usuario del panel creado: {$email}  contraseña: {$password}  (guárdela; no se volverá a mostrar).");
        }
    }
}
