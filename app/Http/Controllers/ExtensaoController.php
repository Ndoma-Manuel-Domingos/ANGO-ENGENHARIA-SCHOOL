<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\calendarios\Matricula;
use App\Models\web\estudantes\Estudante;
use App\Models\web\extensoes\Extensao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class ExtensaoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: extensoes')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Lista das Extensões",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.extensoes.home', $headers);
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
    
        $query = Extensao::when($request->designacao_geral, function ($query, $value) {
            $query->where(function ($q) use ($value) {
                foreach (['extensao', 'status'] as $field) {
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

        if (!$user->can('create: extensoes')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $verificarTurno = Extensao::where('extensao', $request->designacao)
                ->where('shcools_id', $this->escolarLogada())
            ->first();
    
            if ($verificarTurno) {
                return response()->json([
                    'status' => 300,
                    'message' => "Este Extensão já Esta Cadastrado!",
                ]);
            }
    
            $create = Extensao::create([
                "extensao" => $request->designacao,
                'sufix' => $request->sufix,
                "status" => $request->status,
                "tipo" => $request->tipo,
                "shcools_id" => $this->escolarLogada(),
            ]);
    
            if ($create->tipo == "estudantes") {
                $finds = Estudante::where('shcools_id', $this->escolarLogada())->get();
    
                foreach ($finds as $find) {
                    $est = Estudante::find($find->id);
                    $est->numero_processo = $create->extensao . " " . $est->id . "/" . $create->sufix;
                    $est->conta_corrente = "31.1.2.1." . $est->id;
                    $est->update();
                }
    
                foreach ($finds as $find) {
                    $mats = Matricula::where('estudantes_id', $find->id)->get();
                    foreach ($mats as $m) {
                        $mat = Matricula::find($m->id);
                        $mat->numero_estudante =  $create->extensao . " " . $mat->estudantes_id . "/" . $create->sufix;
                        $mat->update();
                    }
                }
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

        return response()->json([
            'status' => 200,
            'message' => 'Dados salvos com sucesso!',
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: extensoes')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        return Extensao::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('update: extensoes')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
  
        $request->validate([
            "designacao" => 'required',
            "status" => 'required',
            "tipo" => 'required',
        ]);
       
        $update = Extensao::findOrFail($id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $update->extensao = $request->designacao;
            $update->status = $request->status;
            $update->tipo = $request->tipo;
            $update->sufix = $request->sufix;
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
            'message' => 'Dados Actualizados com sucesso!',
        ]);
        
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: extensoes')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            Extensao::findOrFail($id)->forceDelete();

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
            "titulo" => "LISTA DAS EXTENSÕES",
            "escola" => Shcool::find($this->escolarLogada()),
            "extensao" => Extensao::where('shcools_id', $this->escolarLogada())->get()
        ];
        
        $documentType = $request->documentType;
        
        if($documentType === 'excel') {
            // return Excel::download(new FormacaoAcademicaExport, 'formacao-academicas.xlsx');
        } else {
            $pdf = \PDF::loadView('downloads.relatorios.lista-extensao', $headers);
            return $pdf->stream('lista-extensao.pdf');
        }
    }
}
