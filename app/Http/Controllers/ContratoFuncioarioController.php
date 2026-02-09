<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Professor;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\Funcionarios;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ContratoFuncioarioController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function contratos()
    {
        $user = auth()->user();

        if (!$user->can('read: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $contratos = FuncionariosControto::where([
            ['tb_contratos.shcools_id', '=', $this->escolarLogada()],
        ])
            ->join('tb_professores', 'tb_contratos.funcionarios_id', '=', 'tb_professores.id')
            ->orderBy('tb_contratos.created_at', 'asc')
            ->select('tb_contratos.id', 'tb_professores.nome',  'tb_professores.id AS FUNID', 'tb_professores.sobre_nome', 'tb_contratos.data_inicio_contrato', 'tb_contratos.data_final_contrato', 'tb_contratos.status')
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos contratos",
            "descricao" => "gestão de contratos",
            "contratos" => $contratos,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.contratos', $headers);
    }

    public function contratosActivar($id)
    {
        $user = auth()->user();

        if (!$user->can('read: contrato')  && !$user->can('update: estado')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $funcionario = FuncionariosControto::findOrFail($id);
        if ($funcionario) {
            if ($funcionario->status === 'Activo') {
                $funcionario->status = 'Desactivo';
            } else {
                $funcionario->status = 'Activo';
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

    public function contratosExcluir($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = FuncionariosControto::findOrFail($id);
        $funcionario->forceDelete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }

    // activar e desactivar turma
    public function criarContrato($id = null)
    {

        $user = auth()->user();

        if (!$user->can('create: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $funcionario = Professor::find(Crypt::decrypt($id));
        
        $funcionarios = Professor::where('status', '=', 'desactivo')->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Funcionários",
            "descricao" => "gestão de contratos ",
            "funcionario" => $funcionario,
            "funcionarios" => $funcionarios,
            "ano_lectivo" => AnoLectivo::find($this->anolectivoActivo()),

            "cargos" => Cargo::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "departamentos" => Departamento::where([
                ['shcools_id', '=', $this->escolarLogada()],
            ])->get(),

            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.criar-contrato', $headers);
    }

    public function criarContratoStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $cargo_geral = Cargo::findOrFail($request->cargo_id)->cargo;

            if ($cargo_geral == "docentes") {
                $cargo_geral = "professor";
            }

            if ($cargo_geral == "Docentes") {
                $cargo_geral = "professor";
            }

            if ($cargo_geral == "Professores") {
                $cargo_geral = "professor";
            }

            if ($cargo_geral == "professores") {
                $cargo_geral = "professor";
            }

            if ($cargo_geral == "Docente") {
                $cargo_geral = "professor";
            }

            if ($cargo_geral == "docente") {
                $cargo_geral = "professor";
            }

            if ($cargo_geral = "professor") {
                $funcionario = Professor::findOrFail($request->funcionario_id);
            } else {
                $funcionario = Funcionarios::findOrFail($request->funcionario_id);
            }

            $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

            $verificar = FuncionariosControto::where("funcionarios_id", $funcionario->id)
                ->where("shcools_id", $this->escolarLogada())
                ->where("level", '4')
                ->first();

            if(!$verificar) {
                // Criar contrato
                FuncionariosControto::create([
                    "funcionarios_id" => $funcionario->id,
                    "documento" => $funcionario->codigo,
                    "salario" => $request->salario,
                    "subcidio" => $request->subcidio,
                    "subcidio_alimentacao" => $request->subcidio_alimentacao,
                    "subcidio_transporte" => $request->subcidio_transporte,

                    "subcidio_ferias" => $request->subcidio_ferias,
                    "pais_id" => $escola->pais_id ?? 6,
                    "provincia_id" => $escola->provincia_id ?? 0,
                    "municipio_id" => $escola->municipio_id ?? 0,
                    "distrito_id" => $escola->distrito_id ?? 0,
                    "subcidio_natal" => $request->subcidio_natal,
                    "subcidio_abono_familiar" => $request->subcidio_abono_familia,
    
                    "data_inicio_contrato" => $request->data_inicio_contrato,
                    "tempos_semanais" => $request->tempos_semanais,
                    "falta_por_dia" => $request->falta_por_dia,
                    "data_final_contrato" => $request->data_final_contrato,
                    "hora_entrada_contrato" => $request->hora_entrada_contrato,
                    "hora_saida_contrato" => $request->hora_saida_contrato,
                    "cargo" => $request->cargo,
                    "conta_bancaria" => $request->conta_bancaria,
                    "status_contrato" => $request->status_contrato,
                    "tempo_contrato" => $request->tempo_contrato,
                    "status" => $request->status,
                    "iban" => $request->iban,
                    "numero_identifcador" => $funcionario->codigo,
    
                    "cargo_geral" => strtolower($cargo_geral),
                    "level" => '4',
    
                    "departamento_id" => $request->departamento_id,
                    "cargo_id" => $request->cargo_id,
                    "clausula" => $request->clausula,
                    "nif" => $request->nif,
                    "data_at" => $this->data_sistema(),
                    "ano_lectivos_id" => $this->anolectivoActivo(),
                    "shcools_id" => $this->escolarLogada(),
                ]);

                $funcionario->status = "activo";
                $funcionario->update();

                $ano_lectivo = AnoLectivo::find($this->anolectivoActivo());
    
                $data_inicio = $ano_lectivo->inicio;
                $data_final = $ano_lectivo->final;
    
                $verificar = CartaoFuncionario::where('funcionarios_id', $funcionario->id)
                    ->where('codigo', $funcionario->codigo)
                    ->where('level', '4')
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->first();
    
                if (!$verificar) {
                    for ($i = 1; $i <= 12; $i++) {
                        CartaoFuncionario::create([
                            "mes_id" => $i,
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
            }else {
                Alert::warning('Informação', 'Já existe um contrato para este funcionário na escola!');
                return redirect()->back();
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

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->route('web.mais-informacao-funcionarios', Crypt::encrypt($request->funcionario_id));
    }

    // activar e desactivar turma
    public function editarContrato($id)
    {
        $user = auth()->user();

        if (!$user->can('update: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $contrato = FuncionariosControto::find(Crypt::decrypt($id));

        if ($contrato->cargo_geral == "professor") {
            $funcionario = Professor::find($contrato->funcionarios_id);
        } else {
            $funcionario = Funcionarios::find($contrato->funcionarios_id);
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Funcionários",
            "descricao" => "gestão de discipinas",
            "funcionario" => $funcionario,
            "contrato" => $contrato,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.editar-contrato', $headers);
    }

    public function editarContratoStore(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());

        $contrato = FuncionariosControto::findOrFail(Crypt::decrypt($id));

        $request->validate([
            "salario" => 'required',
            "data_inicio_contrato" => 'required',
            "data_final_contrato" => 'required',
            "hora_entrada_contrato" => 'required',
            "hora_saida_contrato" => 'required',
            "status_contrato" => 'required',
            "status" => 'required',
        ], [
            "salario.required" => "Campo Obrigatório",
            "data_inicio_contrato.required" => "Campo Obrigatório",
            "data_final_contrato.required" => "Campo Obrigatório",
            "hora_entrada_contrato.required" => "Campo Obrigatório",
            "hora_saida_contrato.required" => "Campo Obrigatório",
            "status_contrato.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);

        try {
            DB::beginTransaction();

            $contrato->salario = $request->salario;
            $contrato->subcidio = $request->subcidio;
            $contrato->subcidio_alimentacao = $request->subcidio_alimentacao;
            $contrato->subcidio_transporte = $request->subcidio_transporte;
            $contrato->pais_id = $escola->pais_id ?? 0;
            $contrato->provincia_id = $escola->provincia_id ?? 0;
            $contrato->municipio_id = $escola->municipio_id ?? 0;
            $contrato->distrito_id = $escola->distrito_id ?? 0;

            $contrato->subcidio_ferias = $request->subcidio_ferias;
            $contrato->subcidio_natal = $request->subcidio_natal;
            $contrato->subcidio_abono_familiar = $request->subcidio_abono_familia;

            $contrato->departamento_id = $request->departamento_id;
            $contrato->cargo_id = $request->cargo_id;

            $contrato->data_inicio_contrato = $request->data_inicio_contrato;
            $contrato->tempos_semanais = $request->tempos_semanais;
            $contrato->falta_por_dia = $request->falta_por_dia;
            $contrato->data_final_contrato = $request->data_final_contrato;
            $contrato->hora_entrada_contrato = $request->hora_entrada_contrato;
            $contrato->hora_saida_contrato = $request->hora_saida_contrato;
            $contrato->cargo = $request->cargo;
            $contrato->conta_bancaria = $request->conta_bancaria;
            $contrato->status_contrato = $request->status_contrato;
            $contrato->status = $request->status;
            $contrato->tempo_contrato = $request->tempo_contrato;
            $contrato->iban = $request->iban;
            $contrato->clausula = $request->clausula;
            $contrato->nif = $request->nif;

            $contrato->data_at = $this->data_sistema();
            $contrato->ano_lectivos_id = $this->anolectivoActivo();
            $contrato->shcools_id = $this->escolarLogada();
            $contrato->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        Alert::success("Bom Trabalho", "contrato actualizado com sucesso");
        return redirect()->route('web.funcionarios-contrato');
    }

    // activar e desactivar turma
    public function visualizarContrato($id)
    {
        $user = auth()->user();

        if (!$user->can('read: contrato')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $contrato = FuncionariosControto::find(Crypt::decrypt($id));

        if ($contrato->cargo_geral == "professor") {
            $funcionario = Professor::find($contrato->funcionarios_id);
        } else {
            $funcionario = Funcionarios::find($contrato->funcionarios_id);
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Listagem dos Funcionários",
            "descricao" => env('APP_NAME'),
            "funcionario" => $funcionario,
            "contrato" => $contrato,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.show-contrato', $headers);
    }
}
