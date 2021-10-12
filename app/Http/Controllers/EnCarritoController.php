<?php

namespace App\Http\Controllers;

use App\carritodetalle;
use App\productoimagenes;
use App\productos;
use App\productovariantesdetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EnCarritoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataProducto = $request->producto;
        $dataMovimiento = $request->movimiento;
        
        $dataProducto = json_encode($dataProducto);
        $dataProducto = json_decode($dataProducto);

        $productoBusqueda = productos::where('idProducto', $dataProducto->idProducto)->first();

        if (!isset($productoBusqueda))
        {
            return response("no se encontro el producto para el carrito", 400);
        }

        /*Precios son iguales?*/
        if ($dataProducto->precio != $productoBusqueda->precio)
        {
            return response("el precio de este producto ha cambiado", 400);
        }

        
        $dataProducto->idCliente = Crypt::decryptString($dataProducto->idCliente);

        /*validar si existe variantes de este producto*/
        if ($productoBusqueda->manejaraTallas)
        {
            if (!isset($dataProducto->idProductoVarianteDetalle) || $dataProducto->idProductoVarianteDetalle == 0)
            {
                return response("no se establecio la talla del producto", 400);
            }

            $varianteProducto = productovariantesdetalle::where([
                ["idProducto", '=', $dataProducto->idProducto],
                ['idProductoVarianteDetalle', '=', $dataProducto->idProductoVarianteDetalle]
            ])->first();

            if (!isset($varianteProducto))
            {
                return response("no existe la variante de tallas seleccionada",400);
            }

        }
        
        $enCarritoCliente = new carritodetalle();

        switch ($dataMovimiento) {
            case 'AGREGAR':

                $enCarritoCliente = carritodetalle::where([
                    ['idProducto', '=', $dataProducto->idProducto],
                    ['idCliente', '=', $dataProducto->idCliente],
                    ['idProductoVarianteDetalle', '=', $dataProducto->idProductoVarianteDetalle]
                ])->first();

                if (isset($enCarritoCliente))
                {
                    return response("ya se encuentra este producto en el carrito", 400);
                }

                $enCarritoCliente = new carritodetalle();
                $enCarritoCliente->idProducto = $dataProducto->idProducto;
                $enCarritoCliente->cantidad = 1;
                $enCarritoCliente->precio = $dataProducto->precio;
                $enCarritoCliente->idCliente = $dataProducto->idCliente;

                if ($productoBusqueda->manejaraTallas)
                {
                    /*solo si hay inventario en variante*/
                    if ($varianteProducto->inventario <= 0)
                    {
                        return response("no hay suficiente inventario de esta talla", 400);
                    }
                    
                    $enCarritoCliente->idProductoVarianteDetalle =$dataProducto->idProductoVarianteDetalle;

                }
                else
                {
                    $enCarritoCliente->idProductoVarianteDetalle = null;
                }

                break;

            case 'MODIFICAR':

                $enCarritoCliente = carritodetalle::where([
                    ['idProducto', '=', $dataProducto->idProducto],
                    ['idCliente', '=', $dataProducto->idCliente]
                ])->first();
                
                if (!isset($enCarritoCliente))
                {
                    response("no se encontro producto en carrito para actualizar", 400);
                }

                if (!isset($dataProducto->cantidad))
                {
                    response("no se establecio cantidad en carrito para actualizar", 400);
                }
                if ($dataProducto->cantidad == 0 || !is_int($dataProducto->cantidad))
                {
                    response("no se  cantidad valida en carrito para actualizar", 400);
                }
                $enCarritoCliente->idProducto = $dataProducto->idProducto;
                $enCarritoCliente->cantidad = $dataProducto->cantidad;
                $enCarritoCliente->precio = $dataProducto->precio;
                $enCarritoCliente->idCliente = $dataProducto->idCliente;


                break;
            case 'ELIMINAR':
                $enCarritoCliente = carritodetalle::where([
                    ['idProducto', '=', $dataProducto->idProducto],
                    ['idCliente', '=', $dataProducto->idCliente]
                ])->first();
                
                if (!isset($enCarritoCliente))
                {
                    response("no se encontro producto en carrito para actualizar", 400);
                }

                $enCarritoCliente->delete();

                return "eliminado";

                break;
            default:
                $enCarritoCliente = null;
                break;
        }
        
        if (!isset($enCarritoCliente))
        {
            return response("no se establecio el carrito para el cliente", 400);
        }

        $enCarritoCliente->save();
        
        return $enCarritoCliente;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Crypt::decryptString($id);

        $enCarrito = carritodetalle::where('idCliente', '=', $id)->
            join('producto', 'producto.idProducto', '=', 'detallecarrito.idProducto')->
            leftJoin('productovariantesdetalle', 
            'productovariantesdetalle.idProductoVarianteDetalle', '=', 'detallecarrito.idProductoVarianteDetalle')
            ->select(['detallecarrito.*', 'producto.*',
                'productovariantesdetalle.nombre as variante', 'productovariantesdetalle.valor'])->get();

        foreach ($enCarrito as $producto) {
            $imagen = productoimagenes::where('idProducto', '=', $producto->idProducto)
            ->select('ruta')->first();

            $imagen->base64 ="";
            $producto->ruta = url('/').'/'.$imagen->ruta;
            
        }
        if (!isset($enCarrito))
        {
            return response("No hay productos en el carrito");
        }

        return response()->json($enCarrito);
    }
    public function cantidad(Request $request)
    {

        $id = Crypt::decryptString($request->idCliente);

        $enCarrito = DB::select("SELECT COUNT(*) as cantidad FROM detallecarrito where idCliente = ".$id);

        return $enCarrito;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function buscarInfo($idProducto)
    {
        /**Se obtendran datos para consultas de productos locales */
        /*Imagen principal */

        $imagenPrincipal = productoimagenes::where('idProducto', $idProducto)->select('ruta')->first();

        $imagenPrincipal->ruta = url('/').'/'.$imagenPrincipal->ruta;

        return ["imagen" => $imagenPrincipal];
    }
}
