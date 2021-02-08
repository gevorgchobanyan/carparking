<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carParkingCustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

//READ

Route::get('/', [carParkingCustomerController::class, 'index'])->middleware(['auth']);

Route::get('/404', [carParkingCustomerController::class, 'errorHandler1'])->middleware(['auth']);
Route::get('/500', [carParkingCustomerController::class, 'errorHandler2'])->middleware(['auth']);
Route::get('/clients', [carParkingCustomerController::class, 'index'])->middleware(['auth']);
Route::get('clients/create_form', [carParkingCustomerController::class, 'createClientForm'])->middleware(['auth']);
Route::get('/clients/edit/{client_id}', [carParkingCustomerController::class, 'editClientForm'])->middleware(['auth']);
Route::get('/vehicle/parked', [carParkingCustomerController::class, 'parkedVehicles'])->middleware(['auth']);
//CREATE
Route::post('/clients/add', [carParkingCustomerController::class, 'createClient'])->middleware(['auth']);
Route::post('/vehicle/add', [carParkingCustomerController::class, 'addVehicle'])->middleware(['auth']);
//UPDATE
Route::put('/vehicle/update/{vehicle_id}', [carParkingCustomerController::class, 'updateVehicle'])->middleware(['auth']);
Route::put('/vehicle/update/parked/{vehicle_id}', [carParkingCustomerController::class, 'vehicleParkedStatusParked'])->middleware(['auth']);
Route::put('/vehicle/update/gone/{vehicle_id}', [carParkingCustomerController::class, 'vehicleParkedStatusGone'])->middleware(['auth']);
Route::put('/clients/update/{client_id}', [carParkingCustomerController::class, 'updateClient'])->middleware(['auth']);
//DELETE
Route::delete('/vehicle/{vehicle_id}', [carParkingCustomerController::class, 'deleteVehicle'])->middleware(['auth']);
