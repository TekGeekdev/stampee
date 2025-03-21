<?php
namespace App\Controllers;

// use App\Models\User;
use App\Providers\View;

// use App\Providers\Auth;

class UserController
{
    public function create()
    {
        return View::render('user/create');
    }

}
