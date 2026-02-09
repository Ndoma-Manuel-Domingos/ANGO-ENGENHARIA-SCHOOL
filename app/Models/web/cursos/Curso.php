<?php

namespace App\Models\web\cursos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = "tb_cursos";

    protected $fillable = [
        'curso',
        'abreviacao',
        'tipo',
        'area_formacao',
        'status',
        'descricao',
        'shcools_id',
    ];

}
