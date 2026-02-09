<?php

namespace App\Models\web\estudantes;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\Servico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoEstudante extends Model
{
    use SoftDeletes;

    protected $table = "tb_cartao_estudantes";

    protected $fillable = [
        'estudantes_id',
        'mes_id',
        'servicos_id',
        'multa',
        'preco_unitario',
        'desconto',
        'status',
        'status_2',
        'cobertura',
        'multa1',
        'multa2',
        'multa3',
        'trimestral',
        'semestral',
        'controle_periodico_id',
        'data_at',
        'data_exp',
        'month_number',
        'month_name',
        'ano_lectivos_id',
        'status_multa',
        'multa_removida',
        'motivo_isencao_mes',
        'motivo_remover_isencao_mes',
        'motivo_isencao_multa',
        'motivo_remover_isencao_multa',
    ];
    
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }
    
    public function controle_periodio()
    {
        return $this->belongsTo(ControlePeriodico::class, 'controle_periodico_id', 'id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }

    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }
    
    public function mes($string)
    {
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
            return "Outubro";
        }

        if ($string == "Nov") {
            return "Novembro";
        }

        if ($string == "Dec") {
            return "Dezembro";
        }
    }
}
        