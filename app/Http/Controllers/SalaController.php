<?php

namespace App\Http\Controllers;

use App\Exports\SalaExport;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\salas\Sala;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SalaController extends Controller
{
    //
    use TraitHelpers;

    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // view salas principal
    public function home()
    {
        $user = auth()->user();
        
        if(!$user->can('read: sala')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        } 
        
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Lista das Salas",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            // "listarSalas" => Sala::where('shcools_id', $this->escolarLogada())->get(),
        ];

        return view('admin.salas.home', $headers);
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: sala')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $sala_registros = $request->sala_registros ?? 5;
    
        $query = Sala::when($request->sala_designacao, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['salas', 'descricao', 'status'] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        })
        ->when($request->sala_status, function($query, $value) {
            $query->where('status', $value);
        })->where('shcools_id', $this->escolarLogada());
    
    
        return response()->json(
            $query->orderByDesc('id')->paginate($sala_registros)
        );
    }
    
    public function edit($id)
    {
        return Sala::findOrFail($id);
    }

    // cadastrar salas
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: sala')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        } 
        
        $request->validate([
            "salas" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $verificarSala = Sala::where('salas', $request->salas) 
                ->where('shcools_id', $this->escolarLogada()) 
            ->first();
            
            if($verificarSala){
                return response()->json([
                    'status' => 300,
                    'message' => "Este Salas já Esta Cadastrado!",
                ]);
            }
    
            Sala::create([
                "salas" => $request->salas,
                "tipo" => $request->tipo,
                "status" => $request->status,
                "descricao" => $request->descricao,
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
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    // actualizar salas
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: sala')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        } 
        
        $request->validate([
            "salas" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ]);
    
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $update = Sala::findOrFail($id);
    
            $update->salas = $request->salas;
            $update->tipo = $request->tipo;
            $update->status = $request->status;
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

    // delete turno
    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: sala')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }              
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            Sala::findOrFail($id)->delete();
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
            "usuario" => User::findOrFail(Auth::user()->id),
        ]);
    }
    
    // retificado
    public function export(Request $request)
    {

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "LISTA DAS SALAS",
            "salas" => Sala::where('shcools_id', $this->escolarLogada())->get()
        ];
        
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            return Excel::download(new SalaExport, 'salas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-salas', $headers);
            return $pdf->stream('lista-salas.pdf');
        }
            
    }

}
