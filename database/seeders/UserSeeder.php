<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            "email" => "mahadi@example.com",
            "name" => "Mahadi Dwi Nugraha",
            "password" => Hash::make("mahadi123"),
            "role" => "mahasiswa"
        ]);
    }
}
