<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class LGASeeder extends Seeder
{
    public function run(): void
    {
        $states = State::all();
        
        $lgas = [
            'Lagos' => ['Lagos Island', 'Lagos Mainland', 'Ikeja', 'Epe', 'Ikorodu', 'Badagry', 'Alimosho', 'Kosofe', 'Mushin', 'Ojo'],
            'Oyo' => ['Ibadan North', 'Ibadan North East', 'Ibadan North West', 'Ibadan South East', 'Ibadan South West', 'Ibarapa Central', 'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo'],
            'Abuja' => ['Abuja Municipal', 'Gwagwalada', 'Kuje', 'Bwari', 'Kwali', 'Abaji']
        ];

        foreach ($states as $state) {
            if (isset($lgas[$state->name])) {
                foreach ($lgas[$state->name] as $lgaName) {
                    $state->lgas()->create(['name' => $lgaName]);
                }
            }
        }
    }
}
