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
       //give the super admin user the default preferences (all)
       User::create($this->superAdmin($preference->id));
    }   

    private function defaultPreferences()
    {
        //default preference values
        return [
            'source' => 'all',
            'category' => 'all',
            'author' => 'all',
        ];
    }

    private function superAdmin($preference)
    {
        //calling super admin user from the config file that should be set in the environment file
        return [
            'fullName'  => 'Super Admin',
            'email'     => config('app.super_admin_email'),
            'password'  => Hash::make(config('app.super_admin_password')),
            'preference_id' => $preference
        ];

    }
}
