<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create();
        User::factory()->films()->create();
        User::factory()->people()->create();
        User::factory()->locations()->create();
        User::factory()->species()->create();
        User::factory()->vehicles()->create();
    }
}
