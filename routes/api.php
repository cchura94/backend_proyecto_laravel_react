<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;


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


Route::middleware('auth:sanctum')->group(function(){

    // Endpoint CRUD de Categorias
    Route::get("/categoria", [CategoriaController::class, "index"])->name("categoria_listar");
    Route::post("/categoria", [CategoriaController::class, "store"]);
    Route::get("/categoria/{id}", [CategoriaController::class, "show"]);
    Route::put("/categoria/{id}", [CategoriaController::class, "update"]);
    Route::delete("/categoria/{id}", [CategoriaController::class, "destroy"]);
    
    // Route::apiResource("/categoria", CategoriaController::class);
    
    Route::apiResource("usuario", UsuarioController::class);
    Route::apiResource("producto", ProductoController::class);

});

Route::get("/no-autorizado", function(){
    return response()->json(["mensaje" => "No estas autorizado para ver esta Pagina"]);
})->name("login");


