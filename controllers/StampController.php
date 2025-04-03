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

            $FileToUpload = new FileToUpload;

            $folderUpload = __DIR__ . '/../public/uploads/';

            $firstData                = [];
            $firstData["position"]    = 0;
            $firstData["description"] = $data["description"];
            $firstData["stamp_id"]    = $idStampUser;

            $target_file1 = $folderUpload . basename($_FILES["file"]["name"]);

            $firstData['file'] = basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $target_file1);
            $FileToUpload->insert($firstData);

            if ($_FILES["secondFile"]["error"] === 0) {
                $secondData                = [];
                $secondData["position"]    = 1;
                $secondData["description"] = $data["secondDescription"];
                $secondData["stamp_id"]    = $idStampUser;

                $target_file2 = $folderUpload . basename($_FILES["secondFile"]["name"]);

                $secondData['file'] = basename($_FILES['secondFile']['name']);
                move_uploaded_file($_FILES['secondFile']['tmp_name'], $target_file2);
                $FileToUpload->insert($secondData);

                if ($_FILES["thirdDescription"]["error"] === 0) {
                    $thirdData                = [];
                    $thirdData["position"]    = 2;
                    $thirdData["description"] = $data["thirdDescription"];
                    $thirdData["stamp_id"]    = $idStampUser;

                    $target_file3 = $folderUpload . basename($_FILES["thirdFile"]["name"]);

                    $thirdData['file'] = basename($_FILES['thirdFile']['name']);
                    move_uploaded_file($_FILES['thirdFile']['tmp_name'], $target_file3);
                    $FileToUpload->insert($thirdData);

                    $_SESSION['stampId'] = null;
                    return View::redirect('user/show');

                } else {
                    $_SESSION['stampId'] = null;
                    return View::redirect('user/show');
                }

            } else {
                $_SESSION['stampId'] = null;
                return View::redirect('user/show');
            }

        } else {
            $errors = $validator->getErrors();
            return View::render('stamp/create-img', ['errors' => $errors, 'description' => $data]);
        }
    }

    public function index()
    {
        $stamp      = new Stamp;
        $color      = new Color;
        $conditions = new Conditions;
        $country    = new CountryOrigin;

        $selectAllStamp = $stamp->selectAllById($_SESSION['user_id'], "user_id", "id");

        foreach ($selectAllStamp as $key => $oneStamp) {
            $colorName                        = $color->selectId($oneStamp["color_id"]);
            $selectAllStamp[$key]["color_id"] = $colorName["color"];

            $conditionsName                        = $conditions->selectId($oneStamp["conditions_id"]);
            $selectAllStamp[$key]["conditions_id"] = $conditionsName["state"];

            $countryName                              = $country->selectId($oneStamp["countryOrigin_id"]);
            $selectAllStamp[$key]["countryOrigin_id"] = $countryName["country"];

        }
        return View::render('stamp/index', ['AllStamp' => $selectAllStamp]);
    }

    public function edit()
    {
        $get = ! empty($get) ? $get : $_GET;
        var_dump($get);
        $idStamp = $get["id"];

        $color        = new Color;
        $selectColors = $color->select();

        $conditions       = new Conditions;
        $selectConditions = $conditions->select();

        $country       = new CountryOrigin;
        $selectCountry = $country->select();

        $stamp       = new Stamp;
        $selectStamp = $stamp->selectID($idStamp);

        // echo('<pre>');
        // print_r($selectStamp);
        // echo('</pre>');
        if ($_SESSION["user_id"] == $selectStamp["user_id"]) {
            return View::render('stamp/edit', ['colors' => $selectColors, 'stamp' => $selectStamp, 'conditions' => $selectConditions, 'countries' => $selectCountry]);
        } else {
            return View::render('error', ['msg' => "Vous n'avez pas accès à cette zone"]);
        }
    }

    public function update($data = [])
    {

        $get     = ! empty($get) ? $get : $_GET;
        $idStamp = $get["id"];

        if ($_SESSION["user_id"] == $data["user_id"]) {
            $validator = new Validator;

            $color        = new Color;
            $selectColors = $color->select();

            $conditions       = new Conditions;
            $selectConditions = $conditions->select();

            $country       = new CountryOrigin;
            $selectCountry = $country->select();

            $validator->field('name', $data['name'], "Le champ titre")->required()->max(200);
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
                $updateStamp     = $stamp->update($data, $idStamp);

                if ($updateStamp) {
                    echo("update ok");
                    die();
                    $_SESSION['stampId'] = $insertStamp;
                    return view::redirect('stamp/create-img');
                } else {
                    return View::render('error', ['msg' => 'Impossible d\'envoyer l\'article']);
                }

            } else {
                $errors = $validator->getErrors();
                return View::render('stamp/create', ['errors' => $errors, 'stamp' => $data, 'colors' => $selectColors, 'conditions' => $selectConditions, 'countries' => $selectCountry]);
            }
        } else {
            return View::render('error', ['msg' => "Vous n'avez pas accès à cette zone"]);
        }
    }

    public function delete($data = [])
    {
        $files          = new FileToUpload;
        $selectAllFiles = $files->selectAllById($data["id"], "stamp_id", "id");

        $allFilesDelelete = false;

        foreach ($selectAllFiles as $oneFile) {
            $file          = new FileToUpload;
            $deleteOneFile = $file->delete($oneFile["stamp_id"]);

            if ($deleteOneFile) {
                $allFilesDelelete = true;
            } else {
                $allFilesDelelete = false;
            }
        }

        if ($allFilesDelelete) {
            $stamp       = new Stamp;
            $deleteStamp = $stamp->delete($data["id"]);

            if ($delete) {
                return view::redirect('stamp/index');
            } else {
                return View::render('error', ['msg' => 'Impossible de supprimer le timbre']);
            }
        } else {
            return View::render('error', ['msg' => 'Impossible de supprimer le timbre']);
        }
    }
}
