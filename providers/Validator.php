<?php
namespace App\Providers;

class Validator
{

    private $errors = [];
    private $key;
    private $value;
    private $name;

    public function field($key, $value, $name = null)
    {
        $this->key   = $key;
        $this->value = $value;
        if ($name == null) {
            $this->name = ucfirst($key);
        } else {
            $this->name = ucfirst($name);
        }
        return $this;
    }

    public function required()
    {
        if (empty($this->value)) {
            $this->errors[$this->key] = "$this->name est requis!";
        }
        return $this;
    }

    public function notSelect()
    {
        if (! isset($this->value) || $this->value === "disabled") {
            $this->errors[$this->key] = "$this->name est requis!";
        }
        return $this;
    }

    public function max($length)
    {
        if (strlen($this->value) > $length) {
            $this->errors[$this->key] = "$this->name doit faire maximum $length caractères!";
        }
        return $this;
    }

    public function min($length)
    {
        if (strlen($this->value) < $length) {
            $this->errors[$this->key] = "$this->name doit faire minimum $length caractères!";
        }
        return $this;
    }

    public function uppercase()
    {
        if (! empty($this->value) && ! preg_match('/[A-Z]/', $this->value)) {
            $this->errors[$this->key] = "$this->name ne contient pas de majuscule!";
        }
        return $this;
    }
    public function lowercase()
    {
        if (! empty($this->value) && ! preg_match('/[a-z]/', $this->value)) {
            $this->errors[$this->key] = "$this->name ne contient pas de miniscule!";
        }
        return $this;
    }

    public function countainNumber()
    {
        if (! empty($this->value) && ! preg_match('/[0-9]/', $this->value)) {
            $this->errors[$this->key] = "$this->name ne contient pas de miniscule!";
        }
        return $this;
    }

    public function specialChars()
    {
        if (! empty($this->value) && ! preg_match('/[\@#\$%\^&\*\-_\+=\[\]{};,.]/', $this->value)) {
            $this->errors[$this->key] = "$this->name doit contenir au moins un caractère spécial !";
        }
        return $this;
    }

    public function number()
    {
        if (! empty($this->value) && ! is_numeric($this->value)) {
            $this->errors[$this->key] = "$this->name doit être un nombre!";
        }
        return $this;
    }

    public function email()
    {
        if (! empty($this->value) && ! filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$this->key] = "$this->name est invalide!";
        }
        return $this;
    }

    // https://www.php.net/manual/en/features.file-upload.errors.php
    // 4 => 'No file was uploaded'
    public function fileUploaded($file)
    {
        if ($_FILES[$file]["error"] == 4) {
            $this->errors[$this->key] = "$this->name est requise!";
        }
        return $this;
    }

    // https://www.w3schools.com/php/php_file_upload.asp
    public function imgFake($file = null)
    {
        if ($_FILES[$file]["error"] != 4) {

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES[$file]["tmp_name"]);
            if ($check === false) {
                $this->errors[$this->key] = "$this->name n'est pas une image!";
            }
        }
        return $this;
    }

    public function fileWeight($file)
    {
        if ($_FILES[$file]["error"] != 4) {

            // Check image size
            if ($_FILES[$file]["size"] > 200) {
                $this->errors[$this->key] = "$this->name est trop lourde!";
            }
        }
        return $this;
    }

    public function fileExists($file)
    {
        if ($_FILES[$file]["error"] != 4) {

            $target_dir  = __DIR__ . "/../public/uploads/";
            $target_file = $target_dir . basename($_FILES[$file]["name"]);
            if (file_exists($target_file)) {
                $this->errors[$this->key] = "$this->name existe!";
            }
        }
        return $this;
    }

    public function imgFormat($file)
    {
        if ($_FILES[$file]["error"] != 4) {

            $target_dir    = __DIR__ . "/../public/uploads/";
            $target_file   = $target_dir . basename($_FILES[$file]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                $this->errors[$this->key] = "$this->name a un mauvais format!";
            }
        }
        return $this;
    }
    public function imgMinSize($file, $witdh=200, $height=200)
    {
        $info = getimagesize($_FILES[$file]["tmp_name"]);
        $imgWidth = $info[0];
        $imgHeight = $info[1];
        if($imgWidth < $witdh || $imgHeight < $height){
            $this->errors[$this->key] = "$this->name a un format trop petit!";
        }
        return $this;
    }

    public function unique($model)
    {
        $model  = 'App\\Models\\' . $model;
        $model  = new $model;
        $unique = $model->unique($this->key, $this->value);
        if ($unique) {
            $this->errors[$this->key] = "$this->name doit être unique.";
            if(array_key_exists($this->key,$this->errors)==false){
                $this->errors[$this->key] = [];
            }
            array_push($this->errors[$this->key],"sdfgsdfgfd");
        }
        return $this;
    }

    public function isSuccess()
    {
        if (empty($this->errors)) {
            return true;
        }

    }

    public function getErrors()
    {
        if (! $this->isSuccess()) {
            return $this->errors;
        }

    }

}
