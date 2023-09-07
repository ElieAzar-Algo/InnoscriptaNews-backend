<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Preference;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialValues extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $preference = Preference::create($this->defaultPreferences());
       User::create($this->superAdmin($preference->id));

    }   

    private function defaultPreferences()
    {
        return [
            'source' => 'all',
            'category' => 'all',
            'author' => 'all',
        ];
    }

    private function superAdmin($preference)
    {

        return [
            'fullName'  => 'Super Admin',
            'email'     => 'superAdmin@mail.com',
            'password'  =>  Hash::make('P@ssw0rd'),
            'preference_id' => $preference
        ];

    }
}
