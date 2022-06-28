<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'mesa';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fechaBaja';

    protected $fillable = [
        'numero', 'estado','fechaBaja','idMozo','codigo'
    ];
}
