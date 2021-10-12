<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class direcciones extends Model
{
    //
    protected $table = 'direcciones';
    protected $primaryKey = 'IdDireccion';
    public $timestamps = false;
}
