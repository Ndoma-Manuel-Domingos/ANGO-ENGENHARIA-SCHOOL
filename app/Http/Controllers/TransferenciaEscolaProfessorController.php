<?php

namespace App\Http\Controllers;

use App\Models\FuncionarioContratoCopia;
use App\Models\CartaoFuncionarioCopia;
use App\Models\Professor;
use App\Models\Shcool;
use App\Models\TransferenciaEscolaProfessor;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Mes;
use App\Models\web\funcionarios\CartaoFuncionario;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TransferenciaEscolaProfessorController extends Controller
{
    use TraitHelpers; 

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        $user = auth()->user();
        
        if(!$user->can('read: transeferencia professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $transferencias = TransferenciaEscolaProfessor::with('user')->with('professor')->with('origem')->with('destino')->get();
        
        $headers = [
            "titulo" => "Transferências",
            "descricao" => env('APP_NAME'),
            'transferencias' => $transferencias,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
        ];


        return view('sistema.professores.lista-transferencia', $headers);

    }

    public function index($id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: transeferencia professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professoroes = Professor::where('status', 'activo')->get();
        
        $headers = [
            "titulo" => "Transferência de Professores de Escola",
            "descricao" => env('APP_NAME'),
            'professoroes' => $professoroes,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            'id' => $id
        ];


        return view('sistema.professores.transferencia-escola', $headers);

    }

    public function store(Request $request)
    {      
        $user = auth()->user();
        
        if(!$user->can('create: transeferencia professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate(
            [
                'professor_id' => 'required',
                'motivo' => 'required',
                'escola_id' => 'required',
            ],
            [
                'professor_id.required' => "Senha Obrigatória",
                'motivo.required' => "Senha Obrigatória",
                'escola_id.required' => "Senha Obrigatória",
            ]
        ); 

        $professor = Professor::findOrFail($request->professor_id);

        if($professor->status == "desactivo"){
            return redirect()->route('web.transferencia-escola-professores')->with("danger", "A candidatura este professor ainda não foi aceite então não pode ser transferido!");
        }


        $contrado = FuncionariosControto::where('funcionarios_id', $professor->id)->where('status_contrato','activo')->first();
       
        $escola = Shcool::findOrFail($request->escola_id);


        if(!$contrado){
            $escola = Shcool::findOrFail($request->escola_id);

            $anolectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();

            // Criar contrato
            $create3 = new FuncionariosControto();
            $create3->funcionarios_id = $professor->id;
            $create3->documento =  $professor->codigo;
            $create3->salario = 0;
            $create3->subcidio = 0;
            $create3->subcidio_alimentacao = 0;
            $create3->subcidio_transporte = 0;

            $create3->subcidio_ferias = 0;
            $create3->subcidio_natal = 0;
            $create3->subcidio_abono_familiar = 0;
            $create3->falta_por_dia =  0;
            
            $create3->distrito_id = $escola->distrito_id;
            $create3->pais_id = $escola->pais_id;
            $create3->provincia_id = $escola->provincia_id;
            $create3->municipio_id = $escola->municipio_id;
    
            $create3->data_inicio_contrato = date("Y-m-d");
            $create3->data_final_contrato = date("Y-m-d");
            $create3->hora_entrada_contrato = "18:30";
            $create3->hora_saida_contrato = "18:30";
            $create3->cargo = $request->input('cargo');
            $create3->conta_bancaria = NULL;
            $create3->status_contrato = "activo";
            $create3->status = "activo";
            $create3->iban = NULL;
            $create3->numero_identifcador = $professor->codigo;

            $create3->cargo_geral = "professor";
            $create3->level = "4";
    
            $create3->departamento_id = 1;
            $create3->cargo_id = 1;
            $create3->clausula = NULL;
            $create3->nif = NULL;
            $create3->data_at = date("Y-m-d");
            $create3->ano_lectivos_id = $anolectivo->id;
            $create3->shcools_id = $escola->id;
            $create3->save();

            $meses = Mes::all();

            if($meses){
                foreach($meses as $mes){
                    $verificar = CartaoFuncionario::where([
                        ['funcionarios_id', '=', $professor->id],
                        ['codigo', '=', $professor->codigo],
                        ['mes_id', '=', $mes->id],
                        ['level', '=', '4'],
                        ['shcools_id', '=', $escola->id],
                        ['ano_lectivos_id', '=', $anolectivo->id],
                    ])->first();

                    if(!$verificar){

                        $newCreate = new CartaoFuncionario();

                        $newCreate->funcionarios_id = $professor->id;
                        $newCreate->mes_id = $mes->id;	
                        $newCreate->codigo = $professor->codigo;	
                        $newCreate->level = '4';	
                        $newCreate->ano_lectivos_id =  $anolectivo->id;    
                        $newCreate->shcools_id =  $escola->id;	
                        $newCreate->status  = 'Nao pago';
                        
                        $newCreate->save();
                    }
                }
            }

            
            if (!empty($request->file('documento'))) {
                $image = $request->file('documento');
                $imageNameBI = time() .'.'. $image->extension();
                $image->move(public_path('assets/arquivos'), $imageNameBI);
            }else{
                $imageNameBI = Null;
            }


            TransferenciaEscolaProfessor::create([
                'professor_id' => $request->professor_id,
                'org_shcools_id' => $escola->id,
                'des_shcools_id' => $request->escola_id,
                'status' => "processo",
                'documento' => $imageNameBI,
                'motivo' => $request->motivo,
                'user_id' => Auth::user()->id
            ]);

            return redirect()->route('web.transferencia-escola-professores')->with("message", "Transferência Realizada com sucesso!");

        }else{
            if($contrado->shcools_id ==  $escola->id){
                return redirect()->route('web.transferencia-escola-professores')->with("danger", "Esta transferência não pode ser realizada, este professor já faz parte desta escola!");
            }
        }
    
    }
    
    
    
    /*** PROVINCIAL */
    
    public function listProvincial()
    {
        $user = auth()->user();
        
        if(!$user->can('read: transeferencia professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $transferencias = TransferenciaEscolaProfessor::with('user')->with('professor')->with('origem')->with('destino')->get();
        
        $headers = [
            "titulo" => "Transferências",
            "descricao" => env('APP_NAME'),
            'transferencias' => $transferencias,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
        ];


        return view('sistema.direccao-provincial.lista-transferencia', $headers);

    }

    public function indexProvincial($id = null)
    {
        $user = auth()->user();
        
        if(!$user->can('read: transeferencia professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $professoroes = Professor::where('status', 'activo')->get();
        
        $headers = [
            "titulo" => "Transferência de Professores de Escola",
            "descricao" => env('APP_NAME'),
            'professoroes' => $professoroes,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            'id' => $id
        ];


        return view('sistema.direccao-provincial.transferencia-professor', $headers);

    }

    public function storeProvincial(Request $request)
    {      
        $user = auth()->user();
        
        if(!$user->can('create: transeferencia professor')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $request->validate(
            [
                'professor_id' => 'required',
                'motivo' => 'required',
                'escola_id' => 'required',
            ],
            [
                'professor_id.required' => "Senha Obrigatória",
                'motivo.required' => "Senha Obrigatória",
                'escola_id.required' => "Senha Obrigatória",
            ]
        ); 

        $professor = Professor::findOrFail($request->professor_id);

        if($professor->status == "desactivo"){
            return redirect()->route('web.transferencia-escola-professores')->with("danger", "A candidatura este professor ainda não foi aceite então não pode ser transferido!");
        }

        $contrado = FuncionariosControto::where('level', '4')->where('funcionarios_id', $professor->id)
        ->where('status','activo')
        ->where('status_contrato','activo')
        ->first();
       
        $escola_destino = Shcool::findOrFail($request->escola_id);
        $anolectivo_destino = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola_destino->id)->first();
        
        $escola = Shcool::findOrFail($request->escola_id);
        $anolectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();

        if(!$contrado){
            // Criar contrato
            $create3 = new FuncionariosControto();
            $create3->funcionarios_id = $professor->id;
            $create3->documento = $professor->codigo;
            $create3->salario = 0;
            $create3->subcidio = 0;
            $create3->subcidio_alimentacao = 0;
            $create3->subcidio_transporte = 0;

            $create3->subcidio_ferias = 0;
            $create3->subcidio_natal = 0;
            $create3->subcidio_abono_familiar = 0;
            $create3->falta_por_dia =  0;
            
            $create3->pais_id = $escola_destino->pais_id;
            $create3->provincia_id = $escola_destino->provincia_id;
            $create3->municipio_id = $escola_destino->municipio_id;
            $create3->distrito_id = $escola_destino->distrito_id;
    
            $create3->data_inicio_contrato = date("Y-m-d");
            $create3->data_final_contrato = date("Y-m-d");
            $create3->hora_entrada_contrato = "18:30";
            $create3->hora_saida_contrato = "18:30";
            $create3->cargo = '4';
            $create3->conta_bancaria = NULL;
            $create3->status_contrato = "activo";
            $create3->status = "activo";
            $create3->iban = NULL;
            $create3->numero_identifcador = $professor->codigo;

            $create3->cargo_geral = "professor";
            $create3->level = "4";
    
            $create3->departamento_id = 2;
            $create3->cargo_id =  5;
            $create3->clausula = NULL;
            $create3->nif = NULL;
            $create3->data_at = date("Y-m-d");
            $create3->ano_lectivos_id = $anolectivo_destino->id;
            $create3->shcools_id = $escola_destino->id;
            $create3->save();

            $meses = Mes::all();

            if($meses){
                foreach($meses as $mes){
                    $verificar = CartaoFuncionario::where([
                        ['funcionarios_id', '=', $professor->id],
                        ['codigo', '=', $professor->codigo],
                        ['mes_id', '=', $mes->id],
                        ['level', '=', '4'],
                        ['shcools_id', '=', $escola_destino->id],
                        ['ano_lectivos_id', '=', $anolectivo_destino->id],
                    ])->first();

                    if(!$verificar){

                        $newCreate = new CartaoFuncionario();

                        $newCreate->funcionarios_id = $professor->id;
                        $newCreate->codigo = $professor->codigo;
                        $newCreate->mes_id = $mes->id;	
                        $newCreate->level = '4';	
                        $newCreate->ano_lectivos_id =  $anolectivo_destino->id;    
                        $newCreate->shcools_id =  $escola_destino->id;	
                        $newCreate->status  = 'Nao pago';
                        
                        $newCreate->save();
                    }
                }
            }

            if (!empty($request->file('documento'))) {
                $image = $request->file('documento');
                $imageNameBI = time() .'.'. $image->extension();
                $image->move(public_path('assets/arquivos'), $imageNameBI);
            }else{
                $imageNameBI = Null;
            }

            TransferenciaEscolaProfessor::create([
                'professor_id' => $request->professor_id,
                'org_shcools_id' => NULL,
                'des_shcools_id' => $request->escola_id,
                'status' => "processo",
                'documento' => $imageNameBI,
                'motivo' => $request->motivo,
                'user_id' => Auth::user()->id
            ]);

            Alert::success("Bom Trabalho", "A Transferência do Professor realizada com sucesso!");
            return redirect()->back();

        }else{
            if($contrado->shcools_id ==  $escola->id){
                Alert::warning("Informação", "Esta transferência não pode ser realizada, este professor já faz parte desta escola!!");
                return redirect()->back();
            }else{
                    
                $create3 = FuncionarioContratoCopia::create([
                    "funcionarios_id" => $contrado->funcionarios_id,
                    "documento" => $contrado->documento,
                    "salario" => $contrado->salario,
                    "subcidio" => $contrado->subcidio,
                    "subcidio_alimentacao" => $contrado->subcidio_alimentacao,
                    "subcidio_transporte" => $contrado->subcidio_transporte,
        
                    "subcidio_ferias" => $contrado->subcidio_ferias,
                    "subcidio_natal" => $contrado->subcidio_natal,
                    "subcidio_abono_familiar" => $contrado->subcidio_abono_familiar,
                    "falta_por_dia" => $contrado->falta_por_dia,
                    
                    "pais_id" => $contrado->pais_id,
                    "provincia_id" => $contrado->provincia_id,
                    "municipio_id" => $contrado->municipio_id,
                    "distrito_id" => $contrado->distrito_id,
            
                    "data_inicio_contrato" => $contrado->data_inicio_contrato,
                    "data_final_contrato" => $contrado->data_final_contrato,
                    "hora_entrada_contrato" => $contrado->hora_entrada_contrato,
                    "hora_saida_contrato" => $contrado->hora_saida_contrato,
                    "cargo" => $contrado->cargo,
                    "conta_bancaria" => $contrado->conta_bancaria,
                    "status_contrato" => $contrado->status_contrato,
                    "status" => $contrado->status,
                    "iban" => $contrado->iban,
                    "numero_identifcador" => $contrado->numero_identifcador,
        
                    "cargo_geral" => $contrado->cargo_geral,
                    "level" => $contrado->level,
            
                    "departamento_id" => $contrado->departamento_id,
                    "cargo_id" => $contrado->cargo_id,
                    "clausula" => $contrado->clausula,
                    "nif" => $contrado->nif,
                    "data_at" => $contrado->data_at,("Y-m-d"),
                    "ano_lectivos_id" => $contrado->ano_lectivos_id,
                    "shcools_id" => $contrado->shcools_id,
                ]);
                
                $meses = Mes::all();

                if($meses){
                    foreach($meses as $mes){
                        $verificar = CartaoFuncionarioCopia::where([
                            ['funcionarios_id', '=', $professor->id],
                            ['mes_id', '=', $mes->id],
                            ['level', '=', 4],
                            ['shcools_id', '=', $escola->id],
                            ['ano_lectivos_id', '=', $anolectivo->id],
                        ])->first();
    
                        if(!$verificar){
    
                            $newCreate = new CartaoFuncionarioCopia();
    
                            $newCreate->funcionarios_id = $professor->id;
                            $newCreate->mes_id = $mes->id;	
                            $newCreate->level = 4;	
                            $newCreate->ano_lectivos_id =  $anolectivo->id;    
                            $newCreate->shcools_id =  $escola->id;	
                            $newCreate->status  = 'Nao pago';
                            
                            $newCreate->save();
                        }
                    }
                }
                
                $update_contrato = FuncionariosControto::findOrFail($contrado->id);
                $update_contrato->pais_id = $escola_destino->pais_id;
                $update_contrato->provincia_id = $escola_destino->provincia_id;
                $update_contrato->municipio_id = $escola_destino->municipio_id;
                $update_contrato->distrito_id = $escola_destino->distrito_id;
                $update_contrato->cargo = $contrado->cargo_id;
                $update_contrato->status_contrato = "desactivo";
                $update_contrato->status = "desactivo";
                $update_contrato->cargo_geral = "professor";
                $update_contrato->level = "4";
    
                $update_contrato->departamento_id = $contrado->departamento_id;
                $update_contrato->cargo_id =  $contrado->cargo_id;
                $update_contrato->ano_lectivos_id = $anolectivo_destino->id;
                $update_contrato->shcools_id = $escola_destino->id;
                
                $update_contrato->update();
                
                $update_cartao = CartaoFuncionario::where([
                    ['funcionarios_id', '=', $professor->id],
                    ['level', '=', 4],
                ])->get();
                
                if($update_cartao){
                    foreach($update_cartao as $item){
                        $update = CartaoFuncionario::find($item->id);
                        $update->ano_lectivos_id =  $anolectivo_destino->id;    
                        $update->shcools_id =  $escola_destino->id;	
                        $update->update();
                    }
                }
                
                if (!empty($request->file('documento'))) {
                    $image = $request->file('documento');
                    $imageNameBI = time() .'.'. $image->extension();
                    $image->move(public_path('assets/arquivos'), $imageNameBI);
                }else{
                    $imageNameBI = Null;
                }
    
                TransferenciaEscolaProfessor::create([
                    'professor_id' => $request->professor_id,
                    'org_shcools_id' => $contrado->shcools_id,
                    'des_shcools_id' => $request->escola_id,
                    'status' => "processo",
                    'documento' => $imageNameBI,
                    'motivo' => $request->motivo,
                    'user_id' => Auth::user()->id
                ]);
            }
            
        }
        
        Alert::success("Bom Trabalho", "A Transferência do Professor realizada com sucesso!");
        return redirect()->back();

    
    }
    
    
    
}
