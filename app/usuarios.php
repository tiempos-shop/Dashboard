<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usuarios extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'IdUsuario';
    public $timestamps = false;
}
