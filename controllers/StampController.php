<?php
namespace App\Controllers;

use App\Models\Color;
use App\Models\Conditions;
use App\Models\CountryOrigin;
use App\Models\FileToUpload;
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
            $data["user_id"] = $_SESSION['user_id'];
            $stamp           = new Stamp;
            $insertStamp     = $stamp->insert($data);

            if ($insertStamp) {
                $_SESSION['stampId'] = $insertStamp;
                return view::redirect('stamp/create-img');
            } else {
                return View::render('error', ['msg' => 'Impossible d\'envoyer l\'article']);
            }

        } else {
            $errors = $validator->getErrors();
            return View::render('stamp/create', ['errors' => $errors, 'stamp' => $data, 'colors' => $selectColors, 'conditions' => $selectConditions, 'countries' => $selectCountry]);
        }
    }

    public function create_stamp_img()
    {
        return View::render('stamp/create-img');
    }

    public function store_stamp_img($data = [])
    {
        var_dump($_SESSION['stampId']);
        $validator = new Validator;

        $validator->field('file', $_FILES["file"], "L'image")->fileUploaded("file")->imgMinSize("file", 300, 200)->imgFormat("file")->fileExists("file");
        $validator->field('description', $data['description'], "Le champ description")->required()->min(5)->max(60);

        if ($_FILES["secondFile"]["error"] === 0) {
            $validator->field('secondFile', $_FILES["secondFile"], "L'image")->imgMinSize("secondFile", 300, 200)->imgFormat("secondFile")->fileExists("secondFile");

            $validator->field('secondDescription', $data['secondDescription'], "Le champ description")->required()->min(5)->max(60);
        }

        if ($_FILES["thirdFile"]["error"] === 0) {
            $validator->field('thirdFile', $_FILES["thirdFile"], "L'image")->imgMinSize("thirdFile", 300, 200)->imgFormat("thirdFile")->fileExists("thirdFile");

            $validator->field('thirdDescription', $data['thirdDescription'], "Le champ description")->required()->min(5)->max(60);
        }

        $idStampUser = $_SESSION['stampId'];

        if ($validator->isSuccess()) {
            echo("validator success");

            $position = 0;

            $allDataInsert = false;

            foreach ($data as $oneData) {

                if ($oneData != null) {
                    $oneDataInsert                = [];
                    $oneDataInsert["position"]    = $position;
                    $oneDataInsert["description"] = $oneData;
                    $oneDataInsert["stamp_id"]    = $idStampUser;
                    $oneDataInsert["file"]        = "";

                    foreach ($_FILES as $oneFile => $fileInfo) {

                        if ($fileInfo["error"] === 0) {
                            $folderUpload = __DIR__ . '/../public/uploads/';
                            $target_file  = $folderUpload . basename($fileInfo["name"]);
                            if (move_uploaded_file($fileInfo['tmp_name'], $target_file)) {
                                $oneDataInsert["file"] = basename($fileInfo["name"]);
                            }
                        }
                    }

                    $FileToUpload = new FileToUpload;
                    $insertData   = $FileToUpload->insert($oneDataInsert);

                    if ($insertData) {
                        $allDataInsert = true;
                    } else {
                        $allDataInsert = false;
                    }

                    $position++;
                }
            }
            // var_dump($allDataInsert);

            if ($allDataInsert) {
                $_SESSION['stampId'] = null;
                return View::redirect('user/show');
            } else {
                return View::render('error', ['msg' => 'Impossible d\'envoyer les images']);
            }

        } else {
            $errors = $validator->getErrors();
            return View::render('stamp/create-img', ['errors' => $errors, 'description' => $data]);
        }
    }

    public function index()
    {
        $stamp          = new Stamp;
        $FileToUpload = new FileToUpload;

        $selectAllStamp = $stamp->selectAllById($_SESSION['user_id'], "user_id", "id");
        echo('<pre>');
        print_r($selectAllStamp);
        echo('</pre>');
        die();
        foreach ($selectAllStamp as $key => $oneStamp) {

        }

        return View::render('stamp/index');

    }
}
