<?php

namespace App\Requests\Users;
use App\Requests\BaseRequestFormApi;

class LoginUserValidator extends BaseRequestFormApi
{
    public function rules(): array
    {
        return [
            'email'     => 'required|min:5|max:100|email',
            'password'  => 'required|min:6|max:30'
        ];
    }

    public function authorized(): bool
    {
        //default value
        return true;
    }
}