<?php
namespace App\Providers;

class Date{
    public function date($date){
        $dateFormated = $date->format("Y-m-d");
        return $dateFormated; 
    }

}