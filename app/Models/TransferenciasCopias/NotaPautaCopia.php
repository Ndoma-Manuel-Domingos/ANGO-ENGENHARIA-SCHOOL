<?php

namespace App\Models\TransferenciasCopias;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaPautaCopia extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mysql2";
    protected $table = "tb_notas_pautas";

    protected $fillable = [
        'mac',
        'npt',
        'mt',
        'mt1',
        'mt2',
        'mt3',
        'mfd',
        'ne',
        'nr',
        'rf',
        'status',
        'turmas_id',
        'estudantes_id',
        'funcionarios_id',
        'ano_lectivos_id',
        'controlo_trimestres_id',
        'disciplinas_id',
        'descricao',
        'transferencia_id',
    ];
}
