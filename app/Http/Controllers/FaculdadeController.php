<?php

namespace App\Http\Controllers;

use App\Exports\FaculdadeExport;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\disciplinas\Faculdade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FaculdadeController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // view cursos principal
    public function home(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('read: faculdade')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $headers = [
            "titulo" => "Lista das faculdades",
            "descricao" => env('APP_NAME'),
            "loyout" => $request->loyout,
            "escola" => $request->loyout == "escolas" ? Shcool::with('ensino')->findOrFail($this->escolarLogada()) : null,
        ];  

        return view('sistema.faculdades.home', $headers);
    }
           
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: faculdade')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
    
        $query = Faculdade::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['nome', 'code'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        });
    
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    }    
    
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: faculdade')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            "designacao" => 'required',
            "abreviacao" => 'required',
            "code" => 'required',
        ]);
 
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $verificarFaculdade = Faculdade::where('nome', $request->designacao)->first();
    
            if($verificarFaculdade){
                return response()->json([
                    'status' => 300,
                    'message' => "Este Faculdade já Esta Cadastrado!",
                ]);
            }
    
            Faculdade::create([
                "nome" => $request->designacao,
                "abreviacao" => $request->abreviacao,
                "code" => $request->code,
                "descricao" => $request->descricao,
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
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    // editar cursos
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: faculdade')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return Faculdade::findOrFail($id);
    }

    // actualizar cursos
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: faculdade')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "designacao" => 'required',
            "abreviacao" => 'required',
            "code" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
  
            $update = Faculdade::findOrFail($id);

            $update->nome = $request->designacao;
            $update->abreviacao = $request->abreviacao;
            $update->code = $request->code;
            $update->descricao = $request->descricao;
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
            'message' => 'Dados ACtualizados com sucesso!',
        ]);
    }

    // delete cursos
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: faculdade')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                      
            Faculdade::findOrFail($id)->delete();

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
            "titulo" => "Lista de Faculdades",
            "faculdades" => Faculdade::get()
        ];
                
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new FaculdadeExport, 'faculdades.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-faculdades', $headers);
            return $pdf->stream('lista-faculdades.pdf');
        }

    }


}
