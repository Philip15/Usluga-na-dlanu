<?php

namespace App\Controllers;

class UserContoller extends BaseController
{
    public function index()
    {
        return view('User');
    }
}