<?php

namespace App\Models\web\disciplinas;

use App\Models\web\turmas\Horario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disciplina extends Model
{
    use SoftDeletes;

    protected $table = "tb_disciplinas";

    protected $fillable = [
        'disciplina',
        'abreviacao',
        'code',
        'descricao',
        'shcools_id',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'disciplinas_id', 'id');
    }
}
