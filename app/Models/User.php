<?php

namespace App\Models;

use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'usuario',
        'nome',
        'email',
        'email_verified_at',
        'last_activity',
        'verification_token',
        'telefone',
        'password',
        'acesso',
        'level',
        'login',
        'color_fundo',
        'numero_avaliacoes',
        'impressora',
        'status',
        'funcionarios_id',
        'shcools_id',
        'lest_login',
        'lest_logout'
    ];

    public function nomeUser($nome, $id)
    {
        if($nome == 'estudante'){
            $dados = Estudante::findOrFail($id);
            return $dados->nome . " " . $dados->sobre_nome;
        }else if($nome == 'admin'){
            return "Ndoma escola";
        }else if('professor'){
            $dados = Professor::findOrFail($id);
            return $dados->nome . " " . $dados->sobre_nome;
        }
    }
}
