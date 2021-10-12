<?php

namespace App\Http\Controllers;

use App\carritodetalle;
use App\Clases\EnCarritoModel;
use App\Clases\ValidacionEnCarrito;
use App\clientes;
use App\direcciones;
use App\Mail\OrderCheckout;
use App\paises;
use App\pedidos;
use App\productos;
use App\productospedido;
use App\productovariantesdetalle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $datos)
    {
        /*CONFIRMACION EN TIENDA DE PEDIDO*/
        /*Se Actualiza la dirección y se guarda el pedido*/
        /*==========================================*/
        /*Comparar total de usuario contra total sistema */
        /*Marcar y mover los productos al pedido*/

        DB::beginTransaction();
        
        $mostrarError = false;

        $problemas = ["total" => "", "moneda" =>"", "codigo_postal" =>"",
            "sistema" =>"", "nombre" =>"", "telefono" =>"", "correo" => "",
            "calle" =>"", "iddireccion" =>"", "codigo_pais" =>"", "idcliente"=>""];

        try {
            
            $idCliente = 0;
            $cantidadProductosEnCarrito =0;
            $idDireccion = 0;

            //se debera actualizar dirección
            //guardar nuevo pedido
            if (!isset($datos->idCliente))
            {
                $problemas["sistema"] = "no se establecio cliente";
                $mostrarError = true;
            }
            else
            {
                //desifrar el id cliente 
                $idDecencriptado = Crypt::decryptString($datos->idCliente);
                $idCliente = $idDecencriptado;    

                $cliente = clientes::where([
                    ['IdCliente', '=', $idCliente]
                ])->first();

                if (!isset($cliente))
                {
                    $problemas["sistema"] = "no se encontro cliente";
                    $mostrarError = true;   
                }
            }
            
            if (!isset($datos->direccion))
            {
                $problemas["sistema"] = "no se establecio direccion.";
                $mostrarError = true;
            }

            if (!isset($datos->direccion["idDireccion"]))
            {
                $problemas["sistema"] = "no se establecio direccion";
                $mostrarError = true;
            }
            if (!isset($datos->direccion["idPais"]))
            {
                $problemas["sistema"] = "no se establecio idPais";
                $mostrarError = true;
            }
            

            if (!isset($datos->moneda))
            {
                $problemas["sistema"] = "no se establecio moneda";
                $mostrarError = true;
            }
            if (!isset($datos->total))
            {
                $problemas["sistema"] = "no se establecio total";
                $mostrarError = true;
            }
            if (!isset($datos->precioEnvio))
            {
                $problemas["sistema"] = "no se establecio envio";
                $mostrarError = true;
            }

            if (!isset($datos->subtotal))
            {
                $problemas["sistema"] = "no se establecio subtotal";
                $mostrarError = true;
            }
            
            if (!isset($datos->idPaypal))
            {
                $problemas["sistema"] = "no se establecio pago paypal";
                $mostrarError = true;
            }
            if (!isset($datos->productos))
            {
                $problemas["sistema"] = "no se establecieron los productos";
                $mostrarError = true;
            }
            else
            {
                $cantidadProductosEnCarrito = count($datos->productos);
                if ($cantidadProductosEnCarrito == 0)
                {
                    $problemas["sistema"] = "deben existir al menos un producto";
                    $mostrarError = true;

                }
            }

            if ($mostrarError)
            {
                return response(json_encode($problemas), 400);
            }
            
            $idDireccion = $datos->direccion["idDireccion"];

            $direccion = direcciones::where('idDireccion', '=',$idDireccion)->where('IdCliente', $idCliente)->first();

            if (!isset($direccion))
            {

                $direccion = new direcciones();
                $direccion->Pais = $datos->direccion["pais"];
                $direccion->CodigoPostal = $datos->direccion["codigo_postal"];
                $direccion->Ciudad = $datos->direccion["ciudad"];   
                $direccion->Calle = $datos->direccion["calle"];   
                $direccion->Colonia = $datos->direccion["colonia"];   
                $direccion->IdCliente = $idCliente;   
                $direccion->nombre = $datos->direccion["nombre"];  
                $direccion->apellido = $datos->direccion["apellido"];
                $direccion->esPrincipal = 1;
                $direccion->telefono = $datos->direccion["telefono"];
                $direccion->company = (isset($datos->direccion["company"]) ? $datos->direccion["company"] : '' );
                $direccion->estado = $datos->direccion["estado"];
                $direccion->idPais = $datos->direccion["idPais"];
                $direccion->save();

            }
            else
            {
                $direccion->nombre = $datos->nombre;
                $direccion->apellido = $datos->apellido;
                $direccion->idPais = $datos->direccion["idPais"];
                $direccion->save();
            }

            if ($mostrarError)
            {
                return response(json_encode($problemas), 400);
            }

            /*guardar pedido*/
            $fechaPedido = date('Y-m-d');
            $pedido = new pedidos();
            $pedido->idEstatusPedido = 1;
            $pedido->fechaPedido = $fechaPedido;
            $pedido->idCliente = $idCliente;
            $pedido->fechaCambio = date('Y-m-d');
            $pedido->idTipoMovimiento = 1;
            $pedido->idUsuarioBase = 1;
            $pedido->idPago = 1;
            $pedido->idDireccion = $direccion->IdDireccion;
            $pedido->moneda = $datos->moneda;
            $pedido->total = $datos->total;
            $pedido->precioEnvio = $datos->precioEnvio;
            $pedido->subtotal = $datos->subtotal;
            $pedido->nombre = $datos->nombre;
            $pedido->apellido = $datos->apellido;
            $pedido->idPagoPaypal = $datos->idPaypal;
            $pedido->save();

            $dataProducto = json_encode($datos->productos);
            $dataProducto = json_decode($dataProducto);

            $cantidadAplicados = 0;

            foreach ($dataProducto  as $producto) {
             

                $validacion = new ValidacionEnCarrito();
                $productoCarrito = new EnCarritoModel();
                $productoCarrito->idDetalle = $producto->idDetalle;
                $productoCarrito->idProducto = $producto->idProducto;
                $productoCarrito->idCliente = $idCliente;
                $productoCarrito->idProductoVarianteDetalle = $producto->idProductoVarianteDetalle;
                $productoCarrito->precio = $producto->precio;

                
                $MensajeError = "";

                $productoEnElCarrito = $validacion->ValidarSiExiteEnCarrito($productoCarrito, $mostrarError, $MensajeError);
                
                
                
                if ($mostrarError)
                {
                    return response(json_encode($MensajeError), 400);
                }

                $pedidoProducto = new productospedido();
                $pedidoProducto->IdPedido = $pedido->IdPedido;
                $pedidoProducto->IdProducto = $producto->idProducto;
                $pedidoProducto->idProductoVarianteDetalle  = $producto ->idProductoVarianteDetalle;
                $pedidoProducto->Cantidad  = $producto->cantidad;
                $pedidoProducto->peso  = $producto->peso;
                $pedidoProducto->alto  = $producto->alto;
                $pedidoProducto->ancho  = $producto->ancho;
                $pedidoProducto->largo  = $producto->largo;
                $pedidoProducto->save();

                $productoEnElCarrito->delete();

                $cantidadAplicados +=1;

            } /*FIN DE CLICLO PRODUCTOS */

            if ($cantidadAplicados != $cantidadAplicados)
            {
                $problemas["sistema"] = "En productos, no se aplicaron la misma cantidad de productos en el carrito";
                $mostrarError = true;
                throw new Exception ("no se guardo, visualizar problema en sistema");
            }

            Mail::to($cliente->CorreoElectronico)->send(new OrderCheckout($pedido));
            DB::commit();
            return $pedido;
        
        } catch (Exception $th) {

            DB::rollback();

            
            if ($mostrarError == true)
            {
                return response(json_encode($problemas), 400);
            }
            else
            {
                return response("no se pudo guardar el pedido, favor de validar información".$th->getMessage(), "400");
            }
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function porCliente($idcliente)
    {
        $pedido = pedidos::where('idcliente', $idcliente)->first();
        $cliente = clientes::where('idcliente', $idcliente)->first();
        $direccion = direcciones::where('idcliente', $idcliente)->where('esPrincipal', 1)->first();

        $pedido_data =  [];
        
        $pedido_data["moneda"] = $pedido->moneda;
        
        $pedido_data["precio"] = $pedido->total;
        $pedido_data["IdPedido"] = $pedido->IdPedido;
        $pedido_data["cliente"] = $cliente->Nombre." ".$cliente->Apellidos;
        $pedido_data["correo"] = $cliente->CorreoElectronico;
        $pedido_data["direccion"] = $direccion;
        /**datos cliente */
        
        return response($pedido_data, 200);
        
    }

    public function pendientes()
    {
        $lista_pedidos = pedidos::join('clientes', 'clientes.idcliente', '=', 'pedidos.idcliente')
        ->join('direcciones', 'direcciones.idcliente', '=', 'pedidos.idcliente')
        ->where('IdEstatusPedido', 1)
        ->select(
            [DB::raw("CONCAT(pedidos.nombre, ' ', pedidos.apellido) AS nombre_recibe"), 
            DB::raw("CONCAT(clientes.Nombre, ' ', clientes.Apellidos) AS nombre_cliente"), 
            "pedidos.*", "direcciones.*", "clientes.CorreoElectronico as correo", "direcciones.Calle as calle", 
            DB::raw("'MX' as codigo_pais"), "direcciones.Ciudad as ciudad"])->get();
       
        return $lista_pedidos;
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

    public function ventasObtener()
    {
        $listaVentas = pedidos::join('clientes','clientes.IdCliente', 'pedidos.IdCliente')->
            select(['pedidos.*', DB::raw("CONCAT(clientes.Nombre, ' ', clientes.Apellidos) AS nombreCliente")])->get();

        return $listaVentas;
    }
    public function ventasPorId($idVenta)
    {
        $venta = pedidos::join('clientes','clientes.IdCliente', 'pedidos.IdCliente')->
            select(['pedidos.*', DB::raw("CONCAT(clientes.Nombre, ' ', clientes.Apellidos) AS nombreCliente")])->
            where('pedidos.IdPedido', '=', $idVenta)
            ->first();

        return $venta;
    }
    public function ventasObtenerDetalle($idVenta)
    {
        $listaProductos = productos::join('productospedido', 'productospedido.IdProducto', 'producto.idProducto')
            ->where('productospedido.IdPedido', '=', $idVenta)->get();

        return $listaProductos;
    }
}
