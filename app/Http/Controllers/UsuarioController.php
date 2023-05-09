<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // listar 
        $users = User::get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // guardar datos en la BD
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(["mesage" => "Usuario registrado"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // mostrar por id

        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // busca por id y luego modifica en la BD
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email,$id",
            "password" => "required"
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->update();

        return response()->json(["mesage" => "Usuario actualizado"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // eliminar
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(["mesage" => "Usuario eliminado"]);
    }
}
