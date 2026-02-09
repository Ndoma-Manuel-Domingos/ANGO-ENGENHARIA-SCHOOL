<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivoGlobal extends Model
{
    use SoftDeletes;

    protected $table = "tb_ano_lectivos_global";

    protected $fillable = [
        'ano',
        'inicio',
        'final',
        'status',
    ];
}
