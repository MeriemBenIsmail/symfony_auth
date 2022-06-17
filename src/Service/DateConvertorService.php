<?php

namespace App\Service;

class DateConvertorService
{
    public function convertStringToDateTime(string $date){
        $newDate= strtotime($date);
        $newDateFinal=new \DateTime('@'.$newDate);
        return $newDateFinal;
    }

}