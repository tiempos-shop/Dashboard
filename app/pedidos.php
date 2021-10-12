<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pedidos extends Model
{
    //
    protected $table = 'pedidos';
    protected $primaryKey = 'IdPedido';
    public $timestamps = false;
}
