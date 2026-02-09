<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\estudantes\Estudante;
use App\Models\web\estudantes\EstudanteDesconto;
use App\Models\web\turmas\Desconto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class EstudanteDescontoController extends Controller
{

    use TraitHelpers;
    use TraitHeader;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function home()
    {
        $user = auth()->user();
        
        if(!$user->can('read: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());
        
        $estudantes = Estudante::where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->get();
        $descontos = Desconto::where('shcools_id', $escola->id)->get(); 
        $anos_lectivos = AnoLectivo::where('shcools_id', $escola->id)->get(); 

        $headers = [
            "escola" => $escola,
            "titulo" => "Estudantes com descontos",
            "descricao" => env('APP_NAME'),
            "descontos" => $descontos,
            "estudantes" => $estudantes,
            "anos_lectivos" => $anos_lectivos,
        ];

        return view('admin.descontos.estudantes', $headers);
    }
            
    public function index(Request $request)
    {
        $user = auth()->user();
    
        if (!$user->can('read: desconto')) {
            Alert::error(
                'Acesso restrito',
                'Você não possui permissão para esta operação, por favor, contacte o administrador!'
            );
            return redirect()->back();
        }
    
        $paginacao = $request->paginacao ?? 5;
        
        $query = EstudanteDesconto::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                // campo da tabela classe
                $q->orWhereHas('estudante', function ($qc) use ($value) {
                    $qc->where('nome', 'like', "%{$value}%");
                })
                ->orWhereHas('estudante', function ($qc) use ($value) {
                    $qc->where('sobre_nome', 'like', "%{$value}%");
                })
                // campo da tabela ensino (classe->ensino)
                ->orWhereHas('desconto', function ($qe) use ($value) {
                    $qe->where('nome', 'like', "%{$value}%");
                })
                // campo da tabela ano_lectivo
                ->orWhereHas('ano', function ($qa) use ($value) {
                    $qa->where('ano', 'like', "%{$value}%");
                });
            });
        })
        ->when($request->ano_lectivos_id, function($query, $value){
            $query->where('ano_lectivos_id', $value);
        })
        ->when($request->descontos_id, function($query, $value){
            $query->where('desconto_id', $value);
        })
        ->when($request->data_status, function($query, $value) {
            $query->where('status', $value);
        })
        ->where('shcools_id', $this->escolarLogada())
        ->with(['estudante', 'ano', 'desconto']);
        
        return response()->json(
            $query->orderByDesc('id')->paginate($paginacao)
        );
    
    }
 
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->can('atribuir desconto')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $user = auth()->user();

        $request->validate([
            'estudante_id' => 'required',
            'desconto_id' => 'required',
            'ano_lectivo_id' => 'required',
        ]);
        
        try {
            DB::beginTransaction();
           
            $verificar_desconto = EstudanteDesconto::where('ano_lectivos_id', $request->ano_lectivo_id)
                ->where('desconto_id', $request->desconto_id)
                ->where('estudante_id', $request->estudante_id)
                ->where('status', 'activo')
            ->first(); 
            
            if(!$verificar_desconto){
                            
                EstudanteDesconto::create([
                    'status' => $request->status,
                    'estudante_id' => $request->estudante_id,
                    'desconto_id' => $request->desconto_id,
                    'ano_lectivos_id' => $request->ano_lectivo_id,
                    'shcools_id' => $this->escolarLogada(),
                ]);
              
            }
            
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
      
    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('atribuir desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        return EstudanteDesconto::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('atribuir desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            'estudante_id' => 'required',
            'desconto_id' => 'required',
            'ano_lectivo_id' => 'required',
        ]);
                                 
        $update = EstudanteDesconto::findOrFail($id); 
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $update->status = $request->status;
            $update->estudante_id = $request->estudante_id;
            $update->desconto_id = $request->desconto_id;
            $update->ano_lectivos_id = $request->ano_lectivo_id;
    
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
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('create: desconto')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                      
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            EstudanteDesconto::findOrFail($id)->delete();
            
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

    public function export(Request $request)
    {
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor

        // CONTROLAR A SESSÃo INICIALIZADA OU NAO
        $estudantes = EstudanteDesconto::with(['estudante', 'desconto', 'escola', 'ano'])
            ->when($request->ano_lectivo_id, function ($query, $value) {
                $query->where('ano_lectivos_id', $value);
            })
            ->when($request->desconto_id, function ($query, $value) {
                $query->where('descontos_id', $value);
            })
            ->when($request->data_status, function($query, $value) {
                $query->where('status', $value);
            })
            ->where('shcools_id', $this->escolarLogada())
        ->get();

        $desconto = Desconto::find($request->descontos_id);
        $ano = AnoLectivo::find($request->ano_lectivos_id);

        $titulo = "LISTA DOS ESTUDANTES COM DESCONTOS";

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => $titulo,
            "desconto" => $desconto,
            "ano" => $ano,
            "estudantes" => $estudantes,
            "filtros" => $request->all('desconto_id', 'ano_lectivos_id'),
        ];
                
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            //return Excel::download(new SalaExport, 'salas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-estudantes-descontos', $headers);
            return $pdf->stream('lista-estudantes-descontos.pdf');
        }
    }
    
}
