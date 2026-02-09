<?php

namespace App\Models\TransferenciasCopias;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstudantesTurmaCopia extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mysql2";

    protected $table = "tb_turmas_estudantes";

    protected $fillable = [
        'ordem',
        'status',
        'turmas_id',
        'estudantes_id',
        'ano_lectivos_id',
        'transferencia_id',
    ];
}
