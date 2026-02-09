<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Comunicado;
use App\Models\Distrito;
use App\Models\FormaPagamento;
use App\Models\Municipio;
use App\Models\Notificacao;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use phpseclib\Crypt\RSA;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\salas\Banco;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use App\Models\web\turnos\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SiteController extends Controller
{
    //
    use TraitHelpers;
    use TraitChavesSaft;

    public function footer($req_id)
    {
        $shcools = Shcool::where('req_id', $req_id)->first();

        if ($shcools) {

            $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $shcools->id)->first();

            if ($ano_lectivo) {

                $cursos = AnoLectivoCurso::where('shcools_id', $shcools->id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->with(['curso', 'coordenador', 'faculdade', 'candidatura'])
                    ->get();
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }

        return [
            "cursos" => $cursos,
            "shcools" => $shcools,
        ];
    }

    public function home(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        if ($shcools) {
            $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $shcools->id)->first();

            if ($ano_lectivo) {
                $comunicados = Comunicado::with(['user', 'escola', 'ano'])
                    ->where('tipo_comunicado', 'comunicado')
                    ->where('shcools_id', $shcools->id)
                    ->orderBy('id', 'desc')
                    ->get();

                $noticias = Comunicado::with(['user', 'escola', 'ano'])
                    ->where('tipo_comunicado', 'noticia')
                    ->where('shcools_id', $shcools->id)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }


        $head = [
            "title" => "Home | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
            "comunicados" => $comunicados,
            "noticias" => $noticias,
        ];

        return view('site.home', $head, $this->footer($request->req_id));
    }


    public function cursos(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        $head = [
            "title" => "Cursos Disponíveis | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
        ];

        return view('site.cursos', $head, $this->footer($request->req_id));
    }

    public function cursos_detalhe(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        if ($shcools) {
            $curso = AnoLectivoCurso::with(['curso', 'coordenador', 'faculdade', 'candidatura'])->findOrFail($request->curso_id);

            $turmas = Turma::selectRaw('MIN(id) as id, classes_id') // Pegamos um ID único por classe
                ->with(['classe', 'disciplinas']) // Relacionamentos
                ->where('cursos_id', $curso->curso->id)
                ->where('shcools_id', $shcools->id)
                ->groupBy('classes_id')
                ->get();
        }

        $head = [
            "title" => "Detalhes do Curso | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
            "shcools" => $shcools,
            "curso" => $curso,
            "turmas" => $turmas,
        ];

        return view('site.detalhe-curso', $head, $this->footer($request->req_id));
    }

    public function noticia_detalhe(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        $comunicado = Comunicado::with(['user', 'escola', 'ano'])->findOrFail($request->id_detalhe);

        $head = [
            "title" => "Detalhes da Notícia | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
            "comunicado" => $comunicado
        ];

        return view('site.detalhe-noticia', $head, $this->footer($request->req_id));
    }

    public function candidaturas(Request $request)
    {
        session()->forget('sessaoPlano');

        $head = [
            "title" => "Detalhes da Notícia | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
        ];

        return view('site.candidaturas', $head, $this->footer($request->req_id));
    }

    public function formulario_comprovativo(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        if (!$shcools) {
            return redirect()->route('login');
        }

        $head = [
            "title" => "Preencher seus dados cuidadosamente | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
            "shcools" => $shcools,
        ];

        return view('site.formulario-comprovativo', $head, $this->footer($request->req_id));
    }

    public function comprovativo(Request $request)
    {
  
        session()->forget('sessaoPlano');
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
     
            $request->validate([
                'referencia' => 'required|exists:tb_pagamentos,ficha',
                'comprovativo' => 'required|file|mimes:pdf,jpeg,jpg,png|max:5120',
            ]);
    
            if (!empty($request->file('comprovativo'))) {
                $image2 = $request->file('comprovativo');
                $imageNameCT = time() . '2.' . $image2->extension();
                $image2->move(public_path('assets/arquivos'), $imageNameCT);
            } else {
                $imageNameCT = Null;
            }
         
            $matricula = Matricula::where('ficha', $request->referencia)->first();
            
            if($matricula) {
                $matricula->comprovativo = true;
                $matricula->comprovativo_url = $imageNameCT;
                $matricula->update();
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

        Alert::success("Bom Trabalhos", "✅ Comprovativo enviado com sucesso! Agradecemos o envio. A sua submissão será analisada e validada pela equipe administrativa no prazo de até 24 horas.");
        return redirect()->back();
   
    }

    public function formulario(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        if ($shcools) {
            $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $shcools->id)->first();

            if ($ano_lectivo) {
                $cursos = AnoLectivoCurso::where('shcools_id', $shcools->id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->with(['curso', 'coordenador', 'faculdade', 'candidatura'])
                    ->get();

                $turnos = AnoLectivoTurno::where('shcools_id', $shcools->id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->with(['turno'])
                    ->get();

                $classes = AnoLectivoClasse::where('shcools_id', $shcools->id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->with(['classe'])
                    ->get();
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }

        $distritos = Distrito::get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();
        $paises = Paise::get();

        $head = [
            "title" => "Preencher seus dados cuidadosamente | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
            "distritos" => $distritos,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "paises" => $paises,

            "cursos" => $cursos,
            "turnos" => $turnos,
            "classes" => $classes,
        ];

        return view('site.formulario-inscricao', $head, $this->footer($request->req_id));
    }

    public function formulario_post(Request $request)
    {
        $ip = $request->ip(); // Captura o IP do usuário (pode ser usado como identificador)
        $bilheite = $request->input('bilheite'); // Supondo que o bilheite é obrigatório no formulário
        $key = "tentativas_{$bilheite}"; // Chave única por usuário

        $request->validate([
            'nome' => 'required',
            'sobre_nome' => 'required',
            'genero' => 'required',
            'estado_civil' => 'required',
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'municipio_id' => 'required',
            'municipio_id' => 'required',
            'data_nascimento' => 'required',
            'bilheite' => 'required',
            'classe_anterior_id' => 'required',
            'classe_actual_id' => 'required',
            'cursos_id' => 'required',
            'turnos_id' => 'required',
            'shcools_id' => 'required',

            'anexo_bilhete' => 'required',
            'anexo_certificado' => 'required',
        ], [
            "nome.required" => "*Obrigatório*",
            "sobre_nome.required" => "*Obrigatório*",
            "genero.required" => "*Obrigatório*",
            "estado_civil.required" => "*Obrigatório*",
            "pais_id.required" => "*Obrigatório*",
            "provincia_id.required" => "*Obrigatório*",
            "municipio_id.required" => "*Obrigatório*",
            "distrito_id.required" => "*Obrigatório*",
            "bilheite.required" => "*Obrigatório*",
            "data_nascimento.required" => "*Obrigatório*",

            "classe_anterior_id.required" => "*Obrigatório*",
            "classe_actual_id.required" => "*Obrigatório*",
            "cursos_id.required" => "*Obrigatório*",
            "turnos_id.required" => "*Obrigatório*",
            "shcools_id.required" => "*Obrigatório*",

            'anexo_bilhete.required' => 'Campo Obrigatório',
            'anexo_certificado.required' => 'Campo Obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            // Verifica se o usuário está bloqueado
            if (Cache::has("bloqueado_{$bilheite}")) {
                Alert::warning("ERRO", "Você atingiu o limite de tentativas. Tente novamente em 30 minutos!");
                return redirect()->back()->with('message', 'Você atingiu o limite de tentativas. Tente novamente em 30 minutos!');
            }

            // Obtém a quantidade de tentativas armazenadas
            $tentativas = Cache::get($key, 0);
            $code = time();

            $tentativas++;

            Cache::put($key, $tentativas, now()->addMinutes(30)); // Armazena por 30 min

            if ($tentativas >= 10) {
                Cache::put("bloqueado_{$bilheite}", true, now()->addMinutes(30)); // Bloqueia
                Alert::warning("ERRO", "Você errou 3 vezes. Aguarde 30 minutos para tentar novamente!");
                return redirect()->back()->with('message', 'Você errou 3 vezes. Aguarde 30 minutos para tentar novamente!');
            }

            $escola = Shcool::findOrFail($request->shcools_id);

            if (!$escola) {
                Alert::warning("Opps!", "Este processo não pôde ser realizado porque nenhuma escola válida foi selecionada. Por favor, entra em contacto com a administração da escola!");
                return redirect()->back()->with('warning', 'Este processo não pôde ser realizado porque nenhuma escola válida foi selecionada. Por favor, entra em contacto com a administração da escola!');
            }

            $ano_lectivo = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola->id)->first();
            
            $banco = Banco::where('status', 'activo')->where('shcools_id', $escola->id)->first();
            if(!$banco) {
                $banco = Banco::where('shcools_id', $escola->id)->first();
            }
            
            $forma_pagamento = FormaPagamento::where("sigla_tipo_pagamento", "MB")->first();

            if ($ano_lectivo) {

                $verificar_existencia_vagas_curso = AnoLectivoCurso::where('cursos_id', $request->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->first();

                if ($verificar_existencia_vagas_curso && $verificar_existencia_vagas_curso->total_vagas > 0) {
                    
                    $turma = Turma::where('classes_id', $request->classe_actual_id)
                        ->where('cursos_id', $request->cursos_id)
                        ->where('ano_lectivos_id', $ano_lectivo->id)
                    ->first();
               
                    if($turma) {
                            
                        $servico_operacional = Servico::where("servico", "Matricula")->where("shcools_id", $escola->id)->first();
                        
                        if( $servico_operacional ) {
                        
                            // DADOS DA MATRICULA start
                            
                            $nacionalidade = Paise::find($request->input('pais_id'));
                            $naturalidade = Provincia::find($request->input('provincia_id'));
        
                            $totalCandidaturas = Matricula::where("shcools_id", $request->escola)
                                ->where("ano_lectivos_id", $ano_lectivo->id)
                                ->where("tipo", 'candidatura')
                                ->where("ano_lectivo_global_id", $this->anolectivoActivoGlobal())
                                ->count();
        
                            $create = Estudante::create([
                                "documento" => $code,
                                "nome" => $request->nome,
                                "sobre_nome" => $request->sobre_nome,
                                "nome_completo" => $request->nome . " " . $request->sobre_nome,
                                "registro" => 'nao_confirmado',
                                "nascimento" => $request->data_nascimento,
                                "genero" => $request->genero,
                                "estado_civil" => $request->estado_civil,
                                "nacionalidade" => $nacionalidade->name,
                                "pais_id" => $request->pais_id,
                                "provincia_id" => $request->provincia_id,
                                "municipio_id" => $request->municipio_id,
                                "distrito_id" => $request->distrito_id,
                                "dificiencia" =>  "Nenhuma",
                                "bilheite" => $request->bilheite,
                                "whatsapp" => null,
                                "instagram" => null,
                                "facebook" => null,
                                "email" => null,
                                "telefone_estudante" => $request->telefone,
                                "telefone_pai" => "Opcional",
                                "telefone_mae" => "Opcional",
                                "endereco" => "Opcional",
                                "naturalidade" => $naturalidade->nome,
                                "shcools_id" => $escola->id,
                                'saldo' => '0',
                                "ano_lectivos_id" => $ano_lectivo->id,
                                "ano_lectivo_global_id" => $this->anolectivoActivoGlobal(),
                            ]);
        
                            $createM = Matricula::create([
                                "documento" => $create->documento,
                                "status_matricula" => 'nao_confirmado',
                                "ficha" => $code,
                                "at_classes_id" => $request->input('classe_anterior_id'),
                                "classes_id" => $request->input('classe_actual_id'),
                                "cursos_id" => $request->input('cursos_id'),
                                "turnos_id" => $request->input('turnos_id'),
                                "tipo" => "candidatura", // confirmação , Matricula, candidautura
                                "status" => "em analise",  // $request->input('situacao_estudante'), // Novo ou repitente
                                "condicao" => "normal", // Novo ou repitente
                                "data_at" => date("Y-m-d"),
                                "shcools_id" => $escola->id,
                                "ano_lectivos_id" => $ano_lectivo->id,
                                "estudantes_id" => $create->id,
                                'media' => NULL,
                                'cursos_primeira_opcao_id' => $request->cursos_segunda_opcao_id,
                                'cursos_segunda_opcao_id' => $request->cursos_terceira_opcao_id,
                                'pais_id' => $escola->pais_id,
                                'provincia_id' => $escola->provincia_id,
                                'municipio_id' => $escola->municipio_id,
                                'distrito_id' => $escola->distrito_id,
                                'level' => '2',
                                "funcionarios_id" => 2,
                                "numeracao" => $totalCandidaturas + 1,
                                "ano_lectivo_global_id" => $this->anolectivoActivoGlobal(),
                            ]);
        
                            $text = "O estudante {$request->nome} {$request->sobre_nome}, enviou uma candidatura.";
                            $text2 = "O Sr(a) acabou de aprovar a matricula de um estudante";
        
                            Notificacao::create([
                                'user_id' => $create->id,
                                'destino' => NULL,
                                'type_destino' => 'escola',
                                'type_enviado' => 'estudante',
                                'notificacao' => $text,
                                'notificacao_user' => $text2,
                                'status' => '0',
                                'model_id' => $createM->id,
                                'model_type' => "canditura",
                                'shcools_id' => $escola->id
                            ]);
        
                            $create->conta_corrente = "31.1.2.1." . $create->id;
                            $create->update();
        
                            if (!empty($request->file('anexo_bilhete'))) {
                                $image = $request->file('anexo_bilhete');
                                $imageNameBI = time() . '1.' . $image->extension();
                                $image->move(public_path('assets/arquivos'), $imageNameBI);
                            } else {
                                $imageNameBI = Null;
                            }
        
                            if (!empty($request->file('anexo_certificado'))) {
                                $image2 = $request->file('anexo_certificado');
                                $imageNameCT = time() . '2.' . $image2->extension();
                                $image2->move(public_path('assets/arquivos'), $imageNameCT);
                            } else {
                                $imageNameCT = Null;
                            }
        
                            Arquivo::create([
                                'model_id' => $create->id,
                                'codigo' => $code,
                                'model_type' => 'estudante',
                                'level' => '0',
                                'certificado' => $imageNameCT,
                                'bilheite' => $imageNameBI,
                                'atestado' => NULL,
                                'outros' => NULL,
                            ]);
                            
                            $reduzir_candidatura = AnoLectivoCurso::findOrFail($verificar_existencia_vagas_curso->id);
                            $reduzir_candidatura->total_vagas -=1;
                            $reduzir_candidatura->update();
                    
                            
                            // DADOS DA MATRICULA end
                        
                            // DADOS DO PAGAMENTO start
                            $contarFactura = Pagamento::where('tipo_factura', 'FT')
                                ->where('factura_ano', $ano_lectivo->serie ?? date("Y"))
                                ->where('shcools_id', $escola->id)
                                ->count();
            
                            $ultimoRecibo = Pagamento::where('tipo_factura', 'FT')
                                ->where('factura_ano', $ano_lectivo->serie ?? date("Y"))
                                ->where('shcools_id', $escola->id)
                                ->latest()
                                ->first();
            
                            if (!$ultimoRecibo) {
                                $hashAnterior = "";
                            } else {
                                $hashAnterior = $ultimoRecibo->hash;
                            }
                                    
                            //Manipulação de datas: data actual
                            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                            
                            $numeroFactura = $contarFactura + 1;
    
                            $rsa = new RSA(); //Algoritimo RSA
            
                            $privatekey = $this->pegarChavePrivada();
                            $publickey = $this->pegarChavePublica();
            
                            // Lendo a private key
                            $rsa->loadKey($privatekey);
            
                            $codigo_designacao_factura = "EAV";
            
                            // Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                            // Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; 
            
                            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "FR {$codigo_designacao_factura}{$ano_lectivo->serie}/{$numeroFactura}" . ';' . number_format($turma->valor_matricula, 2, ".", "") . ';' . $hashAnterior;
            
                            // HASH
                            $hash = 'sha1'; // Tipo de Hash
                            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
            
                            //ASSINATURA
                            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
            
                            // Lendo a public key
                            $rsa->loadKey($publickey);
                          
            
                            $valor_extenso = $this->valor_por_extenso($turma->valor_matricula);
                            
                            $createP = Pagamento::create([
                                "pago_at" => strtolower($servico_operacional->servico),
                                "servicos_id" => $servico_operacional->id,
                                "caixa_at" => $servico_operacional->contas,
                                "ficha" => $code,
                                "status" => "Confirmado",
                                "desconto" => 0,
                                'tipo_servico_detalhe' => 'unico',
                                "valor" => $turma->valor_matricula,
                                "valor2" => $turma->valor_matricula,
                                "multa" => 0,
                                "data_at" => $this->data_sistema(),
                                "mensal" => $this->mesecompleto(),
                                "funcionarios_id" => NULL,
                                "estudantes_id" => $create->id,
                                
                                'valor_entregue' => $turma->valor_matricula,
                                
                                'banco_id' => $banco->id,
                                'caixa_id' => NULL,
                                
                                "numero_factura" => $numeroFactura,
                                'troco' => 0,
                                'data_vencimento' => date("Y-m-d"),
                                'data_disponibilizacao' => date("Y-m-d"),
                                'factura_ano' => $ano_lectivo->serie ?? date("Y"),
                                'prazo' => 0,
                                'data_vencimento' => date("Y-m-d"),
                                "model" => 'estudante',
                                "ano_lectivos_id" => $ano_lectivo->id,
                                "tipo_factura" =>  'FT',
                                "tipo_pagamento" => $forma_pagamento->sigla_tipo_pagamento,
                                "pagamento_id" => $forma_pagamento->id,
                                'next_factura' => "FT {$codigo_designacao_factura}{$ano_lectivo->serie}/{$numeroFactura}",
                                'observacao' => "",
                                'referencia' => $code,
                                'shcools_id' => $escola->id,
            
                                'retificado' => 'N',
                                'convertido_factura' => 'N',
                                'factura_divida' => 'Y',
                                'anulado' => 'N',
            
                                'moeda' => 'AOA',
                                'valor_extenso' => $valor_extenso,
                                'valor_cash' => 0,
                                'valor_multicaixa' => 0,
                                'texto_hash' => $plaintext,
                                'hash' => base64_encode($signaturePlaintext),
                                'nif_cliente' => $create->bilheite,
                                'conta_corrente_cliente' => $create->conta_corrente,
                                'total_iva' => 0,
                                'total_incidencia' => $turma->valor_matricula,
                                'quantidade' => 1,
                            ]);
            
                            // calcudo do total de incidencia
                            // ________________ valor total _____________
                            $valorBase = $turma->valor_matricula * 1;
                            // calculo do iva
                         
                            $valorIva = ($servico_operacional->taxa->taxa / 100) * $valorBase;
            
                            $desconto = ($turma->valor_matricula * 1) * (0 / 100);
            
                            DetalhesPagamentoPropina::create([
                                'code' => $code,
                                'mes_id' => "NULL",
                                'valor_incidencia' => $valorBase,
                                'desconto' => 0,
                                'total_pagar' => $valorBase + $valorIva,
                                'desconto_valor' => $desconto,
                                'valor_iva' => 0,
                                'taxa_id' => $servico_operacional->taxa->taxa,
                                'mes' => date("M"),
                                'model_id' => $create->id,
                                'multa' => 0,
                                'quantidade' => 1,
                                'funcionarios_id' => NULL,
                                'preco' => $valorBase,
                                'status' => 'Pago',
                                'servicos_id' => $servico_operacional->id,
                                'date_att' => $this->data_sistema(),
                                'ano_lectivos_id' => $ano_lectivo->id,
                                'shcools_id' => $escola->id,
                                'pagamentos_id' => $createP->id,
                            ]);
                             // DADOS DO PAGAMENTO end
                        }else {
                            Alert::warning("Opps!", "Serviço de matrícula não disponível nesta turma, verifica esta serviço de mátricula se existe!");
                            return redirect()->back()->with('warning', 'Serviço de matrícula não disponível nesta turma, verifica esta serviço de mátricula se existe');
                        }
                    
                    }else {
                        Alert::warning("Opps!", "Vagas indisponível no momento, problema com as turmas, configura uma turma por favor!");
                        return redirect()->back()->with('warning', 'Vagas indisponível no momento, problema com as turmas, configura uma turma por favor');
                    }
                }else {
                    Alert::warning("Opps!", "Infelizmente, as vagas para este curso já foram preenchidas neste ano. Agradecemos imensamente pelo seu interesse e preferência. Esperamos contar com você na próxima edição!");
                    return redirect()->back()->with('warning', 'Infelizmente, as vagas para este curso já foram preenchidas neste ano. Agradecemos imensamente pelo seu interesse e preferência. Esperamos contar com você na próxima edição!!');
                }
                        
            } else {
                Alert::warning("Opps!", "Este processo não pôde ser concluído porque ainda não há um ano letivo ativo no sistema. Por favor, entra em contacto com a administração da escola.!");
                return redirect()->back()->with('warning', "Este processo não pôde ser concluído porque ainda não há um ano letivo ativo no sistema. Por favor, entra em contacto com a administração da escola.!");
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

        // Se a candidatura for bem-sucedida, limpa as tentativas
        Cache::forget($key);
        Cache::forget("bloqueado_{$bilheite}");

        Alert::success("Bom Trabalhos", "Candidatura Enviada com sucesso!");
        return redirect()->route('site.ficha-factura-candidatura', [$createP->ficha, $createM->id]);
    }

    public function consultar_candidatura(Request $request)
    {
        session()->forget('sessaoPlano');

        $shcools = Shcool::where('req_id', $request->req_id)->first();

        $estudante = null;

        if ($request->bilheite != null) {
            $estudante = Estudante::where('bilheite', $request->bilheite)
                ->join('tb_matriculas', 'tb_estudantes.id', '=', 'tb_matriculas.estudantes_id')
                ->join('tb_cursos', 'tb_matriculas.cursos_id', '=', 'tb_cursos.id')
                ->join('tb_classes', 'tb_matriculas.classes_id', '=', 'tb_classes.id')
                ->join('tb_turnos', 'tb_matriculas.turnos_id', '=', 'tb_turnos.id')
                ->join('tb_shcools', 'tb_estudantes.shcools_id', '=', 'tb_shcools.id')
                ->select('tb_matriculas.id AS idMatricula', 'tb_shcools.nome AS escola', 'tb_matriculas.ficha', 'tb_matriculas.numeracao', 'tb_matriculas.status_matricula', 'tb_estudantes.created_at', 'tb_estudantes.nome', 'tb_estudantes.sobre_nome', 'tb_estudantes.numero_processo', 'tb_classes.classes', 'tb_cursos.curso', 'tb_turnos.turno')
                ->first();
        }

        $head = [
            "title" => "Consultar minha candidatura | ANGOENGENHARIA E SISTEMAS INFORMÁTICOS",
            "estudante" => $estudante,
            "numero" => $request->bilheite,
            "shcools" => $shcools,
        ];

        return view('site.consultar-candidatura', $head, $this->footer($request->req_id));
    }

    public function ficha_candidatura($ficha)
    {
        $matricula = Matricula::where('ficha', $ficha)
            ->first();

        if (!$matricula) {
            Alert::warning("ERRO", "Não foi possível fazer o dawnload da sua ficha de candidatura!");
            return redirect()->back()->with('message', 'Não foi possível fazer o dawnload da sua ficha de candidatura!');
        }

        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $classe_at = Classe::findOrFail($matricula->at_classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);
        $estudante = Estudante::findOrFail($matricula->estudantes_id);

        $ano_lectivo = AnoLectivo::findOrFail($matricula->ano_lectivos_id);
        $escola = Shcool::findOrFail($matricula->shcools_id);

        $headers = [
            'matricula' => $matricula,
            'estudante' => $estudante,
            'turno' => $turno,
            'curso' => $curso,
            'classe' => $classe,
            'classe_at' => $classe_at,
            'ano_lectivo' => $ano_lectivo,
            'escola' => $escola,
        ];

        $pdf = \PDF::loadView('web.candidaturas.ficha-candidatura-original', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream("ficha-candidatura-original-{$estudante->nome}-{$estudante->sobre_nome}.pdf");
    }

    public function ficha_factura_candidatura($ficha, $id)
    {
       $matricula = Matricula::with([
           'ano_lectivo',
           'classe_at',
           'classe',
           'turno',
           'curso',
           'estudante'
        ])->findOrFail($id);
    
        $pagamento = Pagamento::where('ficha', $ficha)
            ->with(['servico'])
        ->first();

        $items = DetalhesPagamentoPropina::with('servico')->where('pagamentos_id', $pagamento->id)->get();
     
                
        $total_incidencia_ise = 0;
        $total_retencao_ise = 0;
        $total_iva_ise = 0;

        $total_incidencia_nor = 0;
        $total_retencao_nor = 0;
        $total_iva_nor = 0;

        $total_incidencia_out = 0;
        $total_retencao_out = 0;
        $total_iva_out = 0;
        
        
        foreach ($items as $item){
            
            $servico = Servico::join('tb_taxas', 'tb_servicos.taxa_id', '=', 'tb_taxas.id')
                ->select('tb_servicos.id', 'sigla', 'taxa', 'contas', 'tb_servicos.servico')
                ->findOrFail($item->servicos_id);
        
            if ($servico->sigla == 'NOR'){
                if($item->preco > 20000){
                    if($servico->tipo == 'S'){
                        $total_retencao_nor = $total_retencao_nor + ($item->valor_incidencia * (6.5 / 100));
                    }
                }
                $total_incidencia_nor = $total_incidencia_nor + $item->valor_incidencia;
                $total_iva_nor = $total_iva_nor + $item->valor_iva;
            }
            
            if ($servico->sigla == 'ISE'){
                if($item->preco > 20000){
                    if($servico->tipo == 'S'){
                        $total_retencao_ise = $total_retencao_ise + ($item->valor_incidencia * (6.5 / 100));
                    } 
                }
                $total_incidencia_ise = $total_incidencia_ise + $item->valor_incidencia;
                $total_iva_ise = $total_iva_ise + $item->valor_iva;
            }
            
            if ($servico->sigla == 'RED'){
                if($item->preco > 20000){
                    if($servico->tipo == 'S'){
                        $total_retencao_out = $total_retencao_out + ($item->valor_base * (6.5 / 100));
                    } 
                }
               
                $total_incidencia_out = $total_incidencia_out + $item->valor_incidencia;
                $total_iva_out = $total_iva_out + $item->valor_iva;
            }
        }
        
        $total_retencao = $total_retencao_ise + $total_retencao_out + $total_retencao_nor;
           
        $estudante = Estudante::find($matricula->estudantes_id);
            
        $curso = Curso::findOrFail($matricula->cursos_id);
        $classe = Classe::findOrFail($matricula->classes_id);
        $turno = Turno::findOrFail($matricula->turnos_id);
        
        $escola = Shcool::with('ensino')->findOrFail($matricula->shcools_id);

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $headers = [
            "escola" => $escola,
          "logotipo" => $temLogotipo ? $logotipoPath : null,
            'matricula' => $matricula,
            'pagamento' => $pagamento,
            'estudante' => $estudante,
            
            'detalhes' => $items,
            'curso' => $curso,
            'classe' => $classe,
            'turno' => $turno,
            
            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,
            "total_retencao_nor" => $total_retencao_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,
            "total_retencao_ise" => $total_retencao_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "total_retencao_out" => $total_retencao_out,
            
            "total_retencao" => $total_retencao,
            
            'ano_lectivo' => AnoLectivo::findOrFail($matricula->ano_lectivos_id),
        ];

        $pdf = \PDF::loadView('site.ficha-factura-candidatura', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream("Factura-matricula-{$pagamento->next_factura}.pdf");
    }

}
