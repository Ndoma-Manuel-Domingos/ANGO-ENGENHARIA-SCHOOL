<?php

namespace App\Models\web\funcionarios;

use App\Models\Categoria;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\FormacaoAcedemico;
use App\Models\Universidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncionariosAcademico extends Model
{
    use SoftDeletes;

    protected $table = "tb_funcionarios_academicos";

    protected $fillable = [
        'escolaridade_id',
        'formacao_academica_id',
        'universidade_id',
        'categoria_id',
        'especialidade_id',
        'ano_trabalho',
        'email',
        'codigo',
        'funcionarios_id',
        'shcools_id',
    ];
    
    public function funcionario()
    {
        return $this->belongsTo(Funcionarios::class, 'funcionarios_id', 'id');
    }
    
    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id', 'id');
    }
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }
    
    public function escolaridade()
    {
        return $this->belongsTo(Escolaridade::class, 'escolaridade_id', 'id');
    }
    
    public function universidade()
    {
        return $this->belongsTo(Universidade::class, 'universidade_id', 'id');
    }    
    
    public function formacao_academica()
    {
        return $this->belongsTo(FormacaoAcedemico::class, 'formacao_academica_id', 'id');
    }    
    
}
