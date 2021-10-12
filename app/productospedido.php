<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productospedido extends Model
{
    protected $table = 'productospedido';
    protected $primaryKey = 'IdProductosPedido';
    public $timestamps = false;
}
