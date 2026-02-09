<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mercadoria extends Model
{
    use SoftDeletes;

    protected $table = "tb_mercadorias";

    protected $fillable = [
        'designacao',
        'status',
        'tipo_mercadoria_id',
    ];
    
    public function tipo()
    {
        return $this->belongsTo(TipoMercadoria::class, 'tipo_mercadoria_id', 'id');
    }

}
