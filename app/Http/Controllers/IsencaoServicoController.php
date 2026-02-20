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
use Illuminate\Support\Facades\DB;

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
        ->orderBy('ano_lectivos_id', 'desc')
        ->get();
        
        return response()->json([
            'cartoes' => $cartoes,
        ]);
    
    }

    public function update(Request $request, $id)
    {
        $cartao = CartaoEstudante::findOrFail($id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($request->action == "isentar-mes") {
                $cartao->status = "Isento";
                $cartao->motivo_isencao_mes = $request['messegem'];
                $cartao->update();
            }else if($request->action == "isentar-remover-mes") {
                $cartao->status = "Nao Pago";
                $cartao->motivo_remover_isencao_mes = $request['messegem'];
                $cartao->update();
            }else if($request->action == "isentar-multa") {
                $cartao->multa_removida = $cartao->multa;
                $cartao->multa = 0;
                $cartao->status_multa = "I";
                $cartao->motivo_isencao_multa = $request['messegem'];
                $cartao->update();
            }else if($request->action == "remover-isentar-multa") {
                $cartao->multa = $cartao->multa_removida;
                $cartao->multa_removida = 0;
                $cartao->status_multa = "N";
                $cartao->motivo_remover_isencao_multa = $request['messegem'];
                $cartao->update();
            }else if($request->action == "editar-multa"){
                $cartao->multa_removida = $cartao->multa;
                $cartao->multa = $request['messegem'];
                $cartao->update();
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
          
        $cartoes = CartaoEstudante::where('estudantes_id', $cartao->estudantes_id)
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->with([
                'servico', 'ano', 
                'estudante.matricula.curso', 
                'estudante.matricula.classe',
                'estudante.matricula.turno'
            ])
            ->where('mes_id', "M")
            ->whereNotIn('status', ["Pago"])
            ->orderBy('ano_lectivos_id', 'desc')
        ->get();
        
        return response()->json([
            'status' => 200,
            'message' => 'Dados Actualizados com sucesso!',
            'cartoes' => $cartoes,
        ]);
    }

}
