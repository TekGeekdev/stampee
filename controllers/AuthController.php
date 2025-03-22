<?php
namespace App\Controllers;

use App\Models\User;
use App\Providers\Validator;
use App\Providers\View;

class AuthController
{
    public function login()
    {
        return View::render('auth/login');
    }

    public function store($data){
        $validator = new Validator;
        // $validator->field('username', $data['username'])->min(2)->max(50);
        // $validator->field('password', $data['password'])->min(2)->max(20);

        if ($validator->isSuccess()) {
            $user      = new User;
            $checkuser = $user->checkUser($data['username'], $data['password']);
            if ($checkuser) {
                return View::redirect('user/show');
            } else {
                $errors['message'] = 'Verifier vos identifiants!';
                return View::render('auth/login', ['errors' => $errors, 'user' => $data]);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('auth/login', ['errors' => $errors, 'user' => $data]);
        }

    }


    public function delete()
    {
        session_destroy();
        return View::redirect('login');
    }

}