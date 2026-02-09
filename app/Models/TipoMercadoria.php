<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMercadoria extends Model
{
    use SoftDeletes;

    protected $table = "tb_tipos_mercadorias";

    protected $fillable = [
        'designacao',
        'status',
    ];

}
