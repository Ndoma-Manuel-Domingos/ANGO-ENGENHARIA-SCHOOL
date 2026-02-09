<?php

namespace App\Models\TransferenciasCopias;

use App\Models\Shcool;
use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\turnos\Turno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatriculaCopia extends Model
{
    
    use HasFactory, SoftDeletes;
    
    protected $connection = "mysql2";
    protected $table = "tb_matriculas";

    protected $fillable = [
        'status',
        'data_at',
        'numero_estudante',
        'ficha',
        'documento',
        'status_matricula',
        'at_classes_id',
        'classes_id',
        'turnos_id',
        'cursos_id',
        'tipo',
        'condicao',
        'funcionarios_id',
        'numeracao',
        'estudantes_id',
        'ano_lectivos_id',
        'shcools_id',
        'ano_lectivo_global_id',
        'transferencia_id',
    ];

    public function escola($id)
    {
        return Shcool::findOrFail($id)->nome;
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id', 'id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turnos_id', 'id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

}
