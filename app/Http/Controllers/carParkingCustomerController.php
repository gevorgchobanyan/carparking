<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//
use App\Models\Client;
use App\Models\Vehicle;

class carParkingCustomerController extends Controller
{
    //READ
    public function errorHandler1(){
        return view('404');
    }
    public function errorHandler2(){
        return view('500');
    }

    public function parkedVehicles(){
        $clients = Client::retrieveAllRecords();
        $parked_vehicles = Vehicle::retrieveParkedVehicleRecords();
        $all_vehicles =  Vehicle::retrieveAllVehicleRecords();

        return view('parked', ['clients' => $clients, 'parked_vehicles' => $parked_vehicles, 'all_vehicles' => $all_vehicles ]);
    }

    public function index(){
        $clients = Client::retrieveJoinedRecords();
        return view('index', ['clients' => $clients]);
    }

    public function createClientForm(){
        return view('createclientform');
    }

    public function editClientForm($client_id){
        // DB::transaction(function() use ($client_id) {
            $client = Client::retrieveRecordById($client_id);
            $vehicles = Vehicle::retrieveRecordsById($client_id);
        // });
        return view('editclient', ['client' => $client, 'vehicles' => $vehicles]);
    }

    //CREATE

    public function addVehicle(Request $request){
        $validated = $request->validate([
                'manufacturer' => 'required',
                'model' => 'required',
                'color' => 'required',
                'plate' => 'required|unique:vehicle',
                'parked' => 'required'
            ]);

        try{
            DB::transaction(function() use ($request) {
                Vehicle::insertNewRecordForExistingClient($request);
                Client::incrementVehicleQuantity($request);
            });
        }catch (Throwable $e) {
            report($e);
            return back()->with("fail", "Something went wrong");
        }
        return back()->with("success", "New vehicle has been added");
    }



    public function createClient(Request $request){

        $vehicles = request('vehicle');
        $new_client = array("name" => request('name'),
                    "gender" => request('gender'),
                    "telephone" => request('telephone'),
                    "address" => request('address'),
                    "car_quantity" => request('quantity')
                    );
        //VALIDATION

        // Client validation

        $validated = $request->validate([
            "name" => 'required|between:3,255',
            "telephone" => 'required|unique:client',
            "address" => 'required',
            "quantity" => 'required|min:1',
        ]);

        //Vehicle validation

        // $vehicles_arr = array();
        // for($i = 0; $i < count($vehicles); $i++){
        //     foreach($vehicles[$i] as $key => $value) {
        //     }
        // }
        //     $validated = $request->validate([
        //         'vehicle.manufacturer' => 'required|between:3,255',
        //         'vehicle.model' => 'required',
        //         'vehicle.color' => 'required',
        //         'vehicle.plate' => 'required|unique',
        //         'vehicle.parked' => 'required'
        //     ]);

        try{
            DB::transaction(function() use ($new_client, $vehicles) {
                $new_client_id = Client::addNewRecordsAndReturnId($new_client);
                Vehicle::insertNewRecordsForNewClient($new_client_id, $vehicles);
                Client::setVehicleQuantity($new_client_id, $new_client);
            });
        }catch (Throwable $e) {
            report($e);
            return back()->with("fail", "Something went wrong");
        }
        return back()->with("success", "A new client has been successfully created");
    }

    //UPDATE

    public function vehicleParkedStatusParked(Request $request, $vehicle_id){
            DB::transaction(function() use ($request, $vehicle_id) {
                Vehicle::updateToParked($request, $vehicle_id);
            });
    }

    public function vehicleParkedStatusGone(Request $request, $vehicle_id){
            DB::transaction(function() use ($request, $vehicle_id) {
                Vehicle::updateParkedToGone($request, $vehicle_id);
            });
    }

    public function updateVehicle(Request $request, $vehicle_id){
        $validated = $request->validate([
            'manufacturer' => 'required',
            'model' => 'required',
            'color' => 'required',
            'plate' => 'required|unique:vehicle,plate,'.$vehicle_id. ',vehicle_id',
            'parked' => 'required'
        ]);

        try{
            DB::transaction(function() use ($request, $vehicle_id) {
                Vehicle::updateRecord($request, $vehicle_id);
            });
        }catch (Throwable $e) {
            report($e);
            return back()->with("fail", "Something went wrong");
        }
        return back()->with("success", "Vehicle details have been updated");
    }

    public function updateClient(Request $request, $client_id){
        try{
            DB::transaction(function() use ($request, $client_id) {
                Client::updateRecord($request, $client_id);
            });
        }catch (Throwable $e) {
            report($e);
            return back()->with("fail", "Something went wrong");
        }
        return back()->with("success", "Vehicle details have been updated");
    }

    //DELETE

    public function deleteVehicle($vehicle_id){
        try{
            DB::transaction(function() use ($vehicle_id) {
                $client_id = Client::retrieveRecordIdByVehicleId($vehicle_id);
                Vehicle::deleteRecord($vehicle_id);
                Client::decrementVehicleQuantity($client_id);
            });
        }catch (Throwable $e) {
            report($e);
            return back()->with("fail", "Something went wrong");
        }
        return back()->with("success", "Vehicle has been removed");
    }
}
