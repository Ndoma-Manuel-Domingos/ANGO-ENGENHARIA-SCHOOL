<?php

namespace App\Models\web\turmas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificacaoEncarregado extends Model
{
    use SoftDeletes;

    protected $table = "tb_notificacoes_encarregados_notas";

    protected $fillable = [
        'titulo',
        'tipo',
        'visto',
        'data_at',
        'turmas_id',
        'trimestres_id',
        'estudantes_id',
        'encarregados_id',
        'ano_lectivos_id',
        'descricao',
        'shcools_id',
    ];
}
