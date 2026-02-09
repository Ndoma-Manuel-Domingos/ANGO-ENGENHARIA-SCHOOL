<?php

namespace App\Models\web\calendarios;

use App\Models\Professor;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoOriginal;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagamentoOriginal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_pagamentos_originais";

    protected $fillable = [
        'pago_at',
        'servicos_id',
        'quantidade',
        'status',
        'caixa_at',
        'ficha',
        'valor',
        'troco',
        'valor_entregue',
        'valor2',
        'desconto',
        'multa',
        'inss',
        'irt',
        'faltas',
        'subcidio',
        'subcidio_transporte',
        'subcidio_alimentacao',
        'subcidio_natal',
        'subcidio_ferias',
        'subcidio_abono_familiar',
        'banco',
        'numero_transacao',
        'tipo_pagamento',
        'pagamento_id',
        'model',
        'data_at',
        'data_vencimento',
        'data_disponibilizacao',
        'mensal',
        'next_factura',
        'factura_ano',
        'numero_factura',
        'tipo_factura',
        'codigo',
        'funcionarios_id',
        'estudantes_id',
        'ano_lectivos_id',
        'shcools_id',
        'referencia',
        'total_iva',
        'retificado',
        'convertido_factura',
        'factura_divida',
        'anulado',
        'moeda',
        'prazo',
        'valor_cash',
        'valor_multicaixa',
        'valor_extenso',
        'texto_hash',
        'hash',
        'nif_cliente',
        'total_incidencia',
        'conta_corrente_cliente',
        'numeracao_proforma',
        'motivo',
    ];
    
    public function escola()
    {
       return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function estudante()
    {
       return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }
    
    public function ano()
    {
       return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function operador()
    {
       return $this->belongsTo(User::class, 'funcionarios_id', 'id');
    }

    public function servico()
    {
       return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }
    
    public function items()
    {
        return $this->hasMany(DetalhesPagamentoPropina::class, 'pagamentos_id', 'id');
    }

    public function detalhes()
    {
        return $this->belongsTo(DetalhesPagamentoPropina::class, 'ficha', 'code');
    }
    
    public function model($string, $id)
    {
        if($string == "escola"){
            $dados = Shcool::findOrFail($id);
        }else if($string == "estudante"){
            $dados = Estudante::findOrFail($id);
        }else if($string == "professor"){
            $dados = Professor::findOrFail($id);
        }else{
            $dados = Funcionarios::findOrFail($id);
        }

        return "{$dados->nome} {$dados->sobre_nome}";
    }

    public function status($string)
    {
        if($string == "N"){
            return "Não";
        }else{
            return "Sim";
        }
    }
}
