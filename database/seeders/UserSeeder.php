<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@teste.com',
            'password' => bcrypt('12345'),
        ]);

        $token = $admin->createToken('AdminToken')->plainTextToken;

        Storage::disk('local')->put('admin_token.txt', $token);
        $this->command->info("
            \nAdmin Token saved to storage/app/private/admin_token.txt
            \nAuthorization: Bearer {$token}\n
        ");

        User::factory(10)->create();
    }
}