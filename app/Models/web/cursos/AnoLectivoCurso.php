<?php

namespace App\Models\web\cursos;

use App\Http\Controllers\TraitHelpers;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\disciplinas\Candidatura;
use App\Models\web\disciplinas\Faculdade;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\Turma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivoCurso extends Model
{
    use SoftDeletes;
    use TraitHelpers;


    protected $table = "tb_ano_lectivo_cursos";

    protected $fillable = [
        'ano_lectivos_id',
        'cursos_id',
        'shcools_id',
        'total_vagas',
        'faculdade_id',
        'candidatura_id',
        'coordenador_id',
        'max_cadeira',
        'duracao',
        'vantagens',
        'area_saidas',
    ];

    function formatListFromText($text)
    {
        // Quebra a string em um array sempre que encontrar ";"
        $items = explode(';', $text);

        // Remove espaços extras ao redor dos itens
        $items = array_map('trim', $items);

        // Filtra para remover itens vazios
        $items = array_filter($items);

        // Gera a lista HTML
        $html = "<ul>\n";
        foreach ($items as $item) {
            $html .= "    <li>{$item}</li>\n";
        }
        $html .= "</ul>";

        return $html;
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class, 'cursos_id', 'cursos_id');
    }

    public function totalEstudanteTurma($curso_id, $classe_id, $ano_lectivo_id, $mes_id)
    {

        $turma_ids = Turma::where('classes_id', $classe_id)
            ->where('cursos_id', $curso_id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $ano_lectivo_id)
            ->pluck('id');

        dd(count($turma_ids));

        $turma = Turma::where('classes_id', $classe_id)
            ->where('cursos_id', $curso_id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $ano_lectivo_id)
            ->first();

        if ($turma_ids && $turma) {

            $total_turma = EstudantesTurma::whereIn('turmas_id', $turma_ids)
                ->where('ano_lectivos_id', $ano_lectivo_id)
                ->count();

            $total_estudantes = EstudantesTurma::whereIn('turmas_id', $turma_ids)
                ->where('ano_lectivos_id', $ano_lectivo_id)
                ->pluck('estudantes_id');

            $total_estudante_pago = CartaoEstudante::where('mes_id', 'M')->whereIn('estudantes_id', $total_estudantes)->where('month_name', $mes_id)->whereIn('status', ['Pago', 'Isento'])->count();
            $total_estudante_nao_pago = CartaoEstudante::where('mes_id', 'M')->whereIn('estudantes_id', $total_estudantes)->where('month_name', $mes_id)->whereIn('status', ['Nao Pago', 'divida'])->count();

            return [
                'valor_proprina' => $turma->valor_propina ?? 0,
                'total' => $total_turma ?? 0,
                'total_estudante_pago' => $total_estudante_pago ?? 0,
                'total_estudante_nao_pago' => $total_estudante_nao_pago ?? 0,
            ];
        }

        return 0;
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }

    public function coordenador()
    {
        return $this->belongsTo(Funcionarios::class, 'coordenador_id', 'id');
    }

    public function faculdade()
    {
        return $this->belongsTo(Faculdade::class, 'faculdade_id', 'id');
    }

    public function candidatura()
    {
        return $this->belongsTo(Candidatura::class, 'candidatura_id', 'id');
    }

    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
