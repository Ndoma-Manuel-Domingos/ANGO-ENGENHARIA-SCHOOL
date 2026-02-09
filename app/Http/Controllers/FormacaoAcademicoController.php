<?php

namespace App\Http\Controllers;

use App\Exports\FormacaoAcademicaExport;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FormacaoAcedemico;
use App\Models\Shcool;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FormacaoAcademicoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: formacao academico')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Formação Académicas",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.formacao_academicas.home', $headers);
    }
        
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: formacao academico')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = FormacaoAcedemico::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['nome', 'status'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        })->where('shcools_id', $this->escolarLogada());
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }

    public function store(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('create: formacao academico')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
           
            FormacaoAcedemico::create([
                "nome" => $request->designacao,
                "status" => $request->status,
                "shcools_id" => $this->escolarLogada(),
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

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!'
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: formacao academico')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        return FormacaoAcedemico::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: formacao academico')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
        ]);
        
        $update = FormacaoAcedemico::find($id);  
      
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                
            $update->nome = $request->designacao;
            $update->status = $request->status;
            $update->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
            'message' => 'Dados salvos com sucesso!'
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: formacao academico')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            FormacaoAcedemico::findOrFail($id)->delete();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
            'message' => 'Dados Excluido com sucesso!',
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);

    }

    public function export(Request $request)
    {
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DE FORMAÇÃO ACADEMICAS",
            "escola" => Shcool::find($this->escolarLogada()),
            "escolaridades" => FormacaoAcedemico::where('shcools_id', $this->escolarLogada())->get()
        ];
                                
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new FormacaoAcademicaExport, 'formacao-academicas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-formacao-academicas', $headers);
            return $pdf->stream('lista-formacao-academicas.pdf');
        }
    }

}
