<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Director;
use App\Models\Distrito;
use App\Models\FormaPagamento;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\User;
use App\Models\Paise;
use App\Models\CartaoTemplate;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\extensoes\Extensao;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\salas\Banco;
use App\Models\web\salas\Caixa;
use App\Models\web\salas\MovimentoCaixa;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use NumberFormatter;
use phpseclib\Crypt\RSA;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class EstudanteMatriculaController extends Controller
{
    use TraitHelpers;
    use TraitChavesSaft;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user->can('create: matricula') && !$user->can('create: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $paises = Paise::whereIn('short_name', ['AO', 'ESTG'])->get();
        $provincias = Provincia::all();
        $municipios = Municipio::all();
        $distritos = Distrito::all();

        $classes = AnoLectivoClasse::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['classe'])->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['turno'])->get();

        $salas = AnoLectivoSala::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['sala'])->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->with(['curso'])->get();

        $bancos = Banco::where('shcools_id', $this->escolarLogada())->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Matricula de Estudante",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "classes" => $classes,
            "bancos" => $bancos,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,
            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())
                ->get(),
            "usuario" => User::findOrFail(Auth::user()->id),
            "formas_pagamento" => FormaPagamento::where('tipo_credito', 2)->get(),
        ];

        return view('admin.estudantes.create', $headers);
    }

    public function store(Request $request)
    {
        $user_admin = auth()->user();

        if (!$user_admin->can('create: matricula') && !$user_admin->can('create: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // dados da escola logada
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        $ano_lectivo = AnoLectivo::findOrFail($request->ano_lectivos_id);
        
        if($escola->categoria == "Privado" && $escola->modulo != "Basico") {
            if ($escola->processo_pagamento_servico == "Secretaria") {
                $request->validate([
                    'nome' => 'required',
                    // 'bilheite' => 'required|string|min:8|max:17|unique:tb_estudantes,bilheite',
                    'sobre_nome' => 'required',
                    'pais_id'  => 'required',
                    'provincia_id'  => 'required',
                    'municipio_id'  => 'required',
                    'ano_lectivos_id'  => 'required',
                    'at_classes_id'  => 'required',
                    'classes_id'  => 'required',
                    'cursos_id'  => 'required',
                    'turnos_id'  => 'required',
                    'tipo_matricula'  => 'required',
                    'situacao_estudante'  => 'required',
                    'condicao_estudante'  => 'required',
                    'ano_lectivos_id'  => 'required',
                    'valor'  => 'required',
                    'valor_entregue'  => 'required',
                    'tipo_pagamento'  => 'required',
                    'documento'  => 'required',
                ]);
            } else {
                $request->validate([
                    'nome' => 'required',
                    'sobre_nome' => 'required',
                    'pais_id'  => 'required',
                    // 'bilheite' => 'required|string|min:8|max:17|unique:tb_estudantes,bilheite',
                    'provincia_id'  => 'required',
                    'municipio_id'  => 'required',
                    'ano_lectivos_id'  => 'required',
                    'at_classes_id'  => 'required',
                    'classes_id'  => 'required',
                    'cursos_id'  => 'required',
                    'turnos_id'  => 'required',
                    'tipo_matricula'  => 'required',
                    'situacao_estudante'  => 'required',
                    'condicao_estudante'  => 'required',
                    'ano_lectivos_id'  => 'required',
                ]);
            }
        }else {
            $request->validate([
                'nome' => 'required',
                'sobre_nome' => 'required',
                'pais_id'  => 'required',
                // 'bilheite' => 'required|string|min:8|max:17|unique:tb_estudantes,bilheite',
                'provincia_id'  => 'required',
                'municipio_id'  => 'required',
                'ano_lectivos_id'  => 'required',
                'at_classes_id'  => 'required',
                'classes_id'  => 'required',
                'cursos_id'  => 'required',
                'turnos_id'  => 'required',
                'tipo_matricula'  => 'required',
                'situacao_estudante'  => 'required',
                'condicao_estudante'  => 'required',
                'ano_lectivos_id'  => 'required',
            ]);
        }
            
        try {
            DB::beginTransaction();

            $virificarBI = Estudante::where('bilheite', $request->bilheite)->first();
            
            $existing = false;
            
            $caixa = NULL;

            if ($virificarBI) {
                $existing = true;  
                $verificar_matricula = Matricula::where('estudantes_id', $virificarBI->estudantes_id)->where('ano_lectivos_id', $ano_lectivo->id)->first();
                
                if($verificar_matricula) {
                    $existing = false;  
                    Alert::warning('informação', 'o estudantes já tem uma matrícula para o ano lectivo '. $ano_lectivo->ano);
                    return redirect()->back();
                }
            }
           
            // ================================================================
            // caixa
            if ($escola->categoria == "Privado" && $escola->modulo != "Basico") {
                if ($escola->processo_pagamento_servico == "Secretaria") {

                    $status_matricula_pagamento = 'Pago';
                    $status_matricula = 'confirmado';

                    if ($user_admin->can('create: pagamento')) {
                        if ($escola->modulo != "Basico") {

                            $caixa = Caixa::where('status', "activo")->where('shcools_id', $escola->id)->where('usuario_id', Auth::user()->id)->first();

                            if (!$caixa) {
                                return response()->json(['message' => "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo."], 404);
                            }

                            $caixaAberto = MovimentoCaixa::where('caixa_id', $caixa->id)
                                ->where('usuario_id', Auth::user()->id)
                                ->where('status', "aberto")
                                ->first()->id;

                            if (!$caixaAberto) {
                                return response()->json(['message' => "Não há nenhum caixa aberto no momento. Por favor, abra um caixa para continuar o processo."], 404);
                            }

                            if (!filter_var($request->valor_entregue, FILTER_VALIDATE_INT) and !!filter_var($request->valor_entregue, FILTER_VALIDATE_INT)) {
                                return response()->json(['message' => "O Valor Invalido!"], 404);
                            }

                            if ($request->valor_entregue < $request->valor) {
                                return response()->json(['message' => "O NUMERARIO Entregue para o pagamento deste serviço é insuficiente!"], 404);
                            }
                        }
                    }
                } else {
                    $status_matricula_pagamento = 'Nao Pago';
                    $status_matricula = 'nao_confirmado';
                }
            } else {
                $status_matricula_pagamento = 'Pago';
                $status_matricula = 'confirmado';
            }

            if ($escola->processo_admissao_estudante == "Prova") {
                $media = 0;
                $prova_acesso = 'Y'; // será que faz prova de exame de acesso para ser admitido 
                $exame_acesso = 'N'; // será que já fez esta prova de acesso
            }

            if ($escola->processo_admissao_estudante == "Normal") {
                $media = $request->input('media_final_curso_medio');
                $prova_acesso = 'N'; // Não precisa de prova
                $exame_acesso = 'N'; // Não precisa de prova
            }

            // ================================================================

            $extensao = Extensao::where('shcools_id', $escola->id)
                ->where('tipo', 'estudantes')
                ->where('status', 'activo')
            ->first();

            $code = time();

            $nacionalidade = Paise::find($request->pais_id);
            $naturalidade = Provincia::find($request->provincia_id);

            /**
             * Antes mesmo de cadastrar estudante temos que verificar se tem ja uma turma que pode receber est estudantes
             */
            $turma = Turma::where('classes_id', $request->classes_id)
                ->where('turnos_id', $request->turnos_id)
                ->where('cursos_id', $request->cursos_id)
                ->where('ano_lectivos_id', $ano_lectivo->id)
            ->first();

            if (!empty($request->file('doc_bilheite'))) {
                $image = $request->file('doc_bilheite');
                $imageNameBI = $code . '.' . $image->extension();
                $image->move(public_path('assets/arquivos'), $imageNameBI);
            } else {
                $imageNameBI = NULL;
            }

            if (!empty($request->file('doc_certificado'))) {
                $image2 = $request->file('doc_certificado');
                $imageNameCT = $code . '.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameCT);
            } else {
                $imageNameCT = NULL;
            }

            if (!empty($request->file('doc_outros'))) {
                $image2 = $request->file('doc_outros');
                $imageNameOD = $code . '.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameOD);
            } else {
                $imageNameOD = NULL;
            }

            if (!empty($request->file('doc_atestedao_medico'))) {
                $image2 = $request->file('doc_atestedao_medico');
                $imageNameAT = $code . '.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameAT);
            } else {
                $imageNameAT = NULL;
            }

            // Realizar operações de banco de dados aqui
            // cadastrar os dados do estudante
            
            if($existing == false) {
                $create = Estudante::create([
                    "documento" => $code,
                    "nome" => $request->nome,
                    "registro" => $status_matricula,
                    "sobre_nome" => $request->sobre_nome,
                    "nascimento" => $request->nascimento,
                    "data_emissao" => $request->data_emissao,
                    "genero" => $request->genero,
                    "estado_civil" => $request->estado_civil,
                    "nacionalidade" => $nacionalidade->name,
                    "pais_id" => $request->pais_id,
                    "provincia_id" => $request->provincia_id,
                    "municipio_id" => $request->municipio_id,
                    "distrito_id" => $request->distrito_id,
                    "dificiencia" => $request->dificiencia,
                    "bilheite" => $request->bilheite ?? time() . "LA000",
                    "pai" => $request->nome_encarregado ?? "",
                    "mae" => $request->nome_da_mae_encarregado ?? "",
                    "telefone_estudante" => $request->telefone,
                    "telefone_pai" => $request->numero_telefonico_encarregado,
                    "telefone_mae" => $request->numero_telefonico_encarregado,
                    "endereco" => $request->endereco,
                    "naturalidade" => $naturalidade->nome,
    
                    "whatsapp" => $request->whatsapp,
                    "instagram" => $request->instagram,
                    "facebook" => $request->facebook,
                    "email" => $request->email,
    
                    "shcools_id" => $escola->id,
                    "ano_lectivos_id" => $ano_lectivo->id,
                    "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
                ]);
            }else {
                $create = $virificarBI;
            }

            $prefx = $extensao ? $extensao->extensao : "ANG";
            $sufx = $extensao ? $extensao->sufix : "ANG";

            // cadastar os dados da sua matricula
            $createM = Matricula::create([
                "documento" => $create->documento,
                "numero_estudante" => $existing ? $create->numero_processo : $prefx . " " . $create->id . "/" .  $sufx,
                "status_matricula" => $status_matricula,
                "status_matricula_pagamento" =>  $status_matricula_pagamento,
                "status_inscricao" => 'Admitido',
                "ficha" => $code,
                "at_classes_id" => $request->at_classes_id,
                "classes_id" => $request->classes_id,
                "cursos_id" => $request->cursos_id,
                "turnos_id" => $request->turnos_id,
                "tipo" => $request->tipo_matricula, // confirmação , Matricula
                "status" => $request->situacao_estudante, // Novo ou repitente
                "condicao" => $request->condicao_estudante, // 'Isento', // Novo ou repitente
                "prova_acesso" => $prova_acesso,
                "exame_acesso" => $exame_acesso,
                "media" => $media,
                "cursos_primeira_opcao_id" => $request->cursos_primeira_opcao_id,
                "cursos_segunda_opcao_id" => $request->cursos_segunda_opcao_id,
                "pais_id" => $escola->pais_id,
                "provincia_id" => $escola->provincia_id,
                "municipio_id" => $escola->municipio_id,
                "distrito_id" => $escola->distrito_id,
                "level" => "1",
                "data_at" => $this->data_sistema(),
                "ano_lectivos_id" => $ano_lectivo->id,
                "shcools_id" => $escola->id,
                "estudantes_id" => $create->id,
                "funcionarios_id" => Auth::user()->id,
                "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
            ]);

            $prefx = $extensao ? $extensao->extensao : "ANG";
            $sufx = $extensao ? $extensao->sufix : "ANG";

            // actualizar doados com conta corrente e numero do processo do estuadntes
            $create->numero_processo = $existing ? $create->numero_processo :  $prefx . " " . $create->id . "/" .  $sufx;
            $create->conta_corrente = $existing ? $create->conta_corrente :   "31.1.2.1." . $create->id;
            $create->update();

            $full = $request->nome . " " . $request->sobre_nome;
            $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);

            $nome = head($usernames) . '.' . last($usernames);
            
            if($existing == false) {
                $user = User::create([
                    'nome' => $full,
                    'email' => head($usernames) . time() . "@gmail.com",
                    'usuario' => $nome,
                    'telefone' => $request->telefone,
                    'password' => Hash::make('123456'),
                    'acesso' => "estudante",
                    'level' => '100',
                    'numero_avaliacoes' => '0',
                    'status' => "Desbloqueado",
                    'login' => 'N',
                    'funcionarios_id' => $create->id,
                    'shcools_id' => $escola->id,
                ]);
    
                $role = Role::where('name', 'estudante')->first();
                $user->assignRole($role);
            }

            Arquivo::create([
                "codigo" => $create->documento,
                'model_id' => $create->id,
                'model_type' => 'estudante',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);

            $text = "O Sr(a) " . Auth::user()->nome . ", faz a matricula do estudante {$full} no curso de " . Curso::find($request->cursos_id)->curso . " classe " . Classe::find($request->classes_id)->classes;
            $text2 = "O Sr(a) acabou de fazer a matricula de um estudante";

            Notificacao::create([
                'user_id' => Auth::user()->id,
                'destino' => NULL,
                'type_destino' => 'escola',
                'type_enviado' => 'funcionario',
                'notificacao' => $text,
                'notificacao_user' => $text2,
                'status' => '0',
                'model_id' => $createM->id,
                'model_type' => "matricula",
                'shcools_id' => $escola->id
            ]);

            $verificar_encarregado = Encarregado::where('telefone', 'like', "%{$request->numero_telefonico_encarregado}%")->first();

            if ($verificar_encarregado && $request->numero_telefonico_encarregado != "" && $request->numero_telefonico_encarregado != NULL) {
                if ($request->grau_parantesco != "") {
                    EncarregadoEstudantes::create([
                        'encarregados_id' => $verificar_encarregado->id,
                        'estudantes_id' => $create->id,
                        'grau_parentesco' => $request->grau_parantesco,
                    ]);
                }
            } else {

                $encarregado = Encarregado::create([
                    // 'nome' => $request->nome,
                    // 'sobre_nome' => $request->sobre_nome,
                    'nome_completo' => $request->nome_encarregado,
                    // 'data_nascimento' => $request->data_nascimento,
                    'estado_civil'    => $request->estado_civil_encarregado,
                    'genero' => $request->genero_encarregado,
                    'profissao'    => $request->profissao_encarregado,
                    'telefone' => $request->numero_telefonico_encarregado,
                    // 'numero_bilhete' => $request->numero_bilhete,
                    // 'email' => $request->email,
                    'shcools_id' => $this->escolarLogada(),
                ]);

                if ($request->grau_parantesco != "") {
                    EncarregadoEstudantes::create([
                        'encarregados_id' => $encarregado->id,
                        'estudantes_id' => $create->id,
                        'grau_parentesco' => $request->grau_parantesco,
                    ]);
                }
            }

            if ($turma) {

                if (($escola->categoria == "Privado" && $escola->processo_pagamento_servico == "Secretaria") || $escola->categoria != "Privado") {
                    ##TODOS
                    $anoLectivoAnterior = AnoLectivo::find($this->anolectivoAnterior($ano_lectivo->id));

                    if ($escola->ensino->nome == "Ensino Superior") {
                        $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
                    } else {
                        $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
                    }

                    if ($escola->ensino->nome == "Ensino Superior") {
                        // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                        if ($anoLectivoAnterior) {
                            // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                            $_classe = Classe::findOrFail($turma->classes_id);
                            // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                            if (strtolower($_classe->classes) == "2º ano") {
                                $classes_1_ano = Classe::where('classes', '1º ano')->first();
                                $this->inserir_turmas_pautas_anterior($create->id, $classes_1_ano->id, $createM->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            }
                        }
                    } else {
                        // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                        if ($anoLectivoAnterior) {
                            // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                            $_classe = Classe::findOrFail($turma->classes_id);

                            // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                            if (strtolower($_classe->classes) == "11ª classe") {
                                $classes_10 = Classe::where('classes', '10ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($create->id, $classes_10->id, $createM->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            }

                            if (strtolower($_classe->classes) == "12ª classe") {
                                $classes_11 = Classe::where('classes', '11ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($create->id, $classes_11->id, $createM->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                                $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                                if ($anoLectivoAnteAnterior) {
                                    $classes_10 = Classe::where('classes', '10ª classe')->first();
                                    $this->inserir_turmas_pautas_anterior($create->id, $classes_10->id, $createM->cursos_id,  $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                                }
                            }

                            // ENSINO SECUNDARIO
                            if (strtolower($_classe->classes) == "8ª classe") {
                                $classes_7 = Classe::where('classes', '7ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($create->id, $classes_7->id, $createM->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            }

                            if (strtolower($_classe->classes) == "9ª classe") {
                                $classes_8 = Classe::where('classes', '8ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($create->id, $classes_8->id, $createM->cursos_id,  $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                                $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                                if ($anoLectivoAnteAnterior) {
                                    $classes_7 = Classe::where('classes', '7ª classe')->first();
                                    $this->inserir_turmas_pautas_anterior($create->id, $classes_7->id, $createM->cursos_id,  $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                                }
                            }
                        }
                    }

                    // criar pauta do estudante ou seja cardeneta
                    $this->inserir_turmas_pautas_anterior($create->id, $turma->id, $createM->cursos_id, $ano_lectivo->id, $trimestres, $turma->id);

                    $servicos = ServicoTurma::where("turmas_id", $turma->id)
                        ->where("model", "turmas")
                        ->where("ano_lectivos_id", $ano_lectivo->id)
                        ->with(["servico"])
                        ->get();

                    /////////////////////////////////////
                    $condicao_estudante = $request->input('condicao_estudante');
                    
                    if ($servicos) {
                        foreach ($servicos as $servico) {
                            if ($servico->pagamento == 'mensal') {
                                if ($condicao_estudante == "Isento" and $servico->servico->servico == "Propinas") {
                                    // verificar se o estudante isento ja tem este servico para n\ao lhe permitir ter esse servico duas vezes
                                    $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $create->id)
                                        ->where('servicos_id', $servico->servicos_id)
                                        ->where('ano_lectivos_id', $ano_lectivo->id)
                                        ->first();

                                    if ($escola->ensino->nome == "Ensino Superior") {
                                        $controle_periodico = 7;
                                    } else {
                                        $controle_periodico = 4;
                                    }

                                    if (!$verificarServicosEstudante) {
                                        CartaoEstudante::create([
                                            "mes_id" => "M",
                                            "estudantes_id" => $create->id,
                                            "servicos_id" => $servico->servicos_id,
                                            "preco_unitario" => $servico->preco,
                                            "data_at" => $servico->data_inicio,
                                            "data_exp" => $servico->data_final,
                                            "multa" => 0,
                                            "month_number" => date("m", strtotime($servico->data_inicio)),
                                            "month_name" =>  date("M", strtotime($servico->data_inicio)),
                                            "controle_periodico_id" => $controle_periodico,
                                            "ano_lectivos_id" => $ano_lectivo->id,
                                            "status" => 'Isento',
                                        ]);
                                    }
                                } else {

                                    // meses
                                    $meses = $this->cartao_estudantes_meses(
                                        $ano_lectivo->inicio,
                                        $servico->intervalo_pagamento_inicio,
                                        $servico->intervalo_pagamento_final
                                    );

                                    foreach ($meses as $mes) {
                                        $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $create->id)
                                            ->where("servicos_id", $servico->servicos_id)
                                            ->where("month_number", $mes['mes'])
                                            ->where("month_name", $mes['sigla'])
                                            ->where("ano_lectivos_id", $ano_lectivo->id)
                                            ->first();

                                        if (!$verificarServicosEstudante) {
                                            if ($escola->ensino->nome == "Ensino Superior") {
                                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "universidade");
                                            } else {
                                                $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "geral");
                                            }

                                            CartaoEstudante::create([
                                                "mes_id" => "M",
                                                "estudantes_id" => $create->id,
                                                "servicos_id" => $servico->servicos_id,
                                                "preco_unitario" => $servico->preco,
                                                "data_at" => $mes['inicio'],
                                                "data_exp" => $mes['fim'],
                                                "month_number" => $mes["mes"],
                                                "month_name" => $mes["sigla"],
                                                "multa" => 0,
                                                "controle_periodico_id" => $controle_periodico,
                                                "status_2" => "Normal",
                                                "ano_lectivos_id" => $ano_lectivo->id,
                                                "status" => "Nao Pago",
                                            ]);
                                        }
                                    }
                                }
                            } else if ($servico->pagamento == 'unico') {
                                $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $create->id)
                                    ->where('servicos_id', $servico->servicos_id)
                                    ->where('ano_lectivos_id', $ano_lectivo->id)
                                    ->first();
                                if (!$verificarServicosEstudante) {

                                    if ($servico->servico == "Matricula") {
                                        $status = 'Pago';
                                    }
                                    if ($servico->servico == "Confirmação") {
                                        $status = 'Pago';
                                    } else {
                                        $status = 'Nao Pago';
                                    }

                                    if ($escola->ensino->nome == "Ensino Superior") {
                                        $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "universidade");
                                    } else {
                                        $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "geral");
                                    }

                                    CartaoEstudante::create([
                                        "mes_id" => "U",
                                        "estudantes_id" => $create->id,
                                        "servicos_id" => $servico->servicos_id,
                                        "preco_unitario" => $servico->preco,
                                        "data_at" => $servico->data_inicio,
                                        "data_exp" => $servico->data_final,
                                        "month_number" => date("m", strtotime($servico->data_inicio)),
                                        "month_name" => date("M", strtotime($servico->data_inicio)),
                                        "status" => $status,
                                        "status_2" => 'Normal',
                                        "controle_periodico_id" => $controle_periodico,
                                        "ano_lectivos_id" => $ano_lectivo->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            //pagamento ou gerar a factura
            if ($escola->categoria == "Privado" && $escola->processo_pagamento_servico == "Secretaria" && $escola->modulo != "Basico") {
                if ($user_admin->can('create: pagamento')) {

                    $forma_pagamento = FormaPagamento::where('sigla_tipo_pagamento', $request->tipo_pagamento)->first();
                    $servico_operacional = Servico::join('tb_taxas', 'tb_servicos.taxa_id', 'tb_taxas.id')->select('tb_servicos.id', 'taxa', 'contas', 'tb_servicos.servico', 'tb_servicos.contas')->findOrFail($request->servicos_id);

                    $valor_multicaixa = 0;
                    $valor_cash = 0;

                    if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                        $valor_cash = $request->valor_a_pagar;
                        $valor_multicaixa = 0;
                    } else if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                        $valor_cash = 0;
                        $valor_multicaixa = $request->valor_a_pagar;
                    } else if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                        $valor_cash = 0;
                        $valor_multicaixa = $request->valor_a_pagar;
                    } else if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                        $valor_cash = 0;
                        $valor_multicaixa = $request->valor_a_pagar;
                    } else if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                        $valor_cash = 0;
                        $valor_multicaixa = $request->valor_a_pagar;
                    }

                    $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());

                    $contarFactura = Pagamento::where('tipo_factura', 'FR')
                        ->where('factura_ano', $ano_lectivo_activo->serie ?? date("Y"))
                        ->where('shcools_id', $this->escolarLogada())
                        ->count();

                    $ultimoRecibo = Pagamento::where('tipo_factura', 'FR')
                        ->where('factura_ano', $ano_lectivo_activo->serie ?? date("Y"))
                        ->where('shcools_id', $this->escolarLogada())
                        ->latest()
                        ->first();

                    if (!$ultimoRecibo) {
                        $hashAnterior = "";
                    } else {
                        $hashAnterior = $ultimoRecibo->hash;
                    }

                    //Manipulação de datas: data actual
                    $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

                    // $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

                    $numeroFactura = $contarFactura + 1;

                    $rsa = new RSA(); //Algoritimo RSA

                    $privatekey = $this->pegarChavePrivada();
                    $publickey = $this->pegarChavePublica();

                    // Lendo a private key
                    $rsa->loadKey($privatekey);

                    $codigo_designacao_factura = "EAV";

                    // Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                    // Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; 

                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "FR {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}" . ';' . number_format($request->valor, 2, ".", "") . ';' . $hashAnterior;

                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

                    // Lendo a public key
                    $rsa->loadKey($publickey);

                    $valor_extenso = $this->valor_por_extenso($request->valor);

                    $code = time();

                    $createP = Pagamento::create([
                        "pago_at" => strtolower($servico_operacional->servico),
                        "servicos_id" => $servico_operacional->id,
                        "caixa_at" => $servico_operacional->contas,
                        "ficha" => $code,
                        "status" => "Confirmado",
                        "desconto" => 0,
                        'tipo_servico_detalhe' => 'unico',
                        "valor" => $request->valor,
                        "valor2" => $request->valor,
                        "multa" => 0,
                        "data_at" => $this->data_sistema(),
                        "mensal" => $this->mesecompleto(),
                        "funcionarios_id" => Auth::user()->id,
                        "estudantes_id" => $create->id,
                        'valor_entregue' => $request->valor_entregue,
                        'banco_id' => $request->banco_id,
                        'caixa_id' => $caixa->id,
                        "numero_factura" => $numeroFactura,
                        'troco' => $request->valor_entregue - $request->valor,
                        'data_vencimento' => date("Y-m-d"),
                        'data_disponibilizacao' => date("Y-m-d"),
                        'factura_ano' => $ano_lectivo_activo->serie ?? date("Y"),
                        'prazo' => 0,
                        'data_vencimento' => date("Y-m-d"),
                        "model" => 'estudante',
                        "ano_lectivos_id" => $this->anolectivoActivo(),
                        "tipo_factura" =>  'FR',
                        "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                        "pagamento_id" => $forma_pagamento->id,
                        'next_factura' => "FR {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$numeroFactura}",
                        'observacao' => "",
                        'referencia' => $code,
                        'shcools_id' => $this->escolarLogada(),

                        'retificado' => 'N',
                        'convertido_factura' => 'N',
                        'factura_divida' => 'N',
                        'anulado' => 'N',

                        'moeda' => 'AOA',
                        'valor_extenso' => $valor_extenso,
                        'valor_cash' => $valor_cash,
                        'valor_multicaixa' => $valor_multicaixa,
                        'texto_hash' => $plaintext,
                        'hash' => base64_encode($signaturePlaintext),
                        'nif_cliente' => $create->bilheite,
                        'conta_corrente_cliente' => $create->conta_corrente,
                        'total_iva' => 0,
                        'total_incidencia' => $request->valor,
                        'quantidade' => 1,
                    ]);

                    // calcudo do total de incidencia
                    // ________________ valor total _____________
                    $valorBase = $request->valor * 1;
                    // calculo do iva
                    $valorIva = ($servico_operacional->taxa / 100) * $valorBase;

                    $desconto = ($request->valor * 1) * (0 / 100);

                    DetalhesPagamentoPropina::create([
                        'code' => $code,
                        'mes_id' => "NULL",
                        'valor_incidencia' => $valorBase,
                        'desconto' => 0,
                        'total_pagar' => $valorBase + $valorIva,
                        'desconto_valor' => $desconto,
                        'valor_iva' => 0,
                        'taxa_id' => $servico_operacional->taxa,
                        'mes' => date("M"),
                        'model_id' => $create->id,
                        'multa' => 0,
                        'quantidade' => 1,
                        'funcionarios_id' => Auth::user()->id,
                        'preco' => $valorBase,
                        'status' => 'Pago',
                        'servicos_id' => $servico_operacional->id,
                        'date_att' => $this->data_sistema(),
                        'ano_lectivos_id' => $this->anolectivoActivo(),
                        'shcools_id' => $this->escolarLogada(),
                        'pagamentos_id' => $createP->id,
                    ]);

                    if ($escola->modulo != "Basico") {
                        $updateCaixaAberto = MovimentoCaixa::findOrFail($caixaAberto);
                        if ($forma_pagamento->sigla_tipo_pagamento == "NU") {
                            $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $request->valor;
                        }

                        if ($forma_pagamento->sigla_tipo_pagamento == "MB") {
                            $updateCaixaAberto->valor_tpa = $updateCaixaAberto->valor_tpa + $request->valor;
                        }

                        if ($forma_pagamento->sigla_tipo_pagamento == "OU") {
                            $updateCaixaAberto->valor_cache = $updateCaixaAberto->valor_cache + $request->valor;
                            $updateCaixaAberto->valor_tpa = 0;
                        }

                        if ($forma_pagamento->sigla_tipo_pagamento == "TT") {
                            $updateCaixaAberto->valor_transferencia =  $updateCaixaAberto->valor_transferencia + $request->valor;
                        }

                        if ($forma_pagamento->sigla_tipo_pagamento == "DD") {
                            $updateCaixaAberto->valor_depositado = $updateCaixaAberto->valor_depositado + $request->valor;
                        }

                        $updateCaixaAberto->valor_fecha = $updateCaixaAberto->valor_fecha + $request->valor;
                        $updateCaixaAberto->qtd_itens = $updateCaixaAberto->qtd_itens + 1;
                        $updateCaixaAberto->update();
                    }
                }
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Dados salvos com sucesso", "redirect" => route("ficha-matricula2", $createM->ficha)], 200);

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->route('ficha-matricula2', $createM->ficha);
    }

    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matricula = Matricula::findOrFail(Crypt::decrypt($id));

        $estudante = Estudante::findOrFail($matricula->estudantes_id);

        $certificado = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->select('certificado')->first();
        $bilheite = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->select('bilheite')->first();
        $atestado = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->select('atestado')->first();
        $outros = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->select('outros')->first();



        $paises = Paise::whereIn('short_name', ['AO', 'ESTG'])->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $distritos = Distrito::get();

        $classes = AnoLectivoClasse::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->with('classe')->get();

        $turnos = AnoLectivoTurno::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->with('turno')->get();

        $salas = AnoLectivoSala::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->with('sala')->get();

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->with('curso')->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Matricula de Estudante",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "classes" => $classes,
            "turnos" => $turnos,
            "salas" => $salas,
            "cursos" => $cursos,

            "matricula" => $matricula,
            "estudante" => $estudante,

            'certificado' => $certificado,
            'bilheite' => $bilheite,
            'atestado' => $atestado,
            'outros' => $outros,

            "anolectivos" => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.edit', $headers);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'estado_civil'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',
            'at_classes_id'  => 'required',
            'classes_id'  => 'required',
            'cursos_id'  => 'required',
            'turnos_id'  => 'required',
            'tipo_matricula'  => 'required',
            'situacao_estudante'  => 'required',
            'ano_lectivos_id'  => 'required',
        ]);

        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'nome' => 'required',
        ]);
                            
        try {
            DB::beginTransaction();   
    
            $matricula = Matricula::findOrFail($id);
            $estudante = Estudante::findOrFail($matricula->estudantes_id);
    
            $nacionalidade = Paise::find($request->input('pais_id'));
            $naturalidade = Provincia::find($request->input('provincia_id'));
    
            $estudante->nome = $request->input('nome');
            $estudante->sobre_nome = $request->input('sobre_nome');
            $estudante->nascimento = $request->input('nascimento');
            $estudante->data_emissao = $request->input('data_emissao');
            $estudante->genero = $request->input('genero');
            $estudante->estado_civil = $request->input('estado_civil');
            $estudante->nacionalidade = $nacionalidade->name;
            $estudante->pais_id = $request->input('pais_id');
            $estudante->provincia_id = $request->input('provincia_id');
            $estudante->bilheite = $request->input('bilheite');
            $estudante->pai = $request->input('pai');
            $estudante->mae = $request->input('mae');
            $estudante->telefone_estudante = $request->input('telefone');
            $estudante->telefone_pai = $request->input('telefone_pai');
            $estudante->telefone_mae = $request->input('telefone_mae');
            $estudante->endereco = $request->input('endereco');
            $estudante->municipio_id = $request->input('municipio_id');
            $estudante->distrito_id = $request->input('distrito_id');
            $estudante->naturalidade = $naturalidade->nome;
    
            $estudante->whatsapp = $request->whatsapp;
            $estudante->instagram = $request->instagram;
            $estudante->facebook = $request->facebook;
            $estudante->email = $request->email;
    
            $estudante->update();
            $matricula->at_classes_id = $request->input('at_classes_id');
            $matricula->classes_id = $request->input('classes_id');
            $matricula->cursos_id = $request->input('cursos_id');
            $matricula->turnos_id = $request->input('turnos_id');
            $matricula->media = $request->input('media_final_curso_medio');
            $matricula->cursos_primeira_opcao_id = $request->input('cursos_primeira_opcao_id');
            $matricula->cursos_segunda_opcao_id = $request->input('cursos_segunda_opcao_id');
            $matricula->tipo = $request->input('tipo_matricula') ?? $matricula->tipo; // confirmação ; Matricula
            $matricula->status = $request->input('situacao_estudante') ??  $matricula->status; // Novo ou repitente
            $matricula->update();
    
            if (!empty($request->file('doc_bilheite'))) {
                $image = $request->file('doc_bilheite');
                $imageNameBI = time() . '.' . $image->extension();
                $image->move(public_path('assets/arquivos'), $imageNameBI);
            } else {
                $imageNameBI = $request->input('doc_bilheite_guardado');
            }
    
            if (!empty($request->file('doc_certificado'))) {
                $image2 = $request->file('doc_certificado');
                $imageNameCT = time() . '.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameCT);
            } else {
                $imageNameCT = $request->input('doc_certificado_guardado');
            }
    
            if (!empty($request->file('doc_outros'))) {
                $image2 = $request->file('doc_outros');
                $imageNameOD = time() . '.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameOD);
            } else {
                $imageNameOD = $request->input('doc_outros_guardado');
            }
    
            if (!empty($request->file('doc_atestedao_medico'))) {
                $image2 = $request->file('doc_atestedao_medico');
                $imageNameAT = time() . '.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameAT);
            } else {
                $imageNameAT = $request->input('doc_atestedao_medico_guardado');
            }
    
            $arquivos = Arquivo::where('model_type', 'estudante')->where('model_id', $estudante->id)->first();
    
            if ($arquivos) {
                $arquivos->certificado = $imageNameCT;
                $arquivos->bilheite = $imageNameBI;
                $arquivos->atestado = $imageNameAT;
                $arquivos->outros = $imageNameOD;
    
                $arquivos->update();
            } else {
                Arquivo::create([
                    'model_id' => $estudante->id,
                    'model_type' => 'estudante',
                    'certificado' => $imageNameCT,
                    'bilheite' => $imageNameBI,
                    'atestado' => $imageNameAT,
                    'outros' => $imageNameOD,
                ]);
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->back();
    }

    // activar e desactivar turma
    public function activarMatriculaEstudantes($id, $back = null)
    {
        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matricula = Matricula::findOrFail($id);

        if ($matricula->status_matricula === 'confirmado') {
            $status = 'inactivo';
            $level = '2';
        } else {
            $status = 'confirmado';
            $level = '1';
        }
        $matricula->status_matricula = $status;
        $matricula->level = $level;
        $matricula->update();

        if ($back) {
            Alert::success('Bom Trabalho', "Dados actualizados com sucesso");
            return redirect()->back();
        }

        return response()->json([
            "status" => 200,
            "usuario" => User::findOrFail(Auth::user()->id),
            "message" => "Dodos Actualizados com sucesso",
        ]);
    }

    // delete estudante
    public function deleteMatriculaEstudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: matricula') && !$user->can('delete: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        // Eleminar ele na matricula
        $matricula = Matricula::findOrFail($id);
        $estudante = Estudante::where($matricula->estudantes_id)->first();

        // Eleminar ele na turma
        $turma = EstudantesTurma::where([
            ['estudantes_id', $estudante->id],
            ['ano_lectivos_id',  $matricula->ano_lectivos_id],
        ])->first();

        if ($turma) {
            EstudantesTurma::findOrFail($turma->id)->delete();
        }

        // eliminar cartao
        $cartao =  CartaoEstudante::where([
            ['estudantes_id', $id],
            ['ano_lectivos_id', $matricula->ano_lectivos_id],
        ])->get();

        if ($cartao) {
            foreach ($cartao as $key) {
                CartaoEstudante::findOrFail($key->id)->delete();
            }
        }

        // eliminar pautas
        $pautas =  NotaPauta::where([
            ['estudantes_id', $id],
            ['ano_lectivos_id', $matricula->ano_lectivos_id],
        ])->get();

        if ($pautas) {
            foreach ($pautas as $key2) {
                NotaPauta::findOrFail($key2->id)->delete();
            }
        }

        $parentesRelacao =  EncarregadoEstudantes::where([
            ['estudantes_id', '=', $id],
        ])->get();

        if ($parentesRelacao) {
            foreach ($parentesRelacao as $key3) {
                EncarregadoEstudantes::findOrFail($key3->id)->delete();
            }
        }

        $pagamentos =  Pagamento::where([
            ['estudantes_id', '=', $id],
        ])->get();

        if ($pagamentos) {
            foreach ($pagamentos as $key4) {
                Pagamento::findOrFail($key4->id)->delete();
            }
        }

        Matricula::findOrFail($matricula->id)->delete();

        $est = Estudante::findOrFail($estudante->id);
        $est->forceDelete();

        return response()->json([
            'status' => 200,
            "usuario" => User::findOrFail(Auth::user()->id),
            'message' => 'Dados Excluido com sucesso!',
        ]);
    }

    // adicionar um unico estudante a uma turma create
    public function adicionarEstudanteTurma($id)
    {
        $matricula = Matricula::findOrFail(Crypt::decrypt($id));
        $estudante = Estudante::findOrFail($matricula->estudantes_id);

        if ($matricula->status_matricula === 'inactivo') {
            Alert::warning('informação', "Precisa ter a matricula activar para adicionar o estudante em uma turma");
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferência de Estudante entre Turma",
            
            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'matricula' => $matricula,
            'anos_lectivos' => AnoLectivo::where('shcools_id', $this->escolarLogada())->get(),
            'turmas' => Turma::where('status', 'activo')->where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $this->anolectivoActivo())->get(),
        ];

        return view('admin.estudantes.adicionar-turma', $headers);
    }

    // adicionar um unico estudante a uma turma update
    public function adicionarEstudanteTurmaStore(Request $request)
    {
        $request->validate([
            "estudante_id" => "required",
            "turmas_id" => "required",
            "matricula_id" => "required",
            "ano_lectivos_id" => "required",
        ]);

        $turma_destino = Turma::findOrFail($request->turmas_id);
        $estudante = Estudante::findOrFail($request->estudante_id);
        $matricula = Matricula::findOrFail($request->matricula_id);

        if ($turma_destino->classes_id != $matricula->classes_id && $turma_destino->cursos_id != $matricula->cursos_id) {
            Alert::warning("Atenção", "O processo não pode ser concluído, pois o curso e a classe do estudante não correspondem ao curso e à classe da turma.");
            return redirect()->route("shcools.mais-informacao-estudante", Crypt::encrypt($estudante->id));
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        $ano_lectivo = AnoLectivo::findOrFail($request->ano_lectivos_id);

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $turma = Turma::where('classes_id', $matricula->classes_id)
                ->where('turnos_id', $matricula->turnos_id)
                ->where('cursos_id', $matricula->cursos_id)
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->first();

            if ($turma) {

                $anoLectivoAnterior = AnoLectivo::find($this->anolectivoAnterior($ano_lectivo->id));

                if ($escola->ensino->nome == "Ensino Superior") {
                    // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                    if ($anoLectivoAnterior) {
                        // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                        $_classe = Classe::findOrFail($turma->classes_id);
                        // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                        if (strtolower($_classe->classes) == "2º ano") {
                            $classes_1_ano = Classe::where('classes', '1º ano')->first();
                            // criar pauta do estudante ou seja cardeneta
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_1_ano->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                        }
                    }
                } else {
                    // Aqui vai se criar as pautas das classes anteriores caso ele não estudava neste instituição
                    if ($anoLectivoAnterior) {
                        // tambem temos que verificar as notas antiores do estudante caso ele nunca tinha sido estudante provavelmwnte tem notas passada que vão precisar ser inserido na ano passado
                        $_classe = Classe::findOrFail($turma->classes_id);
                        // precisamos verificar se tem as notas da 10 classe caso não vamos preenchar ou criar pauta vazias
                        if (strtolower($_classe->classes) == "11ª classe") {
                            $classes_10 = Classe::where('classes', '10ª classe')->first();

                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_10->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                        }
                        if (strtolower($_classe->classes) == "12ª classe") {
                            $classes_11 = Classe::where('classes', '11ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_11->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                            $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                            if ($anoLectivoAnteAnterior) {
                                $classes_10 = Classe::where('classes', '10ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_10->id, $matricula->cursos_id, $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                            }
                        }
                        // ENSINO SECUNDARIO
                        if (strtolower($_classe->classes) == "8ª classe") {
                            $classes_7 = Classe::where('classes', '7ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_7->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);
                        }
                        if (strtolower($_classe->classes) == "9ª classe") {
                            $classes_8 = Classe::where('classes', '8ª classe')->first();
                            $this->inserir_turmas_pautas_anterior($estudante->id, $classes_8->id, $matricula->cursos_id, $this->anolectivoAnterior($ano_lectivo->id), $trimestres);

                            $anoLectivoAnteAnterior = AnoLectivo::find($this->anolectivoAntesAnterior($ano_lectivo->id));
                            if ($anoLectivoAnteAnterior) {
                                $classes_7 = Classe::where('classes', '7ª classe')->first();
                                $this->inserir_turmas_pautas_anterior($estudante->id, $classes_7->id, $matricula->cursos_id, $this->anolectivoAntesAnterior($ano_lectivo->id), $trimestres);
                            }
                        }
                    }
                }

                // criar pauta do estudante ou seja cardeneta
                $this->inserir_turmas_pautas_anterior($estudante->id, $turma->id, $matricula->cursos_id, $ano_lectivo->id, $trimestres, $turma->id);

                $servicos = ServicoTurma::where("turmas_id", $turma->id)
                    ->where("model", "turmas")
                    ->where("ano_lectivos_id", $ano_lectivo->id)
                    ->with(["servico"])
                    ->get();

                /////////////////////////////////////

                if ($servicos) {
                    foreach ($servicos as $servico) {
                        if ($servico->pagamento == 'mensal') {
                            if ($matricula->condicao == "Isento" and $servico->servico->servico == "Propinas") {

                                // verificar se o estudante isento ja tem este servico para n\ao lhe permitir ter esse servico duas vezes
                                $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                                    ->where('servicos_id', $servico->servicos_id)
                                    ->where('ano_lectivos_id', $ano_lectivo->id)
                                    ->first();

                                if ($escola->ensino->nome == "Ensino Superior") {
                                    $controle_periodico = 7;
                                } else {
                                    $controle_periodico = 4;
                                }

                                if (!$verificarServicosEstudante) {
                                    CartaoEstudante::create([
                                        "mes_id" => "M",
                                        "estudantes_id" => $estudante->id,
                                        "servicos_id" => $servico->servicos_id,
                                        "preco_unitario" => $servico->preco,
                                        "data_at" => $servico->data_inicio,
                                        "data_exp" => $servico->data_final,
                                        "multa" => 0,
                                        "month_number" => date("m", strtotime($servico->data_inicio)),
                                        "month_name" =>  date("M", strtotime($servico->data_inicio)),
                                        "controle_periodico_id" => $controle_periodico,
                                        "ano_lectivos_id" => $ano_lectivo->id,
                                        "status" => 'Isento',
                                    ]);
                                }
                            } else {
                                // meses
                                $meses = $this->cartao_estudantes_meses(
                                    $ano_lectivo->inicio,
                                    $servico->intervalo_pagamento_inicio,
                                    $servico->intervalo_pagamento_final
                                );

                                foreach ($meses as $mes) {
                                    $verificarServicosEstudante = CartaoEstudante::where("estudantes_id", $estudante->id)
                                        ->where("servicos_id", $servico->servicos_id)
                                        ->where("month_number", $mes['mes'])
                                        ->where("month_name", $mes['sigla'])
                                        ->where("ano_lectivos_id", $ano_lectivo->id)
                                        ->first();

                                    if (!$verificarServicosEstudante) {

                                        if ($escola->ensino->nome == "Ensino Superior") {
                                            $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "universidade");
                                        } else {
                                            $controle_periodico = $this->mes_periodico($mes["sigla"], "M", "geral");
                                        }

                                        CartaoEstudante::create([
                                            "mes_id" => "M",
                                            "estudantes_id" => $estudante->id,
                                            "servicos_id" => $servico->servicos_id,
                                            "preco_unitario" => $servico->preco,

                                            "data_at" => $mes['inicio'],
                                            "data_exp" => $mes['fim'],
                                            "month_number" => $mes["mes"],
                                            "month_name" => $mes["sigla"],
                                            "multa" => 0,
                                            "controle_periodico_id" => $controle_periodico,
                                            "status_2" => "Normal",
                                            "ano_lectivos_id" => $ano_lectivo->id,
                                            "status" => "Nao Pago",
                                        ]);
                                    }
                                }
                            }
                        } else 
                        if ($servico->pagamento == 'unico') {
                            $verificarServicosEstudante = CartaoEstudante::where('estudantes_id', $estudante->id)
                                ->where('servicos_id', $servico->servicos_id)
                                ->where('ano_lectivos_id', $ano_lectivo->id)
                                ->first();

                            if (!$verificarServicosEstudante) {
                                if ($servico->servico == "Matricula") {
                                    $status = 'Pago';
                                }
                                if ($servico->servico == "Confirmação") {
                                    $status = 'Pago';
                                } else {
                                    $status = 'Nao Pago';
                                }

                                if ($escola->ensino->nome == "Ensino Superior") {
                                    $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "universidade");
                                } else {
                                    $controle_periodico = $this->mes_periodico(date("M", strtotime($servico->data_inicio)), "U", "geral");
                                }

                                CartaoEstudante::create([
                                    "mes_id" => "U",
                                    "estudantes_id" => $estudante->id,
                                    "servicos_id" => $servico->servicos_id,
                                    "preco_unitario" => $servico->preco,
                                    "data_at" => $servico->data_inicio,
                                    "data_exp" => $servico->data_final,
                                    "month_number" => date("m", strtotime($servico->data_inicio)),
                                    "month_name" => date("M", strtotime($servico->data_inicio)),
                                    "status" => $status,
                                    "status_2" => 'Normal',
                                    "controle_periodico_id" => $controle_periodico,
                                    "ano_lectivos_id" => $ano_lectivo->id,
                                ]);
                            }
                        }
                    }
                }
            } else {
                Alert::warning('Informação', "Turma Invalida!");
                return redirect()->back();
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        Alert::success('Bom Trabalho', "Processo terminado com Sucesso!");
        return redirect()->route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id));
    }

    public function marcaFalecido($id)
    {
        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
                    
        try {
            DB::beginTransaction();
    
            $matricula = Matricula::findOrFail(Crypt::decrypt($id));
            $estudante = Estudante::findOrFail($matricula->estudantes_id);
    
            $matricula->status_matricula = 'falecido';
            $matricula->level = '3';
    
            $estudante->registro = 'falecido';
    
    
            $matricula->update();
            $estudante->update();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }



        Alert::success('Bom Trabalho', "Dados actualizados com sucesso");
        return redirect()->back();
    }

    public function marcaVivo($id)
    {
        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
                    
        try {
            DB::beginTransaction();   
    
            $matricula = Matricula::findOrFail(Crypt::decrypt($id));
            $estudante = Estudante::findOrFail($matricula->estudantes_id);
    
            $matricula->status_matricula = 'confirmado';
            $matricula->level = '1';
    
            $estudante->registro = 'confirmado';
    
            $matricula->update();
            $estudante->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', "Dados actualizados com sucesso");
        return redirect()->back();
    }

    public function marcaDesistente($id)
    {
        $user = auth()->user();

        if (!$user->can('update: matricula') && !$user->can('update: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                    
        try {
            DB::beginTransaction();
           
            $matricula = Matricula::findOrFail(Crypt::decrypt($id));
            $estudante = Estudante::findOrFail($matricula->estudantes_id);
    
            $matricula->status_matricula = 'desistente';
            $matricula->resultado_final = 'desistente';
            $matricula->level = '4';
    
            $estudante->registro = 'desistente';
    
            $matricula->update();
            $estudante->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

 

        Alert::success('Bom Trabalho', "Dados actualizados com sucesso");
        return redirect()->back();
    }
    
    
    // mais informaões do estudantes
    public function estudante_carregar_fotos($id = null)
    {
        $user = auth()->user();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        if($id){
            $id = Crypt::decrypt($id);
        }else {
            $id = "";
        }

        $estudante = Estudante::find($id);
       
        $head = [
            "escola" => $escola,
            "titulo" => "Carregar Foto do Estudante",
            "descricao" => env('APP_NAME'), 
            "estudante" => $estudante,
        ];
        
        return view('admin.estudantes.carregar-fotos', $head);

    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function estudante_carregar_fotos_store(Request $request)
    {
     
        $request->validate([
            'estudante_id' => 'required',
            'foto_base64' => 'required|string'
        ]);
        
        $estudante = Estudante::findOrFail($request->estudante_id);
        
        try {
            DB::beginTransaction();
        
            if ($request->has('foto_base64') && !empty($request->foto_base64)) {
                // Decodifica o base64
                $image = str_replace('data:image/png;base64,', '', $request->foto_base64);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);
            
                // Cria um nome único para a imagem
                $imageName = uniqid() . '.png';
            
                // Salva no diretório desejado
                $path = public_path('/assets/images/estudantes');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
            
                file_put_contents($path . '/' . $imageName, $imageData);
            } else {
                // Mantém a imagem antiga
                $imageName = $estudante->image;
            }
           
            // Criar funcionário
   
            $estudante->image = $imageName;
            $estudante->update();
       
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Foto carregada com sucesso"], 200);
        
    }
    
    
    // mais informaões do estudantes
    public function estudante_ver_cartao($id = null)
    {
        $user = auth()->user();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        if($id){
            $id = Crypt::decrypt($id);
        }else {
            $id = "";
        }

        $estudante = Estudante::find($id);
        
        $matricula = Matricula::with(['curso', 'classe'])->where('estudantes_id', $estudante->id)
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->first();
       
        $director = Director::where('instituicao_id', $this->escolarLogada())->where('level', '4')->first();
                
        $cartao = CartaoTemplate::where('shcools_id', $this->escolarLogada())->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'shcools_id' => $this->escolarLogada(),
            'user_id' => Auth::user()->id,
        ]);
        
           // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
       
        $head = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Visualizar Cartão do Estudante",
            "descricao" => env('APP_NAME'), 
            "estudante" => $estudante,
            "template" => $cartao,
            "matricula" => $matricula,
            "director" => $director,
        ];
        
        return view('admin.estudantes.cartao-estudante', $head);

    }
  
}
