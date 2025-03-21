<?php
namespace App\Providers;

class Date{
    public function date(){
        $objDateNow = date_create('now');
        $datePublication = $objDateNow->format("Y-m-d");
        return $datePublication; 
    }

}