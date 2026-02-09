<?php

namespace App\Models\web\encarregados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encarregado extends Model
{
    use SoftDeletes;

    protected $table = "tb_encarregados";

    protected $fillable = [
        'nome_completo',
        'nome',
        'sobre_nome',
        'estado_civil',
        'genero',
        'data_nascimento',
        'profissao',
        'numero_bilhete',
        'telefone',
        'shcools_id',
    ];
          
    public function educandos()
    {
        return $this->hasMany(EncarregadoEstudantes::class, 'encarregados_id', 'id');
    }
    
}
