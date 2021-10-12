<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envios_Movimiento extends Model
{
    //
    protected $table = 'envios_movimiento';
    protected $primaryKey = 'idmovimiento';
    public $timestamps = false;
}
