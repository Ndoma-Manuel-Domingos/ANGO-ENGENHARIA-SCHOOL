<?php

namespace App\Http\Controllers;

use App\Models\Motivo;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\calendarios\Calendario;
use App\Models\web\calendarios\Servico;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\turmas\Turma;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ServicoController extends Controller
{
    use TraitHelpers;

    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // --------------------------------------------------------------------------------------
    // ----------------------------------START CALENDARIOS ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    // view calendarios principal
    public function calendarios()
    {
        $user = auth()->user();
        
        if(!$user->can('read: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $servicos = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')->select('tb_servicos.id', 'tb_taxas.taxa', 'tb_servicos.servico', 'tb_taxas.taxa', 'tb_servicos.status', 'tb_servicos.contas', 'tb_servicos.conta')->where('shcools_id', '=', $this->escolarLogada())->get();
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Lista de todos Serviços",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "servicos" => $servicos
        ];
        
        return view('admin.calendarios.home', $headers);
    }

    public function calendariosCadatrar()
    {
        $user = auth()->user();
        
        if(!$user->can('create: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Cadastrar Serviços",
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "taxas" => DB::table('tb_taxas')->get(), //; Taxa::get(),
            "motivos" => Motivo::get(),
        ];
        
        return view('admin.calendarios.cadastrar-calendario', $headers);
    }

    // cadastrar calendario
    public function cadastrarCalendarios(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $validate = Validator::make($request->all(), [
            "valor_matricula" => 'required',
            "valor_confirmacao" => 'required',
            "valor_propina" => 'required',
            "dia_inicio" => 'required',
            "dia_final" => 'required',
            "status" => 'required',
            "cursos_id" => 'required',
            "classes_id" => 'required',
            "turnos_id" => 'required',
            "ano_lectivos_id" => 'required',
        ]);

        $verificarCalendario = Calendario::where([
            ['classes_id', '=', $request->input('classes_id')],
            ['cursos_id', '=', $request->input('cursos_id')],
            ['turnos_id', '=', $request->input('turnos_id')],
            ['ano_lectivos_id', '=', $request->input('ano_lectivos_id')],
        ])->first();

        if($verificarCalendario){
            return response()->json([
                'status' => 300,
                'message' => "Este Calendario já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
            Calendario::create([
                "valor_matricula" => $request->valor_matricula,
                "valor_confirmacao" => $request->valor_confirmacao,
                "valor_propina" => $request->valor_propina,
                "dia_inicio" => $request->dia_inicio,
                "dia_final" => $request->dia_final,
                "status" => $request->status,
                "classes_id" => $request->classes_id,
    
                "valor_multa" => $request->valor_multa,
                "valor_transporte" => $request->valor_transporte,
                "valor_uniforme_normal" => $request->valor_uniforme_normal,
                "valor_uniforme_ed_fisica" => $request->valor_uniforme_ed_fisica,
                "valor_uniforme_estagio" => $request->valor_uniforme_estagio,
    
                "turnos_id" => $request->turnos_id,
                "cursos_id" => $request->cursos_id,
                "ano_lectivos_id" => $request->ano_lectivos_id,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // cadastrar servivo
    public function cadastrarServicos(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            "servico" => 'required',
            "status" => 'required',
            "contas" => 'required',
        ]);

        $servico = Servico::where([
            ['servico', '=', $request->input('servico')],   
            ['shcools_id', '=', $this->escolarLogada()],
        ])->first();

        if($servico){
            return response()->json([
                'status' => 300,
                'message' => "Este Serviço já Esta Cadastrado!",
            ]);
        }

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        }else{
        
            if($request->input('tipo') == "P"){
                $verifica_conta_contabilidade = Servico::where('shcools_id', $this->escolarLogada())
                    ->where('conta', 'like', "61.1.%")
                    ->count();
                    
                $nova_conta = "61.1." . $verifica_conta_contabilidade + 1;
            }
            if($request->input('tipo') == "S"){
                $verifica_conta_contabilidade = Servico::where('shcools_id', $this->escolarLogada())
                    ->where('conta', 'like', "62.1.%")
                    ->count();
                
                $nova_conta = "62.1." . $verifica_conta_contabilidade + 1;
            }
            
            $create = Servico::create([
                "servico" => $request->input('servico'),
                "status" => $request->input('status'),
                "contas" => $request->input('contas'),
                "taxa_id" => $request->input('taxa_id'),
                "motivo_id" => $request->input('motivo_id'),
                "unidade" => $request->input('unidade'),
                "tipo" => $request->input('tipo'),
                "shcools_id" => $this->escolarLogada(),
                'ordem' => $verifica_conta_contabilidade + 1,
                'conta' => $nova_conta,
            ]);
         
            $create->save();

            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // editar turmas
    public function editarCalendarios($id)
    {
        $user = auth()->user();
        
        $servicoId = Servico::findOrFail($id);
        
        if(!$user->can('update: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        
        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Editar Serviços",
            
            "usuario" => User::findOrFail(Auth::user()->id),
            "servico" => $servicoId,
            "taxas" => DB::table('tb_taxas')->get(), //; Taxa::get(),
            "motivos" => Motivo::get(),
        ];
        
        return view('admin.calendarios.editar-calendario', $headers);
        

    }

    // actualizar turmas
    public function updateCalendarios(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "servico" => 'required',
            "status" => 'required',
            "contas" => 'required',
        ], [
            "servico.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "contas.required" => "Campo Obrigatório",
        ]);

        $update = Servico::findOrFail($id);
        
        if($update->conta == ""){
            
            if($request->input('tipo') == "P"){
                $verifica_conta_contabilidade = Servico::where('shcools_id', $this->escolarLogada())
                    ->where('conta', 'like', "61.1.%")
                    ->count();
                    
                $nova_conta = "61.1." . $verifica_conta_contabilidade + 1;
            }
            if($request->input('tipo') == "S"){
                $verifica_conta_contabilidade = Servico::where('shcools_id', $this->escolarLogada())
                    ->where('conta', 'like', "62.1.%")
                    ->count();
                
                $nova_conta = "62.1." . $verifica_conta_contabilidade + 1;
            }

            $update->ordem = $verifica_conta_contabilidade + 1;
            $update->conta = $nova_conta;
            
        }
                
        $update->servico = $request->input('servico');
        $update->status = $request->input('status');
        $update->contas = $request->input('contas');
        $update->taxa_id = $request->input('taxa_id');
        $update->motivo_id = $request->input('motivo_id');
        $update->unidade = $request->input('unidade');
        $update->tipo = $request->input('tipo');        
        $update->update();
        
        Alert::success('Bom Trabalho', 'Serviço editado com sucesso!');
        return redirect()->back();

    }

    // delete turmas
    public function deleteCalendarios($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: servicos')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $ano = Servico::findOrFail($id);
        $ano->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // activar e desactivar turma
    public function activarCalendarios($id)
    {

        $calendario = Servico::findOrFail($id);
        if ($calendario) {
            if ($calendario->status === 'activo') {
                $calendario->status = 'desactivo';
            }else{
                $calendario->status = 'activo';
            }
            if ($calendario->update()) {
                return response()->json([
                    "status" => 200,
                    "message" => "Dodos Activados com sucesso",
                    "usuario" => User::findOrFail(Auth::user()->id),
                ]);
            }
        }
    }

    // carregarValorMatriculaConfirmacao
    public function carregarValorMatriculaConfirmacao($curso, $classe, $turno, $situacao)
    {
        $turma = Turma::where([
            ['classes_id', '=', $classe],
            ['cursos_id', '=', $curso],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
        ])
        ->select('tb_turmas.id')
        ->first();

        $servicoTurma = ServicoTurma::where([
            ["tb_servicos_turma.turmas_id", "=", $turma->id],
            ["tb_servicos_turma.pagamento", "=", 'unico'],
            ["tb_servicos_turma.ano_lectivos_id", "=", $this->anolectivoActivo()],
            ["tb_servicos_turma.shcools_id", "=", $this->escolarLogada()],
        ])
        ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
        ->select('tb_servicos.servico', 'tb_servicos.id')
        ->get();

        if($servicoTurma){
           return response()->json([
                "status" => 200,
                "servicos" => $servicoTurma,
                "turma" => $turma,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]); 
        }
    }
    
    public function carregarServicoTurma(Request $request)
    {
        $turma = Turma::when($request->classes_id, function($query, $value){
            $query->where('classes_id', $value);
        })
        ->when($request->cursos_id, function($query, $value){
            $query->where('cursos_id', $value);
        })
        ->where('ano_lectivos_id', $request->ano_lectivos_id)
        ->select('tb_turmas.id')
        ->first();
        
        $servicoTurma = ServicoTurma::where('tb_servicos_turma.turmas_id', $turma->id)
            ->where('tb_servicos_turma.pagamento', 'unico')
            ->where('tb_servicos_turma.ano_lectivos_id', $request->ano_lectivos_id)
            ->where('tb_servicos_turma.shcools_id', $this->escolarLogada())
            ->join('tb_servicos', 'tb_servicos_turma.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_servicos.servico', 'tb_servicos.id')
        ->get();

        if($servicoTurma){
            return response()->json([
                "status" => 200,
                "servicos" => $servicoTurma,
                "turma" => $turma,
            ]); 
        }
    }
    // carregarValorMatriculaConfirmacao
    public function carregarValorServicoTurma($servico, $turma, $anoLectivo)
    {
        $escola = Shcool::findOrFail($this->escolarLogada());
    
        if ($escola->modulo == "Basico") {
            return response()->json([
                "status" => 200,
                "servico" => [
                    'preco' => 0
                ],
                "usuario" => User::findOrFail(Auth::user()->id),
            ]); 
        }

        $servico = Servico::find($servico);
        $turma = Turma::find($turma);

        $servicoTurma = ServicoTurma::where("turmas_id", $turma->id)
            ->where("servicos_id", $servico->id)
            ->where("ano_lectivos_id", $anoLectivo)
            ->select('preco')
        ->first();
        
        if($servicoTurma){
            return response()->json([
                "status" => 200,
                "servico" => $servicoTurma,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]); 
        }
    }

    // carregarValorMatriculaConfirmacao
    public function carregarValorServico($servico, $turma)
    {
        $escola = Shcool::findOrFail($this->escolarLogada());

        if ($escola->modulo == "Basico") {
            return response()->json([
                "status" => 200,
                "servico" => ['preco' => 0],
                "usuario" => User::findOrFail(Auth::user()->id),
            ]); 
        }

        $servico = Servico::findOrFail($servico);
        $turma = Turma::findOrFail($turma);

        $servicoTurma = ServicoTurma::where("turmas_id", $turma->id)
        ->where("servicos_id", $servico->id)
        ->where("ano_lectivos_id", $this->anolectivoActivo())
        ->select('preco')
        ->first();
        
        if($servicoTurma){
            return response()->json([
                "status" => 200,
                "servico" => $servicoTurma,
                "usuario" => User::findOrFail(Auth::user()->id),
            ]); 
        }
    }


}
