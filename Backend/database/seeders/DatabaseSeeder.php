<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create test doctors
        for ($i = 1; $i <= 5; $i++) {
            $doctorUser = User::create([
                'name' => 'Dr. Doctor ' . $i,
                'email' => 'doctor' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);

            Doctor::create([
                'user_id' => $doctorUser->id,
                'spesialis' => ['Umum', 'Gigi', 'Mata', 'Jantung', 'Kulit'][$i - 1],
                'no_telepon' => '08' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'jam_praktik' => '08:00-17:00',
            ]);
        }

        // Create test appointments
        $doctors = Doctor::all();
        foreach ($doctors as $doctor) {
            Appointment::create([
                'user_id' => $user->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => now()->addDays(rand(1, 30)),
                'reason' => 'Check-up',
                'status' => 'pending',
            ]);
        }
    }
}
