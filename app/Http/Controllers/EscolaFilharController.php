<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\EscolaFilhar;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\extensoes\Extensao;
use App\Models\web\salas\AnoLectivoSala;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class EscolaFilharController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    // --------------------------------------------------------------------------------------
    // ----------------------------------START ANO LECTIVO----------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------
    
    // ano lectivo
    public function index()
    {
        $user = auth()->user();
        
        // if(!$user->can('read: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        
        
        $escolas = EscolaFilhar::where('shcools_id', $this->escolarLogada())->get();

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Escolas Filhares",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolas" => $escolas
        ];

        return view('admin.escolas-afilhares.home', $headers);
    }

    public function create()
    {
        $user = auth()->user();
        
        // if(!$user->can('create: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        
        
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Cadastrar Nova Escola",
            "descricao" => env('APP_NAME'),
            
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "ensinos" => $ensinos,
            
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.escolas-afilhares.create', $headers);
    }

    // store do ano Lectivo
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('create: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $request->validate([
            "nome" => 'required',
            "status" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);         
        
        // ==========================USUARIO E ESCOLA LOGADAS===========================
        
        $create = EscolaFilhar::create([
            'nome' => $request->nome,
            'status' => $request->status,
            'shcools_id' => $this->escolarLogada(),
            
            'director' => $request->director,
            'sector' => $request->sector,
            'ensino_id' => $request->ensino_id,
            'pais_id' => $request->pais_id,
            'provincia_id' => $request->provincia_id,
            'municipio_id' => $request->municipio_id,
            'distrito_id' => $request->distrito_id,
            'telefone1' => "000-000-000",
            'telefone2' => "000-000-000",
        ]);
        $create->save();


        Alert::success("Bom Trabalho", "Dados salvos com sucesso");
        return redirect()->back();
    }

    // editar ano Lectivo view
    public function edit($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('update: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $escolas = EscolaFilhar::findOrFail(Crypt::decrypt($id));
 
        
            
        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();
        
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Editar Escola",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolas" => $escolas,
            
                        
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "distritos" => $distritos,
            "ensinos" => $ensinos,
        ];

        return view('admin.escolas-afilhares.edit', $headers);
    }

    // actualizar os dados do ano Lectivo
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // if(!$user->can('update: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $request->validate([
            "nome" => 'required',
            "status" => 'required',
        ], [
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]); 

        $update = EscolaFilhar::find($id);
        
        $update->nome = $request->nome;
        $update->status = $request->status;
        
        $update->director = $request->director;
        $update->sector = $request->sector;
        $update->ensino_id = $request->ensino_id;
        $update->pais_id = $request->pais_id;
        $update->provincia_id = $request->provincia_id;
        $update->municipio_id = $request->municipio_id;
        $update->distrito_id = $request->distrito_id;
        
        $update->update();

        Alert::success("Bom trabalho", "Dados Actualizados com successo");
        return redirect()->back();

    }
    
    // apresentar o ano Lectivo
    public function show($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('read: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $escolas = EscolaFilhar::findOrFail(Crypt::decrypt($id));

        
        
                   
        $total_matriculas = Matricula::where('status_matricula', '!=', 'nao_confirmado')
            ->where('status_matricula', '!=', 'rejeitado')
            ->where('status_inscricao', 'Admitido')
            ->where('shcools_filhar_id', $escolas->id)
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->count();

                
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $headers = [ 
            "escola" => $escola,
            'total_matriculas' =>  $total_matriculas,
            
            "titulo" => "Lista dos Anos Lectivos",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "escolas" => $escolas,
        ];

        return view('admin.escolas-afilhares.show', $headers);

    }

    // desactivar ano lectivo em geral so o administrador

    // deletar Ano Lectivo
    public function delete($id)
    {
        $user = auth()->user();
        
        // if(!$user->can('delete: ano lectivo')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        $ano = AnoLectivo::find(Crypt::decrypt($id));
        $text = Auth::user()->usuario . " Activo um Ano Lectivo " . $ano->ano;
            
        Notificacao::create([
            'user_id' => Auth::user()->id,
            'notificacao' => $text,
            'status' => '0',
            'destino' => $this->escolarLogada(),
            'type_destino' => 'ministerio',
            'type_enviado' => 'escola',
            'model_id' => $ano->id,
            'model_type' => "Ano Lectivo",
            'shcools_id' => $this->escolarLogada()
        ]);

        $ano->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // view calendarios principal
    public function estudantes(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante') && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escolas = EscolaFilhar::findOrFail(Crypt::decrypt($id));

        $matriculas = Matricula::where('tb_matriculas.status_matricula', '!=', 'nao_confirmado')
            ->where('tb_matriculas.status_matricula', '!=', 'rejeitado')
            ->when($request->status, function ($q, $v) {
                $q->where('status_matricula', $v);
            })->when($request->cursos_id, function ($q, $v) {
                $q->where('cursos_id', $v);
            })->when($request->classes_id, function ($q, $v) {
                $q->where('classes_id', $v);
            })->when($request->turnos_id, function ($q, $v) {
                $q->where('turnos_id', $v);
            })
            ->whereHas('estudante', function($query) use ($request){
                $query->when($request->genero, function ($q, $v) {
                    $q->where('genero', $v);
                });
                $query->when($request->finalista, function ($q, $v) {
                    $q->where('finalista', $v);
                });
            })
            ->where('status_inscricao', 'Admitido')
            ->where('shcools_filhar_id', $escolas->id)
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
            ->get();



        $classes = AnoLectivoClasse::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('classe')
            ->get();

        $turnos = AnoLectivoTurno::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('turno')
            ->get();

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('curso')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "classes" => $classes,
            "turnos" => $turnos,
            "cursos" => $cursos,
            
            "titulo" => "Lista dos Estudantes",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
            "escolas" => $escolas,
   
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
            "filtros" => $request->all('status', 'cursos_id', 'classes_id', 'turnos_id', 'genero', 'finalista'),
        ];

        return view('admin.escolas-afilhares.estudantes', $headers);
    }

    public function create_estudantes($id)
    {
        $user = auth()->user();

        if (!$user->can('read: estudante')  && !$user->can('read: matricula')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
                
        $escolas = EscolaFilhar::findOrFail(Crypt::decrypt($id));


 
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            'escolas' =>  $escolas,
          
            "titulo" => "Novo Estudante",
            "descricao" => env('APP_NAME'),

            "paises" => Paise::where('id', 6)->get(),
            "provincias" => Provincia::get(),
            "municipios" => Municipio::get(),
            "distritos" => Distrito::get(),

            "classes" => AnoLectivoClasse::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
                ->with('classe')
                ->get(),

            "turnos" => AnoLectivoTurno::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
                ->with('turno')
                ->get(),

            "salas" => AnoLectivoSala::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
                ->with('sala')
                ->get(),

            "anolectivos" => AnoLectivo::where([
                ['shcools_id', '=', $this->escolarLogada()],
                ['status', '=', 'activo']
            ])->get(),

            "cursos" => AnoLectivoCurso::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['shcools_id', '=', $this->escolarLogada()],
            ])
                ->with('curso')
                ->get(),

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.escolas-afilhares.estudantes-create', $headers);
    }

    public function create_estudantes_store(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('create: matricula')  && !$user->can('create: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                        
        $escolas = EscolaFilhar::findOrFail(Crypt::decrypt($id));

        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',
            'at_classes_id'  => 'required',
            'classes_id'  => 'required',
            'cursos_id'  => 'required',
            'turnos_id'  => 'required',
            'tipo_matricula'  => 'required',
            'ano_lectivos_id'  => 'required',
            'media'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",

            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',
            'at_classes_id.required'  => 'Compo Obrigatório',
            'classes_id.required'  => 'Compo Obrigatório',
            'cursos_id.required'  => 'Compo Obrigatório',
            'turnos_id.required'  => 'Compo Obrigatório',
            'tipo_matricula.required'  => 'Compo Obrigatório',
            'ano_lectivos_id.required'  => 'Compo Obrigatório',
            'media.required'  => 'Compo Obrigatório',
        ]);

        $virificarBI = Estudante::where('bilheite', $request->input('bilheite'))->first();

        if ($virificarBI) {
            Alert::warning('Informação', "Número do Bilheite já Existe registrado!");
            return redirect()->back();
        }
   
        $inscricao = 'Admitido';
   
        // ================================================================
        // verfificações
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        
        // ================================================================

        $code = time();

        $nacionalidade = Paise::find($request->input('pais_id'));
        $naturalidade = Provincia::find($request->input('provincia_id'));

        if (!empty($request->file('doc_bilheite'))) {
            $image = $request->file('doc_bilheite');
            $imageNameBI = time() . '.' . $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        } else {
            $imageNameBI = NULL;
        }

        if (!empty($request->file('doc_certificado'))) {
            $image2 = $request->file('doc_certificado');
            $imageNameCT = time() . '.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameCT);
        } else {
            $imageNameCT = NULL;
        }

        if (!empty($request->file('doc_outros'))) {
            $image2 = $request->file('doc_outros');
            $imageNameOD = time() . '.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameOD);
        } else {
            $imageNameOD = NULL;
        }

        if (!empty($request->file('doc_atestedao_medico'))) {
            $image2 = $request->file('doc_atestedao_medico');
            $imageNameAT = time() . '.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameAT);
        } else {
            $imageNameAT = NULL;
        }
    
            try {
                
                $extensao = Extensao::where([
                    ['shcools_id', '=', $this->escolarLogada()],
                    ['tipo', '=', 'estudantes'],
                    ['status', '=', 'activo'],
                ])->first();
                
                $prefix = $extensao ? $extensao->extensao : "ANG";
                $sufx = $extensao ? $extensao->sufix : "24";
                
                // Inicia a transação
                DB::beginTransaction();

                // cadastrar os dados do estudante
                $create = Estudante::create([
                    "documento" => $code,
                    "nome" => $request->input('nome'),
                    "registro" => 'confirmado',
                    "sobre_nome" => $request->input('sobre_nome'),
                    "nascimento" => $request->input('nascimento'),
                    "nome_completo" => $request->input('nome') . " ". $request->input('sobre_nome'),
                    "genero" => $request->input('genero'),
                    "estado_civil" => $request->input('estado_civil'),
                    "nacionalidade" => $nacionalidade->name,
                    "pais_id" => $request->input('pais_id'),
                    "provincia_id" => $request->input('provincia_id'),
                    'municipio_id' => $request->input('municipio_id'),
                    'distrito_id' => $request->input('distrito_id'),
                    "dificiencia" => $request->dificiencia,
                    "bilheite" => $request->input('bilheite'),
                    "pai" => $request->input('pai'),
                    "mae" => $request->input('mae'),
                    "telefone_estudante" => $request->input('telefone'),
                    "telefone_pai" => $request->input('telefone_pai'),
                    "telefone_mae" => $request->input('telefone_mae'),
                    "endereco" => $request->input('endereco'),
                    "naturalidade" => $naturalidade->nome,
                    
                    'shcools_filhar_id' => $escolas->id,
    
                    // "whatsapp" => $request->whatsapp,
                    // "instagram" => $request->instagram,
                    // "facebook" => $request->facebook,
                    // "email" => $request->email,
    
                    "shcools_id" => $this->escolarLogada(),
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
                ]);
    
                // cadastar os dados da sua matricula
                $createM = Matricula::create([
                    "documento" => $create->documento,
                    "status_matricula" => 'confirmado',
                    "status_inscricao" => $inscricao,
                    "resultado_final" => 'estudando',
                    "ficha" => $code,
                    "media" => $request->input('media'),
                    "at_classes_id" => $request->input('at_classes_id'),
                    "classes_id" => $request->input('classes_id'),
                    "cursos_id" => $request->input('cursos_id'),
                    "turnos_id" => $request->input('turnos_id'),
                    "tipo" => $request->input('tipo_matricula'), // confirmação , Matricula, inscricao
                    "status" => 'Novo', // Novo ou repitente
                    "condicao" => 'Isento', // Novo ou repitente
    
                    // 'cursos_primeira_opcao_id'=> $request->cursos_primeira_opcao_id,
                    // 'cursos_segunda_opcao_id'=> $request->cursos_segunda_opcao_id,
                    
                    'shcools_filhar_id' => $escolas->id,
                    
                    'pais_id' => $escola->pais_id,
                    'provincia_id' => $escola->provincia_id,
                    'municipio_id' => $escola->municipio_id,
                    'distrito_id' => $escola->distrito_id,
                    'level' => '1',
    
                    "data_at" => $this->data_sistema(),
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "shcools_id" => $this->escolarLogada(),
                    "estudantes_id" => $create->id,
                    "funcionarios_id" => Auth::user()->id,
                    "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
                ]);
    
                // actualizar doados com conta corrente e numero do processo do estuadntes
                $create->conta_corrente = "31.1.2.1." . $create->id;
                $create->numero_processo= $prefix . " ". $create->create . "/" .  $sufx;
                $create->status = "activo";
                $create->registro = "confirmado";
                $create->update();
                
                $createM->numero_estudante = $prefix . " ". $createM->estudantes_id . "/" .  $sufx;
                $createM->status = "Novo";
                $createM->status_matricula = "confirmado";
                $createM->resultado_final = "estudando";
                $createM->update();
    
                Arquivo::create([
                    "codigo" => $code,
                    'model_id' => $create->id,
                    'model_type' => 'estudante',
                    'certificado' => $imageNameCT,
                    'bilheite' => $imageNameBI,
                    'atestado' => $imageNameAT,
                    'outros' => $imageNameOD,
                ]);
                
                // Comita a transação se tudo estiver correto
                DB::commit();
                // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
            } catch (\Illuminate\Database\QueryException $e) {
                // Se ocorrer algum erro, desfaz a transação
                DB::rollback();
                Alert::error('Error', $e->getMessage());
                // return Response()->json($e->getMessage());
                // Trate o erro ou exiba uma mensagem de falha
                // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
            }

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->back();
    }


    

}
