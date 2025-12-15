<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Riyadh'   => ['Riyadh', 'Al Kharj', 'Al Majmaah', 'Wadi Ad Dawasir'],
            'Makkah'   => ['Makkah', 'Jeddah', 'Taif'],
            'Madinah'  => ['Madinah', 'Yanbu'],
            'Qassim'   => ['Buraydah', 'Unaizah', 'Riyadh Al Khabra'],
            'Eastern Region' => ['Dammam', 'Dhahran', 'Al Khobar', 'Al Ahsa'],
            'Asir'     => ['Abha', 'Khamis Mushait'],
            'Tabuk'    => ['Tabuk'],
            'Hail'     => ['Hail'],
            'Northern Borders' => ['Arar', 'Rafha'],
            'Jazan'    => ['Jazan', 'Sabya'],
            'Najran'   => ['Najran'],
            'Al Bahah' => ['Al Bahah'],
        ];

        foreach ($data as $stateName => $cities) {
            $state = State::where('name', $stateName)->first();

            if (! $state) {
                continue;
            }

            foreach ($cities as $cityName) {
                City::updateOrCreate(
                    [
                        'state_id' => $state->id,
                        'name'     => $cityName,
                    ],
                    []
                );
            }
        }
    }
}
