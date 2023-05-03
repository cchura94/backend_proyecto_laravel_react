<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("v1/auth")->group(function(){
    
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("registro", [AuthController::class, "register"]);
    
    Route::middleware('auth:sanctum')->group(function(){

        Route::get("perfil", [AuthController::class, "miPerfil"]);
        Route::post("salir", [AuthController::class, "salir"]);
    });
});
