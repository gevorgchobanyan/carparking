<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Vehicle extends Model
{
    protected $table = 'vehicle';

    public function client(){
        return $this->belongsTo(Client::class);
    }


    // READ

    static function retrieveParkedVehicleRecords(){
        $parked_vehicles = DB::table('vehicle')
                            ->select('vehicle_id', 'client_id', 'manufacturer', 'model', 'color', 'plate', 'parked')
                            ->where('parked', '=', 1)
                            ->get();
        return $parked_vehicles;
    }

    static function retrieveAllVehicleRecords(){
        $all_vehicles = DB::table('vehicle')
                            ->select('vehicle_id', 'client_id', 'manufacturer', 'model', 'color', 'plate', 'parked')
                            ->get();
        return $all_vehicles;
    }

    static function retrieveRecordsById($client_id){
        $vehicles = DB::table('vehicle')
                        ->select('vehicle_id', 'manufacturer', 'model', 'color', 'plate', 'parked')
                        ->where('client_id', '=', $client_id)
                        ->get();
        return $vehicles;
    }

    //CREATE

    static function insertNewRecordForExistingClient($request){
        DB::table('vehicle')->insert([
            'client_id' => $request -> input('client_id'),
            'manufacturer' => $request -> input('manufacturer'),
            'model' => $request -> input('model'),
            'color' => $request -> input('color'),
            'plate' => $request -> input('plate'),
            'parked' => $request -> input('parked')
        ]);
    }

    static function insertNewRecordsForNewClient($new_client_id, $vehicles){
        for($i = 0; $i < count($vehicles); $i++){
            DB::table('vehicle')->insert([
                'client_id' => $new_client_id,
                'manufacturer' => $vehicles[$i]['manufacturer'],
                'model' => $vehicles[$i]['model'],
                'color' => $vehicles[$i]['color'],
                'plate' => $vehicles[$i]['plate'],
                'parked' => $vehicles[$i]['parked']
            ]);
        }
    }

    //UPDATE

    static function updateToParked($request, $vehicle_id){
        DB::table('vehicle')
                    ->where('vehicle_id', $vehicle_id)
                    ->update([
                        'parked' => $request -> get('parked')
                    ]);
    }

    static function updateParkedToGone($request, $vehicle_id){
        DB::table('vehicle')
                    ->where('vehicle_id', $vehicle_id)
                    ->update([
                        'parked' => $request -> get('parked')
                    ]);
    }


    static function updateRecord($request, $vehicle_id){
        DB::table('vehicle')
                    ->where('vehicle_id', $vehicle_id)
                    ->update([
                        'manufacturer' => $request -> input('manufacturer'),
                        'model' => $request -> input('model'),
                        'color' => $request -> input('color'),
                        'plate' => $request -> input('plate'),
                        'parked' => $request -> input('parked')
                    ]);
    }

    //DELETE

    static function deleteRecord($vehicle_id){
        DB::table('vehicle')->where('vehicle_id', '=', $vehicle_id)->delete();
    }

}
