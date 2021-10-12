<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menus extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'idMenu';
    public $timestamps = false;
}
