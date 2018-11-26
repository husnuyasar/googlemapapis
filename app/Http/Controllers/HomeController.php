<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Agency;
use App\Entities\ViewLocation;
use App\Helpers\AgencyHelper;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $agencies = Agency::all(); 
        return view('layouts.dashboard.index')->with(['agencies'=>$agencies]);
    }

    public function listAgenciesJson(Request $request){
        $searchTerm = '';
        if ($request->input('lat') && $request->input('lng')){
            $lat = $request->input('lat');
            $lng = $request->input('lng');
            $response = AgencyHelper::getAgencyListWithCoordinates($lat,$lng)->get();
            return response()->json($response);
        }
        else{
            $response = AgencyHelper::getAgencyList($searchTerm)->get();
            return response()->json($response);
        }

    }
}
