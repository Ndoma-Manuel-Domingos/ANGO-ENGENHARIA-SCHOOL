<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Cargo;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Escolaridade;
use App\Models\Especialidade;
use App\Models\FormacaoAcedemico;
use App\Models\Instituicao;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\Paise;
use App\Models\Professor;
use App\Models\ProfessorAcedemico;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\Universidade;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Mes;
use App\Models\web\disciplinas\Disciplina;
use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosAcademico;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\anolectivo\ControlePeriodico;
use App\Models\web\calendarios\Semana;
use App\Models\web\calendarios\Tempo;
use App\Models\web\turmas\Turma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FuncionarioController extends Controller
{
    use TraitHelpers;
    // --------------------------------------------------------------------------------------
    // ----------------------------------START FUNCIONARIOS ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------
    // view calendarios principal


    public function __construct()
    {
        $this->middleware('auth');
    }

    /** outros */


    public function outroFuncionarios(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $universidade_id = $request->universidade_id;
        $escolaridade_id = $request->escolaridade_id;
        $formacao_id = $request->formacao_id;
        $especialidade_id = $request->especialidade_id;
        $categora_id = $request->categora_id;


        $funcionarios = Funcionarios::with(['academico', 'contrato'])
            ->whereHas('academico', function ($query) use ($universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id) {
                $query->when($universidade_id, function ($query) use ($universidade_id) {
                    $query->where('universidade_id', $universidade_id);
                });

                $query->when($escolaridade_id, function ($query) use ($escolaridade_id) {
                    $query->where('escolaridade_id', $escolaridade_id);
                });

                $query->when($formacao_id, function ($query) use ($formacao_id) {
                    $query->where('formacao_academica_id', $formacao_id);
                });

                $query->when($especialidade_id, function ($query) use ($especialidade_id) {
                    $query->where('especialidade_id', $especialidade_id);
                });

                $query->when($categora_id, function ($query) use ($categora_id) {
                    $query->where('categoria_id', $categora_id);
                });
            })
            ->where('shcools_id', $this->escolarLogada())
            ->where('level', '4')
            ->orderBy('created_at', 'asc')->get();



        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();

        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::get();
        $distritos = Distrito::get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Funcionários",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "turmas" => Turma::where([
                ['ano_lectivos_id', $this->anolectivoActivo()],
                ['status', 'activo'],
            ])->get(),
            "disciplinas" => Disciplina::where([
                ['shcools_id', $this->escolarLogada()],
            ])->get(),
            "usuario" => User::findOrFail(Auth::user()->id),

            "departamentos" => $departamento,
            "cargos" => $cargos,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "instituicoes" => $instituicoes,
            "distritos" => $distritos,

            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),

            "requests" => $request->all(
                'municipio_id',
                'ano_lectivos_id',
                'shcools_id',
                'distrito_id',
                'status',
                'universidade_id',
                'escolaridade_id',
                'formacao_id',
                'especialidade_id',
                'categora_id',
                'departamento_id',
                'cargo_id'
            ),
        ];

        return view('admin.funcionarios.funcionarios', $headers);
    }

    public function outroFuncionariosCreate()
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $escolas = Shcool::all();

        $departamento = Departamento::where('level', '4')->where('shcools_id', $this->escolarLogada())->get();
        $cargos = Cargo::where('level', '4')->where('shcools_id', $this->escolarLogada())->get();

        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::get();
        $distritos = Distrito::get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Cadastrado dos Funcionários",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "escolas" => $escolas,
            "departamentos" => $departamento,
            "cargos" => $cargos,

            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "instituicoes" => $instituicoes,
            "distritos" => $distritos,

            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.create', $headers);
    }

    public function outroFuncionariosStore(Request $request)
    {

        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',

            'universidade_id'  => 'required',
            'especialidade_id'  => 'required',
            'categoria_id'  => 'required',
            'escolaridade_id'  => 'required',
            'formacao_academica_id'  => 'required',
        ]);

        try {
            DB::beginTransaction();

            $verificarBI = Funcionarios::where('bilheite', $request->bilheite)->first();
            if ($verificarBI) {
                return response()->json(['message' => 'Bilhete de identidade duplicado com sucesso!'], 404);
            }

            $codigo = time();

            $funcionario = Funcionarios::create([
                'nome' => $request->nome,
                'sobre_nome' => $request->sobre_nome,
                'pai' => $request->pai,
                'mae' => $request->mae,
                'codigo' => $codigo,
                'nascimento' => $request->nascimento,
                'genero' => $request->genero,
                'email' => $request->email,
                'level' => '4',
                'estado_civil' => $request->estado_civil,
                'pais_id' => $request->pais_id,
                'provincia_id' => $request->provincia_id,
                'municipio_id' => $request->municipio_id,
                'distrito_id' => $request->distrito_id,
                'bilheite' => $request->bilheite,
                'emissiao_bilheite' => $request->emissiao_bilheite,
                'status' => "activo",
                'telefone' => $request->telefone,
                'endereco' => $request->endereco,
                'whatsapp' => $request->whatsapp,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'outras_redes' => $request->outras_redes,
                'shcools_id' => $this->escolarLogada(),
            ]);

            FuncionariosAcademico::create([
                'universidade_id' => $request->universidade_id,
                'categoria_id' => $request->categoria_id,
                'escolaridade_id' => $request->escolaridade_id,
                'formacao_academica_id' => $request->formacao_academica_id,
                'especialidade_id' => $request->especialidade_id,
                'funcionarios_id' => $funcionario->id,
                'ano_trabalho' => $request->ano_trabalho,
                'codigo' => $codigo,
                "shcools_id" => $this->escolarLogada(),
            ]);

            if (!empty($request->file('doc_bilheite'))) {
                $image = $request->file('doc_bilheite');
                $imageNameBI = time() . '1.' . $image->extension();
                $image->move(public_path('assets/arquivos'), $imageNameBI);
            } else {
                $imageNameBI = Null;
            }

            if (!empty($request->file('doc_certificado'))) {
                $image2 = $request->file('doc_certificado');
                $imageNameCT = time() . '2.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameCT);
            } else {
                $imageNameCT = Null;
            }

            if (!empty($request->file('doc_outros'))) {
                $image2 = $request->file('doc_outros');
                $imageNameOD = time() . '3.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameOD);
            } else {
                $imageNameOD = Null;
            }

            if (!empty($request->file('doc_atestedao_medico'))) {
                $image2 = $request->file('doc_atestedao_medico');
                $imageNameAT = time() . '4.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameAT);
            } else {
                $imageNameAT = Null;
            }

            Arquivo::create([
                'model_id' => $funcionario->id,
                'model_type' => 'funcianario',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => '4',
                'codigo' => $codigo,
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Operação realizada com sucesso!'], 200);

        // Alert::success('Bom Trabalho', 'Dados Salvos com sucesso!');
        // return redirect()->back();
    }

    public function outroFuncionariosEdit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Funcionarios::findOrFail(Crypt::decrypt($id));
        $academico = FuncionariosAcademico::where('funcionarios_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '4')->where('model_type', 'funcianario')->where('model_id', $funcionario->id)->first();

        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $escolas = Shcool::all();

        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();

        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $instituicoes = Instituicao::get();
        $distritos = Distrito::get();




        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Cadastrado dos Funcionários",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "escolas" => $escolas,
            "departamentos" => $departamento,
            "cargos" => $cargos,
            "funcionario" => $funcionario,
            "academico" => $academico,
            "arquivo" => $arquivo,


            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "instituicoes" => $instituicoes,
            "distritos" => $distritos,


            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.edit', $headers);
    }

    public function outroFuncionariosUpdate(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',

            'universidade_id'  => 'required',
            'especialidade_id'  => 'required',
            'categoria_id'  => 'required',
            'escolaridade_id'  => 'required',
            'formacao_academica_id'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",
            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',

            'universidade_id.required'  => 'Campo Obrigatório',
            'especialidade_id.required'  => 'Campo Obrigatório',
            'categoria_id.required'  => 'Campo Obrigatório',
            'escolaridade_id.required'  => 'Campo Obrigatório',
            'formacao_academica_id.required'  => 'Campo Obrigatório',
        ]);


        $update = Funcionarios::findOrFail($id);

        $update->nome = $request->nome;
        $update->sobre_nome = $request->sobre_nome;
        $update->pai = $request->pai;
        $update->mae = $request->mae;
        $update->nascimento = $request->nascimento;
        $update->genero = $request->genero;
        $update->email = $request->email;
        $update->estado_civil = $request->estado_civil;
        $update->pais_id = $request->pais_id;
        $update->provincia_id = $request->provincia_id;
        $update->municipio_id = $request->municipio_id;
        $update->distrito_id = $request->distrito_id;
        $update->bilheite = $request->bilheite;
        $update->telefone = $request->telefone;
        $update->endereco = $request->endereco;
        $update->emissiao_bilheite = $request->emissiao_bilheite;
        $update->endereco = $request->endereco;
        $update->whatsapp = $request->whatsapp;
        $update->facebook = $request->facebook;
        $update->instagram = $request->instagram;
        $update->outras_redes = $request->outras_redes;
        $update->outras_redes = $request->outras_redes;
        $update->level = '4';

        $udpateAcademico = FuncionariosAcademico::findOrFail($request->academico_id);
        $udpateAcademico->universidade_id = $request->universidade_id;
        $udpateAcademico->categoria_id = $request->categoria_id;
        $udpateAcademico->escolaridade_id = $request->escolaridade_id;
        $udpateAcademico->formacao_academica_id = $request->formacao_academica_id;
        $udpateAcademico->especialidade_id = $request->especialidade_id;
        $udpateAcademico->ano_trabalho = $request->ano_trabalho;

        $updateArquivo = Arquivo::find($request->arquivo_id);

        if (!empty($request->file('doc_bilheite'))) {
            $image = $request->file('doc_bilheite');
            $imageNameBI = time() . '1.' . $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        } else {
            $imageNameBI = $request->doc_bilheite_guardado;
        }

        if (!empty($request->file('doc_certificado'))) {
            $image2 = $request->file('doc_certificado');
            $imageNameCT = time() . '2.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameCT);
        } else {
            $imageNameCT = $request->doc_certificado_guardado;
        }

        if (!empty($request->file('doc_outros'))) {
            $image2 = $request->file('doc_outros');
            $imageNameOD = time() . '3.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameOD);
        } else {
            $imageNameOD = $request->doc_outros_guardado;
        }

        if (!empty($request->file('doc_atestedao_medico'))) {
            $image2 = $request->file('doc_atestedao_medico');
            $imageNameAT = time() . '4.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameAT);
        } else {
            $imageNameAT = $request->doc_atestedao_medico_guardado;
        }
        if ($updateArquivo) {
            $updateArquivo->certificado = $imageNameCT;
            $updateArquivo->bilheite = $imageNameBI;
            $updateArquivo->atestado = $imageNameAT;
            $updateArquivo->outros = $imageNameOD;
            $updateArquivo->level = '4';
            $updateArquivo->update();
        } else {
            Arquivo::create([
                'model_id' => $update->id,
                'model_type' => 'funcianario',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => "4",
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);
        }

        $update->update();
        $udpateAcademico->update();

        Alert::success('Bom Trabalho', 'Dados Actualizado com sucesso!');
        return redirect()->back();
    }

    public function outroFuncionariosDestroy($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Funcionarios::findOrFail($id);
        $academico = FuncionariosAcademico::where('level', '4')->where('shcools_id', $this->escolarLogada())->where('funcionarios_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '4')->where('model_type', 'funcianario')->where('model_id', $funcionario->id)->first();

        $academico->delete();
        $arquivo->delete();
        $funcionario->delete();

        Alert::success('Bom Trabalho', 'Dados Eliminado com sucesso!');
        return redirect()->back();
    }

    public function outrocriarContrato($id = null)
    {
        $user = auth()->user();

        if (!$user->can('create: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Funcionarios::find(Crypt::decrypt($id));

        $funcionarios = Funcionarios::where('shcools_id', '=', $this->escolarLogada())->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Funcionários",
            "descricao" => "gestão de contratos ",
            "funcionario" => $funcionario,
            "funcionarios" => $funcionarios,
            "ano_lectivo" => AnoLectivo::find($this->anolectivoActivo()),
            "turmas" => Turma::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'activo'],
            ])->get(),
            "disciplinas" => Disciplina::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "cargos" => Cargo::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "departamentos" => Departamento::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.outro-criar-contrato', $headers);
    }

    public function outrocriarContratoStore(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('create: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "salario" => 'required',
            "data_inicio_contrato" => 'required',
            "data_final_contrato" => 'required',
            "hora_entrada_contrato" => 'required',
            "hora_saida_contrato" => 'required',
            "iban" => 'required',
            "status_contrato" => 'required',
            "status" => 'required',
        ], [
            "salario.required" => "******",
            "data_inicio_contrato.required" => "*****",
            "data_final_contrato.required" => "*****",
            "hora_entrada_contrato.required" => "*****",
            "hora_saida_contrato.required" => "*****",
            "iban.required" => "*****",
            "status_contrato.required" => "*****",
            "status.required" => "*****",
        ]);

        $cargo_geral = Cargo::findOrFail($request->cargo_id)->cargo;

        $funcionario = Funcionarios::findOrFail($request->input('funcionario_id'));

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        // Criar contrato
        FuncionariosControto::create([
            "funcionarios_id" => $funcionario->id,
            "documento" => $funcionario->codigo,
            "salario" => $request->input('salario'),
            "subcidio" => $request->input('subcidio'),
            "subcidio_alimentacao" => $request->input('subcidio_alimentacao'),
            "subcidio_transporte" => $request->input('subcidio_transporte'),

            "pais_id" => $escola->pais_id ?? 0,
            "provincia_id" => $escola->provincia_id ?? 0,
            "municipio_id" => $escola->municipio_id ?? 0,
            "distrito_id" => $escola->distrito_id ?? 0,

            "subcidio_ferias" => $request->input('subcidio_ferias'),
            "subcidio_natal" => $request->input('subcidio_natal'),
            "subcidio_abono_familiar" => $request->input('subcidio_abono_familia'),

            "data_inicio_contrato" => $request->input('data_inicio_contrato'),
            "falta_por_dia" => $request->input('falta_por_dia'),
            "data_final_contrato" => $request->input('data_final_contrato'),
            "hora_entrada_contrato" => $request->input('hora_entrada_contrato'),
            "hora_saida_contrato" => $request->input('hora_saida_contrato'),
            "tempo_contrato" => $request->input('tempo_contrato'),
            "conta_bancaria" => $request->input('conta_bancaria'),
            "status_contrato" => $request->input('status_contrato'),
            "status" => $request->input('status'),
            "iban" => $request->input('iban'),
            "numero_identifcador" => $funcionario->codigo,
            "level" => '4',

            "cargo_geral" => strtolower($cargo_geral),

            "departamento_id" => $request->input('departamento_id'),
            "cargo_id" => $request->input('cargo_id'),
            "clausula" => $request->input('clausula'),
            "nif" => $funcionario->bilheite,
            "data_at" => $this->data_sistema(),
            "ano_lectivos_id" => $this->anolectivoActivo(),
            "shcools_id" => $this->escolarLogada(),
        ]);

        $ano_lectivo = AnoLectivo::find($this->anolectivoActivo());

        $data_inicio = $ano_lectivo->inicio;
        $data_final = $ano_lectivo->final;

        $verificar = CartaoFuncionario::where([
            ['funcionarios_id', '=', $funcionario->id],
            ['codigo', '=', $funcionario->codigo],
            ['level', '=', '4'],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        if (!$verificar) {
            for ($i = 1; $i <= 12; $i++) {
                CartaoFuncionario::create([
                    "ordem" => $i,
                    "funcionarios_id" => $funcionario->id,
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "shcools_id" => $this->escolarLogada(),
                    "level" => '4',
                    "codigo" => $funcionario->codigo,
                    "data_at" => date("Y-m-d", strtotime($data_inicio . "+{$i}month")),
                    "data_exp" => date("Y-m-d", strtotime($data_final . "+{$i}month")),
                    "month_number" => date("m", strtotime($data_inicio . "+{$i}month")),
                    "month_name" => date("M", strtotime($data_inicio . "+{$i}month")),
                    "status" => 'Nao Pago',
                ]);
            }
        }

        Alert::success('Bom Trabalho', 'Dados Salvos com sucesso!');
        return redirect()->back();
    }

    public function outroMaisInformacoesFuncionario($id)
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Funcionarios::where('level', 4)->with('nacionalidade')
            ->with('provincia', 'distrito', 'municipio')
            ->with('academico.escolaridade')
            ->with('academico.especialidade')
            ->with('academico.categoria')
            ->with('academico.escolaridade')
            ->with('academico.universidade')
            ->findOrFail(Crypt::decrypt($id));

        $contrato = FuncionariosControto::where('level', '4')
            ->with('departamento', 'cargos')
            ->where('funcionarios_id', $professor->id)
            ->where('cargo_geral', '!=', 'professor')
            ->first();

        $arquivo = Arquivo::where('level', $professor->level)
            ->where('model_type', 'funcianario')
            ->where('model_id', $professor->id)
            ->first();



        $headers = [

            'professor' => $professor,
            'contrato' => $contrato,
            'documentos' => $arquivo,

            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),

        ];


        return view('admin.funcionarios.outro-mais-infomacoes', $headers);
    }

    public function funcionarios(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $universidade_id = $request->universidade_id;
        $escolaridade_id = $request->escolaridade_id;
        $formacao_id = $request->formacao_id;
        $especialidade_id = $request->especialidade_id;
        $categora_id = $request->categora_id;

        $funcionarios = FuncionariosControto::with(
            'funcionario.provincia',
            'funcionario.academico',
            'escola.provincia',
            'escola.municipio',
            'funcionario.academico.especialidade',
            'funcionario.academico.categoria',
            'funcionario.academico.escolaridade',
            'funcionario.academico.universidade'
        )
            ->whereHas('funcionario.academico', function ($query) use ($universidade_id, $escolaridade_id, $formacao_id, $especialidade_id, $categora_id) {
                $query->when($universidade_id, function ($query) use ($universidade_id) {
                    $query->where('universidade_id', $universidade_id);
                });

                $query->when($escolaridade_id, function ($query) use ($escolaridade_id) {
                    $query->where('escolaridade_id', $escolaridade_id);
                });

                $query->when($formacao_id, function ($query) use ($formacao_id) {
                    $query->where('formacao_academica_id', $formacao_id);
                });

                $query->when($especialidade_id, function ($query) use ($especialidade_id) {
                    $query->where('especialidade_id', $especialidade_id);
                });

                $query->when($categora_id, function ($query) use ($categora_id) {
                    $query->where('categoria_id', $categora_id);
                });
            })
            ->when($request->municipio_id, function ($query, $value) {
                $query->where('municipio_id', $value);
            })
            ->when($request->distrito_id, function ($query, $value) {
                $query->where('distrito_id', $value);
            })
            ->when($request->shcools_id, function ($query, $value) {
                $query->where('shcools_id', $value);
            })
            ->when($request->ano_lectivos_id, function ($query, $value) {
                $query->where('ano_lectivo_global_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->where('shcools_id', '=', $this->escolarLogada())
            ->where('level', '4')
            ->where('cargo_geral', 'professor')
            ->where('status', 'activo')
            ->orderBy('created_at', 'asc')
            ->get();



        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Funcionários",
            "descricao" => "gestão de discipinas",
            "funcionarios" => $funcionarios,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "turmas" => Turma::where([
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status', '=', 'activo'],
            ])->get(),
            "disciplinas" => Disciplina::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),
            "usuario" => User::findOrFail(Auth::user()->id),

            "requests" => $request->all(
                'municipio_id',
                'ano_lectivos_id',
                'shcools_id',
                'distrito_id',
                'status',
                'universidade_id',
                'escolaridade_id',
                'formacao_id',
                'especialidade_id',
                'categora_id'
            ),
        ];

        return view('admin.funcionarios.home', $headers);
    }

    public function funcionariosDocentes()
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $docentes = FuncionariosControto::where([
            ['shcools_id', '=', $this->escolarLogada()],
            ['cargo_geral', '=', 'professor'],
        ])
            ->with('funcionario')
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Docentes",
            "descricao" => env('APP_NAME'),
            "docentes" => $docentes,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.docentes', $headers);
    }

    // editar funcioanrio
    public function editarFuncionarios($id)
    {
        $user = auth()->user();

        if (!$user->can('update: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Funcionarios::findOrFail($id);
        $academico = FuncionariosAcademico::where([
            ['funcionarios_id', '=', $funcionario->id]
        ])->first();

        $contrato = FuncionariosControto::where([
            ['funcionarios_id', '=', $funcionario->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->first();

        if ($funcionario) {
            return response()->json([
                "status" => 200,
                "funcionario" => $funcionario,
                "academico" => $academico,
                "contrato" => $contrato,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Funcionários não Encontrado'
            ]);
        }
    }

    // actualizar turmas
    public function updateFuncionarios(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('update: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome" => 'required',
            "sobre_nome" => 'required',
            "nascimento" => 'required',
            "genero" => 'required',
            "estado_civil" => 'required',
            "bilheite" => 'required',
            "telefone" => 'required',
            "curso" => 'required',
            "area_formacao" => 'required',
            "nivel_academico" => 'required',
            "formacao_pedagogica" => 'required',
            "universidade" => 'required',
            "salario" => 'required',
            "subcidio" => 'required',
            "data_inicio_contrato" => 'required',
            "data_final_contrato" => 'required',
            "hora_entrada_contrato" => 'required',
            "hora_saida_contrato" => 'required',
            "cargo" => 'required',
            "conta_bancaria" => 'required',
            "iban" => 'required',
            "nif" => 'required',
            "status_contrato" => 'required',
            // "clausula" => 'required',
            "status" => 'required',
            "id_informacoes" => 'required',
            "id_academico" => 'required',
            "id_contrato" => 'required',
        ], [
            "nome.required" => "******",
            "sobre_nome.required" => "*****",
            "nascimento.required" => "*****",
            "genero.required" => "*****",
            "estado_civil.required" => "*****",
            "bilheite.required" => "*****",
            "telefone.required" => "*****",
            "curso.required" => "******",
            "area_formacao.required" => "*****",
            "nivel_academico.required" => "*****",
            "formacao_pedagogica.required" => "*****",
            "universidade.required" => "*****",
            "salario.required" => "******",
            "subcidio.required" => "*****",
            "data_inicio_contrato.required" => "*****",
            "data_final_contrato.required" => "*****",
            "hora_entrada_contrato.required" => "*****",
            "hora_saida_contrato.required" => "*****",
            "cargo.required" => "*****",
            "conta_bancaria.required" => "*****",
            "iban.required" => "*****",
            "nif.required" => "*****",
            "status_contrato.required" => "*****",
            // "clausula.required" => "*****",
            "status.required" => "*****",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $verificarInformacoes = Funcionarios::findOrFail($request->input('id_informacoes'));
            $verificarAcademico = FuncionariosAcademico::findOrFail($request->input('id_academico'));
            $verificarContrato = FuncionariosControto::findOrFail($request->input('id_contrato'));

            $verificarInformacoes->nome = $request->input('nome');
            $verificarInformacoes->sobre_nome = $request->input('sobre_nome');
            $verificarInformacoes->nascimento = $request->input('nascimento');
            $verificarInformacoes->genero = $request->input('genero');
            $verificarInformacoes->estado_civil = $request->input('estado_civil');
            $verificarInformacoes->nacionalidade = $request->input('nacionalidade');
            $verificarInformacoes->bilheite = $request->input('bilheite');
            $verificarInformacoes->telefone = $request->input('telefone');
            $verificarInformacoes->endereco = $request->input('endereco');
            $verificarInformacoes->update();

            $verificarAcademico->curso = $request->input('curso');
            $verificarAcademico->area_formacao = $request->input('area_formacao');
            $verificarAcademico->nivel_academico = $request->input('nivel_academico');
            $verificarAcademico->formacao_pedagogica = $request->input('formacao_pedagogica');
            $verificarAcademico->universidade = $request->input('universidade');
            $verificarAcademico->email = $request->input('email');
            $verificarAcademico->update();

            $verificarContrato->salario = $request->input('salario');
            $verificarContrato->subcidio_transporte = $request->input('subcidio_transporte');
            $verificarContrato->subcidio_alimentacao = $request->input('subcidio_alimentacao');

            $verificarContrato->subcidio_natal = $request->input('subcidio_natal');
            $verificarContrato->subcidio_ferias = $request->input('subcidio_ferias');
            $verificarContrato->subcidio_abono_familiar = $request->input('subcidio_abono_familia');

            $verificarContrato->falta_por_dia = $request->input('falta_por_dia');
            $verificarContrato->subcidio = $request->input('subcidio');
            $verificarContrato->data_inicio_contrato = $request->input('data_inicio_contrato');
            $verificarContrato->data_final_contrato = $request->input('data_final_contrato');
            $verificarContrato->hora_entrada_contrato = $request->input('hora_entrada_contrato');
            $verificarContrato->hora_saida_contrato = $request->input('hora_saida_contrato');
            $verificarContrato->cargo = $request->input('cargo');
            $verificarContrato->conta_bancaria = $request->input('conta_bancaria');
            $verificarContrato->status_contrato = $request->input('status_contrato');
            $verificarContrato->status = $request->input('status');
            $verificarContrato->iban = $request->input('iban');
            $verificarContrato->clausula = $request->input('clausula');
            $verificarContrato->nif = $request->input('nif');
            $verificarContrato->shcools_id = $this->escolarLogada();
            $verificarContrato->update();

            return response()->json([
                'status' => 200,
                'message' => 'Dados actualizados com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // delete turmas
    public function deleteFuncionarios($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Funcionarios::findOrFail($id);

        FuncionariosControto::where('funcionarios_id', $funcionario->id)->delete();
        User::where('funcionarios_id', $funcionario->id)->delete();

        $funcionario->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // activar e desactivar turma
    public function activarFuncionarios($id)
    {
        $user = auth()->user();

        if (!$user->can('read: professores') && !$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Funcionarios::findOrFail($id);
        if ($funcionario) {
            if ($funcionario->status === 'activo') {
                $funcionario->status = 'desactivo';
            } else {
                $funcionario->status = 'activo';
            }
            if ($funcionario->update()) {
                return response()->json([
                    "status" => 200,
                    "message" => "Dodos Activados com sucesso",
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    // Estudant funcioanrio
    public function pesquisarFuncionario(Request $request)
    {
        // CONTROLAR A SESSÃo INICIALIZADA OU NAO


        $matricula = FuncionariosControto::where([
            ['documento', '=', $request->input('texto_pesquisar')],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()]
        ])->first();

        $estudantes = Estudante::findOrFail($matricula->estudantes_id);

        return redirect()->route('resultado-pesquisa', $matricula->documento);
    }

    // =====================================================================
    // ==================================================================
    public function cadastrarFuncionariosNovo(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "nome" => 'required',
            "sobre_nome" => 'required',
            "nascimento" => 'required',
            "genero" => 'required',
            "estado_civil" => 'required',
            "bilheite" => 'required',
            "telefone" => 'required',
        ], [
            "nome.required" => "******",
            "sobre_nome.required" => "*****",
            "nascimento.required" => "*****",
            "genero.required" => "*****",
            "estado_civil.required" => "*****",
            "bilheite.required" => "*****",
            "telefone.required" => "*****",
        ]);

        $verificarFunconario = Funcionarios::where([
            ['bilheite', '=', $request->input('bilheite')],
            ['telefone', '=', $request->input('telefone')],
        ])->first();

        if ($verificarFunconario) {
            return response()->json([
                'status' => 300,
                'message' => "Este Funcionário já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {
            $create = new Funcionarios();
            $create->nome = $request->input('nome');
            $create->sobre_nome = $request->input('sobre_nome');
            $create->nascimento = $request->input('nascimento');
            $create->genero = $request->input('genero');
            $create->estado_civil = $request->input('estado_civil');
            $create->nacionalidade = $request->input('nacionalidade');
            $create->bilheite = $request->input('bilheite');
            $create->telefone = $request->input('telefone');
            $create->endereco = $request->input('endereco');
            $create->shcools_id = $this->escolarLogada();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "funcionarios" => $create,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    public function cadastrarFuncionariosAcademico(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "curso" => 'required',
            "area_formacao" => 'required',
            "nivel_academico" => 'required',
            "formacao_pedagogica" => 'required',
            "universidade" => 'required',
        ]);

        $escola = Shcool::findOrFail($this->escolarLogada());

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {


            try {
                DB::beginTransaction();


                $create = Funcionarios::create([
                    "nome" =>  $request->dadosFuncionarios['nome'],
                    "sobre_nome" =>  $request->dadosFuncionarios['sobre_nome'],
                    "nascimento" =>  $request->dadosFuncionarios['nascimento'],
                    "genero" =>  $request->dadosFuncionarios['genero'],
                    "estado_civil" =>  $request->dadosFuncionarios['estado_civil'],
                    "nacionalidade" =>  $request->dadosFuncionarios['nacionalidade'],
                    "bilheite" =>  $request->dadosFuncionarios['bilheite'],
                    "telefone" =>  $request->dadosFuncionarios['telefone'],
                    "endereco" =>  $request->dadosFuncionarios['endereco'],
                    "shcools_id" => $this->escolarLogada(),
                ]);

                FuncionariosAcademico::create([
                    "funcionarios_id" => $create->id,
                    "curso" => $request->curso,
                    "area_formacao" => $request->area_formacao,
                    "nivel_academico" => $request->nivel_academico,
                    "formacao_pedagogica" => $request->formacao_pedagogica,
                    "universidade" => $request->universidade,
                    "email" => $request->email,
                    "shcools_id" => $this->escolarLogada(),
                ]);

                $meses = Mes::all();

                if ($escola->modulo == "Basico") {

                    $ano = AnoLectivo::findOrFail($this->anolectivoActivo());

                    if ($meses) {
                        foreach ($meses as $mes) {
                            $verificar = CartaoFuncionario::where('funcionarios_id', $create->id)
                                ->where('mes_id', $mes->id)
                                ->where('level', 1)
                                ->where('ano_lectivos_id', $this->anolectivoActivo())
                                ->first();

                            if (!$verificar) {
                                CartaoFuncionario::create([
                                    "funcionarios_id" => $create->id,
                                    "mes_id" => $mes->id,
                                    "level" => 1,
                                    "ano_lectivos_id" => $this->anolectivoActivo(),
                                    "shcools_id" => $this->escolarLogada(),
                                    "status"  >= 'Nao pago',
                                ]);
                            }
                        }
                    }

                    FuncionariosControto::create([
                        "funcionarios_id" => $create->id,
                        "documento" => time(),
                        "salario" => 0,
                        "subcidio" => 0,
                        "subcidio_alimentacao" => 0,
                        "subcidio_transporte" => 0,

                        "subcidio_ferias" => 0,
                        "subcidio_natal" => 0,
                        "subcidio_abono_familiar" => 0,

                        "data_inicio_contrato" => $ano->inicio,
                        "data_final_contrato" => $ano->final,
                        "falta_por_dia" => 0,
                        "hora_entrada_contrato" => "12",
                        "hora_saida_contrato" => "12",
                        "conta_bancaria" => "00000000000000" . $create->id,
                        "status_contrato" => "Novo",
                        "status" => "activo",
                        "iban" => "AO06 0346786783" . $create->id,
                        "clausula" => "",
                        "nif" => "0000000LA34" . $create->id,
                        "data_at" => $this->data_sistema(),
                        "ano_lectivos_id" => $this->anolectivoActivo(),
                        "shcools_id" => $this->escolarLogada(),
                    ]);
                }


                // Realizar operações de banco de dados aqui

                // Se todas as operações foram bem-sucedidas, você pode fazer o commit
                DB::commit();
            } catch (\Exception $e) {
                // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
                DB::rollback();

                Alert::warning('Informação', $e->getMessage());
                return redirect()->back();
                // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
            'funcionario_id' => $create->id,
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    public function concluirCadastroFuncionarios(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "cargo" => 'required',
            "status_contrato" => 'required',
            "status" => 'required',
        ], [
            "cargo.required" => "*****",
            "status_contrato.required" => "*****",
            "status.required" => "*****",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {

            $create = new Funcionarios();
            $create->nome =  $request->input('dadosFuncionarios')['nome'];
            $create->sobre_nome =  $request->input('dadosFuncionarios')['sobre_nome'];
            $create->nascimento =  $request->input('dadosFuncionarios')['nascimento'];
            $create->genero =  $request->input('dadosFuncionarios')['genero'];
            $create->estado_civil =  $request->input('dadosFuncionarios')['estado_civil'];
            $create->nacionalidade =  $request->input('dadosFuncionarios')['nacionalidade'];
            $create->bilheite =  $request->input('dadosFuncionarios')['bilheite'];
            $create->telefone =  $request->input('dadosFuncionarios')['telefone'];
            $create->endereco =  $request->input('dadosFuncionarios')['endereco'];
            $create->shcools_id = $this->escolarLogada();

            if ($create->save()) {
                $idFuncionario = DB::table('tb_professores')->where([
                    ['shcools_id', '=', $this->escolarLogada()],
                ])->max('id');

                $meses = Mes::all();

                if ($meses) {
                    foreach ($meses as $mes) {
                        $verificar = CartaoFuncionario::where([
                            ['funcionarios_id', '=', $idFuncionario],
                            ['mes_id', '=', $mes->id],
                            ['level', '=', 1],
                            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                        ])->first();

                        if (!$verificar) {
                            $newCreate = new CartaoFuncionario();

                            $newCreate->funcionarios_id = $idFuncionario;
                            $newCreate->mes_id = $mes->id;
                            $newCreate->level = 1;
                            $newCreate->ano_lectivos_id = $this->anolectivoActivo();
                            $newCreate->shcools_id = $this->escolarLogada();
                            $newCreate->status  = 'Nao pago';

                            $newCreate->save();
                        }
                    }
                }

                $create2 = new FuncionariosAcademico();
                $create2->funcionarios_id = $idFuncionario;

                $create2->curso = $request->input('dadosAcademicos')['curso'];
                $create2->area_formacao = $request->input('dadosAcademicos')['area_formacao'];
                $create2->nivel_academico = $request->input('dadosAcademicos')['nivel_academico'];
                $create2->formacao_pedagogica = $request->input('dadosAcademicos')['formacao_pedagogica'];
                $create2->universidade = $request->input('dadosAcademicos')['universidade'];
                $create2->email = $request->input('dadosAcademicos')['email'];
                $create2->shcools_id = $this->escolarLogada();

                if ($create2->save()) {
                    $create3 = new FuncionariosControto();
                    $create3->funcionarios_id = $idFuncionario;
                    $create3->documento = time();
                    $create3->salario = $request->input('salario');
                    $create3->subcidio = $request->input('subcidio');
                    $create3->subcidio_alimentacao = $request->input('subcidio_alimentacao');
                    $create3->subcidio_transporte = $request->input('subcidio_transporte');

                    $create3->subcidio_ferias = $request->input('subcidio_ferias');
                    $create3->subcidio_natal = $request->input('subcidio_natal');
                    $create3->subcidio_abono_familiar = $request->input('subcidio_abono_familia');

                    $create3->data_inicio_contrato = $request->input('data_inicio_contrato');
                    $create3->falta_por_dia = $request->input('falta_por_dia');
                    $create3->data_final_contrato = $request->input('data_final_contrato');
                    $create3->hora_entrada_contrato = $request->input('hora_entrada_contrato');
                    $create3->hora_saida_contrato = $request->input('hora_saida_contrato');
                    $create3->cargo = $request->input('cargo');
                    $create3->conta_bancaria = $request->input('conta_bancaria');
                    $create3->status_contrato = $request->input('status_contrato');
                    $create3->status = $request->input('status');
                    $create3->iban = $request->input('iban');
                    $create3->clausula = $request->input('clausula');
                    $create3->nif = $request->input('nif');
                    $create3->data_at = $this->data_sistema();
                    $create3->ano_lectivos_id = $this->anolectivoActivo();
                    $create3->shcools_id = $this->escolarLogada();
                    $create3->save();
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    public function maisInformacoesFuncionario($id)
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
     
        $professor = Professor::where('level', 4)->with('nacionalidade')
            ->with('provincia', 'distrito', 'municipio')
            ->with('academico.escolaridade')
            ->with('academico.especialidade')
            ->with('academico.categoria')
            ->with('academico.escolaridade')
            ->with('academico.universidade')
            ->findOrFail(Crypt::decrypt($id));

        $contrato = FuncionariosControto::where('level', '4')
            ->with('departamento', 'cargos')
            ->where('funcionarios_id', $professor->id)
            ->where('cargo_geral', 'professor')
            ->first();

        $arquivo = Arquivo::where('level', $professor->level)
            ->where('model_type', 'professor')
            ->where('model_id', $professor->id)
            ->first();



        $turmas = FuncionariosTurma::with(["professor", "turma", "disciplina"])
            ->select('turmas_id', 'shcools_id', 'ano_lectivos_id', 'funcionarios_id')
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('funcionarios_id', $professor->id)
            ->groupBy('turmas_id', 'shcools_id', 'ano_lectivos_id', 'funcionarios_id')
            ->get();

        $tempos = Tempo::get();
        $semanas = Semana::where('status', 'activo')->get();

        $headers = [
            'professor' => $professor,
            'contrato' => $contrato,
            'documentos' => $arquivo,
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => $turmas,
            "tempos" => $tempos,
            "semanas" => $semanas,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),

        ];


        return view('admin.funcionarios.mais-infomacoes', $headers);
    }

    public function actualizarPrazoNotas($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $funcionario = Professor::findOrFail(Crypt::decrypt($id));

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        if ($escola->ensino->nome == "Ensino Superior") {
            $trimestres = ControlePeriodico::where('ensino_status', '2')->get();
        } else {
            $trimestres = ControlePeriodico::where('ensino_status', '1')->get();
        }


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "funcionario" => $funcionario,
            "trimestres" => $trimestres,
            "usuario" => User::findOrFail(Auth::user()->id),
            "turmas" => FuncionariosTurma::where([
                ['tb_turmas_funcionarios.funcionarios_id', '=', $funcionario->id],
                ['tb_turmas_funcionarios.ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])
                ->join('tb_disciplinas', 'tb_turmas_funcionarios.disciplinas_id', '=', 'tb_disciplinas.id')
                ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
                ->select('tb_disciplinas.disciplina',  'tb_turmas.id', 'tb_turmas.turma', 'tb_turmas_funcionarios.tempo_edicao', 'tb_turmas_funcionarios.updated_at')
                ->get(),
        ];

        return view('admin.funcionarios.tempo-edicao-notas', $headers);
    }

    public function actualizarPrazoNotasStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'professor_id' => 'required',
            'turma_id' => 'required',
            'tempo' => 'required',
            'trimestre_edicao' => 'required',
        ], [
            'professor_id.required' => 'Campo Obrigatário',
            'turma_id.required' => 'Campo Obrigatário',
            'tempo.required' => 'Campo Obrigatário',
            'trimestre_edicao.required' => 'Campo Obrigatário',
        ]);

        $funcionario = Professor::findOrFail($request->professor_id);


        $turmas = FuncionariosTurma::where([
            ['funcionarios_id', '=', $funcionario->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])->get();


        if ($request->turma_id == "todas") {
            foreach ($turmas as $turma) {
                $update = FuncionariosTurma::findOrFail($turma->id);
                $update->tempo_edicao = $request->tempo;
                $update->trimestre_edicao = $request->trimestre_edicao == 1 ? 'I' : ($request->trimestre_edicao == 2 ? 'II' : ($request->trimestre_edicao == 3 ? 'III' : 'Iº'));
                $update->update();
            }
        } else {
            $turma = FuncionariosTurma::where([
                ['funcionarios_id', '=', $funcionario->id],
                ['turmas_id', '=', $request->turma_id],
            ])->first();

            $update = FuncionariosTurma::findOrFail($turma->id);
            $update->tempo_edicao = $request->tempo;
            $update->trimestre_edicao = $request->trimestre_edicao == 1 ? 'I' : ($request->trimestre_edicao == 2 ? 'II' : ($request->trimestre_edicao == 3 ? 'III' : 'Iº'));
            $update->update();
        }

        Alert::success("Bom Trabalho", "Tempo Actualizado com sucesso");
        return redirect()->back();
    }

    // --------------------------------------------------------------------------------------
    // ----------------------------------END FUNCIONARIOS ----------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    public function professoresCreate()
    {
        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        // $departamento = Departamento::where('level', '2')->where('shcools_id', $direccao->id)->get();
        // $cargos = Cargo::where('level', '2')->where('shcools_id', $direccao->id)->get();

        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();

        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        //$instituicoes = Instituicao::get();
        $distritos = Distrito::get();



        $headers = [
            "escola" => $escola,

            "titulo" => "Cadastrar Professores",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "departamentos" => $departamento,
            "cargos" => $cargos,

            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            //"instituicoes" => $instituicoes,
            "distritos" => $distritos,

            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.professores.create', $headers);
    }

    public function professoresStore(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',

            'universidade_id'  => 'required',
            'especialidade_id'  => 'required',
            'categoria_id'  => 'required',
            'escolaridade_id'  => 'required',
            'formacao_academica_id'  => 'required',
            // 'doc_bilheite'  => 'required',
            // 'doc_certificado'  => 'required',
            // 'doc_atestedao_medico'  => 'required',
            // 'doc_outros'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",
            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',

            'universidade_id.required'  => 'Campo Obrigatório',
            'especialidade_id.required'  => 'Campo Obrigatório',
            'categoria_id.required'  => 'Campo Obrigatório',
            'escolaridade_id.required'  => 'Campo Obrigatório',
            'formacao_academica_id.required'  => 'Campo Obrigatório',
            // 'doc_bilheite.required'  => 'Campo Obrigatório',
            // 'doc_certificado.required'  => 'Campo Obrigatório',
            // 'doc_atestedao_medico.required'  => 'Campo Obrigatório',
            // 'doc_outros.required'  => 'Campo Obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificarBI = Professor::where('bilheite', $request->bilheite)->first();
            if ($verificarBI) {
                Alert::warning('Informação', 'Bilhete de identidade duplicado com sucesso!');
                return redirect()->back();
            }

            $codigo = time();

            $prefessor = Professor::create([
                'nome' => $request->nome,
                'sobre_nome' => $request->sobre_nome,
                'pai' => $request->pai,
                'mae' => $request->mae,
                'email' => $request->email,
                'codigo' => $codigo,
                'level' => '4',
                'nascimento' => $request->nascimento,
                'genero' => $request->genero,
                'estado_civil' => $request->estado_civil,
                'pais_id' => $request->pais_id,
                'provincia_id' => $request->provincia_id,
                'municipio_id' => $request->municipio_id,
                'bilheite' => $request->bilheite,
                'distrito_id' => $request->distrito_id,
                'emissiao_bilheite' => $request->emissiao_bilheite,
                'status' => "desactivo",
                'telefone' => $request->telefone,
                'endereco' => $request->endereco,
                'whatsapp' => $request->whatsapp,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'outras_redes' => $request->outras_redes,
                "ano_lectivo_global_id" => $this->anolectivoActivo(),
            ]);

            ProfessorAcedemico::create([
                'universidade_id' => $request->universidade_id,
                'categoria_id' => $request->categoria_id,
                'escolaridade_id' => $request->escolaridade_id,
                'formacao_academica_id' => $request->formacao_academica_id,
                'especialidade_id' => $request->especialidade_id,
                'codigo' => $codigo,
                'professor_id' => $prefessor->id,
                "ano_lectivo_global_id" => $this->anolectivoActivo(),
            ]);

            $full = $request->nome . " " . $request->sobre_nome;
            $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);

            $nome = head($usernames) . '.' . last($usernames);

            $user = User::create([
                'nome' => $request->nome . " " . $request->sobre_nome,
                'telefone' => $request->telefone,
                'usuario' => $nome,
                'password' => Hash::make('123456'),
                'acesso' => 'professor',
                'level' => 50,
                'level2' => '4',
                'shcools_id' => $this->escolarLogada(),
                'numero_avaliacoes' => 3,
                'status' => 'Bloqueado',
                'login' => 'N',
                'email' => $request->email ?? "{$request->bilheite}@gmail.com",
                'funcionarios_id' => $prefessor->id,
            ]);

            $role = Role::where('name', 'professor')->first();
            $user->assignRole($role);

            $text = "O Professor {$request->nome} {$request->sobre_nome} enviou uma candidatura para o ministério da educação";
            $text2 = "O Sr(a) acabou de enviar uma candidatura para o ministerio da educação ";

            Notificacao::create([
                'user_id' => $user->id,
                'destino' => NULL,
                'type_destino' => 'escola',
                'type_enviado' => 'provincial',
                'notificacao' => $text,
                'notificacao_user' => $text2,
                'status' => '0',
                'model_id' => $user->id,
                'model_type' => "candidatura",
                'shcools_id' => NULL
            ]);

            if (!empty($request->file('doc_bilheite'))) {
                $image = $request->file('doc_bilheite');
                $imageNameBI = time() . '1.' . $image->extension();
                $image->move(public_path('assets/arquivos'), $imageNameBI);
            } else {
                $imageNameBI = Null;
            }

            if (!empty($request->file('doc_certificado'))) {
                $image2 = $request->file('doc_certificado');
                $imageNameCT = time() . '2.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameCT);
            } else {
                $imageNameCT = Null;
            }

            if (!empty($request->file('doc_outros'))) {
                $image2 = $request->file('doc_outros');
                $imageNameOD = time() . '3.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameOD);
            } else {
                $imageNameOD = Null;
            }

            if (!empty($request->file('doc_atestedao_medico'))) {
                $image2 = $request->file('doc_atestedao_medico');
                $imageNameAT = time() . '4.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameAT);
            } else {
                $imageNameAT = Null;
            }

            Arquivo::create([
                'model_id' => $prefessor->id,
                'model_type' => 'professor',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => '4',
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
                'codigo' => $codigo,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        // return redirect()->route('web.mais-informacao-funcionarios', $prefessor->id);

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->back();
    }


    public function professoresEdit($id)
    {

        $user = auth()->user();

        if (!$user->can('read: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Professor::with([
            'academico.especialidade',
            'academico.categoria',
            'academico.escolaridade',
            'academico.universidade',
        ])
            ->findOrFail(Crypt::decrypt($id));

        $academico = ProfessorAcedemico::where('professor_id', $funcionario->id)->first();
        $arquivo = Arquivo::where('level', '4')->where('model_id', $funcionario->id)->first();

        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        $municipios = Municipio::get();


        $departamento = Departamento::where('level', '3')->get();
        $cargos = Cargo::where('level', '3')->get();

        $especialidades = Especialidade::get();
        $categorias = Categoria::get();
        $universidades = Universidade::get();
        $distritos = Distrito::get();


        $headers = [
            "escola" => $escola,
            "titulo" => "Editar Professores",
            "descricao" => env('APP_NAME'),
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "departamentos" => $departamento,
            "cargos" => $cargos,
            "funcionario" => $funcionario,
            "academico" => $academico,
            "arquivo" => $arquivo,
            "especialidades" => $especialidades,
            "categorias" => $categorias,
            "universidades" => $universidades,
            "distritos" => $distritos,
            "escolaridade" => Escolaridade::get(),
            "formacao_academicos" => FormacaoAcedemico::get(),
            "usuario" => User::findOrFail(Auth::user()->id),

        ];

        return view('admin.professores.edit', $headers);
    }

    public function professoresUpdate(Request $request, $id)
    {

        $user = auth()->user();

        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'nascimento'  => 'required',
            'genero'  => 'required',
            'pais_id'  => 'required',
            'provincia_id'  => 'required',
            'municipio_id'  => 'required',
            'bilheite'  => 'required',

            'universidade_id'  => 'required',
            'especialidade_id'  => 'required',
            'categoria_id'  => 'required',
            'escolaridade_id'  => 'required',
            'formacao_academica_id'  => 'required',
        ], [
            'nome.required' => "Compo Obrigatório",
            'sobre_nome.required' => "Compo Obrigatório",
            'nascimento.required'  => 'Compo Obrigatório',
            'genero.required'  => 'Compo Obrigatório',
            'pais_id.required'  => 'Compo Obrigatório',
            'provincia_id.required'  => 'Compo Obrigatório',
            'municipio_id.required'  => 'Compo Obrigatório',
            'bilheite.required'  => 'Compo Obrigatório',

            'universidade_id.required'  => 'Campo Obrigatório',
            'especialidade_id.required'  => 'Campo Obrigatório',
            'categoria_id.required'  => 'Campo Obrigatório',
            'escolaridade_id.required'  => 'Campo Obrigatório',
            'formacao_academica_id.required'  => 'Campo Obrigatório',
        ]);

        $update = Professor::findOrFail($id);

        $update->nome = $request->nome;
        $update->sobre_nome = $request->sobre_nome;
        $update->pai = $request->pai;
        $update->mae = $request->mae;
        $update->nascimento = $request->nascimento;
        $update->genero = $request->genero;
        $update->email = $request->email;
        $update->estado_civil = $request->estado_civil;
        $update->pais_id = $request->pais_id;
        $update->provincia_id = $request->provincia_id;
        $update->municipio_id = $request->municipio_id;
        $update->distrito_id = $request->distrito_id;
        $update->bilheite = $request->bilheite;
        $update->telefone = $request->telefone;
        $update->endereco = $request->endereco;
        $update->emissiao_bilheite = $request->emissiao_bilheite;
        $update->endereco = $request->endereco;
        $update->whatsapp = $request->whatsapp;
        $update->facebook = $request->facebook;
        $update->instagram = $request->instagram;
        $update->outras_redes = $request->outras_redes;
        $update->outras_redes = $request->outras_redes;
        $update->level = 4;

        $udpateAcademico = ProfessorAcedemico::findOrFail($request->academico_id);
        $udpateAcademico->universidade_id = $request->universidade_id;
        $udpateAcademico->categoria_id = $request->categoria_id;
        $udpateAcademico->escolaridade_id = $request->escolaridade_id;
        $udpateAcademico->formacao_academica_id = $request->formacao_academica_id;
        $udpateAcademico->especialidade_id = $request->especialidade_id;
        $udpateAcademico->ano_trabalho = $request->ano_trabalho;


        $updateArquivo = Arquivo::where('level', '4')->find($request->arquivo_id);

        if (!empty($request->file('doc_bilheite'))) {
            $image = $request->file('doc_bilheite');
            $imageNameBI = time() . '1.' . $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        } else {
            $imageNameBI = $request->doc_bilheite_guardado;
        }

        if (!empty($request->file('doc_certificado'))) {
            $image2 = $request->file('doc_certificado');
            $imageNameCT = time() . '2.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameCT);
        } else {
            $imageNameCT = $request->doc_certificado_guardado;
        }

        if (!empty($request->file('doc_outros'))) {
            $image2 = $request->file('doc_outros');
            $imageNameOD = time() . '3.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameOD);
        } else {
            $imageNameOD = $request->doc_outros_guardado;
        }

        if (!empty($request->file('doc_atestedao_medico'))) {
            $image2 = $request->file('doc_atestedao_medico');
            $imageNameAT = time() . '4.' . $image2->extension();
            $image2->move(public_path('assets/arquivos'), $imageNameAT);
        } else {
            $imageNameAT = $request->doc_atestedao_medico_guardado;
        }
        if ($updateArquivo) {
            $updateArquivo->certificado = $imageNameCT;
            $updateArquivo->bilheite = $imageNameBI;
            $updateArquivo->atestado = $imageNameAT;
            $updateArquivo->outros = $imageNameOD;
            $updateArquivo->level = 4;
            $updateArquivo->update();
        } else {
            Arquivo::create([
                'model_id' => $update->id,
                'model_type' => 'professor',
                'certificado' => $imageNameCT,
                'bilheite' => $imageNameBI,
                'level' => 4,
                'atestado' => $imageNameAT,
                'outros' => $imageNameOD,
            ]);
        }

        $update->update();
        $udpateAcademico->update();

        Alert::success('Bom Trabalho', 'Dados Actualizado com sucesso!');
        return redirect()->back();
    }

    // activar e desactivar turma
    public function activarProfessores($id)
    {
        $user = auth()->user();

        if (!$user->can('read: professores') && !$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $professor = Professor::findOrFail($id);
        if ($professor) {
            if ($professor->status === 'activo') {
                $professor->status = 'desactivo';
            } else {
                $professor->status = 'activo';
            }
            if ($professor->update()) {
                return response()->json([
                    "status" => 200,
                    "message" => "Dodos Activados com sucesso",
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    // delete turmas
    public function excluirProfessores($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: professores')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Professor::findOrFail($id);

        FuncionariosControto::where('cargo_geral', 'professor')->where('funcionarios_id', $funcionario->id)->delete();
        User::where('funcionarios_id', $funcionario->id)->delete();

        $funcionario->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
}
