<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function OPlogout() 
    {
        $this->session->destroy();
        return self::safeRedirectBack();
    }
}