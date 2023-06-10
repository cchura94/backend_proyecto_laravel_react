<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // http://127.0.0.1:8000/api/cliente?page=2&limit=5&q=112233
        $limit = isset($request->limit)? $request->limit : 10;
        if(isset($request->q)){
            // buscar al cliente
            $clientes = Cliente::orWhere('nombre_completo', "like", "%".$request->q."%")
                                    ->orWhere('ci_nit', "like", "%".$request->q."%")
                                    ->orderBy('id', 'desc')
                                    ->paginate($limit);
        }else{
             $clientes = Cliente::orderBy('id', 'desc')->paginate($limit);
        }

        return response()->json($clientes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validar
        $request->validate([
            "nombre_completo" => "required",
            "ci_nit" => "required"
        ]);
        // guardar
        $clie = new Cliente;
        $clie->nombre_completo = $request->nombre_completo;
        $clie->ci_nit = $request->ci_nit;
        $clie->telefono = $request->telefono;
        $clie->correo = $request->correo;
        $clie->save();
        // res
        return response()->json(["mensaje" => "Cliente registrado", "cliente" => $clie], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $cliente = Cliente::findOrFail($id);

         return response()->json($cliente, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "nombre_completo" => "required",
            "ci_nit" => "required"
        ]);

        $clie = Cliente::findOrFail($id);
        $clie->nombre_completo = $request->nombre_completo;
        $clie->ci_nit = $request->ci_nit;
        $clie->telefono = $request->telefono;
        $clie->correo = $request->correo;
        $clie->update();
        
        return response()->json(["mensaje" => "Cliente Modificado"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clie = Cliente::findOrFail($id);
        $clie->delete();

        return response()->json(["mensaje" => "Cliente Eliminado"], 200);
    }
}
