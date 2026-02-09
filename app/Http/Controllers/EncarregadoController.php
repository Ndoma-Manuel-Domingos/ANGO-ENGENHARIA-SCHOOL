<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\calendarios\Matricula;
use App\Models\web\encarregados\Encarregado;
use App\Models\web\encarregados\EncarregadoEstudantes;
use App\Models\web\estudantes\Estudante;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EncarregadoController extends Controller
{
    use TraitHelpers;

    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // --------------------------------------------------------------------------------------
    // ----------------------------------START ENCARREGADOS ---------------------------------------------------
    // --------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------

    public function index()
    {
        $user = auth()->user();
        
        if(!$user->can('read: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Lista dos Encarregados",
            "descricao" => env('APP_NAME'),  
            "usuario" => User::findOrFail(Auth::user()->id), 
            "listarEncarregado" => Encarregado::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.encarregados.index', $headers);
    }

    public function create()
    {

        $user = auth()->user();
        
        if(!$user->can('read: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $matriculas = Matricula::where('tb_matriculas.status_matricula', '!=', 'nao_confirmado')
            ->where('tb_matriculas.status_matricula', '!=', 'rejeitado')
            ->where('status_inscricao', 'Admitido')
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
        ->get();

        

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "matriculas" =>  $matriculas,
            
            "titulo" => "Lista dos Encarregados",
            "descricao" => env('APP_NAME'),  
            "usuario" => User::findOrFail(Auth::user()->id), 
            "listarEncarregado" => Encarregado::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.encarregados.create', $headers);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if(!$user->can('create: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'data_nascimento' => 'required',
            'genero' => 'required',
            'estado_civil' => 'required',
            'profissao' => 'required',
            'telefone' => 'required',
            'numero_bilhete' => 'required',
        ]);
        
        DB::beginTransaction();  
        
        try {
            // Realizar operações de banco de dados aqui
            Encarregado::create([
                'nome' => $request->nome,
                'sobre_nome' => $request->sobre_nome,
                'nome_completo' => $request->nome . " " . $request->sobre_nome,
                'data_nascimento' => $request->data_nascimento,
                'estado_civil'	=> $request->estado_civil,
                'genero' => $request->genero,
                'profissao'	=> $request->profissao,
                'telefone' => $request->telefone,
                'numero_bilhete' => $request->numero_bilhete,
                'email' => $request->email,
                'shcools_id' => $this->escolarLogada(),
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

        Alert::success('Bom Trabalho', 'Dados actualizado com sucesso!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $encarregado = Encarregado::findOrFail($id);
                
        $matriculas = Matricula::where('tb_matriculas.status_matricula', '!=', 'nao_confirmado')
            ->where('tb_matriculas.status_matricula', '!=', 'rejeitado')
            ->where('status_inscricao', 'Admitido')
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
        ->get();
       

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "encarregado" => $encarregado,
            "matriculas" => $matriculas,
            "titulo" => "Editar Encarrego",
            "descricao" => env('APP_NAME'),  
            "usuario" => User::findOrFail(Auth::user()->id), 
            "listarEncarregado" => Encarregado::where('shcools_id', $this->escolarLogada())->get(),
        ];

        return view('admin.encarregados.edit', $headers);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if(!$user->can('update: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'data_nascimento' => 'required',
            'genero' => 'required',
            'estado_civil' => 'required',
            'profissao' => 'required',
            'telefone' => 'required',
            'numero_bilhete' => 'required',
        ]);
        
        DB::beginTransaction();  
        
        try {
            // Realizar operações de banco de dados aqui
            
            $encarregado = Encarregado::findOrFail($id);
            $encarregado->nome = $request->nome;
            $encarregado->sobre_nome = $request->sobre_nome;
            $encarregado->data_nascimento = $request->data_nascimento;
            $encarregado->nome_completo = $request->nome . " " . $request->sobre_nome;
            $encarregado->estado_civil	= $request->estado_civil;
            $encarregado->genero = $request->genero;
            $encarregado->profissao	= $request->profissao;
            $encarregado->telefone = $request->telefone;
            
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Dados actualizado com sucesso!');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        DB::beginTransaction();  
        
        try {
            // Realizar operações de banco de dados aqui
            Encarregado::findOrFail($id)->delete();
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados excluído com sucesso", 'success' => true]);
      
    }

    public function show($id)
    {
        $encarregado = Encarregado::with([
            'educandos.estudante.turma.turma',
            'educandos.estudante.turma.turma.classe',
            'educandos.estudante.turma.turma.curso',
            'educandos.estudante.turma.turma.turno',
        ])->findOrFail($id);

        $user = auth()->user();
        
        if(!$user->can('update: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $encarregado = Encarregado::findOrFail($id);
                
        $matriculas = Matricula::where('tb_matriculas.status_matricula', '!=', 'nao_confirmado')
            ->where('tb_matriculas.status_matricula', '!=', 'rejeitado')
            ->where('status_inscricao', 'Admitido')
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
        ->get();

        

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "encarregado" => $encarregado,
            "matriculas" => $matriculas,
            "titulo" => "Editar Encarrego",
            "descricao" => env('APP_NAME'),  
            "usuario" => User::findOrFail(Auth::user()->id), 
            "listarEncarregado" => Encarregado::where('shcools_id', $this->escolarLogada())->get(),
        ];

        return view('admin.encarregados.show', $headers);
    }
    
    
    public function indexAssociarEstudnate($id)
    {

        $user = auth()->user();
        
        if(!$user->can('read: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $matriculas = Matricula::where('tb_matriculas.status_matricula', '!=', 'nao_confirmado')
            ->where('tb_matriculas.status_matricula', '!=', 'rejeitado')
            ->where('status_inscricao', 'Admitido')
            ->where('tb_matriculas.ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('tb_matriculas.shcools_id', '=', $this->escolarLogada())
            ->with(['escolas', 'ano_lectivo', 'classe_at', 'estudante', 'classe', 'turno', 'curso'])
        ->get();
        
        $encarregado = Encarregado::findOrFail($id);

        

        $headers = [ 
            "titulo" => "Associar Estudantes à Encarregado",
            "descricao" => env('APP_NAME'),  
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "encarregado" =>  $encarregado,
            "matriculas" =>  $matriculas,
            
            "usuario" => User::findOrFail(Auth::user()->id), 
            "listarEncarregado" => Encarregado::where([
                ['shcools_id', '=', $this->escolarLogada()]
            ])->get(),
        ];

        return view('admin.encarregados.associar-estudante', $headers);
    }
    
    public function AdicionarEstudanteStore(Request $request)
    {
        $request->validate([
            'estudantes_id' => 'required|array|min:1',
            'encarregados_id' => 'required',
            'grau_parentesco' => 'required',
        ]);
                        
        DB::beginTransaction();  
        
        try {
            // Realizar operações de banco de dados aqui
            foreach ($request->estudantes_id as $item) {
            
                $verificar = EncarregadoEstudantes::where('encarregados_id', $request->encarregado)
                    ->where('estudantes_id', $item)
                    ->first();
                    
                if(!$verificar) {
                    EncarregadoEstudantes::create([
                        'encarregados_id' => $request->encarregados_id,
                        'estudantes_id' => $item,
                        'grau_parentesco' => $request->grau_parentesco,
                    ]);
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
        
        Alert::success('Bom Trabalho', 'Dados actualizado com sucesso!');
        return redirect()->route('encarregados.show', $request->encarregados_id);
          
    }
    
    public function buscarEstudantePorBilhete(Request $request)
    {
        $bilhete = $request->query('bilhete');

        $estudante = Estudante::where('bilheite', 'like', "%{$bilhete}%")->first();
    
        if ($estudante) {
            return response()->json($estudante);
        }
    
        return response()->json(null);
    }
    
    public function buscarPorTelefone(Request $request)
    {
        $telefone = $request->query('telefone');

        $encarregado = Encarregado::where('telefone', 'like', "%{$telefone}%")->first();
    
        if ($encarregado) {
            return response()->json($encarregado);
        }
    
        return response()->json(null);
    }
    
    public function ExcluirEstudanteEncarregado($id)
    {
        $user = auth()->user();
        
        if(!$user->can('delete: encarregado')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
                
        DB::beginTransaction();  
        
        try {
            // Realizar operações de banco de dados aqui
                
            $delete = EncarregadoEstudantes::findOrFail($id);
            $delete->delete();
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados excluído com sucesso", 'success' => true]);


    }

}
