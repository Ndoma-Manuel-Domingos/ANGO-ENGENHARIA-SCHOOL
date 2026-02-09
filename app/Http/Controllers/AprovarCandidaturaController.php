<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Notificacao;
use App\Models\Shcool;
use App\Models\User;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Matricula;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\extensoes\Extensao;
use App\Models\web\turnos\Turno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class AprovarCandidaturaController extends Controller
{
    use TraitHelpers; 

    public function aprovarCandidatura($id)
    {
        $user = auth()->user();
        
        if(!$user->can('read: estudante')  && !$user->can('read: matricula')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $estudante = Estudante::with('municipio', 'provincia')->findOrFail(Crypt::decrypt($id));

        $matricula = Matricula::where([
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $this->anolectivoActivo()],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->first();

        if(!$matricula){
            return redirect()->route('web.estudantes-inscricao');
        }

        

        $documentos = Arquivo::where('model_id', $estudante->id)->where('model_type', 'estudante')->first();
        
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "estudante" => $estudante,
            "matricula" => $matricula,
            "titulo" => "Aprovar Candidatura",
            "descricao" => env('APP_NAME'),
            "documentos" => $documentos,
            'curso' => Curso::findOrFail($matricula->cursos_id),
            'turno' => Turno::findOrFail($matricula->turnos_id),
            'classe' => Classe::findOrFail($matricula->classes_id),

            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.estudantes.aprovar-candidatura', $headers);
    }

    public function finalizarAprovarCandidatura($id)
    {

        $user = auth()->user();
        
        if(!$user->can('create: estudante')  && !$user->can('create: matricula')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        try {
            // Inicia a transação
            DB::beginTransaction();
            
            $matricula = Matricula::findOrFail($id);
            $estudante = Estudante::findOrFail($matricula->estudantes_id);
    
            $extensao = Extensao::where('shcools_id', $this->escolarLogada())
                ->where('tipo', 'estudantes')
                ->where('status', 'activo')
            ->first();
            
            $prefix = $extensao ? $extensao->extensao : "ANG";
            $sufx = $extensao ? $extensao->sufix : "24";
            
            // MATRICULA
            $matricula->numero_estudante = $prefix . " ". $matricula->estudantes_id . "/" .  $sufx;
            $matricula->status = "Novo";
            $matricula->status_matricula = "confirmado";
            $matricula->resultado_final = "estudando";
            $matricula->level = '1';
            $matricula->update();
    
            // ESTUDANTE
            $estudante->numero_processo= $prefix . " ". $matricula->estudantes_id . "/" .  $sufx;
            $estudante->status = "activo";
            $estudante->registro = "confirmado";
            $estudante->conta_corrente = "31.1.2.1.". $matricula->estudantes_id;
            $estudante->update();
    
            $full = $estudante->nome . " " . $estudante->sobre_nome;
            $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);
            
            $nome = head($usernames) . '.' . last($usernames);
    
            $user = User::create([
                'nome' => $estudante->nome . " " . $estudante->sobre_nome,
                'telefone' => $estudante->telefone_estudante,
                'usuario' => $nome,
                'password' => Hash::make($estudante->bilheite),
                'acesso' => "estudante",
                'level' => '100',
                'login' => 'N',
                'numero_avaliacoes' => '0',
                'status' => "Desbloqueado",
                'email' => "{$estudante->bilheite}@gmail.com",
                'funcionarios_id' => $estudante->id,
                'shcools_id' => $this->escolarLogada(),
            ]);
    
            $role = Role::where('name', 'estudante')->first();
            $user->assignRole($role);
            
            $text = "" .Auth::user()->nome."  }}, aprovou a candidatura do estudante {$full}.";
            $text2 = "O Sr(a) acabou de aprovar a matricula de um estudante";
            
            Notificacao::create([
                'user_id' => Auth::user()->id,
                'destino' => NULL,
                'type_destino' => 'escola',
                'type_enviado' => 'funcionario',
                'notificacao' => $text,
                'notificacao_user' => $text2,
                'status' => '0',
                'model_id' => $matricula->id,
                'model_type' => "matricula",
                'shcools_id' => $this->escolarLogada()
            ]);
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            Alert::error('Error', $e->getMessage());
        }

        Alert::success('Bom Trabalho', 'Candidatura Aprovada com successo');
        return redirect()->route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id));

    }
}
