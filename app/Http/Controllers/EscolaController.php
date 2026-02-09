<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\User;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EscolaController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // informacoes
    public function informacaoGeraisEscolar()
    {

        $user = auth()->user();

        if (!$user->can('read: escola')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $usuario = User::findOrFail(Auth::user()->id);

        $escola = Shcool::with(['pais', 'provincia', 'municipio', 'ensino'])->findOrFail($this->escolarLogada());
        $director = Director::where('instituicao_id', $this->escolarLogada())->where('level', '4')->first();

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "titulo" => "Informações da Escola",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
            "director" => $director,
        ];

        return view('admin.informacoes-escolares.index', $headers);
    }

    public function informacaoGeraisEscolarEditar($id)
    {

        $user = auth()->user();

        if (!$user->can('update: escola')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $escola = Shcool::with('municipio', 'pais', 'provincia')->findOrFail(Crypt::decrypt($id));
        $director =  Director::where('level', '4')->where('instituicao_id', $escola->id)->first();

        $usuario = User::findOrFail(Auth::user()->id);

        $paises = Paise::where('id', 6)->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $ensinos = Ensino::get();
        $distritos = Distrito::get();

        $headers = [
            "titulo" => "Configurar Informações da Escola",
            "descricao" => env('APP_NAME'),
            "escola" => $escola,
            "director" => $director,
            "usuario" => $usuario,
            "paises" => $paises,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "ensinos" => $ensinos,
            "distritos" => $distritos,
        ];

        return view('admin.informacoes-escolares.edit', $headers);
    }

    public function informacaoGeraisEscolarUpdate(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('update: escola')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            'director' => 'required',
            'genero' => 'required',
            'estado_civil' => 'required',
            'bilheite' => 'required',
            'nome' => 'required',
            'documento' => 'required',
            'ensino_id' => 'required',
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'numero_escola' => 'required',
            'pais_escola' => 'required',
        ]);

        $escola = Shcool::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            if (!empty($request->file('logotipo'))) {
                $image = $request->file('logotipo');
                $imageName = $escola->nome . '.' . $image->extension();
                $image->move(public_path('uploads/logos'), $imageName);
            } else {
                $imageName = Null;
            }
    
            if (!empty($request->file('logotipo2'))) {
                $image2 = $request->file('logotipo2');
                $imageName2 = $escola->nome . '.' . $image2->extension();
                $image2->move(public_path('uploads/logos'), $imageName2);
            } else {
                $imageName2 = Null;
            }
    
            if (!empty($request->file('logotipo_assinatura_director'))) {
                $image3 = $request->file('logotipo_assinatura_director');
                $imageName3 = $escola->nome . '.' . $image3->extension();
                $image3->move(public_path('uploads/logos'), $imageName3);
            } else {
                $imageName3 = Null;
            }
    
            if (!empty($request->file('logotipo_documentos'))) {
                $image4 = $request->file('logotipo_documentos');
                $imageName4 = time() . '.' . $image4->extension();
                $image4->move(public_path('uploads/logos'), $imageName4);
            } else {
                $imageName4 = Null;
            }
    
            $director = Director::findOrFail($request->director_id);
    
            $escola->nome = trim($request->input('nome'));
            $escola->cabecalho1 = $escola->cabecalho1; // "Editar O cabeçalho que vai aparecer nos documento o Ensino Primário";
            $escola->cabecalho2 = $escola->cabecalho2; // "Editar O cabeçalho que vai aparecer nos documento o Ensino Secundário";
            $escola->director = trim($request->input('director'));
            $escola->documento = trim($request->input('documento'));
            $escola->site = $request->site;
            $escola->sigla = $request->sigla;
            $escola->categoria = $request->sector;
            $escola->natureza = "exemplo Geral";
            $escola->ensino_id = $request->input('ensino_id');
            $escola->pais_id =  $request->input('pais_id');
            $escola->provincia_id = $request->input('provincia_id');
            $escola->municipio_id = $request->input('municipio_id');
            $escola->distrito_id = $request->input('distrito_id');
            $escola->endereco = $request->input('endereco');
            $escola->decreto = $request->decreto;
            $escola->agua = $request->agua;
            $escola->electricidade = $request->electricidade;
            $escola->cantina = $request->cantina;
            $escola->biblioteca = $request->biblioteca;
            $escola->campo_desportivo = $request->campo_desportivo;
            $escola->internet = $request->internet;
            $escola->farmacia = $request->farmacia;
            $escola->tipo_regime_id = $request->tipo_regime_id;
            $escola->zip = $request->zip;
            $escola->computadores = $request->computadores;
            $escola->laboratorio = $request->laboratorio;
            $escola->casas_banho = $request->casas_banho;
            $escola->transporte = $request->transporte;
            $escola->telefone1 = trim($request->input('telefone'));
            $escola->telefone2 = "000-000-000";
            $escola->telefone3 = "000-000-000";
            $escola->logotipo = NULL;
            $escola->logotipo2 = NULL;
            $escola->logotipo_assinatura_director = NULL;
            $escola->numero_escola = $request->numero_escola;
            $escola->tipo_cartao = $request->tipo_cartao;
            $escola->formato_cartao = $request->formato_cartao;
            $escola->extensao_cartao = $request->extensao_cartao;
    
            $escola->intervalo_pagamento_inicio = $request->intervalo_pagamento_inicio;
            $escola->intervalo_pagamento_final = $request->intervalo_pagamento_final;
    
            $escola->taxa_multa1 = $request->taxa_multa1;
            $escola->taxa_multa1_dia = $request->taxa_multa1_dia;
            $escola->taxa_multa2 = $request->taxa_multa2;
            $escola->taxa_multa2_dia = $request->taxa_multa2_dia;
            $escola->taxa_multa3 = $request->taxa_multa3;
            $escola->taxa_multa3_dia = $request->taxa_multa3_dia;
            $escola->cobranca_multas = $request->cobranca_multas;
            $escola->desconto_percentagem = $request->desconto_percentagem;
            $escola->email = $request->email;
    
            $escola->banco = $request->banco;
            $escola->conta = $request->conta;
            $escola->iban = $request->iban;
    
            $escola->logotipo = $imageName;
            $escola->logotipo2 = $imageName2;
            $escola->logotipo_assinatura_director = $imageName3;
            $escola->logotipo_documentos = $imageName4;
            $escola->impressora = $request->impressora;
            $escola->pais_escola = $request->pais_escola;
            $escola->tipo_avaliacao = $request->tipo_avaliacao;
            $escola->nota_maxima = $request->nota_maxima;
            $escola->nota_maxima_exame = $request->nota_maxima_exame;
    
            $director->nome = $request->director;
            $director->bilheite = $request->bilheite;
            $director->genero = $request->genero;
            $director->estado_civil = $request->estado_civil;
            $director->especialidade = $request->especialidade;
            $director->descricao = $request->descricao;
            $director->curso = $request->curso;
    
    
            $director->update();
            $escola->update();
              
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Dodos actualizados com sucesso');
        return redirect()->back();
    }

    public function privacidade()
    {
        $usuario = User::findOrFail(Auth::user()->id);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Configurar Informações da Escola",
            "descricao" => env('APP_NAME'),
            "usuario" => $usuario,
        ];

        return view('admin.informacoes-escolares.privacidade', $headers);
    }

    public function privacidadeUpdate(Request $request, $id)
    {
        $request->validate([
            'password_1' => 'required',
            'password_2' => 'required',
            'password_3' => 'required',
            'user' => 'required',
        ]);
        
        $usuario = User::findOrFail($id);
        
        try {
        
            DB::beginTransaction();
        
            if (!Hash::check($request->password_1, $usuario->password)) {
                Alert::warning('Atenção', 'Senha Actual Incorrecta');
                return redirect()->route('informacoes-escolares.privacidade');
            }
    
            if ($request->password_2 != $request->password_3) {
                Alert::warning('Atenção', 'As duas novas senhas não podem ser diferentes');
                return redirect()->route('informacoes-escolares.privacidade');
            }
    
            $usuario->password = Hash::make($request->password_2);
            $usuario->usuario = $request->user;
            $usuario->telefone = $request->telefone;
            $usuario->email = $request->email;
            $usuario->nome = $request->nome;
            $usuario->update();

        
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Credências actualizados com sucesso, Usar da Proxíma vez que Entrar no sistema');
        return redirect()->route('paineis.administrativo');
     
    }
}
