<?php

namespace App\Console\Commands;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class instalador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando executa o instalador inicial do projecto';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!$this->verificar()){
            $rol = $this->crearRolSuperAdmin();
            $usuariob = $this->criarUsuariosSuperAdmin();
        }else{
            $this->error('n]ao foi possivel executar o instalador');
        }

    }

    private function verificar()
    {
        return Rol::find(1);
    }

    private function crearRolSuperAdmin()
    {
        $rol = "Super Administrador";
        return Rol::create([
            "nombre" => $rol,
            "slug" => Str::slug($rol, '_')
        ]);
    }

    private function criarUsuariosSuperAdmin()
    {
        return Usuario::create([
            "nombre" => 'admin',
            "email" => 'ndomamanuel1997@gmail.com',
            "password" => Hash::make('admin'),
            "estado" => 1

        ]);
    }
}
