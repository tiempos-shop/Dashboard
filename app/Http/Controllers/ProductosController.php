<?php

namespace App\Http\Controllers;

use App\colecciones;
use App\menus;
use App\productoidiomas;
use App\productoimagenes;
use App\productomenu;
use App\productos;
use App\productovariantesdetalle;
use App\tipoproductos;
use App\variantesdetalle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = productos::get();
        
        return $productos;
    }

    public function productostiendabusqueda(Request $request)
    {
        /*para busqueda en los productos en tienda */

        $idMenu = $request->idMenu;

        if (isset($idMenu) && $idMenu>0)
        {
            
            $productos = DB::select("SELECT producto.idProducto, nombre, precio, precioComparativo, color FROM producto
            INNER JOIN productomenu on productomenu.idProducto = producto.idProducto where productomenu.idMenu =".$idMenu);
        }
        else
        {
            $productos = DB::select("SELECT idProducto, nombre, precio, precioComparativo, color FROM producto");
            
        }
        
        $urlServidor =url('/');
        
        foreach ($productos as $produc) {
          
            $imagenes = DB::select("SELECT idImagen, ruta FROM productoimagenes where idProducto = ".$produc->idProducto);
            
            foreach ($imagenes as $imagen) {
                $imagen->ruta = $urlServidor.'/'.$imagen->ruta;
            }

            $produc->imagen =$imagenes;
        }

       
        return response()->json($productos);
    }

    public function imagenesproductos()
    {
        $imagenes = productoimagenes::select('idImagen','idProducto', 'ruta')->get();
        
        foreach ($imagenes as $imagen) {
            $imagen->base64 = "";
            $imagen->ruta = url('/').'/'.$imagen->ruta;
        }

        return $imagenes;
    }
    public function productostienda()
    {
        /*para busqueda en los productos en tienda */

        
        
        $productos = DB::select("SELECT idProducto, nombre, precio, descripcion, precioComparativo FROM producto");
        
        foreach ($productos as $produc) {
            $imagenes = productoimagenes::where('idProducto', '=', $produc->idProducto)->
                select('idImagen', 'idProducto', 'idVariante', 'ruta', 'extension') ->get();
            
            foreach ($imagenes as $imagen) {
                $imagen->base64 = "";
                $imagen->ruta = url('/').'/'.$imagen->ruta;
            }

            $produc->imagen =$imagenes;
        }

       
        return $productos;
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
        
        
        $datosProducto = $request->producto;
        $datosImagenes = $request->imagenes;
        $datosVarianteDetalles = $request->varianteDetalles;
        $datosEtiquetas =$request->etiquetas;
        $datosIdioma = $request->english;
        $datosGuiaTallas = $request->rutaGuiaTallas;

        DB::beginTransaction();
        
        $mostrarError = false;

        try {
            if (isset($datosProducto["idProducto"]) && $datosProducto["idProducto"]>0)
            {
                $producto = productos::where('idProducto', $datosProducto["idProducto"])->first();
                
                if (!isset($producto))
                {
                    $mostrarError = true;
                    throw new Exception ("no se encontro producto para actualizar");
                    
                }

                if ($producto == null)
                {
                    $mostrarError = true;
                    throw new Exception ("no se encontro producto");
                }
                
            }
            else
            {
                $producto = new productos();
                
            }
            if (!isset($datosProducto["sku"]))
            {
                $mostrarError = true;
                throw new Exception("no se ingreso el sku del producto");
            }


            if (!isset($datosIdioma))
            {
                $mostrarError = true;
                throw new Exception("no se ingreso el idioma ingles del producto");
            }
            else
            {
                //validacion de campos de english
                $english = $datosIdioma;

                if (!isset($english["idProductoIdioma"]))
                {
                    $mostrarError = true;
                    throw new Exception("no se ingreso el id en ingles del producto");
                }

                if (!isset($english["nombre"]))
                {
                    $mostrarError = true;
                    throw new Exception("no se ingreso el nombre en ingles del producto");
                }

                if (!isset($english["descripcion"]))
                {
                    $mostrarError = true;
                    throw new Exception("no se ingreso la descripcion en ingles del producto");
                }
            }

           

            /*agregando tipos*/
            if (isset($datosProducto["tipo"]))
            {
                $tipoProducto = tipoproductos::where('tipo', $datosProducto["tipo"])->first();
                if (!isset($tipoProducto))
                {
                    $tipoProducto = new tipoproductos();
                    $tipoProducto->tipo =$datosProducto["tipo"];
                    $tipoProducto->activo = 1;
                    $tipoProducto->save();
                }
            }
            
            $idMenu = $datosProducto["idMenu"];
            

            $producto->sku = $datosProducto["sku"];
            $producto->nombre = $datosProducto["nombre"];
            $producto->color = $datosProducto["color"];
            $producto->descripcion = $datosProducto["descripcion"];
            $producto->precio = $datosProducto["precio"];
            $producto->precioComparativo = (isset($datosProducto["precioComparativo"]) ? $datosProducto["precioComparativo"] : 0);
            $producto->costo = $datosProducto["costo"];
            $producto->codigoBarras = (isset($datosProducto["codigoBarras"]) ? $datosProducto["codigoBarras"] : '');
            $producto->peso = (isset($datosProducto["peso"]) ? $datosProducto["peso"] : 0);
            $producto->inventario = (isset($datosProducto["inventario"]) ? $datosProducto["inventario"] : 0);
            $producto->manejaInventario = (isset($datosProducto["manejaInventario"]) ? $datosProducto["manejaInventario"] : 0);
            $producto->manejaraTallas = (isset($datosProducto["manejaraTallas"]) ? $datosProducto["manejaraTallas"] : 0);
            $producto->alto = (isset($datosProducto["alto"]) ? $datosProducto["alto"] : 0);
            $producto->ancho = (isset($datosProducto["ancho"]) ? $datosProducto["ancho"] : 0);
            $producto->largo = (isset($datosProducto["largo"]) ? $datosProducto["largo"] : 0);
            $producto->idColeccion = 0;
            $producto->idTipo = (isset($tipoProducto) && isset($tipoProducto->idTipo) ? $tipoProducto->idTipo : 0);
            $producto->rutaGuiaTallas = "";
            $producto->save();

            $rutaGuiaTallas = "";
            if (isset($datosGuiaTallas))
            {
                if (!isset($datosGuiaTallas["base64"]) & !isset($datosGuiaTallas["base64"]) & strlen($datosGuiaTallas["ruta"]) == 0 )
                {
                    $mostrarError = true;
                    throw new Exception("no se subio una guia de tallas");
                }

                //solo si tiene datos en base64 se actualizara el registro
                if (isset($datosGuiaTallas["base64"]))
                {
                    //actualizar
                    $ubicacion = "img/guias/";

                    /*Obtienedo base 64 */
                    $btallas64 = $datosGuiaTallas["base64"];
                    $btallas64 = preg_replace('/^data:image\/\w+;base64,/', '', $btallas64);
                    $type = explode(';', $datosGuiaTallas["base64"])[0];
                    $type = explode('/', $type)[1];
                    $bin = base64_decode($btallas64, true);

                    
                    $filename = $producto->idProducto.".".$type;

                    $rutaGuiaTallas = $ubicacion.$filename;
         
                    file_put_contents($ubicacion.$filename, $bin);
                }
                else
                {
                    return response('debe agregar base64', 400);
                }
                
            }
            else
            {
                return response('debe agregar una imagen para la guia de tallas de este producto', 400);
            }
            $producto->rutaGuiaTallas =$rutaGuiaTallas;
            $producto->save();

            /*si tiene menu se agregara*/
            if (isset($idMenu) && $idMenu>0)
            {
                $menuDb = menus::where('idMenu', $idMenu)->first();

                if (!isset($menuDb))
                {
                    $mostrarError = true;
                    throw new Exception("debe ingresar un menu valido");
                }

                $menu = productomenu::where('idProducto', $producto->idProducto)->first();
                
                if(!isset($menu))
                {
                    $menu = new productomenu();
                }

                $menu->idMenu = $idMenu;
                $menu->idProducto = $producto->idProducto;
                $menu->save();
                
            }
          
            

            if (isset($datosVarianteDetalles))
            {
                $VarianteTalla = "TALLAS";

                foreach ($datosVarianteDetalles as $varianteDet) {

                    if (isset($varianteDet["idProductoVarianteDetalle"]) )
                    {
                        //buscar si existe
                        
                        $varianteEncontrada = productovariantesdetalle::where(
                            [
                                ['idProducto', '=',  $producto->idProducto],
                                ['idProductoVarianteDetalle','=', $varianteDet["idProductoVarianteDetalle"]]
                            ]
                            )->first();

                        

                        if (isset($varianteDet["eliminar"]) && $varianteDet["eliminar"] == true && $varianteDet["idProductoVarianteDetalle"] > 0)
                        {
                            //desactivar variante
                            $varianteEncontrada->activo = false;
                            $varianteEncontrada->save();
                        }
                        else
                        {
                            if (is_null($varianteEncontrada))
                            {
                                $varianteEncontrada  = new productovariantesdetalle();
                                
                            }
                            

                            $varianteEncontrada->valor =  $varianteDet["valor"];
                            $varianteEncontrada->nombre = $VarianteTalla; //tallas
                            $varianteEncontrada->inventario = $varianteDet["inventario"];
                            $varianteEncontrada->idProducto = $producto->idProducto;
                            $varianteEncontrada->activo = true;

                            $varianteEncontrada->save();
                        }

                        
                    }
                    
                }
                
            }

            if($english["idProductoIdioma"] == 0)
            {
                //es nuevo producto
                $idioma = new productoidiomas();
                
            }
            else
            {
                $idioma =  productoidiomas::where('idProductoIdioma', '=', $english["idProductoIdioma"])->first();
                
            }

            if (!isset($idioma))
            {
                $mostrarError = true;
                throw new Exception("no se ingreso la descripcion en ingles del producto");
            }

            $idioma->nombre = $english["nombre"];
            $idioma->descripcion = $english["descripcion"];
            $idioma->idIdioma = 2;
            $idioma->idProducto = $producto->idProducto;
            $idioma->save();

            $ubicacion = "img/productos/";

            if (isset($datosImagenes))
            {
                if ( count($datosImagenes) != 4)
                {
                    return response("deben ingresar 4 imagenes");
                }
            }
            else
            {
                return response("debe ingresar imagenes");
            }
            
            

            if (isset($datosImagenes))
            {
                
            foreach ($datosImagenes as $img) {
                
                if (isset($img["idImagen"]))
                {
                    if ($img["idImagen"] == 0)
                    {
                        /*Obtienedo base 64 */
                        $b64 = $img["base64"];
                        $b64 = preg_replace('/^data:image\/\w+;base64,/', '', $b64);
                        $type = explode(';', $img["base64"])[0];
                        $type = explode('/', $type)[1];
                        $bin = base64_decode($b64, true);

                        /*guardando reg previo de imagen*/
                        $imagen = new productoimagenes();
                        $imagen->idProducto = $producto->idProducto;
                        $imagen->ruta = "";
                        $imagen->base64 = $b64;
                        $imagen->extension = $type;
                        $imagen->save();
                        
                        $filename = $imagen->idImagen.".".$type;

                        $imagen->ruta = $ubicacion.$filename;
                        /*actualizando ruta final*/
                        $imagen->save();

                        file_put_contents($ubicacion.$filename, $bin);
                    }

                }
            
            }                
        }


        DB::commit();
        return $producto->idProducto;
        } catch (Exception $th) {
            DB::rollback();
            if ($mostrarError == true)
            {
                return response($th->getMessage(), 200);
            }
            else
            {
                return response("no se pudo guardar el producto, favor de validar información: ".$th->getMessage(), "400");
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
        
        $producto = productos::where('idProducto', $id)->first();
     

        $db_imagenes = productoimagenes::where('idProducto', $id)->
        select('idImagen', 'idProducto', 'idVariante', 'ruta', 'extension') ->get();

        $menuProducto = productomenu::where('idProducto', $id)->select('idMenu')->first();

        if (isset($menuProducto))
        {
            $producto->idMenu = $menuProducto->idMenu;
        }
        else
        {
            $producto->idMenu = 0;
        }
        

        $variantes = productovariantesdetalle::where([
            ['idProducto', '=', $id],
            ['activo', '=', 1]
        ])->get();
        $english = productoidiomas::where('idProducto', $id)->first();

        /*Para la guia de tallas*/
        if (strlen($producto->rutaGuiaTallas) > 0)
        {
            $producto->rutaGuiaTallas = url('/').'/'.$producto->rutaGuiaTallas;
        }
        else
        {
            $producto->rutaGuiaTallas = "";
        }
        
        
        $coleccion = colecciones::where('idColeccion', $producto->idColeccion)->first();
        $tipoProducto = tipoproductos::where('idTipo', $producto->idTipo)->first();

        $producto->manejaraTallas = $producto->manejaraTallas == 1 ? true : false;
        $producto->coleccion = isset($coleccion->nombre) ? $coleccion->nombre : '';
        $producto->idColeccion = isset($coleccion->idColeccion) ? $coleccion->idColeccion : '';
        $english = isset($english) ? $english : '';
        
        $producto->tipo = (isset($tipoProducto) && isset($tipoProducto->tipo) ? $tipoProducto->tipo : '');

        
        $imagenes=[];
        
        foreach ($db_imagenes as $imagen) {
            $imagen->base64 = "";
            $imagen->ruta = url('/').'/'.$imagen->ruta;
            $imagenes[] = $imagen;
        }
        
        foreach ($variantes as $detalle) {
            $detalle->eliminar = false;
        }

        $datos = ["producto"=> $producto, "imagenes" => $imagenes, "variantes" => $variantes, "english" => $english];
        return $datos;
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
