<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    protected array $users = [
        ['name' => 'Armando Calderón', 'email' => 'armando@gmail.com'],
        ['name' => 'Guillermo Rodriguez', 'email' => 'guillermo.rodriguez@banpay.com'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->users as $data) {
            User::firstOrCreate($data, ['password' => Hash::make('password')])->assignRole('admin');
        }
    }
}
