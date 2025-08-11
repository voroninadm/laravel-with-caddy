<?php

namespace Database\Seeders;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::find(1);
        $sectors = Sector::all()->pluck('id')->toArray();
        $admin->sectors()->sync($sectors);

        $users = User::all()->except(1);

        foreach ($users as $user) {
            if($user->hasRole('user')) {
                $user->sectors()->sync([1]);
            }
        }
    }
}
