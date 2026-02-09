<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\encarregados\Encarregado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ComunicadoController extends Controller
{
    //
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $user = auth()->user();

        if (!$user->can('read: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Tipo de Comunicados",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.comunicados.home', $headers);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('read: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $comunicados = Comunicado::with(['user', 'escola', 'ano'])->where('shcools_id', $this->escolarLogada())->orderBy('id', 'desc')->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Comunicados",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "comunicados" => $comunicados,
        ];

        return view('admin.comunicados.index', $headers);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comunicar_encarregados()
    {
        $user = auth()->user();

        if (!$user->can('create: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $encarregados = Encarregado::where('shcools_id', $this->escolarLogada())->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "encarregados" =>  $encarregados,
            
            "titulo" => "Comunicado para Encarregados",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.comunicados.create-encarregados', $headers);
    }

    public function enviarSMS($telefone, $sms)
    {
        $res = Http::post('https://telcosms.co.ao/send_message', [
            'message' => [
                'api_key_app' => 'prd4023f71403575cbbee333ba9f1',
                'phone_number' => $telefone,
                'message_body' => $sms,
            ],
        ]);
        return $res['status'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comunicar_encarregados_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'descricao' => 'required',
            'encarregado_id' => 'required|array',
        ]);

        try {
            // Inicia a transação
            DB::beginTransaction();

            foreach ($request->encarregado_id as $item) {
                $encarregado = Encarregado::findOrFail($item);
                $sta = $this->enviarSMS($encarregado->telefone, $request->descricao);
            }
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Exception $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
        }

        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('create: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Comunicados",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.comunicados.create', $headers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('create: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'to_escola' => 'required',
            'titulo' => 'required',
            'descricao' => 'required',
            'tipo_comunicado' => 'required',
            'tipo_acesso_comunicado' => 'required',
        ]);

        try {
            // Inicia a transação
            DB::beginTransaction();

            if (!empty($request->file('anexo'))) {
                $anexo = $request->file('anexo');
                $imageAnexo = time() . '.' . $anexo->extension();
                $anexo->move(public_path('assets/anexos'), $imageAnexo);
            } else {
                $imageAnexo = NULL;
            }

            $create = Comunicado::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'user_id' => Auth::user()->id,
                'shcools_id' => $this->escolarLogada(),
                'ano_lectivo_id' => $this->anolectivoActivo(),
                'to_escola' => $request->to_escola,
                'tipo_comunicado' => $request->tipo_comunicado, // comunicado , noticia
                'tipo_acesso_comunicado' => $request->tipo_acesso_comunicado, // interno , externos
                'documento' => $imageAnexo,
                'to' => $this->escolarLogada(),
                'level_to' => '4',
                'level' => '4', // escola, municipio, provincial, ministerio
                'status' => 'desactivo',
            ]);

            // Comita a transação se tudo estiver correto
            DB::commit();

            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Exception $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());

            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }


        Alert::success('Bom Trabalho', 'Dados salvos com sucesso!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('read: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $comunicado = Comunicado::with(['user', 'escola', 'ano'])->findOrFail($id);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Comunicados",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "comunicado" => $comunicado,
        ];

        return view('admin.comunicados.show', $headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('update: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $comunicado = Comunicado::with(['user', 'escola', 'ano'])->findOrFail($id);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Comunicados",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),

            "comunicado" => $comunicado,
        ];

        return view('admin.comunicados.edit', $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
        $user = auth()->user();

        if (!$user->can('update: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'to_escola' => 'required',
            'titulo' => 'required',
            'descricao' => 'required',
            'tipo_comunicado' => 'required',
            'tipo_acesso_comunicado' => 'required',
        ]);

        $comunicado = Comunicado::with(['user', 'escola', 'ano'])->findOrFail($id);

        if (!empty($request->file('anexo'))) {
            $anexo = $request->file('anexo');
            $imageAnexo = time() . '.' . $anexo->extension();
            $anexo->move(public_path('assets/anexos'), $imageAnexo);
        } else {
            $imageAnexo = $request->anexo_aguardado;
        }

        try {
            // Inicia a transação
            DB::beginTransaction();

            $comunicado->titulo = $request->titulo;
            $comunicado->descricao = $request->descricao;
            $comunicado->to_escola = $request->to_escola;
            $comunicado->tipo_acesso_comunicado = $request->tipo_acesso_comunicado; // interno , externos
            $comunicado->tipo_comunicado = $request->tipo_comunicado;
            $comunicado->documento = $imageAnexo;

            $comunicado->update();

            // Comita a transação se tudo estiver correto
            DB::commit();

            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Exception $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());

            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(['message' => 'Erro ao salvar'], 500);
        }

        Alert::success('Bom Trabalho', 'Dados actualizados com sucesso!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comunicado $comunicado)
    {
        $user = auth()->user();

        if (!$user->can('delete: comunicados')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $comunicado->delete();

        Alert::success('Bom Trabalho', 'Dados Excluídos com sucesso!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {


        $comunicado = Comunicado::with(['user', 'escola', 'ano'])->findOrFail($id);

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            
            "titulo" => "$comunicado->titulo",
            "escola" => Shcool::find($this->escolarLogada()),
            "comunicado" => $comunicado
        ];

        $pdf = \PDF::loadView('downloads.relatorios.comunicados', $headers);
        return $pdf->stream('comunicados.pdf');
    }
}
