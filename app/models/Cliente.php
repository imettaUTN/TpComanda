<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'cliente';
    public $incrementing = true;
    public $timestamps = false;


    protected $fillable = [
        'idMesa','nombre', 'idUsuario'
    ];
}
