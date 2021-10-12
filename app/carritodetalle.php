<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class carritodetalle extends Model
{
    //detallecarrito
    protected $table = 'detallecarrito';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;
}
