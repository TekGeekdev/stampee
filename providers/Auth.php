<?php
namespace App\Providers;

use App\Providers\View;

class Auth
{
    public static function session()
    {
        if (isset($_SESSION['fingerPrint']) and $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])) {
            return true;
        } else {
            return view::redirect('login');
        }
    }

    public static function privilege($id)
    {
        if ($_SESSION['privilege_id'] == $id) {
            return true;
        } else {
            return view::redirect('utilisateur/show');
            exit();
        }
    }

}
