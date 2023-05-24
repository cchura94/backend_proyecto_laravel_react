<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // http://127.0.0.1:8000/api/producto?page=2&limit=5&q=tec
        $limit = $request->limit?$request->limit:5;
        if(isset($request->q)){
            $productos = Producto::where('nombre', 'like', "%".$request->q."%")
                                    ->with('categoria')
                                    ->orderBy('id', 'desc')
                                    ->paginate($limit);
        }else{
            $productos = Producto::orderBy('id', 'desc')
                                    ->with('categoria')
                                    ->paginate($limit);
        }

        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validar
        $request->validate([
            "nombre" => "required",
            "categoria_id" => "required"
        ]);        
        
        // guardar
        $prod = new Producto();
        $prod->nombre =   $request->nombre;
        $prod->precio =   $request->precio;    
        $prod->stock =   $request->stock;
        $prod->descripcion =   $request->descripcion;
        $prod->categoria_id =   $request->categoria_id;
        $prod->save();
        
        // responder
        return response()->json(["message" => "Producto registrado."], 201);
    }

    public function actualizarImagen(Request $request, $id)
    {
        if($file = $request->file("imagen")) {
            $direccion_imagen = time() . "-". $file->getClientOriginalName();
            $file->move("imagenes", $direccion_imagen);

            $direccion_imagen = "imagenes/" . $direccion_imagen;

            $producto = Producto::find($id);
            $producto->imagen = $direccion_imagen;
            $producto->update();            
            return response()->json(["mensaje" => "Imagen Actualizada"]);
        }

        return response()->json(["mensaje" => "Se requiere Imagen para actualizar"], 422);        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validar
        $request->validate([
            "nombre" => "required",
            "categoria_id" => "required"
        ]);   

        DB::beginTransaction();

        try {
            
            // guardar
        $prod = Producto::find($id);
        $prod->nombre =   $request->nombre;
        $prod->precio =   $request->precio;    
        $prod->stock =   $request->stock;
        $prod->descripcion =   $request->descripcion;
        $prod->categoria_id =   $request->categoria_id;

        if($file = $request->file("imagen")) {
            $direccion_imagen = time() . "-". $file->getClientOriginalName();
            $file->move("imagenes", $direccion_imagen);

            $direccion_imagen = "imagenes/" . $direccion_imagen;

            $prod->imagen = $direccion_imagen;
        }
        $prod->update(); 
        
        // responder

            DB::commit();
            // all good
            return response()->json(["mensaje" => "Producto actualizado"], 201);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(["mensaje" => "Error al actualizar el producto"], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);

        $producto->delete();

        return response()->json(["message" => "Producto eliminado"], 200);
    }
}
