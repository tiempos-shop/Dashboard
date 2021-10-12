<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tipoproductos extends Model
{
    protected $table = 'tipoproductos';
    protected $primaryKey = 'idTipo';
    public $timestamps = false;
}
