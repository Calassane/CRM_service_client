<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Urgent', 'slug' => 'urgent'],
            ['name' => 'Paiement', 'slug' => 'paiement'],
            ['name' => 'Annulation', 'slug' => 'annulation'],
            ['name' => 'Aéroport', 'slug' => 'aeroport'],
            ['name' => 'Suivi', 'slug' => 'suivi'],
            ['name' => 'VIP', 'slug' => 'vip'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(['slug' => $tag['slug']], $tag);
        }
    }
}
