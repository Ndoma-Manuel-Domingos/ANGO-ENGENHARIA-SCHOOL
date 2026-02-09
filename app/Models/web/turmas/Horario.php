<?php

namespace App\Models\web\turmas;

use App\Models\Professor;
use App\Models\web\calendarios\Semana;
use App\Models\web\calendarios\Tempo;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use SoftDeletes;

    protected $table = "tb_horario_turmas";

    protected $fillable = [
        'semanas_id',
        'tempos_id',
        'professor_id',
        'hora_inicio',
        'hora_final',
        'turmas_id',
        'disciplinas_id',
        'ano_lectivos_id',
        'shcools_id',
    ];
        
    public function semana()
    {
        return $this->belongsTo(Semana::class, 'semanas_id', 'id');
    }
        
    public function tempo()
    {
        return $this->belongsTo(Tempo::class, 'tempos_id', 'id');
    }
        
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }
    
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }
        
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }
    
}
