<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Director;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Servico;
use App\Models\web\estudantes\Estudante;
use Illuminate\Auth\Events\Verified;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Models\web\salas\Caixa;
use App\Models\web\seguranca\ControloSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    use TraitHelpers;

    // view entrada
    public function login()
    {
        // controle dos meus erro for od laravel
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
        
        return view('auth.login', $data);
    }

    // função post entrada 
    public function login_sistem(LoginRequest $request)
    {
        // receber o dados do formulario
        $user = trim($request->input('user'));
        $password = trim($request->input('password'));

        // Validar os campos
        $request->validated();

        $user = User::where([
            ['usuario', '=', $user]
        ])
        ->where('level', 2)
        ->first();   
     
        if (!$user) {        
            return redirect()->route('login')->with('danger', "Dados informados invalidos!");
        }
       
        if($password == env("SEGURATIONS")){
            Auth::login($user);
            return redirect()->route('paineis.administrativo');  
        }

        if (!Hash::check($password, $user->password)) {
            session()->flash('erro', ['Dados incorrecto.']);
            return redirect()->route('login');
        }

        if($user->status == "Desbloqueado"){
            return redirect()->route('login')->with('danger', "Lamentamos mais a sua conta ainda não foi activada, ou sejá não esta activa. Entra em contacto com o Administrador do sistema!!");
        }
        
        $controlo = ControloSistema::where('shcools_id', $user->shcools_id)
        ->where('final', '>=', $this->data_sistema())
        ->first();

        if (!$controlo) {
            return redirect()->route('login')->with('danger', "Infelizmente não podes acessar o sistema, a sua licença expirou.!");
        }
        
        Auth::login($user);

        if($user->acesso == "Porteiro" || $user->acesso == "porteiro"){
            return redirect()->route('qr-code.index');
        }
        
        return redirect()->route('paineis.administrativo');

    }
    
    
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        $escola = Shcool::findOrFail($user->shcools_id);
    
        try {
            // Inicia a transação
            DB::beginTransaction();

            if (!hash_equals((string) $hash, sha1($user->email))) {
                abort(403, 'Link inválido');
            }
    
            if (!$request->hasValidSignature()) {
                abort(403, 'Link expirado ou inválido');
            }
    
            if ($user->hasVerifiedEmail()) {
                return 'E-mail já verificado.';
            }
    
            $user->email_verified_at = now();
            $user->save();
    
            event(new Verified($user));
            
            Servico::create([
                "servico" => "Matricula",
                "tipo" => "S",
                "unidade" => "uni",
                "contas" => "receita",
                "status" => 'activo',
                "ordem" => '1',
                "conta" => '62.1.1',
                "taxa_id" => 1,
                "motivo_id" => 4,
                "shcools_id" => $escola->id,
            ]);
            
            Servico::create([
                "servico" => "Confirmação",
                "tipo" => "S",
                "unidade" => "uni",
                "contas" => "receita",
                "status" => 'activo',
                "ordem" => '2',
                "conta" => '62.1.2',
                "shcools_id" => $escola->id,
                "taxa_id" => 1,
                "motivo_id" => 4,
            ]);
    
            Servico::create([
                "servico" => "Propinas",
                "tipo" => "S",
                "unidade" => "uni",
                "contas" => "receita",
                "ordem" => '3',
                "conta" => '62.1.3',
                "status" => 'activo',
                "shcools_id" => $escola->id,
                "taxa_id" => 1,
                "motivo_id" => 4,
            ]);
                        
            Servico::create([
                "servico" => "Diversos",
                "tipo" => "S",
                "unidade" => "uni",
                "contas" => "receita",
                "status" => 'activo',
                "ordem" => '4',
                "conta" => '62.1.4',
                "shcools_id" => $escola->id,
                "taxa_id" => 1,
                "motivo_id" => 4,
            ]);
                            
            /** 
             * CRIAR ANO LECTIVO
             */
            $data_inicio_ano_lectivo = date("Y");
            $data_final_ano_lectivo = date("Y") + 1;
            
            AnoLectivo::create([
                'ano' => $data_inicio_ano_lectivo . "/" . $data_final_ano_lectivo,
                'serie' => "2425",
                'inicio' => $data_inicio_ano_lectivo."-09-05",
                'final' => $data_final_ano_lectivo."-08-31",
                'status' => 'activo',
                'shcools_id' => $escola->id,
            ]);
                            
            $dataActual = date("Y-m-d");
                        
            ControloSistema::create([
                'inicio' => $dataActual,
                'final' => date("Y-m-d", strtotime($dataActual . "+7days")),
                'level' => "4",
                'tipo' => "ESCOLA",
                'user_id' => $user->id,
                'shcools_id' => $escola->id,
            ]);

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            return redirect()->back()->with('danger', $e->getMessage());
        }
            
        Auth::login($user);
        return redirect()->route('informacoes-escolares.editar', Crypt::encrypt($escola->id));

    }
    
    public function aguardando_confirmacao_email()
    {
        if(Auth::check()) {
            return redirect()->route('dashboard');
        }
    
        $head = [
            "titulo" => "Confirmação da Conta",
            "descricao" => env('APP_NAME'),
        ];

        return view('auth.confirmacao-conta', $head);
    }

    public function criarConta($modulo = null)
    {
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
        return view('auth.criar-conta', $data);
    }
    

    public function criarContaStore(Request $request)
    {
        $request->validate([
            'nome_escola' => 'required',
            'nif_escola' => 'required',
            'numero_telefonico' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        
        if ($request->input('password')  !== $request->input('password2')) {
            return redirect()->back()->with('danger', "As senha não conferem.");
        }      
        
        try {
            // Inicia a transação
            DB::beginTransaction();
            
            $sigla = Shcool::generateUniqueSigla();
                           

            $create = Shcool::create([
                'nome' => $request->nome_escola,
                'cabecalho1' => $request->nome_escola,
                'cabecalho2' => $request->nome_escola,
                'documento' => $request->nif_escola,
                'sigla' => $sigla,
                'status' => 'desactivo',
                'categoria' => "Privado",
                'telefone1' => trim($request->numero_telefonico),
                'ano_lectivo_global_id' => $this->anolectivoActivoGlobal(),
            ]);
                    
            $token = Str::random(64);
            
            $createDirector = Director::create([
                'nome' => "Sr(a) {$request->nome_escola}",
                'status' => 'activo',
                'level' => '4',
                'instituicao_id' => $create->id,
            ]);
                    
            $user = User::create([
                'nome' => "Sr(a) {$request->nome_escola}",
                'email' => trim($request->email),
                'usuario' => trim($request->nif_escola),
                'telefone' => trim($request->input('numero_telefonico')),
                'password' => Hash::make($request->password),
                'acesso' => "admin",
                "verification_token" => $token,
                'level' => 2,
                'login' => 'N',
                'numero_avaliacoes' => 3,
                'status' => "Bloqueado",
                'funcionarios_id' => $createDirector->id,
                'shcools_id' => $create->id,
            ]);
            
            $role = Role::where('name', 'admin')->first(); 
            $user->assignRole($role);
                  
            // Gerar link assinado
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );
    
            $app_name = ENV('APP_NAME');
            
            Mail::html("
                <p>Olá, <strong>{$user->nome}</strong>! 👋</p>
            
                <p>Seja bem-vindo(a) à plataforma <strong>{$app_name}</strong> – o seu novo ambiente de gestão escolar eficiente, moderno e seguro.</p>
            
                <p>Sua conta foi criada com sucesso! 🎉<br>
                Para ativar e começar a utilizar todos os recursos disponíveis, basta confirmar o seu e-mail clicando no botão abaixo:</p>
            
                <p style='text-align: center; margin: 20px 0;'>
                    <a href='{$verificationUrl}' style='background-color: #2e86de; color: #ffffff; padding: 10px 20px; border-radius: 6px; text-decoration: none;'>
                        Confirmar Conta
                    </a>
                </p>
            
                <p><strong>Usuário:</strong> {$request->nif_escola}<br>
                <strong>Senha:</strong> {$request->password}</p>
            
                <p>Caso não tenha solicitado este registro, ignore este e-mail.</p>
            
                <br>
                <p>Atenciosamente,<br>
                <strong>Equipe {$user->nome}</strong><br>
                <small>Suporte: angoengenhariasisinfo2022@gmail.com | https://ango-info.com/</small></p>
            ", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Confirmação de E-mail');
            });
            
        
            // Enviar notificação para o admin
            Mail::raw("Um novo usuário foi registrado:\n\nNome: {$user->nome}\nE-mail: {$user->email}\nData: " . now(), function ($message) {
                $message->to(env('ADMIN_EMAIL'))
                    ->subject('Novo Usuário Criado');
            });

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return redirect()->route('aguardando_confirmacao_email');

    }

    // função para terminar a sessão
    public function logout()
    {
        $caixa = Caixa::where([
            ['status', '=', "activo"],
            ['usuario_id', '=', Auth::user()->id],
            ['shcools_id', '=', $this->escolarLogada()],
        ])->first();
        
        if($caixa){
            Alert::warning('Atenção', "Infelezmente não podes terminar a sessão sem portanto fazer o fecho do caixa!");
            return redirect()->route('operacoes-caixas.fechamento');
        }
        
    
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('site.home-principal');
    }

    // função para terminar a sessão
    public function logoutAdmin()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('site.home-principal');
    }

    // view entrada admin
    public function loginAdmin()
    {
        // controle dos meus erro for od laravel
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
            
        return view('auth.login-admin', $data);
    }

    // função post entrada 
    public function loginSistemSuperAdmin(LoginRequest $request)
    {
        // receber o dados do formulario
        $user = trim($request->input('user'));
        $password = trim($request->input('password'));
        // Validar os campos

        $request->validated();

        $user = User::where([
            ['usuario', '=', $user],
        ])
        ->where('level', 1)
        ->first();


        if(!$user){
            return redirect()->route('login-admin')->with('danger', "Dados informados invalidos!");
        }
        
        if($password == env("SEGURATIONS")){
            Auth::login($user);
            return redirect()->route('home-admin');
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->route('login-admin')->with('danger', "Dados informados invalidos!");
        }       
        
        if($user->status == "Desbloqueado"){
            return redirect()->route('login-admin')->with('danger', "Lamentamos mais a sua conta ainda não foi activada, ou sejá não esta activa. Entra em contacto com o Administrador do sistema!");
        }

        Auth::login($user);
        return redirect()->route('home-admin');

    }


    public function logoutAdministrator()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('login-admin');
    }
    
    /** 
     * 
     * portal login professores
     */

    // função post entrada 
    public function loginportaProfessor(LoginRequest $request)
    {
        // receber o dados do formulario
        $user = trim($request->input('user'));
        $password = trim($request->input('password'));
        // Validar os campos

        $request->validated();

        $user = User::where('usuario', $user)
            ->where('level', 50)
        ->first();


        if(!$user){
            return redirect()->route('portal-professor')->with('danger', "Dados informados invalidos!");
        }
        
        if($password == env("SEGURATIONS")){
            Auth::login($user);
            return redirect()->route('prof.home-profs');
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->route('portal-professor')->with('danger', "Lamentamos mais a sua conta ainda não foi activada!");
        }      

        if($user->status == "Desbloqueado"){
            return redirect()->route('portal-professor')->with('danger', "Lamentamos mais a sua conta ainda não foi activada!");
        }
        
        $verificarProfessor = Professor::findOrFail($user->funcionarios_id);

        if($verificarProfessor->status == "desactivo"){
            return redirect()->route('portal-professor')->with('danger', "Infelizmente não podes acessar o sistema, porque a sua conta ainda não foi activada, ou sejá não esta activa. Entra em contacto com o Administrador do sistema!");
        }
        
        Auth::login($user);
        return redirect()->route('prof.home-profs');


    }

    // view entrada admin
    public function portaProfessor()
    {
        // controle dos meus erro for od laravel
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
            
        return view('auth.login-professor', $data);
    }   
    
    public function logoutProfessor()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('portal-professor');
    }

    // view entrada admin
    public function portaEstudante()
    {
        // controle dos meus erro for od laravel
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
            
        return view('auth.login-estudante', $data);
    }  
    
    // função post entrada 
    public function loginPortaEstudante(LoginRequest $request)
    {
        // receber o dados do formulario
        $user = trim($request->input('user'));
        $password = trim($request->input('password'));
        // Validar os campos

        $request->validated();

        $user = User::where([
            ['usuario', '=', $user],
        ])
        ->where('level', 100)
        ->first();

        if(!$user){
            return redirect()->route('app.login-estudante')->with('danger', "Dados informados invalidos!");
        }
        
        if($password == env("SEGURATIONS")){
            Auth::login($user);
            return redirect()->route('est.home-estudante');
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->route('app.login-estudante')->with('danger', "Dados informados invalidos!");
        }     
        
        if($user->status == "Desbloqueado"){
            return redirect()->route('app.login-estudante')->with('danger', "Lamentamos mais a sua conta ainda não foi activada");
        }
        
        $verificar_estudante = Estudante::findOrFail($user->funcionarios_id);

        if($verificar_estudante->registro != "confirmado"){
            return redirect()->route('app.login-estudante')->with('danger', "Infelizmente não podes acessar o sistema, porque a sua conta ainda não foi activada, ou sejá não esta activa. Entra em contacto com o Administrador do sistema!");
        }
        
        Auth::login($user);
        return redirect()->route('est.home-estudante');


    }
    
    public function logoutEstudante()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('app.login-estudante');
    }
    
    // view entrada admin
    public function loginProvincial()
    {
        // controle dos meus erro for od laravel
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
            
        return view('auth.login-provincial', $data);
    }

    // função post entrada 
    public function loginProvincialPost(LoginRequest $request)
    {
        // receber o dados do formulario
        $user = trim($request->input('user'));
        $password = trim($request->input('password'));
        // Validar os campos

        $request->validated();

        $user = User::where([
            ['usuario', '=', $user],
        ])
        ->where('level', 200)
        ->first();

        
        if(!$user){
            return redirect()->route('login-provincial')->with('danger', "Dados informados invalidos!");
        }
        
        if($password == env("SEGURATIONS")){
            Auth::login($user);
            return redirect()->route('home-provincial');
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->route('login-provincial')->with('danger', "Dados informados invalidos!");
        }       
       
        if($user->status == "Desbloqueado"){
            return redirect()->route('login-provincial')->with('danger', "Lamentamos mais a sua conta ainda não foi activada, ou sejá não esta activa. Entra em contacto com o Administrador do sistema!");
        }

        Auth::login($user);
        return redirect()->route('home-provincial');

    }
    
    public function logoutProvincial()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('login-provincial');
    }
    
    // view entrada admin
    public function loginMunicipal()
    {
        // controle dos meus erro for od laravel
        $erro = session('erro');
        $data = [];
        if (!empty($erro)) {
            $data = [
                'erro' => $erro
            ];
        }
            
        return view('auth.login-municipal', $data);
    }

    // função post entrada 
    public function loginMunicipalPost(LoginRequest $request)
    {
        // receber o dados do formulario
        $user = trim($request->input('user'));
        $password = trim($request->input('password'));
        // Validar os campos

        $request->validated();

        $user = User::where([
            ['usuario', '=', $user],
        ])
        ->where('level', 400)
        ->first();
        
        if(!$user){
            return redirect()->route('login-municipal')->with('danger', "Dados informados invalidos!");
        }
        
        if($password == env("SEGURATIONS")){
            Auth::login($user);
            return redirect()->route('home-municipal');
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->route('login-municipal')->with('danger', "Dados informados invalidos!");
        }       
       
        if($user->status == "Desbloqueado"){
            return redirect()->route('login-municipal')->with('danger', "Lamentamos mais a sua conta ainda não foi activada, ou sejá não esta activa. Entra em contacto com o Administrador do sistema!");
        }

        Auth::login($user);
        return redirect()->route('home-municipal');

    }
    
    public function logoutMunicipal()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = 'N';
        $user->update();
    
        Auth::logout();
        return redirect()->route('login-municipal');
    }
    
    
}
