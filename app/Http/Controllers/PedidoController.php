<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = isset($request->limit) ? $request->limit : 10;
        if (isset($request->q)) {
            $pedidos = Pedido::orWhere('fecha', 'like', "%" . $request->q . "%")
                ->orWhereHas('cliente', function ($query) use ($request) {
                    $query->where('nombre_completo', 'like', '%' . $request->q . '%');
                })
                ->orderBy('id', 'desc')
                ->with(['cliente', 'user', 'productos'])
                ->paginate($limit);
        } else {

            $pedidos = Pedido::orderBy('id', 'desc')
                ->with(['cliente', 'user', 'productos'])
                ->paginate($limit);
        }

        return response()->json($pedidos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validacion
        $request->validate([
            "cliente_id" => "required",
            "productos" => "required"
        ]);
        // transaccion
        DB::beginTransaction();

        try {
                /*
                    {
                        cliente_id: 7,
                        productos: [
                            {producto_id: 5, cantidad: 2},
                            {producto_id: 9, cantidad: 1},
                            {producto_id: 2, cantidad: 3}
                        ]
                    }
                    // $request->cliente_id
                    // $request->productos[1]['producto_id']
                    [
                        cliente_id => 7,
                        productos => [
                            [producto_id => 5, cantidad => 2],
                            [producto_id => 9, cantidad => 1],
                            [producto_id => 2, cantidad => 3]
                        ]
                    ]
                */
            // registrar pedido
            $pedido = new Pedido();
            $pedido->fecha = date("Y-m-d H:i:s");
            $pedido->estado = 1; // 1, 2, 3
            $pedido->cliente_id = $request->cliente_id;
            $pedido->user_id = Auth::user()->id;
            $pedido->observaciones = "EN PROCESO";
            $pedido->save();

            // asignamos los productos al pedido
            foreach ($request->productos as $prod) {
                $producto_id = $prod["producto_id"];
                $cantidad = $prod["cantidad"];
                
                $pedido->productos()->attach($producto_id, ["cantidad" => $cantidad]);
            }

            $pedido->estado = 2;
            $pedido->observaciones = $request->observaciones;
            $pedido->update();

            DB::commit();

            return response()->json(["mensaje" => "Pedido registrado"], 201);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(["mensaje" => "Error al registrar el Pedido"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
