<?php

namespace App\Models;

use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfessorAcedemico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_professores_academicos";

    protected $fillable = [
        'especialidade_id',
        'categoria_id',
        'ano_trabalho',
        'codgo',
        'escolaridade_id',
        'formacao_academica_id',
        'universidade_id',
        'professor_id',
        'ano_lectivo_global_id',
    ];

    public function formacao()
    {
        return $this->belongsTo(FormacaoAcedemico::class, 'formacao_academica_id', 'id');
    }
    
    
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
