<?php

namespace App\Services;
use App\Models\User;
use Hash;

class UserService
{
    public function createUser(array $data):User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
}

