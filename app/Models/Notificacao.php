<?php

namespace App\Models;

use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacao extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_notificacoes";

    protected $fillable = [
        'user_id',
        'destino',
        'type_destino',
        'type_enviado',
        'notificacao',
        'notificacao_user',
        'model_id',
        'model_type',
        'status',
        'shcools_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

    public function enviador($type, $id)
    {
        $user = User::findOrFail($id);

        if($type == 'estudante'){
            $estudante = Estudante::findOrFail($user->funcionarios_id);
            return "{$estudante->nome} {$estudante->sobre_nome}";
        }

        if($type == 'professor'){
            $estudante = Professor::findOrFail($user->funcionarios_id);
            return "{$estudante->nome} {$estudante->sobre_nome}";
        }

        if($type == 'escola'){
            $estudante = Shcool::findOrFail($user->shcools_id);
            return "{$estudante->nome}";
        }
    }
}
