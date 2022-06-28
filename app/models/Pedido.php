<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'pedido';
    public $incrementing = true;
    public $timestamps = false;
    const DELETED_AT = 'fechaBaja';

    public function productos()
    {
        return $this->hasMany(ProductosPedido::class);
    }
    // busco el producto que mas iempo tarde y eso es el timepo de 
    // tabla intermedia un pedido, muhcos prod.
    protected $fillable = [
        'estado', 'codigo','idEmpleadoResponzable','tiempoPedido','fechaInicio','fechaFin', 'idMesa'
    ];
}
