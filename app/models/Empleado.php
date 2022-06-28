<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'empleado';
    public $incrementing = false;
    public $timestamps = false;

    const DELETED_AT = 'fechaBaja';

    protected $fillable = [
        'idUsuario','estado','sector','fechaBaja'
    ];

    
}