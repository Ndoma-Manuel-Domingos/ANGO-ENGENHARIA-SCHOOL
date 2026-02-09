<?php

namespace App\Models\web\calendarios;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\estudantes\Estudante;
use App\Models\web\turmas\Turma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresencaEstudante extends Model
{
    use SoftDeletes;

    protected $table = "tb_presencas_estudantes";

    protected $fillable = [
        'data_entrada',
        'hora_entrada',
        'data_saida',
        'hora_saida',
        'status_entrada',
        'status_saida',
        'estudantes_id',
        'turma_id',
        'shcools_id',
        'ano_lectivo_id',
    ];
    
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turma_id', 'id');
    }
    
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id', 'id');
    }
}
