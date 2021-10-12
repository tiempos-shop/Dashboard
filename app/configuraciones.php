<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class configuraciones extends Model
{
    protected $table = 'configuracion';
    protected $primaryKey = 'idConfiguracion';
    public $timestamps = false;
}
