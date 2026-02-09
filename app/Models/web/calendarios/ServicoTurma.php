<?php

namespace App\Models\web\calendarios;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\turmas\Turma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicoTurma extends Model
{
    use SoftDeletes;

    protected $table = "tb_servicos_turma";

    protected $fillable = [
        'model',
        'status',
        'pagamento',
        'preco',
        'preco_sem_iva',
        'multa',
        'intervalo_pagamento_inicio',
        'intervalo_pagamento_final',
        'taxa_multa1',
        'taxa_multa1_dia',
        'taxa_multa2',
        'taxa_multa2_dia',
        'taxa_multa3',
        'taxa_multa3_dia',
        'desconto',
        'data_inicio',
        'data_final',
        'total_vezes',
        'servicos_id',
        'turmas_id',
        'ano_lectivos_id',
        'shcools_id',
    ];
    
    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }
    
    public function entidade($model, $id)
    {
        if ($model == "turmas"){
            $dado = Turma::findOrFail($id);
        }
        
        if ($model == "escola"){
            $dado = Shcool::findOrFail($id);
        }

        return $dado;
    }   
    
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }
    
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }
}
