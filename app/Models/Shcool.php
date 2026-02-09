<?php

namespace App\Models;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\CartaoEscola;
use App\Models\web\calendarios\MapaEfectividade;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Servico;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\salas\Sala;
use App\Models\web\seguranca\ControloSistema;
use App\Models\web\turmas\NotificacaoEncarregado;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shcool extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_shcools';

    protected $fillable = [
        'nome',
        'cabecalho1',
        'cabecalho2',
        'director',
        'documento',
        'site',
        'sigla',
        'status',
        'categoria',
        'modulo',
        'natureza',
        'ensino_id',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'endereco',
        'decreto',
        'agua',
        'tipo_regime_id',
        'electricidade',
        'cantina',
        'farmacia',
        'biblioteca',
        'campo_desportivo',
        'internet',
        'zip',
        'computadores',
        'laboratorio',
        'casas_banho',
        'transporte',
        'telefone1',
        'telefone2',
        'telefone3',
        'email',
        'logotipo',
        'logotipo2',
        'logotipo_assinatura_director',
        'logotipo_documentos',
        'numero_escola',    
        'conta',
        'banco',
        'iban',
        'processo_pagamento_servico',
        'processo_admissao_estudante',
        'intervalo_pagamento_inicio',
        'intervalo_pagamento_final',
        'taxa_multa1',
        'taxa_multa1_dia',
        'taxa_multa2',
        'taxa_multa2_dia',
        'taxa_multa3',
        'taxa_multa3_dia',
        'cor_cartao',
        'cor_letra_cartao',
        'impressora',
        'cobranca_multas',
        'desconto_percentagem',
        'pais_escola', // internacional / Nacional
        'opniao',
        'tipo_avaliacao',  // U - uma avalição trimestral, D - duas avalição trimestral, T - trÊs avalição trimestral, P - Padrão
        'nota_maxima', // Nota máxima para dispensa de cadeiras
        'nota_maxima_exame', // Nota máxima para dispensa de cadeiras
        'ano_lectivo_global_id',
        'req_id',
        'tipo_cartao',
        'extensao_cartao',
    ];
        
    /**
     * Gera uma sigla única de 3 letras.
     */
    public static function generateUniqueSigla()
    {
        do {
            // Gerar uma sigla aleatória de 3 letras maiúsculas
            $sigla = strtoupper(Str::random(3)); // Exemplo: 'ABC'
        } while (self::where('sigla', $sigla)->exists()); // Verifica se a sigla já existe

        return $sigla;
    }

    public function ensino()
    {
        return $this->belongsTo(Ensino::class, 'ensino_id', 'id');
    }
    
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

    public function pais()
    {
        return $this->belongsTo(Paise::class, 'pais_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivoGlobal::class, 'ano_lectivo_global_id');
    }

    public function anolectivo()
    {
        return $this->hasOne(AnoLectivo::class, 'shcools_id');
    }

    public function anolectivos()
    {
        return $this->hasMany(AnoLectivo::class, 'shcools_id');
    }

    public function classe()
    {
        return $this->hasOne(Classe::class, 'shcools_id');
    }

    public function classes()
    {
        return $this->hasMany(Classe::class, 'shcools_id');
    }

    public function curso()
    {
        return $this->hasOne(Curso::class, 'shcools_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'shcools_id');
    }

    public function turno()
    {
        return $this->hasOne(Turno::class, 'shcools_id');
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'shcools_id');
    }

    public function sala()
    {
        return $this->hasOne(Sala::class, 'shcools_id');
    }

    public function salas()
    {
        return $this->hasMany(Sala::class, 'shcools_id');
    }

    public function turma()
    {
        return $this->hasOne(Turma::class, 'shcools_id');
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class, 'shcools_id');
    }

    public function servico()
    {
        return $this->hasOne(Servico::class, 'shcools_id');
    }

    public function servicos()
    {
        return $this->hasMany(Servico::class, 'shcools_id');
    }

    public function disciplina()
    {
        return $this->hasOne(Disciplina::class, 'shcools_id');
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class, 'shcools_id');
    }

    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'shcools_id');
    }

    public function estudantes()
    {
        return $this->hasMany(Estudante::class, 'shcools_id');
    }

    public function matricula()
    {
        return $this->hasOne(Matricula::class, 'shcools_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'shcools_id');
    }

    public function funcionario()
    {
        return $this->hasOne(Funcionarios::class, 'shcools_id');
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionarios::class, 'shcools_id');
    }

    public function notificacao_encarregado()
    {
        return $this->hasOne(NotificacaoEncarregado::class, 'shcools_id');
    }

    public function notificacaos_encarregado()
    {
        return $this->hasMany(NotificacaoEncarregado::class, 'shcools_id');
    }

    public function encarregado()
    {
        return $this->hasOne(Encarregado::class, 'shcools_id');
    }

    public function encarregados()
    {
        return $this->hasMany(Encarregado::class, 'shcools_id');
    }

    public function mapa_efectividade()
    {
        return $this->hasOne(MapaEfectividade::class, 'shcools_id');
    }

    public function mapa_efectividades()
    {
        return $this->hasMany(MapaEfectividade::class, 'shcools_id');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'shcools_id');
    }

    public function admins()
    {
        return $this->hasMany(User::class, 'shcools_id');
    }

    public function cartao_escola()
    {
        return $this->hasOne(CartaoEscola::class, 'shcools_id');
    }

    public function cartoes_escola()
    {
        return $this->hasMany(CartaoEscola::class, 'shcools_id');
    }

    public function cartao_funcionario()
    {
        return $this->hasOne(CartaoFuncionario::class, 'shcools_id');
    }

    public function cartoes_funcioarios()
    {
        return $this->hasMany(CartaoFuncionario::class, 'shcools_id');
    }

    public function contrato()
    {
        return $this->hasOne(FuncionariosControto::class, 'shcools_id');
    }

    public function contratos()
    {
        return $this->hasMany(FuncionariosControto::class, 'shcools_id');
    }

    public function controlo_sistema()
    {
        return $this->hasOne(ControloSistema::class, 'shcools_id');
    }

    public function controlos_sistema()
    {
        return $this->hasMany(ControloSistema::class, 'shcools_id');
    }

    public function controlo_trimestre()
    {
        return $this->hasOne(ControlePeriodico::class, 'shcools_id');
    }

    public function controlos_trimestre()
    {
        return $this->hasMany(ControlePeriodico::class, 'shcools_id');
    }

    public function total_estudantes($escola)
    {
        $total = Estudante::where([
            ['shcools_id', $escola],
            ['registro', 'confirmado'],
        ])->count();
        
        return $total;
    }

    public function total_professores($escola)
    {
        $total = FuncionariosControto::where([
            ['shcools_id', $escola],
            ['status', 'activo'],
            ['level', '4'],
        ])
        ->where('cargo_geral', 'professor')
        ->count();
        
        return $total;
    }
        
    public function controle()
    {
        return $this->hasOne(ControloSistema::class, 'shcools_id', 'id')->where('level', '4')->where('tipo', 'ESCOLA');
    }
        
    public function dias_licencas($id)
    {
        date_default_timezone_set('Africa/Luanda');
        /*sistema de datas*/
        $dia = @date("d");
        $mes = @date("m");
        $ano = @date("Y");
        $dataFinal = $ano."-".$mes."-".$dia;
        
        $controlo = Shcool::with(['controle'])->findOrFail($id);
        
        $diasRestantes = 0;
        
        $date1 = date_create($controlo->controle->final ?? NULL);
        $date2 = date_create($dataFinal ?? NULL);
        // $date2 = date_create($controlo->inicio);
        $diff = date_diff($date1,$date2);
        $diasRestantes = $diff->format("%a");
        
        
        return $diasRestantes;
    }
    
        
    public function fnc_dias_licencas()
    {
        date_default_timezone_set('Africa/Luanda');
        /*sistema de datas*/
        $dia = @date("d");
        $mes = @date("m");
        $ano = @date("Y");
        $dataFinal = $ano."-".$mes."-".$dia;
    
        $admin = User::findOrFail(Auth::user()->id);
        $escola = Shcool::findOrFail($admin->shcools_id);
    
        $controlo = ControloSistema::where('shcools_id', $escola->id)
            ->where('level', '4')
        ->first();
        
        $date1 = date_create($controlo->final);
        $date2 = date_create($dataFinal);
        $diff = date_diff($date1,$date2);
        $diasRestantes = $diff->format("%a");
        
        return $diasRestantes;
    }
    
    
    

}
