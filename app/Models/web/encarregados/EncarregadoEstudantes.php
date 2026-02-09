<?php

namespace App\Models\web\encarregados;

use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncarregadoEstudantes extends Model
{
    use SoftDeletes;

    protected $table = "tb_encarregado_estudantes";

    protected $fillable = [
        'grau_parentesco',
        'encarregados_id',
        'estudantes_id',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function encarregado()
    {
        return $this->belongsTo(Encarregado::class, 'encarregados_id', 'id');
    }
}
