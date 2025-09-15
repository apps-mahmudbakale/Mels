<?php

namespace Database\Seeders;

use App\Models\Constituency;
use App\Models\Lga;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ConstituencySeeder extends Seeder
{
    public function run(): void
    {
        // Create Federal Constituency (President)
        $federal = Constituency::create([
            'name' => 'Federal Republic of Nigeria',
            'type' => 'federal',
            'state_id' => null,
        ]);
        
        // Attach all LGAs to the federal constituency
        $federal->lgas()->attach(Lga::pluck('id'));

        $states = State::with('lgas')->get();
        
        foreach ($states as $state) {
            // Create State Constituency (Governor)
            $stateConstituency = Constituency::create([
                'name' => $state->name . ' State Constituency',
                'type' => 'state',
                'state_id' => $state->id,
            ]);
            $stateConstituency->lgas()->attach($state->lgas->pluck('id'));
            
            // Create Senatorial Districts (3 per state)
            $this->createSenatorialDistricts($state);
            
            // Create Federal Constituencies (House of Reps)
            $this->createFederalConstituencies($state);
            
            // Create State House of Assembly Constituencies
            $this->createStateHouseConstituencies($state);
        }
    }
    
    protected function createSenatorialDistricts(State $state): void
    {
        $lgas = $state->lgas;
        $lgasPerDistrict = ceil($lgas->count() / 3);
        
        foreach (range(1, 3) as $districtNumber) {
            $districtLgas = $lgas->splice(0, $lgasPerDistrict);
            
            $constituency = Constituency::create([
                'name' => $state->name . ' Senatorial District ' . $districtNumber,
                'type' => 'senatorial',
                'state_id' => $state->id,
            ]);
            
            $constituency->lgas()->attach($districtLgas->pluck('id'));
        }
    }
    
    protected function createFederalConstituencies(State $state): void
    {
        $lgas = $state->lgas;
        // Approximately 3-4 LGAs per federal constituency
        $lgasPerConstituency = $lgas->count() > 10 ? 4 : 3;
        $lgas->chunk($lgasPerConstituency)
            ->each(function (Collection $lgasChunk, $index) use ($state) {
                $constituency = Constituency::create([
                    'name' => $state->name . ' Federal Constituency ' . ($index + 1),
                    'type' => 'lga',
                    'state_id' => $state->id,
                ]);
                
                $constituency->lgas()->attach($lgasChunk->pluck('id'));
            });
    }
    
    protected function createStateHouseConstituencies(State $state): void
    {
        $lgas = $state->lgas;
        
        // Create 2-3 state house constituencies per senatorial district
        $senatorialDistricts = $state->lgas->chunk(ceil($lgas->count() / 3));
        
        foreach ($senatorialDistricts as $districtIndex => $districtLgas) {
            // Create 2-3 state house constituencies per senatorial district
            $constituenciesPerDistrict = $districtLgas->count() > 5 ? 3 : 2;
            $lgasPerConstituency = ceil($districtLgas->count() / $constituenciesPerDistrict);
            
            $districtLgas->chunk($lgasPerConstituency)
                ->each(function (Collection $lgasChunk, $index) use ($state, $districtIndex) {
                    $constituency = Constituency::create([
                        'name' => $state->name . ' State Constituency ' . (($districtIndex * 3) + $index + 1),
                        'type' => 'state_house',
                        'state_id' => $state->id,
                    ]);
                    
                    $constituency->lgas()->attach($lgasChunk->pluck('id'));
                });
        }
    }
}
