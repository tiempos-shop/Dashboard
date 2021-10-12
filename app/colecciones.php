<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class colecciones extends Model
{
    protected $table = 'colecciones';
    protected $primaryKey = 'idColeccion';
    public $timestamps = false;
}
