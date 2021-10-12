<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class monedas extends Model
{
    protected $table = 'monedas';
    protected $primaryKey = 'idMoneda';
    public $timestamps = false;
}
