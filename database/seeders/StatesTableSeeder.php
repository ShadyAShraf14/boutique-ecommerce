<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\Country;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::where('code', 'SA')->firstOrFail();

        $states = [
            'Riyadh',
            'Makkah',
            'Madinah',
            'Qassim',
            'Eastern Region',
            'Asir',
            'Tabuk',
            'Hail',
            'Northern Borders',
            'Jazan',
            'Najran',
            'Al Bahah',
        ];

        foreach ($states as $name) {
            State::updateOrCreate(
                [
                    'country_id' => $country->id,
                    'name'       => $name,
                ],
                []
            );
        }
    }
}
