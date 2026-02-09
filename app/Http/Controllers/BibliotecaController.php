<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\biblioteca\Autor;
use App\Models\web\biblioteca\DevolucaoEmprestimoLivro;
use App\Models\web\biblioteca\Editora;
use App\Models\web\biblioteca\EmprestimoLivro;
use App\Models\web\biblioteca\GeneroLivro;
use App\Models\web\biblioteca\Livro;
use App\Models\web\biblioteca\TipoMeterial;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class BibliotecaController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function controle(Request $request)
    {
        $user = auth()->user();


            
       
        $headers = [
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Painel da Biblioteca",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
            "ano_lectivo_id" => AnoLectivo::findOrFail($this->anolectivoActivo()),
            "generos" => GeneroLivro::where('shcools_id', $this->escolarLogada())->get(),
            "tipo_materias" => TipoMeterial::where('shcools_id', $this->escolarLogada())->get(),
            "editoras" => Editora::where('shcools_id', $this->escolarLogada())->get(),
            "autores" => Autor::where('shcools_id', $this->escolarLogada())->get(),
            "livros" => Livro::where('shcools_id', $this->escolarLogada())->get(),
            "emprestimos" => EmprestimoLivro::where('shcools_id', $this->escolarLogada())->get(),
            "devolucoes" => DevolucaoEmprestimoLivro::where('shcools_id', $this->escolarLogada())->get(),
        ];

        return view('admin.bibliotecas.dashboard', $headers);
    }

}
