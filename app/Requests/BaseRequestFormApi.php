<?php

namespace App\Requests;

use Illuminate\Http\Request;
use Validator;

abstract class BaseRequestFormApi
{
    protected $_request;
    private $status=true;
    private $errors=[];
    abstract public function rules(): array;

    public function __construct(Request $request = null, $forceDie = true)
    {
        if (!is_null($request)) 
        {
            $this->_request = $request;
            $rules = $this->rules();

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) 
            {
                if ($forceDie) 
                {
                    $this->status = false;
                    $this->errors = $validator->errors()->toArray();
                }
                else
                {
                    $this->status = false;
                    $this->errors = $validator->errors()->toArray();
                }
            }
        }
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setRequest($request)
    {
        $this->_request=$request;
    }

    public function request()
    {
        return $this->_request;
    }
}