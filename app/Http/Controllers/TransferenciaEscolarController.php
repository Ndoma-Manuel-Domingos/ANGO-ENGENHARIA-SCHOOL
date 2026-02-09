<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\TransferenciaEscolar;
use App\Models\TransferenciasCopias\CartaoEstudanteCopia;
use App\Models\TransferenciasCopias\EstudantesTurmaCopia;
use App\Models\TransferenciasCopias\MatriculaCopia;
use App\Models\TransferenciasCopias\NotaPautaCopia;
use App\Models\TransferenciaTurma;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\Servico;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use App\Models\web\extensoes\Extensao;
use App\Models\web\financeiros\DetalhesPagamentoPropina;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use phpseclib\Crypt\RSA;



class TransferenciaEscolarController extends Controller
{
    //
    use TraitHelpers;
    use TraitChavesSaft;


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function list_turma(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencias = TransferenciaTurma::with('user')
            ->with('estudante')
            ->with('origem')
            ->with('destino')
            ->where('shcools_id', $this->escolarLogada())
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferências entre turmas",

            "descricao" => env('APP_NAME'),
            'transferencias' => $transferencias,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            'requests' => $request->all('transferencias', 'status'),
        ];


        return view('admin.transferencias-escolares.lista-transferencia-turmas', $headers);
    }


    public function list(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('read: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencias = TransferenciaEscolar::when(!$request->transferencias, function ($query, $value) {
            $query->where('des_shcools_id', $this->escolarLogada())
                ->orWhere('org_shcools_id', $this->escolarLogada());
        })
            ->when($request->transferencias, function ($query, $value) {
                if ($value == "enviadas") {
                    $query->where('org_shcools_id', $this->escolarLogada());
                } else if ($value == "recebidas") {
                    $query->where('des_shcools_id', $this->escolarLogada());
                }
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->with('user')
            ->with('estudante')
            ->with('origem')
            ->with(['destino', 'classe', 'curso', 'turno'])
            ->get();



        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferências",

            "descricao" => env('APP_NAME'),
            'transferencias' => $transferencias,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            'escola' => $this->escolarLogada(),
            'requests' => $request->all('transferencias', 'status'),
        ];


        return view('admin.transferencias-escolares.lista-transferencia', $headers);
    }

    public function rejeitar($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')  && !$user->can('read: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencia = TransferenciaEscolar::findOrFail($id);

        // rejeitada = sem ser processado
        //cancelada = em quanto processado
        if ($transferencia->des_shcools_id != $this->escolarLogada()) {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências não foi feita de esta escola!");
            return redirect()->back();
        }

        if ($transferencia->status == "rejeitada") {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências já foi rejeitada");
            return redirect()->back();
        } else {

            $transferencia->status = "rejeitada";

            $transferencia->update();

            Alert::success("Bom Trabalho", "Transferência Jeitada com sucesso");
            return redirect()->back();
        }
    }


    public function rejeitar_turma($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')  && !$user->can('read: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencia = TransferenciaTurma::findOrFail($id);

        if ($transferencia->status == "rejeitada") {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências já foi rejeitada");
            return redirect()->back();
        } else {

            $transferencia->status = "rejeitada";

            $transferencia->update();

            Alert::success("Bom Trabalho", "Transferência Jeitada com sucesso");
            return redirect()->back();
        }
    }

    public function cancelar($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')  && !$user->can('read: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencia = TransferenciaEscolar::findOrFail($id);

        // rejeitada = sem ser processado
        //cancelada = em quanto processado
        if ($transferencia->des_shcools_id != $this->escolarLogada()) {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências não foi feita de esta escola!");
            return redirect()->back();
        }

        if ($transferencia->status == "cancelar") {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências já foi cancelada");
            return redirect()->back();
        } else {

            $transferencia->status = "cancelar";

            $transferencia->update();

            Alert::success("Bom Trabalho", "Transferência Cancelada com sucesso");
            return redirect()->back();
        }
    }

    public function cancelar_turma($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')  && !$user->can('read: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencia = TransferenciaTurma::findOrFail($id);

        if ($transferencia->status == "cancelar") {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências já foi cancelada");
            return redirect()->back();
        } else {

            $transferencia->status = "cancelar";

            $transferencia->update();

            Alert::success("Bom Trabalho", "Transferência Cancelada com sucesso");
            return redirect()->back();
        }
    }


    public function aceitar($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')  && !$user->can('read: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $transferencia = TransferenciaEscolar::findOrFail($id);

        if ($transferencia->status == "aceita") {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências já foi aceite");
            return redirect()->back();
        }

        /**
         * Dados da eescola destino
         */
        // localizar a escola destino
        $escola_destino = Shcool::findOrFail($transferencia->des_shcools_id);
        //localizar ano lectivo activo desta escola
        $ano_lectivo_destino = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola_destino->id)->first();

        /**
         * Dados da eescola destino
         */
        // localizar a escola destino
        $escola_origem = Shcool::findOrFail($transferencia->org_shcools_id);
        //localizar ano lectivo activo desta escola
        $ano_lectivo_origem = AnoLectivo::where('status', 'activo')->where('shcools_id', $escola_origem->id)->first();

        // cartão
        // turma
        // notas
        // propinas
        $estudante = Estudante::findOrFail($transferencia->estudantes_id);
        $matricula = Matricula::where('ano_lectivos_id', $ano_lectivo_origem->id)->where('estudantes_id', $estudante->id)->first();

        if (!$matricula) {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido Estudante sem matricula!");
            return redirect()->back();
        }

        if (!$ano_lectivo_destino) {
            Alert::warning("Informação", "esta escola na qual esta a ser transferido o estudante não tem um ano lectivo activo cadastra uma no lectivo, e tenta novamente");
            return redirect()->back();
        }

        $notas = NotaPauta::where('ano_lectivos_id', $ano_lectivo_origem->id)->where('estudantes_id', $estudante->id)->get();

        $turma_antiga = EstudantesTurma::where('ano_lectivos_id', $ano_lectivo_origem->id)->where('estudantes_id', $estudante->id)->first();

        $cartaos = CartaoEstudante::where('ano_lectivos_id', $ano_lectivo_origem->id)->where('estudantes_id', $estudante->id)->get();

        $createMatriucula = MatriculaCopia::create([
            'status' => "transferencia",
            'data_at' => $matricula->data_at,
            'numero_estudante' => $matricula->numero_estudante,
            'ficha' => $matricula->ficha,
            'documento' => $matricula->documento,
            'status_matricula' => $matricula->status_matricula,
            'at_classes_id' => $matricula->at_classes_id,
            'classes_id' => $matricula->classes_id,
            'turnos_id' => $matricula->turnos_id,
            'cursos_id' => $matricula->cursos_id,
            'tipo' => $matricula->tipo,
            'condicao' => $matricula->condicao,
            'funcionarios_id' => $matricula->funcionarios_id,
            'numeracao' => $matricula->numeracao,
            'estudantes_id' => $matricula->estudantes_id,
            'ano_lectivos_id' => $matricula->ano_lectivos_id,
            'shcools_id' => $matricula->shcools_id,
            'ano_lectivo_global_id' => $matricula->ano_lectivo_global_id,
            'transferencia_id' => $transferencia->id,
        ]);

        if ($notas) {

            foreach ($notas as $nota) {
                $verificarNota = NotaPautaCopia::where([
                    ['turmas_id', $nota->turmas_id],
                    ['estudantes_id', $nota->estudantes_id],
                    ['ano_lectivos_id', $nota->ano_lectivos_id],
                    ['controlo_trimestres_id', $nota->controlo_trimestres_id],
                    ['disciplinas_id', $nota->disciplinas_id],
                ])->get();

                if (count($verificarNota) > 0) {
                } else {
                    $createNota = NotaPautaCopia::create([
                        'mac' => $nota->mac,
                        'npt' => $nota->npt,
                        'mt' => $nota->mt,
                        'mt1' => $nota->mt1,
                        'mt2' => $nota->mt2,
                        'mt3' => $nota->mt3,
                        'mfd' => $nota->mfd,
                        'ne' => $nota->ne,
                        'nr' => $nota->nr,
                        'rf' => $nota->rf,
                        'status' => $nota->status,
                        'turmas_id' => $nota->turmas_id,
                        'estudantes_id' => $nota->estudantes_id,
                        'funcionarios_id' => $nota->funcionarios_id,
                        'ano_lectivos_id' => $nota->ano_lectivos_id,
                        'controlo_trimestres_id' => $nota->controlo_trimestres_id,
                        'disciplinas_id' => $nota->disciplinas_id,
                        'descricao' => $nota->descricao,
                        'transferencia_id' => $transferencia->id,
                    ]);
                }
            }
        }

        if ($cartaos) {
            foreach ($cartaos as $cartao) {

                $verificarCartao = CartaoEstudanteCopia::where([
                    ['estudantes_id', $cartao->estudantes_id],
                    ['servicos_id', $cartao->servicos_id],
                    ['status', $cartao->status],
                    ['data_at', $cartao->data_at],
                    ['data_exp', $cartao->data_exp],
                    ['ano_lectivos_id', $cartao->ano_lectivos_id],
                ])->get();

                if (count($verificarCartao) == 0) {
                    CartaoEstudanteCopia::create([
                        'estudantes_id' => $cartao->estudantes_id,
                        'mes_id' => $cartao->mes_id,
                        'servicos_id' => $cartao->servicos_id,
                        'status' => $cartao->status,
                        'data_at' => $cartao->data_at,
                        'data_exp' => $cartao->data_exp,
                        'month_number' => $cartao->month_number,
                        'month_name' => $cartao->month_name,
                        'ano_lectivos_id' => $cartao->ano_lectivos_id,
                        'transferencia_id' => $transferencia->id,
                    ]);
                }
            }
        }

        if ($turma_antiga) {
            EstudantesTurmaCopia::create([
                'ordem' => $turma_antiga->ordem,
                'status' => $turma_antiga->status,
                'turmas_id' => $turma_antiga->turmas_id,
                'estudantes_id' => $turma_antiga->estudantes_id,
                'ano_lectivos_id' => $turma_antiga->ano_lectivos_id,
                'transferencia_id' => $transferencia->id,
            ]);
        }


        $contarFacturas = Pagamento::where([
            ['tipo_factura', '=', 'FR'],
            ['factura_ano', '=', date("Y")],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->count();

        $ultimoRecibo = Pagamento::where([
            ['tipo_factura', '=', 'FR'],
            ['factura_ano', '=', date("Y")],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->orderBy('id', 'DESC')
            ->first();

        /**
         * hashAnterior inicia vazio
         */
        if (!$ultimoRecibo) {
            $hashAnterior = "";
        } else {
            $hashAnterior = $ultimoRecibo->hash;
        }
        //Manipulação de datas: data actual
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $ano = date("Y");
        $numeroFactura = $contarFacturas + 1;

        $rsa = new RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "FR AGT{$ano}/{$numeroFactura}" . ';' . number_format(0, 2, ".", "") . ';' . $hashAnterior;


        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

        // Lendo a public key
        $rsa->loadKey($publickey);

        $valor_extenso = $this->valor_por_extenso(0);

        // actualizar dados da matricula do estudante
        // $estudante = Estudante::findOrFail($transferencia->estudantes_id);

        $extensoes = Extensao::where('tipo', 'estudantes')->where('shcools_id', $escola_destino->id)->first();

        $ficha = time();

        $servico = Servico::with('taxa')->where('servico', 'Matricula')->where('shcools_id', $this->escolarLogada())->first();

        $estudante->ano_lectivos_id = $this->anolectivoActivo();
        $estudante->shcools_id = $this->escolarLogada();
        $estudante->numero_processo = "{$extensoes->extensao} {$estudante->id}/{$extensoes->sufix}";
        $estudante->update();

        // adaptar os dados da matricula dele para a matricula nova
        $matricula_update = Matricula::findOrFail($matricula->id);
        $matricula_update->numero_estudante = "{$extensoes->extensao} {$estudante->id}/{$extensoes->sufix}";
        $matricula_update->status = "Transferido";
        $matricula_update->at_classes_id = $transferencia->classes_id;
        $matricula_update->classes_id = $transferencia->classes_id;
        $matricula_update->cursos_id = $transferencia->cursos_id;
        $matricula_update->turnos_id = $transferencia->turnos_id;
        $matricula_update->ano_lectivos_id = $ano_lectivo_destino->id;
        $matricula_update->shcools_id = $escola_destino->id;
        $matricula_update->ano_lectivo_global_id = $this->anolectivoActivoGlobal();
        $matricula_update->update();

        //alterar a turma antiga
        $turma_antiga_update = EstudantesTurma::findOrFail($turma_antiga->id);
        $turma_antiga_update->delete();

        foreach ($notas as $nota) {
            $update_nota = NotaPauta::findOrFail($nota->id);
            $update_nota->delete();
        }

        // adaptar cartado
        foreach ($cartaos as $cartao) {
            $update_cartao = CartaoEstudante::findOrFail($cartao->id);
            $update_cartao->delete();
        }

        // Matricula::create([
        //     "documento" => $matricula->documento,
        //     "ficha" => $ficha,
        //     'numero_estudante' => "{$extensoes->extensao} {$estudante->id}/{$extensoes->sufix}",
        //     "at_classes_id" => $transferencia->classes_id,
        //     "classes_id" => $transferencia->classes_id,
        //     "cursos_id" => $transferencia->cursos_id,
        //     "turnos_id" => $transferencia->turnos_id,
        //     "tipo" => "matricula", // confirmação , Matricula
        //     "status" => "Transferido", // Novo ou repitente
        //     "condicao" => "Paga", // paga ou nao paga propinas
        //     "data_at" => $this->data_sistema(),
        //     "status_matricula" => 'confirmado',
        //     "ano_lectivos_id" => $this->anolectivoActivo(),
        //     "estudantes_id" => $estudante->id,
        //     "shcools_id" => $this->escolarLogada(),
        //     "funcionarios_id" => Auth::user()->id,
        //     "ano_lectivo_global_id" => $this->anolectivoActivoGlobal()
        // ]);

        $create = Pagamento::create([
            "pago_at" => strtolower($servico->servico),
            "servicos_id" => $servico->id,
            "caixa_at" => $servico->contas,
            "ficha" => $ficha,
            "status" => "Confirmado",
            "desconto" => 0,
            "valor" => 0,
            "multa" => 0,
            "data_at" => $this->data_sistema(),
            "mensal" => $this->mesecompleto(),
            "funcionarios_id" => Auth::user()->id,
            "estudantes_id" => $estudante->id,
            'valor_entregue' => 0,
            "numero_factura" => $contarFacturas + 1,
            'troco' => 0,
            'data_vencimento' => date("Y-m-d"),
            'data_disponibilizacao' =>  date("Y-m-d"),
            'factura_ano' => $ano,
            'prazo' => 0,
            'data_vencimento' => date("Y-m-d"),
            "model" => 'estudante',
            "ano_lectivos_id" => $this->anolectivoActivo(),
            "tipo_factura" =>  "FR",
            "tipo_pagamento" => "NU",
            'next_factura' => "FR AGT{$ano}/{$numeroFactura}",
            'observacao' => "",
            'referencia' => time(),
            'shcools_id' => $this->escolarLogada(),
            'retificado' => 'N',
            'convertido_factura' => 'N',
            'factura_divida' => 'N',
            'anulado' => 'N',
            'moeda' => 'AOA',
            'valor_extenso' => $valor_extenso,
            'valor_cash' => 0,
            'valor_multicaixa' => 0,
            'texto_hash' => $plaintext,
            'hash' => base64_encode($signaturePlaintext),
            'nif_cliente' => $estudante->bilheite,
            'total_iva' => 0,
            'total_incidencia' => 0,
            'quantidade' => 0,
        ]);

        DetalhesPagamentoPropina::create([
            'total_pagar' => 0,
            'code' => $ficha,
            'mes_id' => "NULL",
            'valor_incidencia' => 0,
            'valor_iva' => 0,
            'taxa_id' => $servico->taxa->taxa,
            'mes' => NULL,
            'model_id' => $estudante->id,
            'quantidade' => 0,
            'funcionarios_id' => Auth::user()->id,
            'preco' => 0,
            'status' => 'Nao Pago',
            'servicos_id' => $servico->id,
            'date_att' => $this->data_sistema(),
            'ano_lectivos_id' => $this->anolectivoActivo(),
            'shcools_id' => $this->escolarLogada(),
            'pagamentos_id' => $create->id,
        ]);

        // $turma_escola_local = Turma::where('shcools_id', $escola_destino->id)->where('cursos_id', $transferencia->cursos_id)->where('classes_id', $transferencia->classes_id)->first();
        // if($turma_escola_local){

        //     $total_estudante_turma_nova = EstudantesTurma::where('turmas_id', $turma_escola_local->id)->count() + 1;

        //     //alterar a turma antiga
        //     $turma_antiga_update = EstudantesTurma::findOrFail($turma_antiga->id);
        //     $turma_antiga_update->turmas_id = $turma_escola_local->id;
        //     $turma_antiga_update->ano_lectivos_id = $turma_escola_local->ano_lectivos_id;
        //     $turma_antiga_update->ordem = "EST Nº ".  $total_estudante_turma_nova . "/" .$turma_escola_local->turma;
        //     $turma_antiga_update->update();
        // }

        // if($ano_lectivo_destino){
        //     // localizar uma turma nesta esocla com os dados do estudante transferido
        //     $turma_escola_local = Turma::where('shcools_id', $escola_destino->id)->where('cursos_id', $matricula->cursos_id)->where('classes_id', $matricula->classes_id)->first();

        //     if($turma_escola_local){

        //         $total_estudante_turma_nova = EstudantesTurma::where('turmas_id', $turma_escola_local->id)->count() + 1;

        //         //alterar a turma antiga
        //         $turma_antiga_update = EstudantesTurma::findOrFail($turma_antiga->id);
        //         $turma_antiga_update->turmas_id = $turma_escola_local->id;
        //         $turma_antiga_update->ano_lectivos_id = $turma_escola_local->ano_lectivos_id;
        //         $turma_antiga_update->ordem = "EST Nº ".  $total_estudante_turma_nova . "/" .$turma_escola_local->turma;
        //         $turma_antiga_update->update();

        //         //adaptar os dados da estudantil dele para nova escola
        //         $extensoes = Extensao::where('tipo', 'estudantes')->where('shcools_id', $escola_destino->id)->first();

        //         $estudante->ano_lectivos_id = $ano_lectivo_destino->id;
        //         $estudante->shcools_id = $escola_destino->id;
        //         $estudante->numero_processo = "{$extensoes->extensao} {$estudante->id}/{$extensoes->sufix}";
        //         $estudante->update();

        //         // adaptar os dados da matricula dele para a matricula nova
        //         $matricula_update = Matricula::findOrFail($matricula->id);

        //         $matricula_update->numero_estudante = "{$extensoes->extensao} {$estudante->id}/{$extensoes->sufix}";
        //         $matricula_update->status = "Transferido";
        //         $matricula_update->ano_lectivos_id = $ano_lectivo_destino->id;
        //         $matricula_update->shcools_id = $escola_destino->id;
        //         $matricula_update->update();

        //         foreach($notas as $nota){
        //             $update_nota = NotaPauta::findOrFail($nota->id);

        //             $update_nota->ano_lectivos_id = $ano_lectivo_destino->id;
        //             $update_nota->funcionarios_id = Auth::user()->id;
        //             $update_nota->turmas_id = $turma_escola_local->id;
        //             $update_nota->update();

        //         }

        //         // adaptar cartado

        //         foreach($cartaos as $cartao){
        //             $update_cartao = CartaoEstudante::findOrFail($cartao->id);
        //             $update_cartao->ano_lectivos_id = $ano_lectivo_destino->id;
        //             $update_cartao->update();
        //         }


        //     }else{
        //         // caso esta escola não tem uma turma com este dados da transferencia do estudante

        //         $sala = AnoLectivoSala::where('shcools_id', $escola_destino->id)->where('ano_lectivos_id', $ano_lectivo_destino->id)->first();

        //         // criamos uma nota turma
        //         $create_turma = Turma::create([
        //             'turma' => "NOVA TURMA",
        //             'numero_maximo' => "60",
        //             'status' => "activo",
        //             'shcools_id' => $escola_destino->id,
        //             'classes_id' => $matricula->classes_id,
        //             'turnos_id' => $matricula->turnos_id,
        //             'cursos_id' => $matricula->cursos_id,
        //             'salas_id' => $sala->id,
        //             'ano_lectivos_id' => $ano_lectivo_destino->id,
        //         ]);


        //         // criar turma estudantes
        //         $create_turma_estudante = EstudantesTurma::create([
        //             'ordem' => "EST Nº 1/" .$create_turma->turma,
        //             'status' => 'activo',
        //             'turmas_id' => $create_turma->id,
        //             'estudantes_id' => $estudante->id,
        //             'ano_lectivos_id' => $ano_lectivo_destino->id,
        //         ]);

        //     }

        // }else{
        //     Alert::warning("Informação", "esta escola na qual esta a ser transferido o estudante não tem um ano lectivo activo cadastra uma no lectivo, e tenta novamente");
        //     return redirect()->back();
        // }

        $transferencia->status = "aceita";

        $transferencia->update();

        Alert::success("Bom Trabalho", "Transferência Aceitada com sucesso");
        return redirect()->back();
    }



    public function aceitar_turma($id)
    {
        $user = auth()->user();

        if (!$user->can('update: estado')  && !$user->can('read: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $transferencia = TransferenciaTurma::findOrFail($id);

        if ($transferencia->status == "aceita") {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências já foi aceite");
            return redirect()->back();
        }

        /**
         * Dados da eescola destino
         */
        // localizar a escola destino
        $escola_destino = Turma::findOrFail($transferencia->des_turmas_id);

        $escola_origem = Turma::findOrFail($transferencia->org_turmas_id);


        if ($escola_destino->cursos_id == $escola_origem->cursos_id) {
            Alert::warning("Informação", "Cursos diferentes!");
            return redirect()->back();
        }

        if ($escola_destino->classes_id == $escola_origem->classes_id) {
            Alert::warning("Informação", "Classes diferentes!");
            return redirect()->back();
        }


        $estudante = Estudante::findOrFail($transferencia->estudantes_id);

        $notas = NotaPauta::where('turmas_id', $escola_origem->id)->where('estudantes_id', $estudante->id)->get();

        foreach ($notas as $nota) {
            $update_nota = NotaPauta::findOrFail($nota->id);
            $update_nota->turmas_id = $escola_destino->id;
            $update_nota->update();
        }

        $turma_antiga = EstudantesTurma::where('turmas_id', $escola_origem->id)->where('estudantes_id', $estudante->id)->first();

        $total_estudante_turma_nova = EstudantesTurma::where('turmas_id', $escola_destino->id)->count() + 1;

        //alterar a turma antiga
        $turma_antiga_update = EstudantesTurma::findOrFail($turma_antiga->id);
        $turma_antiga_update->turmas_id = $escola_destino->id;
        $turma_antiga_update->ordem = "EST Nº " .  $total_estudante_turma_nova . "/" . $escola_destino->turma;
        $turma_antiga_update->update();


        $transferencia->status = "aceita";

        $transferencia->update();

        Alert::success("Bom Trabalho", "Transferência Aceitada com sucesso");
        return redirect()->back();
    }

    public function eliminar($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }


        $transferencia = TransferenciaEscolar::findOrFail($id);
        // rejeitada = sem ser processado
        //cancelada = em quanto processado
        if ($transferencia->des_shcools_id != $this->escolarLogada()) {
            Alert::warning("Informação", "Infelizmente este processo não pode ser concluido por esta transferências não foi feita de esta escola!");
            return redirect()->back();
        } else {
            $transferencia->delete();
            Alert::success("Bom Trabalho", "Transferência Eliminado com sucesso");
            return redirect()->back();
        }
    }


    public function eliminar_turma($id)
    {
        $user = auth()->user();

        if (!$user->can('delete: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $transferencia = TransferenciaTurma::findOrFail($id);
        $transferencia->delete();
        Alert::success("Bom Trabalho", "Transferência Eliminado com sucesso");
        return redirect()->back();
    }

    public function visualizar($id)
    {
        $user = auth()->user();

        if (!$user->can('read: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $transferencia = TransferenciaEscolar::with('user')
            ->with('classe')
            ->with('curso')
            ->with('turno')
            ->with('origem')
            ->with('destino')->findOrFail($id);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferências",

            "descricao" => env('APP_NAME'),
            'transferencia' => $transferencia,
            'escola' => $this->escolarLogada(),
        ];


        return view('admin.transferencias-escolares.visualizar', $headers);
    }

    public function visualizar_turma($id)
    {
        $user = auth()->user();

        if (!$user->can('read: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }



        $transferencia = TransferenciaTurma::with('user')
            ->with('estudante.matricula.classe')
            ->with('estudante.matricula.curso')
            ->with('estudante.matricula.turno')
            ->with('origem')
            ->with('destino')->findOrFail($id);

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferências",

            "descricao" => env('APP_NAME'),
            'transferencia' => $transferencia,
            'escola' => $this->escolarLogada(),
        ];


        return view('admin.transferencias-escolares.visualizar_turma', $headers);
    }

    public function imprimir($id)
    {

        $transferencia = TransferenciaEscolar::with('user')
            ->with('classe')
            ->with('curso')
            ->with('turno')
            ->with('origem')
            ->with('destino')
            ->findOrFail($id);



        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,

            'transferencia' => $transferencia,
            'ano_lectivo' => AnoLectivo::findOrFail($this->anolectivoActivo()),
        ];

        $pdf = \PDF::loadView('admin.transferencias-escolares.imprimir', $headers)->setPaper('A4', 'portrait');
        return $pdf->stream('imprimir-transferências.pdf');
    }


    public function index($id = null)
    {
        $user = auth()->user();

        if (!$user->can('create: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudantes = Matricula::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['status_matricula', '=', 'confirmado'],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('estudante')
            ->get();


        $estudante = null;

        if (Crypt::decrypt($id)) {
            $estudante = Estudante::where('id', Crypt::decrypt($id))->where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->first();

            if (!$estudante) {
                Alert::warning("Informação", "Infelzmente não podemos realizar este processo porque o estudante ainda não esta condirmado neste ano lectivo!");
                return redirect()->back();
            }

            $matricula = Matricula::where([
                ['estudantes_id', '=', $estudante->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->first();

            if (!$matricula) {
                Alert::warning("Informação", "Infelzmente não podemos realizar este processo porque o estudante ainda não esta inscrito neste ano lectivo!");
                return redirect()->back();
            }
        }




        $cursos = Curso::get();
        $classes = Classe::get();
        $turnos = Turno::get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferência de Estudante de Escola",

            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'estudantes' => $estudantes,
            'escolas' => Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get(),
            "cursos" => $cursos,
            "classes" => $classes,
            "turnos" => $turnos,
        ];


        return view('admin.transferencias-escolares.transferencia-escola', $headers);
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: transeferencia estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        $request->validate([
            "escola_id" => 'required',
            "motivo" => 'required',
            "condicao" => 'required',
            "documento" => 'required',
            "estudante_id" => 'required',
        ]);

        $estudante = Estudante::findOrFail($request->estudante_id);
        $escola = Shcool::findOrFail($request->escola_id);

        if ($estudante->shcools_id ==  $escola->id) {
            Alert::warning("informação", "Essa transferência não pode ser realizada porque o estudante já se encontra nesta escola!");
            return redirect()->back();
        }

        if (!empty($request->file('documento'))) {
            $image = $request->file('documento');
            $imageNameBI = time() . '.' . $image->extension();
            $image->move(public_path('assets/arquivos'), $imageNameBI);
        } else {
            $imageNameBI = Null;
        }


        $ano = AnoLectivo::findOrFail($this->anolectivoActivo());

        TransferenciaEscolar::create([
            'estudantes_id' => $request->estudante_id,
            'org_shcools_id' => $this->escolarLogada(),
            'des_shcools_id' => $request->escola_id,
            'condicao' => $request->condicao,
            'status' => "processo",
            'documento' => $imageNameBI,
            'motivo' => $request->motivo,
            'user_id' => Auth::user()->id,

            'data_final' => $ano->final,

            'cursos_id' => $request->cursos_id,
            'classes_id' => $request->classes_id,
            'turnos_id' => $request->turnos_id,
        ]);

        Alert::success("Bom Trabalho", "Transferência Realizada com sucesso!");
        return redirect()->back();
    }

    public function index_turma($id = null)
    {
        $user = auth()->user();

        if (!$user->can('create: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudantes = Matricula::where([
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['status_matricula', '=', 'confirmado'],
            ['shcools_id', '=', $this->escolarLogada()],
        ])
            ->with('estudante')
            ->get();

        $estudante = null;

        if (Crypt::decrypt($id)) {
            $estudante = Estudante::where('id', Crypt::decrypt($id))->where('registro', 'confirmado')->where('shcools_id', $this->escolarLogada())->first();

            if (!$estudante) {
                return redirect()->route('pesquisa-sem-resultado');
            }

            $matricula = Matricula::where([
                ['estudantes_id', '=', $estudante->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ['status_matricula', '=', 'confirmado'],
                ['shcools_id', '=', $this->escolarLogada()],
            ])->first();

            if (!$matricula) {
                return redirect()->route('pesquisa-sem-resultado');
            }
        }





        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "titulo" => "Transferência de Estudante entre Turma",

            "descricao" => env('APP_NAME'),
            'estudante' => $estudante,
            'estudantes' => $estudantes,
            'turmas' => Turma::where('status', 'activo')->where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', $this->anolectivoActivo())->get(),
        ];

        return view('admin.transferencias-escolares.transferencia-turma', $headers);
    }


    public function store_turma(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: distribuicao de estudante')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $request->validate([
            "turmas_id" => 'required',
            "motivo" => 'required',
            // "documento" => 'required',
            "estudante_id" => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $estudante = Estudante::findOrFail($request->estudante_id);
            // turma destino
            $turma = Turma::findOrFail($request->turmas_id);

            /** se o estudante tem uma turma */
            $verificarturmasEstudante = EstudantesTurma::where([
                ['estudantes_id', '=', $estudante->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->first();

            $matricula = Matricula::where([
                ['estudantes_id', '=', $estudante->id],
                ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ])->first();

            if (!$matricula) {
                Alert::warning("informação", "Estudante sem turmas antes!");
                return redirect()->back();
            }

            if (!$verificarturmasEstudante) {

                // verificar se as turmas tem as mesma classes e cursos caso não, não podemos realizar a transferencia
                if (!($turma->cursos_id ==  $matricula->cursos_id and $turma->classes_id ==  $matricula->classes_id)) {
                    Alert::warning("informação", "Por favor verifica a classe e curso do estudante e a turma onde esta sendo transferido não coincidem os dados, tem que ser uma turma com o mesmo curso do estudante!");
                    return redirect()->back();
                }

                $turma->adicionar_estudante_na_turma($turma->id, $estudante->id);
            } else {

                // turma origem
                $turma_origem = Turma::findOrFail($verificarturmasEstudante->turmas_id);

                // verificar se as turmas tem as mesma classes e cursos caso não, não podemos realizar a transferencia
                if (!($turma->cursos_id ==  $turma_origem->cursos_id and $turma->classes_id ==  $turma_origem->classes_id)) {
                    Alert::warning("informação", "Por favor verifica a classe e curso do estudante e a turma onde esta sendo transferido não coincidem os dados, tem que ser uma turma com o mesmo curso do estudante!");
                    return redirect()->back();
                }

                /** se esta na mesma turma */
                $verificarturmas = EstudantesTurma::where([
                    ['estudantes_id', '=', $estudante->id],
                    ['turmas_id', '=', $turma->id],
                    ['ano_lectivos_id', '=', $this->anolectivoActivo()],
                ])->first();

                if ($verificarturmas) {
                    Alert::warning("informação", "Estudante já encontra-se nesta turma!");
                    return redirect()->back();
                }

                // turma onde estudante esta vamos alterar os seus dados para outra turma
                $turma_localizado = EstudantesTurma::findOrFail($verificarturmasEstudante->id);
                $turma_localizado->turmas_id = $turma->id;
                $turma_localizado->update();

                // verificar as pautas verificar as pautas dele

                $notas = NotaPauta::with(['disciplina'])->where('ano_lectivos_id',  $this->anolectivoActivo())->where('estudantes_id', $estudante->id)->where('turmas_id', $turma_origem->id)->get();

                if ($notas) {
                    foreach ($notas as $nota) {
                        $up = NotaPauta::findOrFail($nota->id);
                        $up->turmas_id = $turma->id;
                        $up->update();
                    }
                }

                if (!empty($request->file('documento'))) {
                    $image = $request->file('documento');
                    $imageNameBI = time() . '.' . $image->extension();
                    $image->move(public_path('assets/arquivos'), $imageNameBI);
                } else {
                    $imageNameBI = Null;
                }

                TransferenciaTurma::create([
                    'estudantes_id' => $request->estudante_id,
                    'org_turmas_id' => $verificarturmasEstudante->turmas_id,
                    'des_turmas_id' => $turma->id,
                    'status' => "processo",
                    'documento' => $imageNameBI,
                    'motivo' => $request->motivo,
                    'user_id' => Auth::user()->id,
                    'shcools_id' => $this->escolarLogada()
                ]);
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

        Alert::success("Bom Trabalho", "Transferência Realizada com sucesso!");
        return redirect()->back();
    }
}
