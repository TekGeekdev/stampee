<?php
namespace App\Controllers;

use App\Models\Color;
use App\Models\Conditions;
use App\Models\CountryOrigin;
use App\Models\Stamp;
use App\Providers\Auth;
use App\Providers\Validator;
use App\Providers\View;

class StampController
{

    public function __construct()
    {
        // Logs::history();
        Auth::session();
    }

    public function create()
    {
        $color        = new Color;
        $selectColors = $color->select();

        $conditions       = new Conditions;
        $selectConditions = $conditions->select();

        $country       = new CountryOrigin;
        $selectCountry = $country->select();

        return View::render('stamp/create', ['colors' => $selectColors, 'conditions' => $selectConditions, 'countries' => $selectCountry]);

    }

    public function store($data = [])
    {
        $validator = new Validator;

        $color        = new Color;
        $selectColors = $color->select();

        $conditions       = new Conditions;
        $selectConditions = $conditions->select();

        $country       = new CountryOrigin;
        $selectCountry = $country->select();

        $validator->field('name', $data['name'], "Le champ titre")->required()->max(200);
        // TODO: ajoute une couche pour verifier que c'est une date
        $validator->field('dateRelease', $data['dateRelease'], "Le champ date")->required();
        $validator->field('width', $data['width'], "Le champ largeur")->required()->number();
        $validator->field('height', $data['height'], "Le champ longueur")->required()->number();
        $validator->field('certified', $data['certified'] ?? null, "Le champ certifier")->notSelect();
        $validator->field('color_id', $data['color_id'] ?? null, "Le champ couleur")->notSelect();
        $validator->field('countryOrigin_id', $data['countryOrigin_id'] ?? null, "Le champ pays")->notSelect();
        $validator->field('conditions_id', $data['conditions_id'] ?? null, "Le champ condition")->notSelect();
        $validator->field('content', $data['content'], "Le champ content")->required();

        if ($validator->isSuccess()) {
            echo("valaditor ok");
            $data["user_id"] = $_SESSION['user_id'];
            $stamp = new Stamp;
            $insertStamp = $stamp->insert($data);
            // echo('<pre>');
            // print_r($insertStamp);
            // echo('</pre>');
            // die();
            if ($insertStamp) {
                return view::redirect('stamp/create-img?id='. $insertStamp);
            } else {
                return View::render('error', ['msg' => 'Impossible d\'envoyer l\'article']);
            }
        
        } else {
            $errors = $validator->getErrors();
            return View::render('stamp/create', ['errors' => $errors, 'stamp' => $data, 'colors' => $selectColors, 'conditions' => $selectConditions, 'countries' => $selectCountry]);
        }
    }

    public function create_stamp_img(){
                return View::render('stamp/create-img');
    }

    public function store_stamp_img(){

        // $check0 = getimagesize($_FILES["file"]["tmp_name"]);
        // $check1 = getimagesize($_FILES["file2"]["tmp_name"]);
        echo('<pre>');
        // $info = getimagesize($_FILES["file"]["tmp_name"]);
        // $width = $info[0];
        var_dump(getimagesize($_FILES["file"]["tmp_name"]));
        echo('</pre>');
        echo('<pre>');
        // echo($width);
        // var_dump($check0);
        // echo('</pre>');
        // echo('<pre>');
        // var_dump($check1);
        // echo('</pre>');

        $validator = new Validator;

        // $validator->field('file', $_FILES["file"])->fileUploaded("file");
        // $validator->field('file', $_FILES["file"], "L'image")->fileExists("file");
        // $validator->field('file', $_FILES["file"], "L'image")->imgFormat("file");
        $validator->field('file', $_FILES["file"], "L'image")->imgMinSize("file", 800, 800);

        if ($validator->isSuccess()) {
            echo("validator success");
            // $check0 = getimagesize($_FILES["file"]["tmp_name"]);
            // $check1 = getimagesize($_FILES["file2"]["tmp_name"]);
            // echo(__DIR__ . "/public/uploads");
            // echo("<br>");
            // echo(UPLOADS);
            // echo('<pre>');
            // print_r(__DIR__);
            echo('</pre>');
            // echo('<pre>');
            // var_dump($check0);
            // echo('</pre>');
            // echo('<pre>');
            // var_dump($check1);
            // echo('</pre>');
            echo("<br>");
            // fonctionne
            // $target_dir = __DIR__ . "/../public/uploads/";

            // $target_dir = __DIR__ . "/../public/uploads/";
            // $target_file = $target_dir . basename($_FILES["file"]["name"]);
            // echo($target_file);
            // echo("<br>");
            // var_dump(file_exists($target_file));
        }else{
            $errors = $validator->getErrors();
            return View::render('stamp/create-img', ['errors' => $errors]);
        }
        // return View::render('stamp/create-img');
        // boucle{
        // $folderUpload = __DIR__ . '/../public/uploads/';
        // $target_file  = $folderUpload . basename($_FILES["image"]["name"]);
       
        // $data['image'] = basename($_FILES['image']['name']);
        // move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        // ["name"]
        }
}
    // stockage en deux temps
    // stockage du stamp en premier pour recup id
    // stockage des images grace à l'id utilisation sur fileToUpload


