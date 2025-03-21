<?php
namespace App\Controllers;

use App\Models\User;
use App\Providers\View;

// use App\Providers\Auth;

class UserController
{
    public function create()
    {
        return View::render('user/create');
    }

    public function store($data)
    {
        $user                 = new User;
        $data['username']     = $data["email"];
        $data["privilege_id"] = '0';
        $insert               = $user->insert($data);
        if ($insert) {
            return view::redirect('user/show');
        } else {
            return View::render('error', ['msg' => 'impossible de saisir l\'utilisateur']);
        }
    }

    public function show()
    {
        $get = ! empty($get) ? $get : $_GET;
        if (isset($get['id']) && $get['id'] != null) {
            $user = new User;
            if ($selectUser = $user->selectId($get['id'])) {
                return View::render('user/show', ['user' => $selectUser]);
            } else {
                return View::render('error', ['msg' => 'L\'utilisateur n\'existe pas']);
            }
        } else{
            return View::render('error', ['msg' => 'Inscrivez-vous!']);
        }
    }
}
