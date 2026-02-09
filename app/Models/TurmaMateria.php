<?php

namespace App\Models;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\turmas\Turma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurmaMateria extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_turmas_materias";

    protected $fillable = [
        'titulo',
        'descricao',
        'documento1',
        'documento2',
        'documento3',
        'professor_id',
        'turmas_id',
        'disciplinas_id',
        'data_limite',
        'ano_lectivos_id',
        'shcools_id',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }
    
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }
    
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }
}
