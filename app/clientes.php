<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'IdCliente';
    public $timestamps = false;
}
