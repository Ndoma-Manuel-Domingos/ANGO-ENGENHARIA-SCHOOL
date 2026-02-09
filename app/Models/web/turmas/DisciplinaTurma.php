<?php

namespace App\Models\web\turmas;

use App\Models\web\anolectivo\Trimestre;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisciplinaTurma extends Model
{
    use SoftDeletes;

    protected $table = "tb_discplinas_turmas";

    protected $fillable = [
        'status',
        'turmas_id',
        'trimestre_id',
        'disciplinas_id',
        'peso_primeira_freq',
        'peso_segunda_freq',
    ];

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }
    
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id', 'id');
    }
    
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplinas_id', 'id');
    }
}
