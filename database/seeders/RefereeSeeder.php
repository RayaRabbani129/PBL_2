<?php

namespace Database\Seeders;

use App\Models\Referee;
use Illuminate\Database\Seeder;

class RefereeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $referees = [
            [
                'name' => 'Bambang Sutrisno',
                'phone' => '081234567890',
                'email' => 'bambang@referee.com',
                'experience_years' => 8,
                'certification_level' => 'professional',
                'hourly_rate' => 200000,
                'is_available' => true,
                'city' => 'Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'rating' => 4.8,
                'total_matches_refereed' => 45,
            ],
            [
                'name' => 'Ahmad Hidayat',
                'phone' => '081234567891',
                'email' => 'ahmad@referee.com',
                'experience_years' => 6,
                'certification_level' => 'advanced',
                'hourly_rate' => 150000,
                'is_available' => true,
                'city' => 'Jakarta',
                'latitude' => -6.2095,
                'longitude' => 106.8467,
                'rating' => 4.5,
                'total_matches_refereed' => 32,
            ],
            [
                'name' => 'Rinto Harahap',
                'phone' => '081234567892',
                'email' => 'rinto@referee.com',
                'experience_years' => 5,
                'certification_level' => 'intermediate',
                'hourly_rate' => 120000,
                'is_available' => true,
                'city' => 'Jakarta',
                'latitude' => -6.2100,
                'longitude' => 106.8480,
                'rating' => 4.3,
                'total_matches_refereed' => 18,
            ],
            [
                'name' => 'Sudi Prawoto',
                'phone' => '081234567893',
                'email' => 'sudi@referee.com',
                'experience_years' => 7,
                'certification_level' => 'professional',
                'hourly_rate' => 180000,
                'is_available' => true,
                'city' => 'Bandung',
                'latitude' => -6.9271,
                'longitude' => 107.6411,
                'rating' => 4.7,
                'total_matches_refereed' => 38,
            ],
            [
                'name' => 'Eka Prasetya',
                'phone' => '081234567894',
                'email' => 'eka@referee.com',
                'experience_years' => 4,
                'certification_level' => 'intermediate',
                'hourly_rate' => 100000,
                'is_available' => true,
                'city' => 'Bandung',
                'latitude' => -6.9280,
                'longitude' => 107.6420,
                'rating' => 4.2,
                'total_matches_refereed' => 12,
            ],
            [
                'name' => 'Dion Prayogo',
                'phone' => '081234567895',
                'email' => 'dion@referee.com',
                'experience_years' => 3,
                'certification_level' => 'basic',
                'hourly_rate' => 80000,
                'is_available' => true,
                'city' => 'Surabaya',
                'latitude' => -7.2506,
                'longitude' => 112.7508,
                'rating' => 3.8,
                'total_matches_refereed' => 8,
            ],
        ];

        foreach ($referees as $referee) {
            Referee::create($referee);
        }
    }
}
