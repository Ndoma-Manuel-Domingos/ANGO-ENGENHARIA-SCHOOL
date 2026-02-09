<?php

namespace App\Models\web\financeiros;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\PagamentoRecibo;
use App\Models\web\calendarios\Servico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalhesPagamentoRecibo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_detalhes_pagamentos_recibos";

    protected $fillable = [
        'status',
        'code',
        'mes_id',
        'mes',
        'quantidade',
        'model',
        'model_id',
        'preco',
        'valor_iva',
        'valor_incidencia',
        'pagamentos_id',
        'total_pagar',
        'desconto',
        'desconto_valor',
        'taxa_id',
        'multa',
        'date_att',
        'servicos_id',
        'funcionarios_id',
        'ano_lectivos_id',
        'shcools_id',
    ];

    public function servico()
    {
       return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }

    public function escola()
    {
       return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

    public function ano_lectivo()
    {
       return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function pagamento()
    {
       return $this->belongsTo(PagamentoRecibo::class, 'pagamentos_id', 'id');
    }
}
