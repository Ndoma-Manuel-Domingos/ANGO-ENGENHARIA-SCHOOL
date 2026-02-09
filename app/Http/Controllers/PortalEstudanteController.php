<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Comunicado;
use App\Models\Efeito;
use App\Models\FormaPagamento;
use App\Models\Notificacao;
use App\Models\Shcool;
use App\Models\SolicitacaoDocumento;
use App\Models\TransferenciaEscolar;
use App\Models\TurmaMateria;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\Deposito;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Semana;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\calendarios\Tempo;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\salas\Sala;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\Horario;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use phpseclib\Crypt\RSA;

class PortalEstudanteController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function home(Request $request)
    {
        $estudante = Estudante::with('escola', 'provincia', 'municipio')->findOrFail(Auth::user()->funcionarios_id);
        // ano lectivo da escola onde estudante estuda
        $todos_anos_lectivos = AnoLectivo::whereIn('shcools_id', [$estudante->shcools_id])->orderBy('status', 'asc')->get();
        
        if(!session()->has('ano_lectivo_selecionado_estudante')){
            session()->forget('ano_lectivo_selecionado_estudante');
        }

        $ano_lectivo_estudante = null;

        if(!empty($request->ano_lectivo_selecionado_id)){

            $ano_lectivo_estudante = AnoLectivo::findOrFail($request->ano_lectivo_selecionado_id);

            session()->put('ano_lectivo_selecionado_estudante', $ano_lectivo_estudante->id);
        }

     
        $matriculas = Matricula::with('escolas', 'ano_lectivo', 'classe_at', 'classe', 'turno', 'curso', 'estudante')->where( 'estudantes_id', '=', $estudante->id )->get();

        $documentos = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->first();
 
        
        $headers = [
            "titulo" => "Informações geral do Estudante",
            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'matriculas' => $matriculas,
            'documentos' => $documentos,
            'todos_anos_lectivos' => $todos_anos_lectivos,
            'ano_lectivo_estudante' => $ano_lectivo_estudante,
            "usuario" => User::findOrFail(Auth::user()->id),
      
        ];

        return view('estudantes.home', $headers);
    }

    // pautas estudantes
    public function pautaEstudantes()
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $estudante = Estudante::with(['escola'])->findOrFail(Auth::user()->funcionarios_id);
        
        $cartao = null;
        $mes_actual = date("m");
        $mes_actual_nome = date("M");
        
        if($estudante->escola->sector == "Privado"){
            if(session()->has('ano_lectivo_selecionado_estudante')){
                $ano_lectivo_estudante = AnoLectivo::findOrFail(session()->get('ano_lectivo_selecionado_estudante'));
                
                $cartao = CartaoEstudante::where('estudantes_id', '=', $estudante->id)
                ->where('ano_lectivos_id', '=', $ano_lectivo_estudante->id)
                ->where('month_number', '=', $mes_actual)
                ->where('month_name', '=', $mes_actual_nome)
                ->first();
                
                if($cartao && $cartao->status == 'Nao Pago' && $cartao->data_exp > date("Y-m-d")){
                    Alert::warning("Informação", "Não podes ver as suas notas porque ainda não efeito o pagamento de propinas do mês corrente!");
                    return redirect()->back(); 
                }
                
            }
        }
        
              
        if(session()->has('ano_lectivo_selecionado_estudante')){
            $ano_lectivo_estudante = AnoLectivo::findOrFail(session()->get('ano_lectivo_selecionado_estudante'));
            // dd(session()->get('ano_lectivo_selecionado_estudante'));
        }else{
            Alert::warning("Informação", "Selecione um ano para poder ter a informações do mesmo ano!");
            return redirect()->back();
        }
        // ano lectivo da escola onde estudante estuda
        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  

        $turma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['status', '=', 'activo'],
            ['ano_lectivos_id', '=', $ano_lectivo_estudante->id],
        ])
        ->first();
        
        $turma = Turma::findOrFail($turma->turmas_id);

        if(!$turma){
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }
           
        $escola = Shcool::with('ensino')->findOrFail($turma->shcools_id);
    
        $headers = [ 
            'ano_lectivos' => AnoLectivo::where([
                ['shcools_id', '=', $estudante->shcools_id]
            ])
            ->get(),
            'estudante' => $estudante,
            'escola' => $escola,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('estudantes.pauta', $headers);
    }

    public function mapaAproveitamentoGeralEstudante(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        
        $estudante = Estudante::findOrFail(Crypt::decrypt($request->input('estudantes_id')));
        $ano = AnoLectivo::findOrFail(Crypt::decrypt($request->input('ano_lectivos_id')));

        $turmasEstudante = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])->first();

        if(!$turmasEstudante){
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }

        $turma = Turma::findOrFail($turmasEstudante->turmas_id);
        $totalDisciplinas = DisciplinaTurma::where([
            ['turmas_id', '=', $turma->id]
        ])->count('id');

        $trimestre1 = ControlePeriodico::where('trimestre', '=', 'Iª Trimestre')->first();
        $trimestre2 = ControlePeriodico::where('trimestre', '=', 'IIª Trimestre')->first();
        $trimestre3 = ControlePeriodico::where('trimestre', '=', 'IIIª Trimestre')->first();
        $trimestre4 = ControlePeriodico::where('trimestre', '=', 'Geral')->first();

        $notasSomaMdf = NotaPauta::where([
            ['estudantes_id', '=', $estudante->id],
            ['controlo_trimestres_id', '=', $trimestre4->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])->sum('mfd');

        $notasSomaNe = NotaPauta::where([
            ['estudantes_id', '=', $estudante->id],
            ['controlo_trimestres_id', '=', $trimestre4->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])->sum('ne');

        $notas = NotaPauta::where([
            ['estudantes_id', '=', $estudante->id],
            ['controlo_trimestres_id', '=', $trimestre4->id],
            ['ano_lectivos_id', '=', $ano->id],
        ])
        ->join('tb_disciplinas', 'tb_notas_pautas.disciplinas_id', '=', 'tb_disciplinas.id')
        ->select('tb_notas_pautas.mfd', 'tb_notas_pautas.ne', 'tb_disciplinas.disciplina', 'tb_notas_pautas.id')
        ->get();
        
        $escola = Shcool::with('ensino')->findOrFail($turma->shcools_id);
    
        
        $headers = [
            "titulo" => "Notas do Estudante",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "estudantes" => $estudante,
            "estudante" => $estudante,
            "turma" => $turma,
            "turmaDisciplinas" => DisciplinaTurma::where([
                ['turmas_id', '=', $turma->id],
            ])
            ->join('tb_disciplinas', 'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->join('tb_turmas', 'tb_discplinas_turmas.turmas_id', '=', 'tb_turmas.id')
            ->select('tb_disciplinas.id')
            ->get(),
            'curso' => Curso::findOrFail($turma->cursos_id),
            'sala' => Sala::findOrFail($turma->salas_id),
            'classe' => Classe::findOrFail($turma->classes_id),
            'turno' => Turno::findOrFail($turma->turnos_id),
            'anoLectivo' => AnoLectivo::findOrFail($ano->id),
            'ano_lectivos' => AnoLectivo::where([
                ['shcools_id', '=', $estudante->shcools_id]
            ])->get(),
            'estudantes_id' => $estudante->id,
            "notas" => $notas,
            "escola" => $escola,
            "somaMFD" => $notasSomaMdf,
            "somaNE" => $notasSomaNe,    
            'totalDisciplinas' => $totalDisciplinas,
            'trimestre1' => $trimestre1,
            'trimestre2' => $trimestre2,
            'trimestre3' => $trimestre3,
            'trimestre4' => $trimestre4,
        ];

        return view('estudantes.pauta', $headers);
    }

    // extrato do estudante
    public function pagamentos()
    {
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);

        if(session()->has('ano_lectivo_selecionado_estudante')){
            $ano_lectivo_estudante = AnoLectivo::findOrFail(session()->get('ano_lectivo_selecionado_estudante'));
            // dd(session()->get('ano_lectivo_selecionado_estudante'));
        }else{
            Alert::warning("Informação", "Selecione um ano para poder ter a informações do mesmo ano!");
            return redirect()->back();
        }

        // ano lectivo da escola onde estudante estuda
        // $ano_lectivo_estudante = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->shcools_id)->first();

        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  
   
        $cartao = CartaoEstudante::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $ano_lectivo_estudante->id],
        ])->get();

        $matricula = Matricula::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $ano_lectivo_estudante->id],
            ['status_matricula', '=', 'confirmado'],
            ['shcools_id', '=', $estudante->shcools_id],
        ])->first();

        $estudanteTurma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $ano_lectivo_estudante->id],
        ])->first();

        if(!$estudanteTurma){
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }

        $servicosTurma = ServicoTurma::where([
            ['turmas_id', '=', $estudanteTurma->turmas_id],
            ['model', '=', 'turmas'],
            ['ano_lectivos_id', '=', $ano_lectivo_estudante->id],
        ])
        ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
        ->get();

        $turma = Turma::findOrFail($estudanteTurma->turmas_id);

        $headers = [ 
            "titulo" => "Esxtrato Financeiro",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "matricula" => $matricula,
            "cartao" => $cartao,
            "curso" => Curso::findOrFail($turma->cursos_id),
            "classe" => Classe::findOrFail($turma->classes_id),
            "turno" => Turno::findOrFail($turma->turnos_id),
            "sala" => Sala::findOrFail($turma->salas_id),
            "ano" => AnoLectivo::findOrFail($ano_lectivo_estudante->id),
            "turma" => $turma,
            "servicosTurma" => $servicosTurma,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
       
        return view('estudantes.situacao-financeria', $headers);
    }

    // mais informaões do estudantes
    public function historicos(Request $request)
    {
        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
        $escola = Shcool::with('ensino')->findOrFail($estudante->shcools_id);

        if(session()->has('ano_lectivo_selecionado_estudante')){
            $ano_lectivo_estudante = AnoLectivo::findOrFail(session()->get('ano_lectivo_selecionado_estudante'));
        }else{
            Alert::warning("Informação", "Selecione um ano para poder ter a informações do mesmo ano!");
            return redirect()->back();
        }

        // ano lectivo da escola onde estudante estuda
        // $ano_lectivo_estudante = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->shcools_id)->first();
      
        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  

        if($request->ano_lectivo){
            $ano_lectivo_principal = Crypt::decrypt($request->ano_lectivo);
        }else{
            $ano_lectivo_principal = $ano_lectivo_estudante->id;
        }

        $transferencias = TransferenciaEscolar::with([
            'user',
            'estudante',
            'origem',
            'destino'
        ])->where('estudantes_id', $estudante->id)->get();

        $matriculas = Matricula::with([
            'classe_at',
            'classe',
            'turno',
            'curso',
            'estudante',
            'ano_lectivo'
        ])
        ->where('estudantes_id', $estudante->id)
        ->orderBy('id', 'desc')
        ->get();
        
        $servico = Servico::where('shcools_id', $estudante->shcools_id)->where('servico', 'Propinas')->first();

        $cartoes = CartaoEstudante::with([
            'servico',
            'ano'
        ])
        ->where('servicos_id', $servico->id)
        ->where('estudantes_id', $estudante->id)
        ->where('ano_lectivos_id', $ano_lectivo_estudante->id)
        ->get();
        
        $estudanteTurma = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=',  $ano_lectivo_principal],
        ])->first();

        if(!$estudanteTurma){
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não estava inserido em nenhuma turma neste Ano Lectivo!");
            return redirect()->back();
        }

        $servicosTurma = ServicoTurma::where([
            ['turmas_id', '=', $estudanteTurma->turmas_id],
            ['model', '=', 'turmas'],
            ['ano_lectivos_id', '=',  $ano_lectivo_principal],
        ])
        ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
        ->get();
        /**
         * npotas
         */
         
        $trimestre1 = ControlePeriodico::where('trimestre', 'Iª Trimestre')->first();
        $trimestre2 = ControlePeriodico::where('trimestre', 'IIª Trimestre')->first();
        $trimestre3 = ControlePeriodico::where('trimestre', 'IIIª Trimestre')->first();
        $trimestre4 = ControlePeriodico::where('trimestre', 'Geral')->first();

        $notasSomaMdf = NotaPauta::where('estudantes_id', $estudante->id)->where('controlo_trimestres_id', $trimestre4->id)->where('ano_lectivos_id',  $ano_lectivo_principal)->sum('mfd');
        $notasSomaNe = NotaPauta::where('estudantes_id', $estudante->id)->where('controlo_trimestres_id', $trimestre4->id)->where('ano_lectivos_id',  $ano_lectivo_principal)->sum('ne');

        
        // notas turma do estudante
        $turmasEstudante = EstudantesTurma::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=',  $ano_lectivo_principal],
        ])->first();
        //turma dele
        $turma = Turma::findOrFail($turmasEstudante->turmas_id);
        //total disciplnas turma
        $totalDisciplinas = DisciplinaTurma::where([
            ['turmas_id', '=', $turma->id]
        ])->count('id');
        
        $mes_actual = date("m");
        $mes_actual_nome = date("M");
        
        $cartao = CartaoEstudante::where('estudantes_id', '=', $estudante->id)
        ->where('ano_lectivos_id', '=', $ano_lectivo_principal)
        ->where('month_number', '=', $mes_actual)
        ->where('month_name', '=', $mes_actual_nome)
        ->first();

        $headers = [
            "escola" => $escola,
            "servicosTurma" => $servicosTurma,
            "titulo" => "Historico do Estudante",
            "descricao" => "gestão de discipinas",
            'estudante' => $estudante,

            'transferencias' => $transferencias,            
            'matriculas' => $matriculas,            
            'cartao' => $cartao,  
            'cartoes' => $cartoes,  
            "ano" => AnoLectivo::findOrFail( $ano_lectivo_principal),          
            "anos" => AnoLectivo::where('shcools_id', $estudante->shcools_id)->get(),          

            "usuario" => User::findOrFail(Auth::user()->id),
            //notas
            "turmaDisciplinas" => DisciplinaTurma::where([
                ['turmas_id', '=', $turma->id],
            ])
            ->join('tb_disciplinas', 'tb_discplinas_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
            ->join('tb_turmas', 'tb_discplinas_turmas.turmas_id', '=', 'tb_turmas.id')
            ->select('tb_disciplinas.id')
            ->get(),
            "anoLectivo" => AnoLectivo::findOrFail($ano_lectivo_principal),
            "somaMFD" => $notasSomaMdf,
            "somaNE" => $notasSomaNe,    
            'totalDisciplinas' => $totalDisciplinas,
            'trimestre1' => $trimestre1,
            'trimestre2' => $trimestre2,
            'trimestre3' => $trimestre3,
            'trimestre4' => $trimestre4,

            "requests" => $request->all('ano_lectivo')
        ];


        return view('estudantes.historicos', $headers);
    }

    public function horarios()
    {
        $user = auth()->user();      
        
        if(!$user->can('read: horario')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);
        // ano lectivo da escola onde estudante estuda

        if(session()->has('ano_lectivo_selecionado_estudante')){
            $ano_lectivo_estudante = AnoLectivo::findOrFail(session()->get('ano_lectivo_selecionado_estudante'));
            // dd(session()->get('ano_lectivo_selecionado_estudante'));
        }else{
            Alert::warning("Informação", "Selecione um ano para poder ter a informações do mesmo ano!");
            return redirect()->back();
        }
      
        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  

        $turma_estudante = EstudantesTurma::where('ano_lectivos_id', $ano_lectivo_estudante->id)->where('estudantes_id', $estudante->id)->first();

        if(!$turma_estudante){
            Alert::warning('Informação', 'Sem Turma no momento!');
            return redirect()->back();
        }

        $turma = Turma::findOrFail($turma_estudante->turmas_id);
                
        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();
        
        $headers = [
            "titulo" => "Horários",
            "descricao" => "Horário",
            "estudante" => $estudante,
            "turma" => $turma,
            "tempos" => $tempos,
            "semanas" => $semanas,
        ];

        return view('estudantes.horarios', $headers);

    }

    public function solicitacaoDeclaracao(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: documento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);
        // ano lectivo da escola onde estudante estuda
        $ano_lectivo_estudante = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->shcools_id)->first();
        
        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  
        
        $escola = Shcool::with('ensino')->findOrFail($estudante->shcools_id);
        
        if($escola->ensino->nome == "Ensino Superior"){
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get(); 
        }else{
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get(); 
        }

        $efeitos = Efeito::get(); 

        $headers = [
            "titulo" => "Solicitação de Documentos",
            "descricao" => env('APP_NAME'),
            "trimestres" => $trimestres,
            "efeitos" => $efeitos,
            "escola" => $escola,
            "anos" => AnoLectivo::where('shcools_id', $estudante->shcools_id)->get(),
            "minhas_solicitacoes" => SolicitacaoDocumento::with(['finalizador', 'enviador'])->where('user_id', $estudante->id)->orderBy('created_at', 'DESC')->get(),
        ];


        return view('estudantes.solicitar-declaracao', $headers);
    }

    public function solicitacaoDeclaracaoStore(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('create: documento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            'tipo_documento' => 'required',
            'efeito_id' => 'required',
            'trimestre_id' => 'required',
            'descricao' => 'required',
            'ano_lectivos_id' => 'required',
        ], [
        
            'tipo_documento.required' => 'Campo Obrigatório',
            'efeito_id.required' => 'Campo Obrigatório',
            'trimestre_id.required' => 'Campo Obrigatório',
            'descricao.required' => 'Campo Obrigatório',
            'ano_lectivos_id.required' => 'Campo Obrigatório',
        ]);
      
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);

        $create = SolicitacaoDocumento::create([
            'user_id' => $estudante->id,
            'type_model' => 'estudante',
            'tipo_documento' => $request->tipo_documento,
            'efeito_id' => $request->efeito_id,
            'trimestre_id' => $request->trimestre_id,
            'descricao' => $request->descricao,
            'status' => '0',
            'ano_lectivos_id' => $request->ano_lectivos_id,
            'shcools_id' => $estudante->shcools_id
        ]);

        $text = "O estudante {$estudante->nome} {$estudante->sobre_nome} fez uma solicitação de uma documento especificamento ".$request->tipo_documento;
        $text2 = "O Sr(a) acabou de enviar uma solicação de documento especificamente " .$request->tipo_documento;
            
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $estudante->shcools_id,
            'type_destino' => 'escola',
            'type_enviado' => 'estudante',
            'notificacao' => $text,
            'notificacao_user' => $text2,
            'status' => '0',
            'model_id' => $create->id,
            'model_type' => "Documentos",
            'shcools_id' => $estudante->shcools_id
        ]);

        Alert::success("Bom Trabalho", "solicitação enviada com sucesso");
        return redirect()->back();
    }


    public function solicitacaoVagas(Request $request)
    {
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);
        // ano lectivo da escola onde estudante estuda
        $ano_lectivo_estudante = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->shcools_id)->first();
      
        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  

        $cursos_vagas = AnoLectivoCurso::when($request->cursos_id, function($query, $value){
            $query->where('cursos_id', $value);
        })
        ->with('curso', 'ano_lectivo', 'escola.pais', 'escola.provincia')
        ->get();

        $headers = [
            "titulo" => "Solicitações de Vagas",
            "descricao" => env('APP_NAME'),
            "cursos" => Curso::get(),
            "cursos_vagas" => $cursos_vagas,
            "requests" => $request->all('cursos_id')
        ];


        return view('estudantes.vagas', $headers);
    }

    public function solicitacaoTransferencia(Request $request)
    {
    
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);
        // ano lectivo da escola onde estudante estuda
        $ano_lectivo_estudante = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->shcools_id)->first();
      
        if(!$ano_lectivo_estudante){
            return redirect()->route('pesquisa-sem-resultado'); 
        }  

        $curso = Curso::findOrFail($request->curso);
        $escola = Shcool::findOrFail($request->escola);
        $anolectivo = AnoLectivo::findOrFail($request->ano);

        $classes = AnoLectivoClasse::with('classe')->where('shcools_id', $escola->id)->where('ano_lectivos_id', $anolectivo->id)->get();
        $turnos = AnoLectivoTurno::with('turno')->where('shcools_id', $escola->id)->where('ano_lectivos_id', $anolectivo->id)->get();

        $headers = [
            "titulo" => "Solicitações de Vagas",
            "descricao" => env('APP_NAME'),
            "curso" => $curso,
            "escola" => $escola,
            "anolectivo" => $anolectivo,
            "classes" => $classes,
            "turnos" => $turnos,
        ];

        return view('estudantes.solicitar-transferencia', $headers);
    }

    public function storeTransferencia(Request $request)
    {

        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);

        $request->validate([
            "escola_id" => 'required',
            "motivo" => 'required',
            // "documento" => 'required',
            'cursos_id' => 'required',
            'classes_id' => 'required',
            'turnos_id' => 'required',
            'ano_lectivos_id' => 'required',
        ], 
        [
            'escola_id.required' => "Campo Obrigatório",
            'motivo.required' => "Campo Obrigatório",
            'cursos_id.required' => "Campo Obrigatório",
            'classes_id.required' => "Campo Obrigatório",
            'turnos_id.required' => "Campo Obrigatório",
            'ano_lectivos_id.required' => "Campo Obrigatório",
        ]);

        // escola origem 
        $escola_origem = Shcool::findOrFail($estudante->shcools_id);
        // escola destino
        $escola_destino = Shcool::findOrFail($request->escola_id);
        

        if($escola_destino->id ==  $escola_origem->id){
            Alert::warning("informação", "Essa transferência não pode ser realizada porque o estudante já se encontra nesta escola!");
            return redirect()->back();
        }

        $verificar_transeferencia_mesma_escola = TransferenciaEscolar::where('estudantes_id', $estudante->id )
        ->where('des_shcools_id', $escola_destino->id)
        ->where('org_shcools_id', $escola_origem->id)
        ->where('cursos_id',$request->cursos_id)
        ->where('classes_id',$request->classes_id)
        ->where('turnos_id',$request->turnos_id)
        ->where('status',"processo")
        ->first();

        if($verificar_transeferencia_mesma_escola){
            Alert::warning("informação", "Essa transferência não pode ser realizada porque o estudante já realizou em transferência Neste escola!");
            return redirect()->back();
        }

        $verificar_transeferencia_outra_escola = TransferenciaEscolar::where('estudantes_id', $estudante->id )
        ->where('cursos_id',$request->cursos_id)
        ->where('classes_id',$request->classes_id)
        ->where('turnos_id',$request->turnos_id)
        ->where('status',"processo")
        ->first();

        if($verificar_transeferencia_outra_escola){
            Alert::warning("informação", "Essa transferência não pode ser realizada porque o estudante já realizou em transferência que esta ser processado!");
            return redirect()->back();
        }

        if (!empty($request->file('documento'))) {
            $image = $request->file('documento');
            $imageNameBI = time() .'.'. $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        }else{
            $imageNameBI = Null;
        }

        $ano = AnoLectivo::findOrFail($request->ano_lectivos_id);
        $curso = Curso::findOrFail($request->cursos_id);
        $classe = Classe::findOrFail($request->classes_id);
        $turno = Turno::findOrFail($request->turnos_id);

        $create = TransferenciaEscolar::create([
            'estudantes_id' => $estudante->id,
            'org_shcools_id' => $escola_origem->id,
            'des_shcools_id' => $escola_destino->id,
            'condicao' => $request->condicao,

            'cursos_id' => $request->cursos_id,
            'classes_id' => $request->classes_id,
            'turnos_id' => $request->turnos_id,
            'data_final' => $ano->final,

            'status' => "processo",
            'documento' => $imageNameBI,
            'motivo' => $request->motivo,
            'user_id' => Auth::user()->id
        ]);


        $text = "O estudante {$estudante->nome} {$estudante->sobre_nome}  fez uma solicitação de transferências para a escola {$escola_destino->nome} no curso de {$curso->curso} na classe {$classe->classes} e no turno {$turno->turnos} ano lectivo {$ano->ano}";
            
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $escola_destino->id,
            'type_destino' => 'escola',
            'type_enviado' => 'estudante',
            'notificacao' => $text,
            'notificacao_user' => $text,
            'status' => '0',
            'model_id' => $create->id,
            'model_type' => "Documentos",
            'shcools_id' => $estudante->shcools_id
        ]);

        Alert::success("Bom Trabalho", "Transferência Realizada com sucesso!");
        return redirect()->back();

    }

    public function eliminar($id)
    {
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);

        $transferencia = TransferenciaEscolar::findOrFail($id);
        $transferencia->delete();

        $text = "O estudante {$estudante->nome} {$estudante->sobre_nome} eliminou um transferências";
            
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $estudante->shcools_id,
            'type_destino' => 'escola',
            'type_enviado' => 'estudante',
            'notificacao' => $text,
            'notificacao_user' => $text,
            'status' => '0',
            'model_id' => $id,
            'model_type' => "eliminação",
            'shcools_id' => $estudante->shcools_id
        ]);

        Alert::success("Bom Trabalho", "Transferência Eliminado com sucesso");
        return redirect()->back();
    }
    
        
    public function meusDepositos(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: deposito')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
        
        $depositos = Deposito::with(['escola', 'estudante', 'ano', 'operador'])
        ->when($request->ano_lectivos_id, function($query, $value){
            $query->where('ano_lectivos_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->where('date_at', '>=', $value);
        })
        ->when($request->data_final, function($query, $value){
            $query->where('date_at', '<=', $value);
        })
        ->where('estudantes_id', $estudante->id)
        ->get();
        
        
        $headers = [
            "titulo" => "Meus Depositos",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "depositos" => $depositos,
            'requestAll' => $request->all('data_inicio', 'data_final'),
        ];


        return view('estudantes.meus-depositos', $headers);
    }
    
    public function meusPagamento(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: pagamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
        
        $servicos = Servico::where('shcools_id', $estudante->escola->id)->get();
        
        $pagamentos = Pagamento::when($request->servico, function($query, $value){
            $query->where('servicos_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->where('data_at', '>=', $value);
        })
        ->when($request->data_final, function($query, $value){
            $query->where('data_at', '<=', $value);
        })
        ->where('estudantes_id', $estudante->id)
        ->where('model','estudante')
        ->with('operador', 'escola', 'estudante', 'ano', 'servico')
        ->get();
        
        $headers = [
            "titulo" => "Meus Pagamentos",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "pagamentos" => $pagamentos,
            "servicos" => $servicos,
        ];


        return view('estudantes.meus-pgamentos', $headers);
    }
    
    public function meusPagamentoDetalhe($ficha)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: pagamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $pagamento = Pagamento::where([
            ['ficha', '=', Crypt::decrypt($ficha)],
        ])
        ->with(['operador', 'servico', 'estudante', 'items'])
        ->first();
      
        $items_pagamentos = DetalhesPagamentoPropina::where('pagamentos_id', $pagamento->id)->get();
       
        $headers = [ 
            "usuario" => User::findOrFail(Auth::user()->id),
            'pagamento' => $pagamento,
            'items_pagamentos' => $items_pagamentos,
        ];

        return view('estudantes.meus-pgamentos-detalhes', $headers);
    }
    
    public function efectuarPagamentos(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('create: pagamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
        
        $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->escola->id)->first();
        
        $turma = EstudantesTurma::where('estudantes_id', $estudante->id)->where('ano_lectivos_id', $ano_lectivo->id)->first();
        
        if(!$turma){
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque não estás inserido em nenhuma turma!");
            return redirect()->back();
        }
        
        if($turma){
            $servicos = ServicoTurma::where([
                ['turmas_id', '=', $turma->turmas_id],
                ['model', '=', 'turmas'],
            ])
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico' ,'tb_servicos.id')
            ->get();
        }
        
        // $servicos = Servico::where('shcools_id', $estudante->escola->id)->get();
        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($estudante->escola->id),
            "verAnoLectivoActivo" => AnoLectivo::find($ano_lectivo->id),
            "formas_pagamento" => FormaPagamento::get(),
            "titulo" => "Efectuar Pagamentos",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "estudante" => $estudante,
            "servicos" => $servicos,
        ];

        return view('estudantes.efectuar-pagamentos', $headers);
    }
    
    public function efectuarPagamentosStore(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('create: pagamento')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
        
        $escola = Shcool::with('ensino')->findOrFail($estudante->escola->id);
        
        $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->escola->id)->first();
           
        $validate = Validator::make($request->all(), [
            'valor' => 'required',
            'servico' => 'required',
            'valor_entregue' => 'required',
        ], [
            "valor.required" => "******",
            "servico.required" => "******",
            "valor_entregue.required" => "******",
        ]);
          
        // dd($request->all());
          
        $request->documento = "FR";        
        $request->desconto = 0;        
        $request->aplicacao_multa = "sim";        
        $request->tipo_pagamento = "MB";
        $request->banco = "Nenhum";
        $request->numero_transicao = time();

          
        $valor_entregue = (int) $request->valor_entregue;
        $valor = (int) $request->valor;
        $quantidade = $request->quantidade ?? 1;
      
        $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();
        
        $servico = Servico::with('taxa','motivo')->findOrFail($request->servico);
       
        if ((!filter_var($request->valor, FILTER_VALIDATE_FLOAT) AND !filter_var($request->valor, FILTER_VALIDATE_INT)) AND
            (!filter_var($request->desconto, FILTER_VALIDATE_FLOAT) AND !filter_var($request->desconto, FILTER_VALIDATE_INT)) AND
            (!filter_var($request->valor_entregue, FILTER_VALIDATE_FLOAT) AND !filter_var($request->valor_entregue, FILTER_VALIDATE_INT)) AND
            (!filter_var($request->multa, FILTER_VALIDATE_FLOAT) AND !filter_var($request->multa, FILTER_VALIDATE_INT))) {
            return response()->json([
                'status' => 300,
                'message' => "Os Valores não podem ser Letras por favor",
            ]);
        }
        
        if($valor_entregue != $estudante->saldo){
            return response()->json([
                'status' => 300,
                'message' => "Saldo Invalido, por favor verifica o seu saldo!",
            ]);
        }
     
        if($valor_entregue < $valor * $quantidade){
            return response()->json([
                'status' => 300,
                'message' => "O valor Entregue para o pagamento deste serviço é insuficiente O total seria " . number_format($valor * $quantidade ?? 1, 2, ',', '.'),
            ]);
        }
  
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }
        else{
                
            try {
                DB::beginTransaction();
 
                $cartao_estudantil = CartaoEstudante::where([
                    ['status', '=', 'processo'],
                    ['estudantes_id', '=', $estudante->id],
                    ['servicos_id', '=', $servico->id],
                    ['ano_lectivos_id', '=', $ano_lectivo->id],
                ])->get(); 
                
                
                $contarFactura = Pagamento::where('tipo_factura', '=', $request->documento)->where('factura_ano', '=', date("Y"))->where('shcools_id', '=', $escola->id)->count();
            
                $ultimoRecibo = Pagamento::where('tipo_factura', '=', $request->documento)->where('factura_ano', '=', date("Y"))->where('shcools_id', '=', $escola->id)->orderBy('id', 'DESC')->limit(1)->first();
        
                if(!$ultimoRecibo){
                    $hashAnterior = "";
                }else{
                    $hashAnterior = $ultimoRecibo->hash;
                }
            
                //Manipulação de datas: data actual
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        
                // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        
                $ano = date("Y");
                $numeroFactura = $contarFactura + 1;
                
                if(!$ultimoRecibo){
                    $hashAnterior = "";
                }else{
                    $hashAnterior = $ultimoRecibo->hash;
                }
                
                //Manipulação de datas: data actual
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        
                $ano = date("Y");
                $numeroFactura = $contarFactura + 1;
    
                $rsa = new RSA(); //Algoritimo RSA
        
                $privatekey = $this->pegarChavePrivada();
                $publickey = $this->pegarChavePublica();
        
                // Lendo a private key
                $rsa->loadKey($privatekey);
                
                if($request->input('status_servico_pagar') == "unico"){
                               
                    $servico = Servico::with('taxa','motivo')->findOrFail($request->servico);
                    
                    // calcudo do total de incidencia
                    //________________ valor total _____________
                    $valor_incidencia = ($valor * $quantidade) + $request->multa;
                    // calculo do iva
                    $valorIva = ($servico->taxa->taxa / 100) * $valor_incidencia;
                                
                    /**
                    * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                    * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
            
                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} AGT{$ano}/{$numeroFactura}" . ';' . number_format(($valor_incidencia), 2, ".", "") . ';' . $hashAnterior;
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
            
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
            
                    // Lendo a public key
                    $rsa->loadKey($publickey);
                    
                    
                    $valor_extenso = $this->valor_por_extenso($valor_incidencia);
                    
                    if($forma_pagamento->sigla_tipo_pagamento == "NU"){
                        $valor_cash = $valor_incidencia;
                        $valor_deposito = 0;
                        $valor_multicaixa = 0;
                    }else if($forma_pagamento->sigla_tipo_pagamento == "MB"){
                        $valor_deposito = 0;
                        $valor_cash = 0;
                        $valor_multicaixa = $valor_incidencia;
                    }else if($forma_pagamento->sigla_tipo_pagamento == "DD"){
                        $valor_multicaixa = 0;
                        $valor_cash = 0;
                        $valor_deposito = $valor_incidencia;
                    }
                    
                    $codigo = time();
                    
                    $createPagamento = Pagamento::create([
                        "pago_at" => $servico->servico,	
                        "quantidade" => $quantidade,
                        "servicos_id" => $servico->id,	
                        "status" => "Confirmado",
                        'tipo_servico_detalhe' => 'unico',
                        "caixa_at" => $servico->contas,	
                        "ficha" => $codigo,	
                        "referencia" => $codigo,
                        "valor" => $valor,	
                        "valor2" => $quantidade * $valor,	
                        "troco" => $valor_entregue - $valor_incidencia,	
                        "valor_entregue" => $valor_entregue,	
                        "desconto" => 0,	
                        "multa" => $request->multa,	
                        "banco" => $request->banco,	
                        "numero_transacao" => $request->numero_transicao,	
                        "data_at" => $this->data_sistema(),
                        "mensal" => $this->mesecompleto(),	
                        "funcionarios_id" => Auth::user()->id,
                        "estudantes_id" => $estudante->id,
                        "numero_factura" => $numeroFactura,
                        "tipo_factura" => $request->documento,
                        "next_factura" => "{$request->documento} AGT{$ano}/{$numeroFactura}",
                        "shcools_id" => $escola->id,
    
                        "model" => 'estudante',
                        "ano_lectivos_id" => $ano_lectivo->id, 
                        'data_vencimento' => date("Y-m-d"),
                        'data_disponibilizacao' => date("Y-m-d"),
                        
                        'factura_ano' => date("Y"),
                        'prazo' => 0,
                        
                        
                        "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                        "pagamento_id" => $forma_pagamento->id,
                        "valor_extenso" => $valor_extenso,
                        'total_iva' => $valorIva,
                        'valor_cash' => $valor_cash,
                        'valor_multicaixa' => $valor_multicaixa,
                        'valor_deposito' => $valor_deposito,
                        'texto_hash' => $plaintext,
                        'hash' => base64_encode($signaturePlaintext),
                        'nif_cliente' => $estudante->bilheite,
                        'total_incidencia' => $valor_incidencia,
                        'conta_corrente_cliente' => $estudante->conta_corrente,
                    ]);
                    
                    
                    DetalhesPagamentoPropina::create([
                        'code' => $codigo,	
                        'pagamentos_id' => $createPagamento->id,	
                        'multa' => $request->multa,
                        'total_pagar' => $valor_incidencia,
                        'mes_id' => "NULL",
                        'valor_incidencia' => $valor_incidencia,
                        'valor_iva' => $valorIva,
                        'taxa_id' => $servico->taxa->taxa,
                        'mes' => date('M'),
                        'model_id' => $estudante->id,
                        'quantidade' => $request->quantidade,
                        'funcionarios_id' => Auth::user()->id,
                        'preco' => $request->valor,
                        'status' => 'Pago',
                        'servicos_id' => $servico->id,
                        'date_att' => $this->data_sistema(),
                        'ano_lectivos_id' => $ano_lectivo->id,
                        'shcools_id' => $escola->id,
                    ]);
                    
                    $cartao_estudantil_unico_servico = CartaoEstudante::where([
                        ['estudantes_id', '=', $estudante->id],
                        ['servicos_id', '=', $servico->id],
                        ['ano_lectivos_id', '=', $ano_lectivo->id],
                    ])->first();  
        
                    $updateCartao = CartaoEstudante::find($cartao_estudantil_unico_servico->id);
                    $updateCartao->status = 'Pago';
                    $updateCartao->save();
                    
                
                }else if($request->input('status_servico_pagar') == 'mensal'){
                                
                    $servico = Servico::with('taxa','motivo')->findOrFail($request->servico);                
                    
                    $items = DetalhesPagamentoPropina::selectRaw('
                        SUM(multa) as total_multa,
                        SUM(preco) as total_preco,
                        SUM(quantidade) as total_quantidade,
                        SUM(valor_incidencia) as total_incidencia,
                        SUM(valor_iva) as total_iva
                    ')->where([
                        ['status', '=', 'processo'],
                        ['funcionarios_id', '=', Auth::user()->id],
                        ['servicos_id', '=', $servico->id],
                        ['model_id', '=', $estudante->id],
                    ])
                    ->first();
                    
                    $timestemp = DetalhesPagamentoPropina::where('status', 'processo')->where('funcionarios_id', Auth::user()->id)->where('servicos_id', $servico->id)->where('model_id', $estudante->id)
                    ->first();    
                    
                    $timestemps = DetalhesPagamentoPropina::where('status', 'processo')->where('funcionarios_id', Auth::user()->id)->where('servicos_id', $servico->id)->where('model_id', $estudante->id)
                    ->get(); 
                    
                    /**
                    * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                    * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
            
                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->documento} AGT{$ano}/{$numeroFactura}" . ';' . number_format(($items->total_preco + $items->total_multa), 2, ".", "") . ';' . $hashAnterior;
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
            
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
            
                    // Lendo a public key
                    $rsa->loadKey($publickey);
                    
                    $valor_extenso = $this->valor_por_extenso($items->total_preco + $items->total_multa);
                    
                    if($forma_pagamento->sigla_tipo_pagamento == "NU"){
                        $valor_cash = $items->total_preco + $items->total_multa;
                        $valor_multicaixa = 0;
                        $valor_deposito = 0;
                    }else if($forma_pagamento->sigla_tipo_pagamento == "MB"){
                        $valor_cash = 0;
                        $valor_deposito = 0;
                        $valor_multicaixa = $items->total_preco + $items->total_multa;
                    }else if($forma_pagamento->sigla_tipo_pagamento == "DD"){
                        $valor_multicaixa = 0;
                        $valor_cash = 0;
                        $valor_deposito = $items->total_preco + $items->total_multa;
                    }
                    
                    $createPagamento = Pagamento::create([
                        "pago_at" =>  $servico->servico,	
                        "quantidade" => $items->total_quantidade ?? 1,	
                        "servicos_id" => $servico->id,	
                        'tipo_servico_detalhe' => 'mensal',
                        "status" => "Confirmado",	
                        "caixa_at" => 'receita',	
                        "ficha" => $timestemp->code,	
                        "referencia" => $timestemp->code,
                        
                        "valor" => $items->total_preco / $items->total_quantidade, //$request->input('valor'),	
                        "valor2" => $items->total_preco + $items->total_multa, //$request->input('valor'),	
        
                        "troco" => $valor_entregue - ($items->total_preco + $items->total_multa),	
                        "valor_entregue" => $valor_entregue,	
                        "desconto" => 0,	
                        "multa" => $items->total_multa,	
                        "banco" => $request->banco,	
        
                        "numero_transacao" => $request->input('numero_transicao'),	
                        "data_at" => $this->data_sistema(),	
                        "mensal" => $this->mesecompleto(),
                        "funcionarios_id" => Auth::user()->id,
                        "estudantes_id" => $estudante->id,
                        "model" => 'estudante',
                        "ano_lectivos_id" => $ano_lectivo->id,
                        "numero_factura" => $numeroFactura,
                        "tipo_factura" => $request->documento,
                        "next_factura" => "{$request->documento} AGT{$ano}/{$numeroFactura}",
                        "shcools_id" => $escola->id,
    
                        'data_vencimento' => date("Y-m-d"),
                        'data_disponibilizacao' => date("Y-m-d"),
    
                        "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                        "pagamento_id" => $forma_pagamento->id,
                        "valor_extenso" => $valor_extenso,
                        'factura_ano' => date("Y"),
                        'prazo' => 0,
                        'total_iva' => $items->total_iva,
                        'valor_cash' => $valor_cash,
                        'valor_multicaixa' => $valor_multicaixa,
                        'valor_deposito' => $valor_deposito,
                        'texto_hash' => $plaintext,
                        'hash' => base64_encode($signaturePlaintext),
                        'nif_cliente' => $estudante->bilheite,
                        'total_incidencia' => $items->total_incidencia,
                        'conta_corrente_cliente' => $estudante->conta_corrente,
                    ]);
                    
                    if ($timestemps) {
                        foreach ($timestemps as $item) {
                            $upd = DetalhesPagamentoPropina::findOrFail($item->id);
                            $upd->status = 'Pago';
                            $upd->pagamentos_id = $createPagamento->id;
                            $upd->shcools_id = $escola->id;
                            $upd->ano_lectivos_id = $ano_lectivo->id;
                            $upd->update();    
                        }
                    }           
    
                    if ($cartao_estudantil) {
                        foreach ($cartao_estudantil as $cartao) {
                            $upd = CartaoEstudante::findOrFail($cartao->id);
                            $upd->status = 'Pago';
                            $upd->update();    
                        }
                    }
                }
                            
                         
                $estudantes = Estudante::findOrFail($estudante->id); 
                $estudantes->saldo = $estudantes->saldo - $valor;
                $estudantes->update();
                            
                // Código que realiza operações no banco de dados
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // Tratar a exceção, registrar logs ou tomar outras medidas necessárias
                echo "Ocorreu um erro: " . $e->getMessage();
            }

        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            "ficha" => $createPagamento->ficha, //->get();
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

        
    public function minhaMateria(Request $request)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: materias')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
 
        $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $estudante->escola->id)->first();
        
        $turma = EstudantesTurma::where('estudantes_id', $estudante->id)->where('ano_lectivos_id', $ano_lectivo->id)->first();
        
        $disciplinas = DisciplinaTurma::with(['disciplina'])->where('turmas_id', $turma->turmas_id)->get();
        $professores = FuncionariosTurma::with(['professor'])->where('turmas_id', $turma->turmas_id)->get();
                
        $materias = TurmaMateria::when($request->professor_id, function($query, $value){
            $query->where('professor_id', $value);
        })->when($request->disciplinas_id, function($query, $value){
            $query->where('disciplinas_id', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
        })
        ->with(['professor', 'turma', 'disciplina', 'escola', 'ano'])
        ->where('turmas_id', $turma->turmas_id)
        ->get();

        $headers = [
            "titulo" => "Minhas Matérias",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "materias" => $materias,
            "disciplinas" => $disciplinas,
            "professores" => $professores,
        ];


        return view('estudantes.minhas-materias', $headers);
    }
    
    public function minhaMateriaApresentar($id)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: materias')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $materia = TurmaMateria::with(['professor', 'turma', 'disciplina', 'escola', 'ano'])
        ->findOrfail($id);
        
        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);

        $headers = [
            "titulo" => "Editar matérias",
            "descricao" => "Professor",
            'estudante' => $estudante,
            'materia' => $materia,
        ];

        return view('estudantes.apresentar-materias', $headers);
    }
    
    
    public function meusComunicados()
    {
        $user = auth()->user();      
        
        if(!$user->can('read: comunicados')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);
        
        $comunicados = Comunicado::where('shcools_id', $estudante->shcools_id)->where('level', '4')->where('to_escola', 'Estudantes')->orWhere('to_escola', 'Todos')->with(['user', 'escola', 'ano'])->orderBy('id', 'desc')->get();

        $headers = [
            "titulo" => "Comunicados",
            "descricao" => "Listagem",
            'estudante' => $estudante,
            "comunicados" => $comunicados,
        ];

        return view('estudantes.comunicados', $headers);
    }   
    
    public function detalheComunicado($id)
    {
        $user = auth()->user();      
        
        if(!$user->can('read: comunicados')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudante = Estudante::with('escola')->findOrFail(Auth::user()->funcionarios_id);

        $comunicado = Comunicado::where('shcools_id', $estudante->shcools_id)->where('level', '4')->where('to_escola', 'Professores')->orWhere('to_escola', 'Todos')->with(['user', 'escola', 'ano'])->findOrFail($id);

        $headers = [
            "titulo" => "Detalhes do Comunicado",
            "descricao" => "gestão de comunicados",
            'estudante' => $estudante,
            "comunicado" => $comunicado,
        ];

        return view('estudantes.detalhe-comunicados', $headers);
    }
    
    
    public function estudantesFotoPerfil(Request $request)
    {
        
        $validacao = $request->validate([
            'estudanteFoto' => 'required',
            'fotografiaEstudante' => 'required|mimes:jpg,jpeg,png',
        ], [
            'estudanteFoto.required' => "***",
            'fotografiaEstudante.required' => "Deves Selecionar uma imagem"
        ]);

        $estudantes = Estudante::findOrFail($request->input('estudanteFoto'));

        if (!empty($request->file('fotografiaEstudante'))) {
            $image = $request->file('fotografiaEstudante');
            $imageName = time() .'.'. $image->extension();
            $image->move(public_path('assets/images/recursosHumanos'), $imageName);
        }else{
            $imageName = Null;
        }

        $estudantes->image = $imageName;
        $estudantes->update();

        return redirect()->route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes->id));

    }

    public function privacidade()
    {
        $usuario = User::findOrFail(Auth::user()->id);
        
        $headers = [
            "titulo" => "Privacidade",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('estudantes.privacidade', $headers);
    }

    public function privacidadeUpdate(Request $request, $id)
    {
        $request->validate([
            'password_1' => 'required',
            'password_2' => 'required',
            'password_3' => 'required',
            'user' => 'required',
        ]);

        $usuario = User::findOrFail($id);
        $estudante = Estudante::findOrFail(Auth::user()->funcionarios_id);

        if (!Hash::check($request->password_1, $usuario->password)) {
            Alert::warning('Atenção', 'Senha Actual Incorrecta');
            return redirect()->route('est.privacidade')->with('danger', 'Senha Actual Incorrecta');
        }      
        
        if ($request->password_2 != $request->password_3) {
            Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
            return redirect()->route('est.privacidade')->with('danger', 'As duas novas senhas não podem ser diferentes');
        } 

        // $usuario->password = Hash::make($request->password_2);
        $usuario->usuario = $request->user;
        $usuario->email = $request->email;
        $usuario->nome = $request->nome;
        $usuario->telefone = $request->telefone;
        $usuario->update();

        $text = "O estudante {$estudante->nome} {$estudante->sobre_nome} fez uma actualizações no se dados";
            
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'destino' => $estudante->shcools_id,
            'type_destino' => 'escola',
            'type_enviado' => 'estudante',
            'notificacao' => $text,
            'notificacao_user' => $text,
            'status' => '0',
            'model_id' => $usuario->id,
            'model_type' => "actulizações",
            'shcools_id' => $estudante->shcools_id
        ]);

        if($usuario->update()){
            Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
            return redirect()->route('est.privacidade')->with('message', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        }
    }

}
