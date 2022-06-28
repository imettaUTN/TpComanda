<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Logs extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'log';
    public $incrementing = true;
    public $timestamps = false;


    protected $fillable = [
        'fecha', 'tipo', 'descripcion', 'idUsuario'
    ];
}
