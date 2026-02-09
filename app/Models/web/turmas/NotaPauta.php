<?php

namespace App\Models\web\turmas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaPauta extends Model
{
    use SoftDeletes;

    protected $table = "tb_notas_pautas";

    protected $fillable = [
        'mac',
        'npt',
        'mt',
        'mt1',
        'mt2',
        'mt3',
        'mfd',
        'ne', // nota exame
        'nr', // nota do recurso
        'rf',
        'mf', // media final
        'pt', // projecto tecnologico
        'pap', // prova de apitadão profissional
        'status',
        'turmas_id',
        'estudantes_id',
        'funcionarios_id',
        'ano_lectivos_id',
        'shcools_id',
        'controlo_trimestres_id',
        'disciplinas_id',
        'descricao',
        'obs',

        // UNIVERIDADES
        'p1',
        'p2',
        'p3',
        'p4',
        'med',
        'obs1',
        'exame_1_especial',
        'obs2',
        'media_final',
        'obs3',
        'recurso',
        'exame_especial',
        'resultado_final',
    ];

    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'controlo_trimestres_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turmas_id', 'id');
    }

    public function arredondado_para_cima($valor)
    {
        $arredondado = ceil($valor ?? 0);
        $cor = $this->obter_cor($arredondado);

        return (object) ['valor' => $arredondado, 'cor' => $cor];
    }
    
    
    public function arredondar($valor) 
    {
    
        $parteInteira = floor($valor);  // Parte inteira do número
        $parteDecimal = $valor - $parteInteira;  // Parte decimal do número
    
        // Se a parte decimal for menor que 0.4, arredonda para baixo
        if ($parteDecimal < 0.4) {
            return $parteInteira;
        }
        // Se a parte decimal for maior ou igual a 0.5, arredonda para cima
        elseif ($parteDecimal >= 0.5) {
            return ceil($valor);
        }
        // Se a parte decimal for entre 0.4 e 0.5, arredonda para cima
        else {
            return ceil($valor);
        }
    }

    public function obter_cor($valor)
    {
        // Lógica para determinar a cor com base no valor arredondado
        if ($valor >= 10) {
            return 'green';
        } elseif ($valor < 10) {
            return 'red';
        }
    }

    public function arredondado_para_baixo($valor)
    {
        $arredondado = floor($valor ?? 0);
        $cor = $this->obter_cor($arredondado);

        return (object) ['valor' => $arredondado, 'cor' => $cor];
    }

    function valor_por_extenso($v)
    {

        $v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);

        $sin = array("Centavo", "Zeal", "Mil", "Milhão", "Bilhão", "Trilhão", "Quatrilhão");
        $plu = array("Centavos", "", "Mil", "Milhões", "Bilhões", "Trilhões", "Quatrilhões");

        $c = array("", "Cem", "duzentos", "Trezentos", "Quatrocentos", "Quinhentos", "Seiscentos", "Setecentos", "Oitocentos", "Novecentos");
        $d = array("", "Dez", "Vinte", "Trinta", "Quarenta", "Cinquenta", "Sessenta", "Setenta", "Oitenta", "Noventa");
        $d10 = array("Dez", "Onze", "Doze", "Treze", "Quatorze", "Quinze", "Dezesseis", "Dezesete", "Dezoito", "Dezenove");
        $u = array("", "Um", "Dois", "Três", "Quatro", "Cinco", "Seis", "Sete", "Oito", "Nove");

        $z = 0;

        $v = number_format($v, 2, ".", ".");
        $int = explode(".", $v);

        for ($i = 0; $i < count($int); $i++) {
            for ($ii = mb_strlen($int[$i]); $ii < 3; $ii++) {
                $int[$i] = "0" . $int[$i];
            }
        }

        $rt = null;
        $fim = count($int) - ($int[count($int) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($int); $i++) {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "Cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($int) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ($v == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($int[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plu[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "Zero");
    }
}
