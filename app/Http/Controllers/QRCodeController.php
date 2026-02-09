<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\PresencaEstudante;
use App\Models\web\calendarios\Servico;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\turmas\EstudantesTurma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class QRCodeController extends Controller
{
    //
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = auth()->user();

        // if(!$user->can('read: comunicados')){
        //      Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //      return redirect()->back();
        // }
            

                    
        $listas = PresencaEstudante::with(['turma.curso', 'turma.classe', 'turma.turno', 'estudante', 'escola', 'ano'])
            ->where('shcools_id', $this->escolarLogada())
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            'listas' =>  $listas,
            "titulo" => "Controle de Entradas e Saídas",
            "descricao" => "Estudantes",
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.qr-code.home', $headers);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        // if(!$user->can('read: comunicados')){
        //      Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //      return redirect()->back();
        // }
    


        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "QR-Code",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.qr-code.index', $headers);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $codigoQr = Crypt::decrypt($request->codigo_qr);
        // Buscar estudante no banco de dados
        $estudante = Estudante::findOrFail($codigoQr);
        
        $matricula = Matricula::where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->where('estudantes_id', $estudante->id)
            ->with(['ano_lectivo', 'classe_at', 'classe', 'turno', 'curso'])
            ->first();
        
        // esta em alguma turma
        $turma = EstudantesTurma::with(['turma'])->where('estudantes_id', $estudante->id)->where('ano_lectivos_id', $this->anolectivoActivo())->first();
        
        $servico = Servico::where('servico', 'Propinas')->where('shcools_id', $this->escolarLogada())->first();
        
        $cartao = CartaoEstudante::where('estudantes_id', $estudante->id)->where('ano_lectivos_id', $this->anolectivoActivo())->where('servicos_id', $servico->id)->get();
        
        if ($estudante) {
            return response()->json([
                'sucesso' => true,
                'cartao' => $cartao,
                'nome' => $estudante->nome . " " . $estudante->sobre_nome,
                'ano' => $matricula->ano_lectivo->ano,
                'classe_at' => $matricula->classe_at->classes,
                'classe' => $matricula->classe->classes,
                'curso' => $matricula->curso->curso,
                'turno' => $matricula->turno->turno,
                'turma' => $turma->turma->turma,
                'turma_id' => $turma->turma->id,
                'estudante_id' => $estudante->id,
                'imagem' => '/assets/images/estudantes/user.png',
            ]);
        } else {
            return response()->json(['sucesso' => false]);
        }
        
        return response()->json(['mensagem' => "QR Code recebido: $codigoQr"]);
    }  
    
  

    public function confirmar_entrada(Request $request)
    {
 
        try {
            // Inicia a transação
            DB::beginTransaction();

            $estudante = Estudante::findOrFail($request->estudante_id);
            
            $verificar_entrada = PresencaEstudante::whereDate('data_entrada', date("Y-m-d"))
                ->where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivo_id', $this->anolectivoActivo())
                ->where('estudantes_id', $estudante->id)->first();
            
            if(!$verificar_entrada){
            
                PresencaEstudante::create([
                    'data_entrada' => date("Y-m-d"),
                    'hora_entrada' => date("H:i:s"),
                    'status_entrada' => 1,
                    'status_saida' => 0,
                    'turma_id' => $request->turma_id,
                    'estudantes_id' => $estudante->id,
                    'shcools_id' => $this->escolarLogada(),
                    'ano_lectivo_id' => $this->anolectivoActivo(),
                ]);    
            }
         
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
        }
        
        return response()->json(['mensagem' => "Entrada confirmada", 'sucesso' => true], 200);
    }
    
    public function confirmar_saida(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();  
            
            $estudante = Estudante::findOrFail($request->estudante_id);
            
            $verificar_saida = PresencaEstudante::whereDate('data_entrada', date("Y-m-d"))
                ->where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivo_id', $this->anolectivoActivo())
                ->where('estudantes_id', $estudante->id)->first();
            
            if($verificar_saida){
                $update = PresencaEstudante::findOrFail($verificar_saida->id);
                $update->data_saida = date("Y-m-d");
                $update->hora_saida = date("H:i:s");
                $update->status_saida = true;
                $update->update();
            }else {
                PresencaEstudante::create([
                    'data_saida' => date("Y-m-d"),
                    'hora_saida' => date("H:i:s"),
                    'status_saida' => true,
                    'turma_id' => $request->turma_id,
                    'estudantes_id' => $estudante->id,
                    'shcools_id' => $this->escolarLogada(),
                    'ano_lectivo_id' => $this->anolectivoActivo(),
                ]);
            }
            
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
        }
        
        return response()->json(['mensagem' => "Saída confirmada", 'sucesso' => true], 200);
    }
    
}
