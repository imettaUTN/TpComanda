<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'usuario';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fechaBaja';

    //perfiles : cliente / empleado / admin
    protected $fillable = [
        'usuario', 'clave','perfil' ,'fechaBaja'
    ];
}