<?php

namespace App\Models\web\cursos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMeterial extends Model
{
    use SoftDeletes;

    protected $table = "tb_tipos_materiais";

    protected $fillable = [
        'nome',
        'shcools_id',
    ];

}
