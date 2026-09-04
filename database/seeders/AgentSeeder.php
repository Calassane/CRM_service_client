<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            ['name' => 'Agent Démo', 'email' => 'demo@bollirental.africa'],
            ['name' => 'Awa Koné', 'email' => 'awa.kone@bollirental.africa'],
            ['name' => 'Mariam Traoré', 'email' => 'mariam.traore@bollirental.africa'],
            ['name' => 'Koffi Yao', 'email' => 'koffi.yao@bollirental.africa'],
        ];

        foreach ($agents as $agent) {
            User::updateOrCreate(
                ['email' => $agent['email']],
                [
                    'name' => $agent['name'],
                    'password' => 'BolliDemo2026!',
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
