<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RolesAndPermissionsSeeder::class);


        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Main Admin',
                'password' => bcrypt('password123'), // غيريه بعدين
            ]
        );

        $admin->assignRole('admin');

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            ShippingMethodSeeder::class,
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,


        ]);
    }
}
