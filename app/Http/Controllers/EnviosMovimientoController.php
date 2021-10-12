<?php

namespace App\Http\Controllers;


use App\Clases\DHL\DHL;
use App\Clases\DHL\PackageModel;
use App\Clases\EnCarritoModel;
use App\Clases\ValidacionEnCarrito;
use App\direcciones;
use App\envios_cotizacion;
use App\Envios_Movimiento;
use App\paises;
use App\pedidos;
use DateTime;
use DateTimeZone;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EnviosMovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $empaquetados = pedidos::join('envios_movimiento', 'envios_movimiento.idpedido',  '=', 'pedidos.IdPedido')
        ->join('direcciones', 'direcciones.idcliente', '=', 'pedidos.IdCliente')
        ->join('clientes', 'clientes.IdCliente', '=', 'pedidos.IdCliente')
        ->where('IdEstatusPedido', 2)
        ->select(
            [DB::raw("CONCAT(pedidos.nombre, ' ', pedidos.apellido) AS nombre_recibe"), 
            DB::raw("CONCAT(clientes.Nombre, ' ', clientes.Apellidos) AS nombre_cliente"), 
            "pedidos.*", "direcciones.*", "clientes.CorreoElectronico as correo", "direcciones.Calle as calle", 
            DB::raw("'MX' as codigo_pais"), "direcciones.Ciudad as ciudad", "envios_movimiento.*"])
        ->get();
        return $empaquetados;
    }

    public function pdfPorMovimiento($idmovimiento)
    {
        $mov = pedidos::join('envios_movimiento', 'envios_movimiento.idpedido',  '=', 'pedidos.IdPedido')
        ->where('pedidos.IdPedido',$idmovimiento)->first();
        return $mov;
    }

    public function crearDocumentoPdf($idpedido)
    {
        $movimiento = Envios_Movimiento::where('idpedido', $idpedido)->first();
          /**GENERAR PDF */
            
        try {
            $b64 = $movimiento->archvo_base64;
            
            $bin = base64_decode($b64, true);
            if (strpos($bin, '%PDF') !== 0) {
                throw new Exception('Missing the PDF file signature');
            }
            $filename =$movimiento->idmovimiento."-".$movimiento->trackingnumber;
            file_put_contents($filename.'.pdf', $bin);
        } catch (\Throwable $th) {
            
        } 

        return $movimiento;
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
    public function store(Request $data_info)
    {
        /*CONFIRMAR PEDIDO EN TIENDA*/
        //Se debe cotizar nuevamente para validar el precio de envio
        

        $hasError = false;
        $problemas = ["total" => "", "moneda" =>"", "codigo_postal" =>"",
            "sistema" =>"", "nombre" =>"", "telefono" =>"", "correo" => "",
            "calle" =>"", "cuidad" =>"", "codigo_pais" =>"", "idpedido"=>"", "sistema" =>""];

        if (!isset($data_info->total))
        {
            $problemas["precio"] ="No se establecio el precio";
            $hasError = true;
        }

        if (!isset($data_info->IdPedido))
        {
            $problemas["idpedido"] ="No se establecio el idpedido";
            $hasError = true;
        }

        if (!isset($data_info->nombre_recibe))
        {
            $problemas["nombre"] ="No se establecio el nombre";
            $hasError = true;
        }

        if (!isset($data_info->telefono))
        {
            $problemas["telefono"] ="No se establecio el telefono";
            $hasError = true;
        }
        if (!isset($data_info->ciudad))
        {
            $problemas["ciudad"] ="No se establecio la cuidad";
            $hasError = true;
        }
        if (!isset($data_info->calle))
        {
            $problemas["calle"] ="No se establecio la calle";
            $hasError = true;
        }
        if (!isset($data_info->codigo_pais))
        {
            $problemas["codigo_pais"] ="No se establecio el codigo de pais";
            $hasError = true;
        }
        if (!isset($data_info->correo))
        {
            $problemas["correo"] ="No se establecio el correo";
            $hasError = true;
        }
        if (!isset($data_info->moneda))
        {
            $problemas["moneda"] ="No se establecio el moneda";
            $hasError = true;
        }

        if (!isset($data_info->CodigoPostal))
        {
            $problemas["codigo_postal"] ="No se establecio el codigo postal";
            $hasError = true;
        }

        if (!isset($data_info->IdCliente))
        {
            $problemas["sistema"] ="No se establecio el cliente";
            $hasError = true;
        }

        if ($hasError)
        {
            return response($problemas, 400);
        }
        

        $pedido_valido = pedidos::where('IdPedido', $data_info->IdPedido)->first();
        if (!isset($pedido_valido))
        {
            return response("no existe pedido ".$data_info->IdPedido, 400);
        }

        

        $pedido_valido = pedidos::where('IdPedido', $data_info->IdPedido)->where('IdEstatusPedido', 1)->first();
        if (!isset($pedido_valido))
        {
            return response("el pedido no esta en estatus Carrito", 400);
        }

        $direccion = direcciones::where('IdDireccion', $pedido_valido->iddireccion)->first();

        if (!isset($direccion))
        {
            return response("no se encontro direccion",400);
        }

        $pais = paises::where('idPais', $direccion->idPais)->first();
        
        if (!isset($pais))
        {
            return response("no se encontro pais",400);
        }

        $esNacional = ($pais->idPais == 157);

        $empacado = new PackageModel();
        $empacado->number = 1;
        $empacado->height = 1;
        $empacado->length = 1;
        $empacado->weight=2;
        $empacado->width=5;

        

        $dhl_service = new DHL();

        if($esNacional)
        {
            $info = $dhl_service->ShipmentInfo($data_info->total, $data_info->moneda);
        }
        else
        {
            
            $info = $dhl_service->ShipmentInfoInternacional($data_info->total, $data_info->moneda);
        }
        
        $ship = $dhl_service->ShipmentRequested(
        $info,$data_info->CodigoPostal,
        $data_info->nombre_recibe, $data_info->nombre_recibe, $data_info->telefono,
        $data_info->correo, $data_info->calle,
        $data_info->ciudad, $pais->codigo, $empacado);

       // echo json_encode($ship);
        //return;
        $response = $dhl_service->ShipingApiCall($ship);

        if (isset($response["ShipmentResponse"]))
        {
            $datos = ["TrackingNumber" =>"0", "LabelImageFormat"=> ""];
            if (isset($response["ShipmentResponse"]["PackagesResult"]))
            {
                $packageResult = $response["ShipmentResponse"]["PackagesResult"]["PackageResult"][0];
            }
            else
            {
                if (isset($response["ShipmentResponse"]["Notification"][0]))
                {
                    return response($response["ShipmentResponse"]["Notification"][0]["Message"], 400);
                }
                else
                {
                    return response('JSON DHL'.json_encode($ship).json_encode($response), 400);
                }
                
            }
            
            $labelImage =$response["ShipmentResponse"]["LabelImage"][0]["LabelImageFormat"];
            $Archvo64 = $response["ShipmentResponse"]["LabelImage"][0]["GraphicImage"];
            if (isset($packageResult))
            {
                $datos["TrackingNumber"] = $packageResult["TrackingNumber"];
            }
            if (isset($labelImage))
            {
                $datos["LabelImageFormat"] = $labelImage;
            }

            $movimiento = new Envios_Movimiento();
            $movimiento->packagesresult_number = 1;
            $movimiento->trackingnumber = $datos["TrackingNumber"];
            $movimiento->tipo_archivo = "PDF";
            $movimiento->archvo_base64 = $Archvo64;
            $movimiento->ShipmentIdentificationNumber = 1;
            $movimiento->filename = "";
            $movimiento->idpedido = $data_info->IdPedido;
            $movimiento->save();


            $filename =$movimiento->idmovimiento."-".$movimiento->trackingnumber;
            $movimiento->filename = $filename;
            $movimiento->save();
            
            $pedido = pedidos::find($data_info->IdPedido);
            $pedido->IdEstatusPedido = 2;
            $pedido->save();

            /**GENERAR PDF */
            
            try {
                $b64 = $movimiento->archvo_base64;
                
                $bin = base64_decode($b64, true);
                if (strpos($bin, '%PDF') !== 0) {
                    throw new Exception('Missing the PDF file signature');
                }
    
                file_put_contents($filename.'.pdf', $bin);
            } catch (\Throwable $th) {
                
            } 

           

            return $movimiento->idmovimiento;
        }
        else
        {
            
            $problemas["sistema"] = "No se pudo obtener cotización";
        
            return response($problemas, "400");
        }

        return $data_info;
    }

    public function cotizar(Request $data_info)
    {

        /*COTIZACIÓN EN TIENDA*/

        $hasError = false;
        $problemas = ["precio" => "", "moneda" =>"", "codigo_postal" =>"", "sistema" =>""];

        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('GMT'));

        //return $date->format('Y-m-d\TH:i:s');
        
        if (!isset($data_info->precio))
        {
            $problemas["precio"] ="No se establecio el precio";
            $hasError = true;
        }
        if (!isset($data_info->idCliente))
        {
            $problemas["precio"] ="No se establecio el cliente";
            $hasError = true;
        }
        if (!isset($data_info->moneda))
        {
            $problemas["moneda"] ="No se establecio el moneda";
            $hasError = true;
        }

        if (!isset($data_info->codigo_postal))
        {
            $problemas["codigo_postal"] ="No se establecio el codigo postal";
            $hasError = true;
        }

        if (!isset($data_info->idPais) || $data_info->idPais == 0)
        {
            $problemas["codigo_postal"] ="No se establecio el pais";
            $hasError = true;
        }

        if (!isset($data_info->productos))
        {
            $problemas["sistema"] ="No se establecio la lista de productos en el carrito";
            $hasError = true;
        }

        if ($hasError)
        {
            http_response_code(400);
            echo  json_encode($problemas);
            return;
        }

        $dataProducto = json_encode($data_info->productos);
        $dataProducto = json_decode($dataProducto);

        $idCliente = Crypt::decryptString($data_info->idCliente);

        $cantidadAplicados = 0;
        $alto = 0;
        $ancho = 0;
        $largo  = 0;
        $peso = 0;

        foreach ($dataProducto  as $producto) {
             

            $validacion = new ValidacionEnCarrito();
            $productoCarrito = new EnCarritoModel();
            $productoCarrito->idDetalle = $producto->idDetalle;
            $productoCarrito->idProducto = $producto->idProducto;
            $productoCarrito->idCliente = $idCliente;
            $productoCarrito->idProductoVarianteDetalle = $producto->idProductoVarianteDetalle;
            $productoCarrito->precio = $producto->precio;

            
            $MensajeError = "";
            $productoBusqueda = null;

            $productoEnElCarrito = $validacion->ValidarSiExiteEnCarrito($productoCarrito, $mostrarError, $MensajeError, $productoBusqueda);
            
            if ($mostrarError)
            {
                throw new Exception($MensajeError);
                
            }

            $cantidad =$productoEnElCarrito->cantidad;
            $alto = $alto + ($cantidad * $productoBusqueda->alto);
            $ancho = $ancho + ($cantidad * $productoBusqueda->ancho);
            $largo = $largo + ($cantidad * $productoBusqueda->largo);
            $peso = $peso + ($cantidad * $productoBusqueda->peso);

            $cantidadAplicados +=1;

        } /*FIN DE CLICLO PRODUCTOS */

        $peso = round($peso, 4);
        //return array("alto" => $alto, "ancho" => $ancho, "largo" => $largo, "peso" => $peso);

        /*Id Pais 157 MEXICO*/
        $esNacional = ($data_info->idPais == 157);
        $dhl_service = new DHL();

        
        //return var_dump($solicitud);

        if ($esNacional)
        {
            $solicitud = $dhl_service->GetRateRequest($data_info->precio,$data_info->moneda,"97133",$data_info->codigo_postal,
                $cantidadAplicados, $peso,$largo,$ancho,$alto, "MX");

            $respuesta = $dhl_service->RateApiCall(
                round($data_info->precio, 2),
                $data_info->moneda,
                "97133",
                $data_info->codigo_postal,$cantidadAplicados, $peso,$largo,$ancho,$alto, "MX");
        }
        else
        {
            /*buscar coodigo pais */
            $pais = paises::where('idPais', $data_info->idPais)->first();
            
            if (!isset($pais))
            {
                return response("no se encontro pais destino",400);
            }
            if (!isset($pais->codigo))
            {
                return response("no se encontro el codigo del pais", 400);
            }

            $solicitud = $dhl_service->GetRateRequestInternacional($data_info->precio,$data_info->moneda,"97133",$data_info->codigo_postal,
            $cantidadAplicados, $peso,$largo,$ancho,$alto, $pais->codigo);

            
            $respuesta = $dhl_service->RateApiCallInternacional(
                round($data_info->precio, 2),
                $data_info->moneda,
                "97133",
                $data_info->codigo_postal,$cantidadAplicados, $peso,$largo,$ancho,$alto,$pais->codigo);
        }
        
        
        $datos_respuesta = $respuesta;

        /*leer la config de dias dhl*/
        $configDiasDHL = new ConfiguracionesController();
        $diasAgregar = $configDiasDHL->diasdhl();

        if ($esNacional)
        {
            if (isset($datos_respuesta["RateResponse"]["Provider"][0]["Service"]["TotalNet"]))
            {
                $servicio =$datos_respuesta["RateResponse"]["Provider"][0]["Service"];
                $precio = $servicio["TotalNet"]["Amount"];            
                $moneda = $servicio["TotalNet"]["Currency"];
    
                if (!isset($precio))
                {
                    return response("no se establecio amount dh", 400);
                }
                if (!isset($moneda))
                {
                    return response("no esta establecida la moneda", 400);
                }
    
                /*fechas*/
                $fechaEntrega =  date_create( $servicio["DeliveryTime"]);
                $fechaInicio = date_create( $servicio["CutoffTime"]);
                $diferencia = date_diff($fechaInicio, $fechaEntrega);
    
                /*obtener diferencia dias*/ 
                
                $cotizacion = [];
                $cotizacion["precio"] = $precio;
                $cotizacion["moneda"] = $moneda;
                $cotizacion["dias"] = $diferencia->format("%d");
    
    
                /*guardar en BD*/
                $cotizacion_row = new envios_cotizacion;
                $cotizacion_row->idcliente = 1;
                $cotizacion_row->moneda = $moneda;
                $cotizacion_row->precio = $precio;
                $cotizacion_row->dias = $cotizacion["dias"];
                $cotizacion_row->diasExtras = $diasAgregar;
                $cotizacion_row->json= json_encode($respuesta);
                $cotizacion_row->solicitud_json = json_encode($solicitud);
                $cotizacion_row->save();
                $cotizacion_row->dias = (int)$cotizacion_row->dias + (int)$cotizacion_row->diasExtras;
    
                $cotizacion_row->json = "";
                $cotizacion_row->solicitud_json = "";
                return $cotizacion_row;
            }
            else
            {
                return response("no se obtuvo respuesta con el formato especificado", 400);
            }
        }
        else
        {

            if (isset($datos_respuesta["RateResponse"]["Provider"]["Service"]["TotalNet"]))
            {
                $servicio =$datos_respuesta["RateResponse"]["Provider"]["Service"];
                $precio = $servicio["TotalNet"]["Amount"];            
                $moneda = $servicio["TotalNet"]["Currency"];
    
                if (!isset($precio))
                {
                    return response("no se establecio amount dh", 400);
                }
                if (!isset($moneda))
                {
                    return response($solicitud, 400);
                }
    
                /*fechas*/
                $fechaEntrega =  date_create( $servicio["DeliveryTime"]);
                $fechaInicio = date_create( $servicio["CutoffTime"]);
                $diferencia = date_diff($fechaInicio, $fechaEntrega);
    
                /*obtener diferencia dias*/ 
                
                $cotizacion = [];
                $cotizacion["precio"] = $precio;
                $cotizacion["moneda"] = $moneda;
                $cotizacion["dias"] = $diferencia->format("%d");
    
    
                /*guardar en BD*/
                $cotizacion_row = new envios_cotizacion;
                $cotizacion_row->idcliente = 1;
                $cotizacion_row->moneda = $moneda;
                $cotizacion_row->precio = $precio;
                $cotizacion_row->dias = $cotizacion["dias"];
                $cotizacion_row->diasExtras = $diasAgregar;
                $cotizacion_row->json= json_encode($respuesta);
                $cotizacion_row->solicitud_json = json_encode($solicitud);
                $cotizacion_row->save();
                $cotizacion_row->dias = (int)$cotizacion_row->dias + (int)$cotizacion_row->diasExtras;
    
                $cotizacion_row->json = "";
                $cotizacion_row->solicitud_json = "";
                return $cotizacion_row;
            }
            else
            {
                return response("no se encontro formato de respuesta envio".json_encode($solicitud));
            }
        }
       
        
        return "problem";
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
}
