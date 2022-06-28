<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Encuesta extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'encuesta';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'puntacionMesa','textoMesa', 'puntacionResto','textoResto','puntacionMozo','textoMozo','puntacionCocinero','textoCocinero','mesa_id'
    ];
}
