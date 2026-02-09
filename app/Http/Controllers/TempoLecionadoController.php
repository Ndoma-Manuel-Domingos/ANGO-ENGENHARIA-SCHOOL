<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\funcionarios\TempoLecionado;
use App\Models\web\turmas\Horario;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TempoLecionadoController extends Controller
{
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // if (!$user->can('read: contrato')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $efetividades = TempoLecionado::with(['funcionario', 'ano_lectivo', 'escola'])
            ->where('shcools_id', $this->escolarLogada())
            ->get();
            
        // Definir locale para português
        Carbon::setLocale('pt');

        $inicio = Carbon::parse($request->data_inicio ?? date("Y-m-d"));
        $fim = Carbon::parse($request->data_final ?? date("Y-m-d"));
        $ano_lectivo = AnoLectivo::find($request->ano_lectivos_id ?? $this->anolectivoActivo());

        $professores = FuncionariosControto::with(['funcionario'])
            ->where('tb_contratos.shcools_id', $this->escolarLogada())
            ->get();
            
        $dados = [];

        foreach ($professores as $prof) {
        
            $temposDados = TempoLecionado::with(['funcionario', 'ano_lectivo', 'escola'])
                ->where('professor_id', $prof->funcionarios_id)
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->when($request->mes, function($query, $value) {
                    $query->where('mes', $value);
                })
                ->when($request->ano, function($query, $value) {
                    $query->where('ano', $value);
                })
                ->whereBetween('data', [$inicio, $fim])
                ->where('shcools_id', $this->escolarLogada())
            ->sum('tempos_dados');
            
            
            // total tempos previstos (plano semanal) * qtd de semanas no intervalo
            $totalPrevistos = 0;
            $dias = CarbonPeriod::create($inicio, $fim);
     
            foreach ($dias as $dia) {
                $diaSemana = $dia->dayOfWeekIso; // 1=Segunda, 2=Terça ... 7=Domingo
                // pega quantos tempos professor tinha nesse dia da semana
                $previstos = Horario::where('professor_id', $prof->funcionarios_id)
                    ->where('semanas_id', $diaSemana)
                    ->where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->count();
                
                $totalPrevistos += $previstos;
            }
               
            $naoDados = $totalPrevistos - $temposDados;

            if ($temposDados > 0 || $naoDados > 0) {
                $dados[] = [
                    'id' => $prof->funcionarios_id,
                    'nome' => $prof->funcionario->nome . " " . $prof->funcionario->sobre_nome,
                    'tempos_previstos' => $totalPrevistos,
                    'tempos_dados' => $temposDados,
                    'tempos_nao_dados' => $naoDados <= 0 ? 0 : $naoDados,
                    'mes' => $inicio->translatedFormat('F'),
                    'ano' => $inicio->year,
                ];
            }
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Tempos Lecionados",
            "descricao" => "",
            "professores" => $professores,
            "efetividades" => $efetividades,
            "dados" => $dados,
            "requests" => $request->all('data_inicio', 'data_final', 'mes', 'ano'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.funcionarios.tempos.index', $headers);
    }

    public function relatorio(Request $request)
    {
        $user = auth()->user();

        // Definir locale para português
        Carbon::setLocale('pt');

        $inicio = Carbon::parse($request->data_inicio ?? date("Y-m-d"));
        $fim = Carbon::parse($request->data_final ?? date("Y-m-d"));
        $ano_lectivo = AnoLectivo::find($request->ano_lectivos_id ?? $this->anolectivoActivo());

        $professores = FuncionariosControto::with(['funcionario'])
            ->where('tb_contratos.shcools_id', $this->escolarLogada())
            ->get();
            
        $dados = [];

        foreach ($professores as $prof) {
        
            $temposDados = TempoLecionado::with(['funcionario', 'ano_lectivo', 'escola'])
                ->where('professor_id', $prof->funcionarios_id)
                ->where('ano_lectivos_id', $ano_lectivo->id)
                ->when($request->mes, function($query, $value) {
                    $query->where('mes', $value);
                })
                ->when($request->ano, function($query, $value) {
                    $query->where('ano', $value);
                })
                ->whereBetween('data', [$inicio, $fim])
                ->where('shcools_id', $this->escolarLogada())
            ->sum('tempos_dados');
            
            // total tempos previstos (plano semanal) * qtd de semanas no intervalo
            $totalPrevistos = 0;
            $dias = CarbonPeriod::create($inicio, $fim);
     
            foreach ($dias as $dia) {
                $diaSemana = $dia->dayOfWeekIso; // 1=Segunda, 2=Terça ... 7=Domingo
               
                // pega quantos tempos professor tinha nesse dia da semana
                $previstos = Horario::where('professor_id', $prof->funcionarios_id)
                    ->where('semanas_id', $diaSemana)
                    ->where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->count();
                
                $totalPrevistos += $previstos;
            }
               
            $naoDados = $totalPrevistos - $temposDados;

            if ($temposDados > 0 || $naoDados > 0) {
                $dados[] = [
                    'nome' => $prof->funcionario->nome,
                    'tempos_previstos' => $totalPrevistos,
                    'tempos_dados' => $temposDados,
                    'tempos_nao_dados' => $naoDados <= 0 ? 0 : $naoDados,
                    'mes' => $inicio->translatedFormat('F'),
                    'ano' => $inicio->year,
                ];
            }
        }
        
                // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Relatórios dos Tempos Lecionados",
            "descricao" => "",
            "professores" => $professores,
            "dados" => $dados,
            "requests" => $request->all('data_inicio', 'data_final', 'mes', 'ano'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];
        
        $pdf = \PDF::loadView('downloads.relatorios.efectividades', $headers);
        return $pdf->stream('lista-estudantes-novo.pdf');
        // return $pdf->stream();

        return view('admin.funcionarios.tempos.index', $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'professor_id' => 'required|exists:tb_professores,id',
            'data' => 'required|date',
            'tempos_dados' => 'required|integer|min:0',
        ]);

        $verificar = TempoLecionado::where('professor_id', $request->professor_id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->where('data', $request->data)
        ->first();

        if (!$verificar) {
            
            $mesReferencia = Carbon::parse($request->data)->format('M');
            $AnoReferencia = Carbon::parse($request->data)->format('Y');
            
            $efetividade = TempoLecionado::create([
                'observacao' => $request->observacao,
                'data' => $request->data,
                'mes' => $mesReferencia,
                'ano' => $AnoReferencia,
                'tempos_dados' => $request->tempos_dados,
                'professor_id' =>  $request->professor_id,
                'ano_lectivos_id' => $this->anolectivoActivo(),
                'shcools_id' => $this->escolarLogada(),
            ]);
        }

        return response()->json(['success' => true, 'data' => $efetividade->load('funcionario')]);
    }

    // activar e desactivar turma
    public function edit($id)
    {
        $user = auth()->user();

        // if (!$user->can('update: contrato')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $dados = TempoLecionado::findOrFail($id);

        return response()->json($dados, 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'professor_id' => 'required|exists:tb_professores,id',
            'data' => 'required|date',
            'tempos_dados' => 'required|integer|min:0',
        ]);

        $mesReferencia = Carbon::parse($request->data)->format('M');
        $AnoReferencia = Carbon::parse($request->data)->format('Y');
    
        $efetividade = TempoLecionado::findOrFail($id);
        $efetividade->observacao = $request->observacao;
        $efetividade->data = $request->data;
        $efetividade->professor_id = $request->professor_id;
        $efetividade->tempos_dados = $request->tempos_dados;
        $efetividade->mes = $mesReferencia;
        $efetividade->ano = $AnoReferencia;
        
        $efetividade->update();

        return response()->json(['success' => true, 'data' => $efetividade->load('funcionario')]);
    }

    // activar e desactivar turma
    public function destroy($id)
    {
        $user = auth()->user();
        // if (!$user->can('read: contrato')) {
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $efetividade = TempoLecionado::findOrFail($id);
        $efetividade->delete();

        return response()->json(['success' => true]);
    }
}
