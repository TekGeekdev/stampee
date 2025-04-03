<?php
namespace App\Controllers;

use App\Models\FileToUpload;
use App\Models\Stamp;
use App\Providers\View;

class AuctionController{

    public function home(){
        return View::render('auction/home');
    }

    public function index(){
        return View::render('auction/catalogue');
    }
}
