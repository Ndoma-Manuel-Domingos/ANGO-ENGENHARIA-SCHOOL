<?php

namespace App\Models\web\calendarios;

use App\Models\FormaPagamento;
use App\Models\Professor;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamento extends Model
{
    use SoftDeletes;

    protected $table = "tb_pagamentos";

    protected $fillable = [
        'pago_at',
        'caixa_id',
        'banco_id',
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
        'type_service',
        'inss',
        'irt',
        'faltas',
        'presenca',
        'subcidio',
        'subcidio_transporte',
        'subcidio_alimentacao',
        'subcidio_natal',
        'subcidio_ferias',
        'subcidio_abono_familiar',

        'total_tempos_semanal',
        'total_tempos_mensal',
        'salario_por_tempo',
        'desconto_por_tempo',
        'salario_bruto',

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
        'ano_lectivos_referente_id',
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
        'valor_deposito',
        'valor_extenso',
        'texto_hash',
        'hash',
        'observacao',
        'nif_cliente',
        'tipo_servico_detalhe',
        'operador_pagamento',
        'total_incidencia',
        'conta_corrente_cliente',
        'numeracao_proforma',
        'motivo',
    ];

    public $items = [];

    public function addItem($items)
    {
        $this->items[] = $items;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

    public function factura_recibo()
    {
        return $this->hasOne(PagamentoRecibo::class, 'codigo_pagamento', 'id');
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'pagamento_id', 'id');
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
        return $this->hasMany(DetalhesPagamentoPropina::class, 'code', 'ficha');
    }

    public function detalhes()
    {
        return $this->belongsTo(DetalhesPagamentoPropina::class, 'ficha', 'code');
    }

    public function descricao_mes($string)
    {
        if ($string == "Nov") {
            return "Novembro";
        }
        if ($string == "Dec") {
            return "Dezembro";
        }
        if ($string == "Jan") {
            return "Janeiro";
        }
        if ($string == "Feb") {
            return "Fevereiro";
        }
        if ($string == "Mar") {
            return "Março";
        }
        if ($string == "Apr") {
            return "Abril";
        }
        if ($string == "May") {
            return "Maio";
        }
        if ($string == "Jun") {
            return "Junho";
        }
        if ($string == "Jul") {
            return "Julho";
        }
        if ($string == "Aug") {
            return "Agosto";
        }
        if ($string == "Sep") {
            return "Setembro";
        }
        if ($string == "Oct") {
            return "Outumbro";
        }
    }

    public function descricao_mes_completo($string)
    {
        if ($string == "November") {
            return "Novembro";
        }
        if ($string == "December") {
            return "Dezembro";
        }
        if ($string == "January") {
            return "Janeiro";
        }
        if ($string == "February") {
            return "Fevereiro";
        }
        if ($string == "March") {
            return "Março";
        }
        if ($string == "April") {
            return "Abril";
        }
        if ($string == "May") {
            return "Maio";
        }
        if ($string == "June") {
            return "Junho";
        }
        if ($string == "July") {
            return "Julho";
        }
        if ($string == "August") {
            return "Agosto";
        }
        if ($string == "September") {
            return "Setembro";
        }
        if ($string == "October") {
            return "Outumbro";
        }
    }
    
    // public function model($string, $id)
    // {
    //     $map = [
    //         'escola'    => Shcool::class,  // (acho que é "School" 😉)
    //         'estudante' => Estudante::class,
    //         'professor' => Professor::class,
    //         'funcionario' => Funcionarios::class,
    //     ];
    
    //     if (! array_key_exists($string, $map)) {
    //         throw new \InvalidArgumentException("Modelo não suportado: {$string}");
    //     }
    
    //     $model = $map[$string]::find($id);
    
    //     return trim("{$model->nome} {$model->sobre_nome}");
    // }

    public function model($string, $id)
    {
        if ($string == "escola") {
            $dados = Shcool::find($id);
        } else if ($string == "estudante") {
            $dados = Estudante::find($id);
        } else if ($string == "professor") {
            $dados = Professor::find($id);
        } else {
            $dados = Funcionarios::find($id);
        }
        
        $nome = $dados->nome??'';
        $sobre_nome = $dados->sobre_nome??'';

        return "{$nome} {$sobre_nome}";
    }

    public function status($string)
    {
        if ($string == "N") {
            return "Não";
        } else {
            return "Sim";
        }
    }

    public function descricao_forma_pagamento($descricao)
    {
        $forma = FormaPagamento::where('sigla_tipo_pagamento', $descricao)->first();
        return $forma ? $forma->descricao : 'Númerario';
    }


    function obterCaracteres($texto)
    {
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
