<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\biblioteca\DevolucaoEmprestimoLivro;
use App\Models\web\biblioteca\EmprestimoLivro;
use App\Models\web\biblioteca\Livro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DevolucaoEmprestimoLivroController extends Controller
{
    //
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // view cursos principal
    public function index()
    {
        $user = auth()->user();

        // if(!$user->can('read: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Devoluções de Livros",
            
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "emprestimos" => EmprestimoLivro::with(["emprestado_por", "emprestado_para", "items", "escola"])
                ->where('status', true)
                ->where('shcools_id', $this->escolarLogada())
                ->get(),
            "devolucoes" => DevolucaoEmprestimoLivro::with(["emprestimo", "escola"])
                ->where('status', true)
                ->where('shcools_id', $this->escolarLogada())
                ->get(),

        ];

        return view('admin.bibliotecas.devolucoes.index', $headers);
    }

    // cadastrar cursos
    public function store(Request $request)
    {
        $user = auth()->user();

        // if(!$user->can('create: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $validate = Validator::make($request->all(), [
            "emprestimo_id" => 'required',
            "status" => 'required',
            "data_devolucao" => 'required',
            "observacao" => 'required',
        ], [
            "emprestimo_id.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "data_devolucao.required" => "Campo Obrigatório",
            "observacao.required" => "Campo Obrigatório",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages(),
            ]);
        } else {


            try {
                DB::beginTransaction();


                $emprestimo = EmprestimoLivro::with(['items'])->findOrFail($request->emprestimo_id);

                foreach ($emprestimo->items as $item) {

                    $it = Livro::findOrFail($item->livro_id);
                    $it->status = "Disponível";
                    $it->update();
                }

                $emprestimo->status = false;
                $emprestimo->update();

                $create = DevolucaoEmprestimoLivro::create([
                    'emprestimo_id' => $request->emprestimo_id,
                    'status' => $request->status,
                    'data_devolucao' => $request->data_devolucao,
                    'observacao' => $request->observacao,
                    'shcools_id' => $this->escolarLogada(),
                ]);

                DB::commit();
            } catch (\Exception $e) {
                // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
                DB::rollback();

                Alert::warning('Informação', $e->getMessage());
                return redirect()->back();
                // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
            }


            return response()->json([
                'status' => 200,
                'message' => 'Dados salvos com sucesso!',
                "usuario" => User::findOrFail(Auth::user()->id),
            ]);
        }
    }

    // delete cursos
    public function destroy($id)
    {
        $user = auth()->user();

        // if(!$user->can('delete: curso')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }

        $delete = Livro::findOrFail($id);
        $delete->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
}
