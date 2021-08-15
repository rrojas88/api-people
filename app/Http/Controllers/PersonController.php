<?php
namespace App\Http\Controllers;

use App\Person;
use App\Address;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function setResponse($data, $message, $code){
        if( $code == 500 ){
            $message = $data[ 2 ];
            if( $data[ 1 ] == '1451' ){
                $message = 'Para eliminar el registro primero se debe eliminar la dirección.';
            }
            //$data = NULL;
        }
        $response = [
            'data'=> $data,
            'message'=> $message,
        ];
        return response()->json($response, $code);
    }

    public function setData($person, $address){
        $row = new \stdClass();
        $row->id = $person->id;
        $row->name = $person->name;
        $row->phone_number = $person->phone_number;
        $row->email_address = $person->email_address;
        $row->person_id = $person->id;
        if( $address !== NULL ){
            $row->street = $address->street;
            $row->city = $address->city;
            $row->state = $address->state;
            $row->postal_code = $address->postal_code;
            $row->country = $address->country;
        }
        else{
            $row->street = '';
            $row->city = '';
            $row->state = '';
            $row->postal_code = '';
            $row->country = '';
        }
        return $row;
    }

    public function index(){
        $all = Person::select(
            'people.id',
            'people.name',
            'people.phone_number',
            'people.email_address',
            'addresses.id AS address_id',
            'addresses.street',
            'addresses.city',
            'addresses.state',
            'addresses.postal_code',
            'addresses.country',
            )
            ->leftJoin('addresses', 'addresses.person_id', '=', 'people.id')
            ->get();
        return $this->setResponse($all, '', 200);
    }


    public function show(Request $request){
        $person = Person::find($request->id);
        if( ! $person || $person === NULL ){
            return $this->setResponse(NULL, 'Registro no encontrado', 404);
        }

        $address = Address::where('person_id', $person->id)
            ->first();

        $row = $this->setData( $person, $address );

        return $this->setResponse($row, '', 200);
    }


    public function store(Request $request){
        try{
            $person = new Person();
            $person->name = $request->name;
            $person->phone_number = $request->phone_number;
            $person->email_address = $request->email_address;
            $person->save();

            $address = new Address();
            $address->person_id = $person->id;
            $address->street = $request->street;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->postal_code = $request->postal_code;
            $address->country = $request->country;
            $address->save();

            $row = $this->setData( $person, $address );

            return $this->setResponse($row, '', 200);
        }
        catch (\Throwable $th ) {
            $errorInfo = $th->errorInfo;
            return $this->setResponse($th->errorInfo, '', 500);
        }
    }


    public function update(Request $request){
        try{
            $person = Person::find($request->id);
            if( ! $person || $person === NULL ){
                return $this->setResponse(NULL, 'Registro no encontrado', 404);
            }

            $person->name = $request->name;
            $person->phone_number = $request->phone_number;
            $person->email_address = $request->email_address;
            $person->save();

            $address = Address::where('person_id', $person->id)
                ->first();
            $address->person_id = $person->id;
            $address->street = $request->street;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->postal_code = $request->postal_code;
            $address->country = $request->country;
            $address->save();

            $row = $this->setData( $person, $address );
            
            return $this->setResponse($row, '', 200);
        }
        catch (\Throwable $th ) {
            $errorInfo = $th->errorInfo;
            return $this->setResponse($th->errorInfo, '', 500);
        }
    }


    public function delete(Request $request){
        try {
            $address = Address::where('person_id', $request->id)
            ->first();

            if( $address && $address !== NULL ){
                $address->delete();
            }
            $person = Person::destroy($request->id);

            return $this->setResponse(NULL, '', 200);
        } catch (\Throwable $th ) {
            $errorInfo = $th->errorInfo;
            return $this->setResponse($th->errorInfo, '', 500);
        }
    }
}
