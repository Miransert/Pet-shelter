<?php

namespace Database\Seeders;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach($users as $user)
        {
            Adoption::factory(rand(3,6))->create([
                'adopted_by' => $user->id
            ]);
        }
    }
}
