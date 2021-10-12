<?php

namespace App\Clases;

class EnCarritoModel
{
    ///Modelo para los datos del paquete a enviar;
    /// Debe contener la suma de las dimensiones de los productos;
    public int $idDetalle;
    public int $idProducto;
    public int $idCliente;
    public ?int $idProductoVarianteDetalle;
    public float $precio;
    

    public function __construct()
    {

    }
}