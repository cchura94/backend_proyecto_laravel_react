<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // listar con SQL
        // $categorias = DB::select("select * from categorias");

        // listar con Query Builder
        $categorias = DB::table("categorias")->get();

        // retornar en formato JSON
        return response()->json($categorias, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // mostrar un form de creacion para categoria

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        // guarda los datos en la BD SQL
        // DB::insert("insert into categorias (nombre, detalle) values (?, ?)", [$request->nombre, $request->detalle]);
        // guarda los datos en la BD Query Builder
        DB::table("categorias")->insert(['nombre' => $request->nombre, 'detalle' => $request->detalle]);
        return response()->json(["message" => "Categoria registrada"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // buscar por id y mostrar un recurso SQL
        // $categoria = DB::select("select * from categorias where id=?", [$id]);
        // buscar por id y mostrar un recurso SQL
        $categoria = DB::table("categorias")->find($id);

        return response()->json($categoria, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // mostrar un form de edicion para un recurso por id 


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // modificar un recurso por ID en la BD ELOQUENT ORM
        $categoria = Categoria::find($id);

        $categoria->nombre = $request->nombre;
        $categoria->detalle = $request->detalle;
        $categoria->update();
        return response()->json(["message" => "Categoria Modificada"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // buscar por id y luego eliminar de la BD
        $categoria = Categoria::find($id);
        $categoria->delete();

        return response()->json(["message" => "Categoria Eliminada"]);
    }
}
