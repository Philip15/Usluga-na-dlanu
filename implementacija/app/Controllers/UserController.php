<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index() 
    {
        echo 'Jana';
    }

    public function OPlogout() 
    {
        $this->session->destroy();
        return redirect()->to(base_url());
    }

}