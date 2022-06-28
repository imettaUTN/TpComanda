<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductosPedido extends Model
{
    //use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'productosPedido';
    public $incrementing = true;
    public $timestamps = false;
    //const DELETED_AT = 'fechaBaja';
    // busco el producto que mas iempo tarde y eso es el timepo de 
    // tabla intermedia un pedido, muhcos prod.
    protected $fillable = [
        'idProducto','pedido_id'
    ];
}
