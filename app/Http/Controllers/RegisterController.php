<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Circle;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Models\Country;
use Stevebauman\Location\Models\State;
use App\Helpers\Permission;
use Illuminate\Support\Facades\Session;
use GeoIP;
class RegisterController extends Controller
{
    public function create(Request $request)
    {
        $ip = $request->ip();
        $location = GeoIP::getLocation($ip);
        $countryName = $location->country; // Fetching Country name
        $countryCode = $location->iso_code;// Fetching Country Code
        $state = Permission::getStates($countryName);
        $data['country'] = $countryName;
        $data['stateData'] = $state;
        return view('session.register')->with($data);
    }
    public function SetPassword(){
        $data=  [];
        if(empty(session('mobile'))){
           return redirect('login');
        }
        $mobile = (session('mobile'));
        $data['user'] = User::where('mobile',$mobile)->first();
        Session::put('on_password_step', true);
        return view('session.set-password')->with($data);
    }
    public function countries(){
        $data['contries_data'] = Permission::getCountries();
        return response()->json(view('session.countries')->with($data)->render());
    }
    public function state($country){
        $state = Permission::getStates($country);
        return response()->json($state);
    }
    public function city($state){
        $city = Permission::getCity($state);
        return response()->json($city);
    }
}