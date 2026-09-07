<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['Aminata', 'Coulibaly', 'aminata.coulibaly@example.ci', '+225 07 01 10 20 30', 'Cocody Angré', 'Abidjan'],
            ['Yannick', 'Kouamé', 'yannick.kouame@example.ci', '+225 05 02 11 21 31', 'Marcory Zone 4', 'Abidjan'],
            ['Fatou', 'Diabaté', 'fatou.diabate@example.ci', '+225 01 03 12 22 32', 'Riviera Palmeraie', 'Abidjan'],
            ['Serge', 'N’Guessan', 'serge.nguessan@example.ci', '+225 07 04 13 23 33', 'Deux-Plateaux Vallon', 'Abidjan'],
            ['Nadia', 'Bamba', 'nadia.bamba@example.ci', '+225 05 05 14 24 34', 'Quartier France', 'Grand-Bassam'],
            ['Ibrahim', 'Touré', 'ibrahim.toure@example.ci', '+225 01 06 15 25 35', 'Bingerville Centre', 'Bingerville'],
            ['Grâce', 'Yapi', 'grace.yapi@example.ci', '+225 07 07 16 26 36', 'Cité Alabra', 'Cocody'],
            ['Mamadou', 'Konaté', 'mamadou.konate@example.ci', '+225 05 08 17 27 37', 'Abobo Baoulé', 'Abidjan'],
            ['Estelle', 'Amani', 'estelle.amani@example.ci', '+225 01 09 18 28 38', 'Résidentiel', 'Anyama'],
            ['Arnaud', 'Koffi', 'arnaud.koffi@example.ci', '+225 07 10 19 29 39', 'Port-Bouët Centre', 'Abidjan'],
            ['Marième', 'Fofana', 'marieme.fofana@example.ci', '+225 05 11 20 30 40', 'Treichville Avenue 8', 'Abidjan'],
            ['Jean-Marc', 'Assi', 'jean-marc.assi@example.ci', '+225 01 12 21 31 41', 'Yopougon Niangon', 'Abidjan'],
            ['Aïcha', 'Ouattara', 'aicha.ouattara@example.ci', '+225 07 13 22 32 42', 'Koumassi Remblais', 'Abidjan'],
            ['Patrick', 'Zadi', 'patrick.zadi@example.ci', '+225 05 14 23 33 43', 'Riviera Bonoumin', 'Abidjan'],
            ['Clarisse', 'Dago', 'clarisse.dago@example.ci', '+225 01 15 24 34 44', 'Zone industrielle', 'Vridi'],
        ];

        foreach ($clients as [$firstName, $lastName, $email, $phone, $address, $city]) {
            Client::query()->updateOrCreate(
                ['phone' => $phone],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'address' => $address,
                    'city' => $city,
                ],
            );
        }
    }
}
