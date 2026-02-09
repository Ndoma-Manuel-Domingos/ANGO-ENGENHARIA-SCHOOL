<?php

namespace App\Models;

use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\estudantes\Estudante;
use App\Models\web\turnos\Turno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferenciaEscolar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_transferencias_escolar";

    protected $fillable = [
        'estudantes_id',
        'org_shcools_id',
        'des_shcools_id',
        'cursos_id',
        'classes_id',
        'turnos_id',
        'data_final',
        'status',
        'condicao',
        'documento',
        'motivo',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id', 'id');
    }
    
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }
    
    
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turnos_id', 'id');
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function origem()
    {
        return $this->belongsTo(Shcool::class, 'org_shcools_id', 'id');
    }

    public function destino()
    {
        return $this->belongsTo(Shcool::class, 'des_shcools_id', 'id');
    }
}
