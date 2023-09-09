<?php

namespace App\Requests\Users;
use App\Requests\BaseRequestFormApi;

class CreateUserValidator extends BaseRequestFormApi
{
    public function rules(): array
    {
        return [
            'fullName'  => 'required|min:3|max:50',
            'email'     => 'required|min:5|max:100|email|unique:users,email',
            'password'  => 'required|min:6|max:30|confirmed'
        ];
    }

    public function authorized(): bool
    {
        //default value
        return true;
    }
}