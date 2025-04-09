<?php
namespace App\Providers;
use DateTimeImmutable;

class Date{
    public function dateNow(){
        $objDateNow = date_create('now');
        $dateFormated = $objDateNow->format("Y-m-d H:i:s");
        return $dateFormated; 
    }


    // https://www.php.net/manual/fr/datetime.diff.php
    public function dateDiff($dateNow,$dateTarget){
        $origin = new DateTimeImmutable($dateNow);
        $target = new DateTimeImmutable($dateTarget);
        $interval = $origin->diff($target);
        $dateFormated = $interval->format('%a jours %H:%I:%S');
        return $dateFormated;
    }
}