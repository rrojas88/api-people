<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function setResponse($data, $message, $code){
        $response = [
            'data'=> $data,
            'message'=> $message,
        ];
        return response()->json($response, $code);
    }


    public function index(){
        $all = Address::all();
        return $this->setResponse($all, '', 200);
    }


    public function show(Request $request){
        $address = Address::find($request->id);
        if( ! $address || $address === NULL ){
            return $this->setResponse(NULL, 'Registro no encontrado', 404);
        }
        return $this->setResponse($address, '', 200);
    }


    public function store(Request $request){
        $address = new Address();
        $address->person_id = $request->person_id;
        $address->street = $request->street;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->save();
        return $this->setResponse($address, '', 200);
    }


    public function update(Request $request){
        $address = Address::find($request->id);
        if( ! $address || $address === NULL ){
            return $this->setResponse(NULL, 'Registro no encontrado', 404);
        }

        $address->person_id = $request->person_id;
        $address->street = $request->street;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->save();
        return $this->setResponse($address, '', 200);
    }


    public function delete(Request $request){
        $address = Address::destroy($request->id);
        return $this->setResponse(NULL, '', 200);
    }
}
