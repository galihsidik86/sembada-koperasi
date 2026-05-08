<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::firstOrCreate(
            ['email' => 'admin@sembada.test'],
            [
                'name'     => 'Admin Sembada',
                'password' => Hash::make('sembada123'),
                'role'     => 'admin',
            ]
        );

        $this->command->info("User: id={$u->id}, email={$u->email}, role={$u->role}");
    }
}
