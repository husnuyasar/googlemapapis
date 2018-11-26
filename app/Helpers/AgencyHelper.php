<?php

namespace App\Helpers;
use App\Entities\Agency;

class AgencyHelper {

    public static function getAgencyList($searchTerm){

        $relation = Agency::where('name','LIKE', '%'.$searchTerm.'%');
        
        return $relation;
    }
    public static function getAgencyListWithCoordinates($lat,$lng){

        $agecies = Agency::all();
        $earthRadius = 5000;
        $ids = [];
        foreach ($agecies as $agency ) {

            $theta = $lng - $agency->lng;
            $dist = sin(deg2rad($lat)) * sin(deg2rad($agency->lat)) +  cos(deg2rad($lat)) * cos(deg2rad($agency->lat)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $distance = $miles * 1.609344;
            if ($distance < 5) {
                array_push($ids,$agency->id);
            } 
        }
        $relation = Agency::whereIn('id',$ids);
       
        return $relation;
    }
}