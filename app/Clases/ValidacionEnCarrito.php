<?php

namespace App\Clases;

use App\carritodetalle;
use App\productos;
use App\productovariantesdetalle;
use Exception;
use PhpParser\Node\Stmt\Return_;

class ValidacionEnCarrito
{
    public function ValidarSiExiteEnCarrito(EnCarritoModel $producto, &$mostrarError, &$problemas, &$productoBusqueda = null):carritodetalle
    {
        
        $mostrarError = false;

        $productoEnElCarrito = new carritodetalle();

        try {
            if (!isset($producto->idDetalle))
            {
                $problemas = "En productos, no se establecio el id detalle";
                $mostrarError = true;
                throw new Exception ($problemas);
                
            }

            if (!isset($producto->idCliente))
            {
                $problemas = "En productos, no se establecio el id cliente";
                $mostrarError = true;
                throw new Exception ($problemas);
            }

            if (!isset($producto->idProducto))
            {
                $problemas = "En productos, no se establecio el id producto";
                $mostrarError = true;
                throw new Exception ($problemas);
            }

            if (!isset($producto->precio))
            {
                $problemas = "En productos, no se establecio el precio";
                $mostrarError = true;
                throw new Exception ($problemas);
            }

                /*si existe en el carrito del cliente*/

            $productoEnElCarrito = carritodetalle::where([
                ['idCliente', '=', $producto->idCliente],
                ['idDetalle', '=', $producto->idDetalle],
                ['idProductoVarianteDetalle', '=', $producto->idProductoVarianteDetalle]
            ])->first();

            $productoBusqueda = productos::where('idProducto', $producto->idProducto)->first();

            if (!isset($productoBusqueda))
            {
                $problemas = $problemas;
                $mostrarError = true;
                throw new Exception ($problemas);
            }

            /*Precios son iguales?*/
            if ($producto->precio != $productoBusqueda->precio)
            {
                $problemas = "el precio de este producto ha cambiado";
                $mostrarError = true;
                throw new Exception ($problemas);
            }

            /*validar si existe variantes de este producto*/
            if ($productoBusqueda->manejaraTallas)
            {
                if (!isset($producto->idProductoVarianteDetalle) || $producto->idProductoVarianteDetalle == 0)
                {
                    $problemas = "no se establecio la talla del producto";
                    $mostrarError = true;
                    throw new Exception ($problemas);
                }

                $varianteProducto = productovariantesdetalle::where([
                    ["idProducto", '=', $producto->idProducto],
                    ['idProductoVarianteDetalle', '=', $producto->idProductoVarianteDetalle]
                ])->first();

                if (!isset($varianteProducto))
                {
                    $problemas = "no existe la variante de tallas seleccionada";
                    $mostrarError = true;
                    throw new Exception ($problemas);
                }

                
                if(!isset($productoEnElCarrito))
                {
                    $problemas = "En productos, no se establecio variante";
                    $mostrarError = true;
                    throw new Exception ($problemas);
                }
            }
        } catch (Exception $th) {
            //$problemas = $th->getMessage();
            if (!$mostrarError)
            {
                $problemas = "no se guardo, visualizar problema en sistema, validar_carrito";    
            }
            
            //throw new Exception("no se guardo, visualizar problema en sistema, validar_carrito");
        
        }
        
        return $productoEnElCarrito;

    }
}
