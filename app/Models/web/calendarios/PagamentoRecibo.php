<?php

namespace App\Models\web\calendarios;

use App\Models\FormaPagamento;
use App\Models\Professor;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagamentoRecibo extends Model
{
    use SoftDeletes;

    protected $table = "tb_pagamentos_recibos";
    
    protected $fillable = [
        'pago_at',
        'servicos_id',
        'tipo_servico_detalhe',
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
        'codigo_pagamento',
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
    
    public function pagamento()
    {
       return $this->belongsTo(Pagamento::class, 'codigo_pagamento', 'id');
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
    
    public function descricao_forma_pagamento($descricao)
    {
        $forma = FormaPagamento::where('sigla_tipo_pagamento', $descricao)->first();
        return $forma ? $forma->descricao : 'Númerario';
    }

        
    function obterCaracteres($texto) {
        $posicoes = [1, 11, 21, 31];
        $caracteres = '';
    
        foreach ($posicoes as $posicao) {
            // Garante que a posição está dentro dos limites da string
            if ($posicao <= strlen($texto)) {
                $caracteres .= $texto[$posicao - 1];
            }
        }
    
        return $caracteres . "-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS";
    }
}
