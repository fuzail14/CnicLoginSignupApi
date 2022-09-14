<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SuperAdminLoginAuthController;
use App\Http\Controllers\API\AddSocietyController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register/{superadminid?}/{societyid?}',[SuperAdminLoginAuthController::class,'register']);
Route::post('login',[SuperAdminLoginAuthController::class,'login']);



Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('logout',[SuperAdminLoginAuthController::class,'logout']);
    Route::get('alluser',[SuperAdminLoginAuthController::class,'alluser']);
    Route::post('addsociety',[AddSocietyController::class,'addsociety']);
    Route::post('updatesociety/{id}',[AddSocietyController::class,'updatesociety']);
    Route::get('viewallsocieties/{mainadminid}',[AddSocietyController::class,'viewallsocieties']);
    Route::delete('deletesociety/{id}',[AddSocietyController::class,'deletesociety']);
    Route::get('searchsociety/{q?}',[AddSocietyController::class,'searchsociety']);
    
    

    
    
    

    

    
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
