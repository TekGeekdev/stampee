<?php
namespace App\Controllers;

use App\Models\Stamp;
use App\Models\User;
use App\Models\Color;
use App\Models\Conditions;
use App\Models\CountryOrigin;
use App\Models\FileToUpload;

use App\Providers\Auth;
use App\Providers\Validator;
use App\Providers\View;

class StampController{
    public function create(){
        $color = new Color;
        $selectColors = $color->select();

        $conditions = new Conditions;
        $selectConditions = $conditions->select();

        $country = new CountryOrigin;
        $selectCountry = $country->select();

        return View::render('stamp/create', ['colors' => $selectColors, 'conditions' => $selectConditions, 'countries' => $selectCountry]);

        // echo('<pre>');
        // print_r($selectCountry);
        // echo('</pre>');
        // die();
    }






}