<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    protected $table = 'client';

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

    //READ

    static function retrieveJoinedRecords(){
        $clients = DB::table('client')
            ->join('vehicle', 'client.id', '=', 'vehicle.client_id')
            ->select('client.id', 'client.name', 'vehicle.vehicle_id', 'vehicle.manufacturer', 'vehicle.plate')
            ->paginate(8);
        return $clients;
    }

    static function retrieveAllRecords(){
        $clients = DB::table('client')
                        ->select('id','name')
                        ->get();
        return $clients;
    }

    static function retrieveRecordById($client_id){
        $client = DB::table('client')
                        ->select('id','name', 'telephone', 'address', 'gender', 'car_quantity')
                        ->where('id', '=', $client_id)
                        ->get();
        return $client;
    }

    static function retrieveRecordIdByVehicleId($vehicle_id){
        $client_id = DB::table('client')
                        ->join('vehicle', 'client.id', '=', 'vehicle.client_id')
                        ->where('vehicle_id', $vehicle_id)
                        ->value('id');
        return $client_id;
    }

    //CREATE

    static function incrementVehicleQuantity($request){
        DB::table('client')
            ->where('id', $request -> input('client_id'))
            ->increment('car_quantity', 1);
    }

    static function decrementVehicleQuantity($client_id){
        DB::table('client')
            ->where('id', '=', $client_id)
            ->decrement('car_quantity', 1);
    }

    static function addNewRecordsAndReturnId($new_client){
        $new_client_id = DB::table('client')->insertGetId([
            'name' => $new_client['name'],
            'gender' => $new_client['gender'],
            'telephone' => $new_client['telephone'],
            'address' => $new_client['address'],
            'car_quantity' => 0
        ]);
        return $new_client_id;
    }

    static function setVehicleQuantity($new_client_id, $new_client){
        DB::table('client')
            ->where('id', $new_client_id)
            ->update(['car_quantity' => $new_client['car_quantity']
        ]);
    }

    //UPDATE

    static function updateRecord($request, $client_id){
        DB::table('client')
                    ->where('id', $client_id)
                    ->update([
                        'name' => $request -> input('name'),
                        'telephone' => $request -> input('telephone'),
                        'address' => $request -> input('address'),
                        'gender' => $request -> input('gender'),
                        'car_quantity' => $request -> input('quantity'),
                    ]);
    }


    //DELETE


}
