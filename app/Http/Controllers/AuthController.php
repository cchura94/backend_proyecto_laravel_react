<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        # autenticación
        $credeciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if(!Auth::attempt($credeciales)){
            return response()->json(["message" => "No Autenticado"], 401);
        }

        // generamos el token con sactum
        $user = Auth::user();
        $token = $user->createToken("token personal")->plainTextToken;
        // responder
        
        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "usuario" => $user
        ]);
    }

    public function register(Request $request)
    {
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);
        // guardar
        $usuario = new User;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

        // return
        return response()->json(["messaje" => "Usuario Registrado"], 201);
    }

    public function miPerfil()
    {
        $user = Auth::user();

        return response()->json($user);
        
    }
    public function salir()
    {

        Auth::user()->tokens()->delete();
        
        return response()->json(["messaje" => "SALIO"]);
        
    }
}
