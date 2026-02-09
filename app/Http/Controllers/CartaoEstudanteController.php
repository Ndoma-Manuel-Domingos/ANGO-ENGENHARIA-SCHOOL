<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\Servico;
use App\Models\web\estudantes\CartaoEstudante;
use App\Models\web\estudantes\Estudante;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CartaoEstudanteController extends Controller
{
    use TraitHelpers;
    use TraitChavesSaft;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // confirmacao estudante
    public function emissao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: cartao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.cartoes.emitir', $headers);
    }

    // confirmacao estudante
    public function buscar(Request $request)
    {
        $request->validate([
            'matricula' => 'required|string'
        ]);

        $estudante = Estudante::with(['matricula.curso'])
            ->where('numero_processo', $request->matricula)
            ->first();

        if (!$estudante) {
            return response()->json(['message' => 'Estudante não encontrado'], 404);
        }

        if ($estudante) {
            return response()->json(['redirect' => route('web.estudante.carregar-foto', Crypt::encrypt($estudante->id))]);
        }
    }


    // Salvar foto do estudante
    public function salvarFoto(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|string'
        ]);

        $estudante = Estudante::findOrFail($id);
        $image = $request->foto;

        // remover prefixo data:image/...;base64,
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            $image = substr($image, strpos($image, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, jpeg, gif
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['error' => 'Tipo de imagem não suportado'], 422);
            }
        } else {
            return response()->json(['error' => 'Imagem em formato inválido'], 422);
        }

        $image = base64_decode($image);
        $imageName = 'estudante_' . $estudante->id . '_' . time() . '.' . $type;

        Storage::disk('public')->put("estudantes/" . $imageName, $image);

        $estudante->image = "estudantes/" . $imageName;
        $estudante->save();

        return response()->json(['success' => true, 'foto' => $estudante->image]);
    }

    // confirmacao estudante
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: cartao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = Matricula::with(['classe_at', 'classe', 'turno', 'curso', 'estudante'])
            ->where('ano_lectivos_id', '=', $this->anolectivoActivo())
            ->where('shcools_id', '=', $this->escolarLogada())
            ->get();

        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),

            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => $matriculas
        ];

        return view('admin.cartoes.index', $headers);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('create: cartao')) {
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $matriculas = $request->estudante_id;

        $escola = Shcool::with('ensino')->findOrFail($this->escolarLogada());

        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $director = Director::where('level', '4')->where('instituicao_id', $escola->id)->first();
        $ano = AnoLectivo::findOrFail($this->anolectivoActivo());
        $servico = Servico::where('servico', 'Propinas')->where('shcools_id', $this->escolarLogada())->where('shcools_id', $this->escolarLogada())->first();

        $headers = [
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
            "usuario" => User::findOrFail(Auth::user()->id),
            "matriculas" => $matriculas,
            "servico" => $servico,
            "director" => $director,
            "ano" => $ano,
        ];


        $pdf = \PDF::loadView('downloads.estudantes.cartao', $headers)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('cartao.pdf');
    }

    // extrato mes a pagar activa

    public function activarMesPagar($mes, $est)
    {
        $estudante = Estudante::findOrFail($est);
        $mes = CartaoEstudante::with(["controle_periodio", "estudante", "servico", "ano"])->findOrFail($mes);
        $mes->status = "Nao Pago"; //pagar
        $mes->update();

        return redirect()->route('web.sistuacao-financeiro', Crypt::encrypt($estudante->id));
    }

    public function activarMesNaoPagar($mes, $est)
    {
        $estudante = Estudante::findOrFail($est);
        $mes = CartaoEstudante::with(["controle_periodio", "estudante", "servico", "ano"])->findOrFail($mes);
        $mes->status = "Pago";
        $mes->update();

        return redirect()->route('web.sistuacao-financeiro', Crypt::encrypt($estudante->id));
    }

    public function activarMesDivida($mes, $est)
    {
        $estudante = Estudante::findOrFail($est);
        $mes = CartaoEstudante::with(["controle_periodio", "estudante", "servico", "ano"])->findOrFail($mes);
        $mes->status = "divida";
        $mes->update();

        return redirect()->route('web.sistuacao-financeiro', Crypt::encrypt($estudante->id));
    }
}
