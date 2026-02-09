<?php

namespace App\Models\web\turmas;

use App\Models\ControloLancamentoNotas;
use App\Models\ControloLancamentoNotasEscolas;
use App\Models\Professor;
use App\Models\Shcool;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncionariosTurma extends Model
{
    use SoftDeletes;

    protected $table = "tb_turmas_funcionarios";

    protected $fillable = [
        'cargo_turma',
        'tempo_edicao',
        'trimestre_edicao',
        'disciplinas_id',
        'turmas_id',
        'funcionarios_id',
        'ano_lectivos_id',
        'shcools_id',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'funcionarios_id', 'id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }
    
        
    public function tempo_lancamento_notas($ano_lectivo, $escola)
    {
        // controle lancamento de notas se esta activo ou não
        $controlo = ControloLancamentoNotasEscolas::where('ano_lectivo_id', $ano_lectivo)->where('shcools_id', $escola)->first();
        
        $lancamento = ControloLancamentoNotas::where('status', 'activo')->where('id', $controlo->lancamento_id)->first();
        
        return $lancamento;
    }
    
        
    public function categoria_escola($escola)
    {
        $escola = Shcool::findOrFail($escola);
        
        return $escola->categoria;
    }
}
