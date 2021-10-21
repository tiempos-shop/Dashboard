<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class archivo extends Model
{
    protected $table = 'archivo';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
