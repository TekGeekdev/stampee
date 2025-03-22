<?php
namespace App\Controllers;

use App\Models\User;
use App\Providers\Auth;
use App\Providers\View;

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
        $data['password']     = $user->hashPassword($data['password'], 15);
        $insert               = $user->insert($data);
        if ($insert) {
            return view::redirect('user/show');
        } else {
            return View::render('error', ['msg' => 'impossible de saisir l\'utilisateur']);
        }
    }

    public function show()
    {
        if (Auth::session()) {
            if (isset($_SESSION['user_id'])) {
                $user = new User;
                if ($selectUser = $user->selectId($_SESSION['user_id'])) {
                    return View::render('user/show', ['user' => $selectUser]);
                } else {
                    return View::render('error', ['msg' => 'L\'utilisateur n\'existe pas']);
                }
            } else {
                return view::redirect('login');
            }
        }
    }

    public function edit()
    {
        if (Auth::session()) {
            if (isset($_SESSION['user_id'])) {
                $user = new User;
                if ($selectId = $user->selectId($_SESSION['user_id'])) {
                    return View::render('user/edit', ['user' => $selectId]);
                } else {
                    return View::render('error', ['msg' => 'L\'utilisateur n\'existe pas']);
                }
            } else {
                return view::redirect('login');
            }
        } else {
            return view::redirect('login');
        }
    }

    public function update($data)
    {

        // TODO: faire validation avec clause comme password dessous
        if (Auth::session()) {

            $user = new User;
            if (! empty(trim($data["password"])) && strlen($data["password"]) >= 5) {
                $data['password'] = $user->hashPassword($data['password'], 15);
            } else {
                unset($data['password']);
            }

            $update = $user->update($data, $_SESSION['user_id']);
            if ($update) {
                return view::redirect('user/show');
            } else {
                return View::render('error', ['msg' => 'Impossible d\'appliquer les modifications']);
            }
        }
    }
}
