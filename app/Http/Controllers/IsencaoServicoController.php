<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Shcool;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


class IsencaoServicoController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('create: isentar propina')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Isenções de Serviços",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.financeiros.isencoes.home', $headers);
    }
    
    
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('create: isentar propina')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
        
        $cartoes = CartaoEstudante::query()->when($request->designacao_geral, function ($query, $value) {
            $query->whereHas('estudante', function ($q) use ($value) {
                $q->where(function ($sub) use ($value) {
                    $sub->where('nome', 'like', "%{$value}%")
                        ->orWhere('sobre_nome', 'like', "%{$value}%")
                        ->orWhere('bilheite', 'like', "%{$value}%")
                        ->orWhere('nome_completo', 'like', "%{$value}%")
                        ->orWhere('numero_processo', 'like', "%{$value}%");
                });
            });
        })
        ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->with([
            'servico', 'ano', 
            'estudante.matricula.curso', 
            'estudante.matricula.classe',
            'estudante.matricula.turno'
        ])
        ->where('mes_id', "M")
        ->whereNotIn('status', ["Pago"])
        ->get();
        
        return response()->json([
            'cartoes' => $cartoes,
        ]);
    
    }
    
    
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('read: matricula') && !$user->can('read: estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $estudantes = Estudante::findOrFail($id);

        $servicosPropina = Servico::where('servico', 'Propinas')
            ->where('shcools_id', $this->escolarLogada())
        ->first();

        $cartoes = CartaoEstudante::with(['servico'])->where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('mes_id', "M")
            ->where('servicos_id', $servicosPropina->id)
        ->get();

        $estudanteTurma = EstudantesTurma::where('estudantes_id', $estudantes->id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->first();

        if (!$estudanteTurma) {
            Alert::warning("Informação", "Infelizmente não pode acessar esta área porque estudante não esta inserido em nenhuma turma!");
            return redirect()->back();
        }

        $turma = Turma::findOrFail($estudanteTurma->turmas_id);
        
        $bolseiro = Bolseiro::with(['instituicao','bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'activo')
            ->where('estudante_id', $estudantes->id)
        ->first();

        return response()->json([
            "estudante" => $estudantes,
            "bolseiro" => $bolseiro,
            "cartoes" => $cartoes,
            "ano" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "turma" => $turma,
            "servicosPropina" => $servicosPropina,
        ]);

    }

    

    // isentar multa para estudantes
    public function isentarPagamento($id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $user = auth()->user();

        // if(!$user->can('create: pagamento')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }



        $estudante = Estudante::findOrFail(Crypt::decrypt($id));

        $cartao = CartaoEstudante::with(['servico'])->where('estudantes_id', $estudante->id)
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->whereIn('status', ['divida', 'Nao Pago', 'Isento'])
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "titulo" => "Isentar Multa",
            "descricao" => env('APP_NAME'),
            "estudante" => $estudante,
            "cartoes" => $cartao,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.financeiros.isentar-multas', $headers);
    }

    public function isentarPropina(Request $request, $id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $cartao = CartaoEstudante::findOrFail($id);

        if ($cartao) {

            $cartao->status = "Isento";
            $cartao->motivo_isencao_mes = $request['motivo_isencao'];
            $cartao->update();

            return response()->json([
                'status' => 200,
                'message' => 'Dados Actualizados com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Ocorreu um erro, verificar se preencheu o motivo correctamente!'
            ]);
        }
    }

    public function removerIsentarPropina(Request $request, $id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $cartao = CartaoEstudante::findOrFail($id);

        if ($cartao) {

            $cartao->status = "Nao Pago";
            $cartao->motivo_remover_isencao_mes = $request['remover_motivo_isencao'];
            $cartao->update();

            return response()->json([
                'status' => 200,
                'message' => 'Dados Actualizados com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Ocorreu um erro, verificar se preencheu o motivo correctamente!'
            ]);
        }
    }

    public function editarMulta(Request $request, $id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $cartao = CartaoEstudante::findOrFail($id);

        if ($cartao) {
            $cartao->multa_removida = $cartao->multa;
            $cartao->multa = $request['nova_multa'];
            $cartao->update();

            return response()->json([
                'status' => 200,
                'message' => 'Dados Actualizados com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Ocorreu um erro, verificar se preencheu o motivo correctamente!'
            ]);
        }
    }

    public function isentarMulta(Request $request, $id)
    {

        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário

        $cartao = CartaoEstudante::findOrFail($id);

        if ($cartao) {
            $cartao->multa_removida = $cartao->multa;
            $cartao->multa = 0;
            $cartao->status_multa = "I";
            $cartao->motivo_isencao_multa = $request['motivo_isencao_multa'];
            $cartao->update();

            return response()->json([
                'status' => 200,
                'message' => 'Dados Actualizados com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Ocorreu um erro, verificar se preencheu o motivo correctamente!'
            ]);
        }
    }

    public function removerIsentarMulta(Request $request, $id)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        $cartao = CartaoEstudante::findOrFail($id);

        if ($cartao) {
            $cartao->multa = $cartao->multa_removida;
            $cartao->multa_removida = 0;
            $cartao->status_multa = "N";
            $cartao->motivo_remover_isencao_multa = $request['remover_motivo_isencao_multa'];
            $cartao->update();

            return response()->json([
                'status' => 200,
                'message' => 'Dados Actualizados com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'Ocorreu um erro, verificar se preencheu o motivo correctamente!'
            ]);
        }
    }

}
