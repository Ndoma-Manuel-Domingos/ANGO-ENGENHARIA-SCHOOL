<?php

namespace App\Models\web\financeiros;

use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalhesPagamentoPropina extends Model
{
    use SoftDeletes;

    protected $table = "tb_detalhes_pagamentos";

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
    
    public function descricao_mes($string)
    {
        if($string == "Nov"){
            return "Novembro";
        }
        if($string == "Dec"){
            return "Dezembro";
        }
        if($string == "Jan"){
            return "Janeiro";
        }
        if($string == "Feb"){
            return "Fevereiro";
        }
        if($string == "Mar"){
            return "Março";
        }
        if($string == "Apr"){
            return "Abril";
        }
        if($string == "May"){
            return "Maio";
        }
        if($string == "Jun"){
            return "Junho";
        }
        if($string == "Jul"){
            return "Julho";
        }
        if($string == "Aug"){
            return "Agosto";
        }
        if($string == "Sep"){
            return "Setembro";
        }
        if($string == "Oct"){
            return "Outumbro";
        }
    }

    public function pagamento()
    {
       return $this->belongsTo(Pagamento::class, 'code', 'ficha');
    }
    
    public function servico()
    {
       return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }
}
