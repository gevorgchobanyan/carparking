<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carParkingCustomerController;

//READ
Route::get('/404', [carParkingCustomerController::class, 'errorHandler1']);
Route::get('/500', [carParkingCustomerController::class, 'errorHandler2']);
Route::get('/', [carParkingCustomerController::class, 'index']);
Route::get('/clients', [carParkingCustomerController::class, 'index']);
Route::get('clients/create_form', [carParkingCustomerController::class, 'createClientForm']);
Route::get('/clients/edit/{client_id}', [carParkingCustomerController::class, 'editClientForm']);
Route::get('/vehicle/parked', [carParkingCustomerController::class, 'parkedVehicles']);
//CREATE
Route::post('/clients/add', [carParkingCustomerController::class, 'createClient']);
Route::post('/vehicle/add', [carParkingCustomerController::class, 'addVehicle']);
//UPDATE
Route::put('/vehicle/update/{vehicle_id}', [carParkingCustomerController::class, 'updateVehicle']);
Route::put('/vehicle/update/parked/{vehicle_id}', [carParkingCustomerController::class, 'vehicleParkedStatusParked']);
Route::put('/vehicle/update/gone/{vehicle_id}', [carParkingCustomerController::class, 'vehicleParkedStatusGone']);
Route::put('/clients/update/{client_id}', [carParkingCustomerController::class, 'updateClient']);
//DELETE
Route::delete('/vehicle/{vehicle_id}', [carParkingCustomerController::class, 'deleteVehicle']);


