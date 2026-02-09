<?php

namespace App\Models\web\cursos;

use App\Models\CategoriaDisciplina;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisciplinaCurso extends Model
{
    use SoftDeletes;

    protected $table = "tb_discplinas_cursos";

    protected $fillable = [
        'categoria_id',
        'disciplinas_id',
        'peso',
        'cursos_id',
        'shcools_id',
        'ano_lectivos_id',
    ];
    
    public function categoria() 
    {
        return $this->belongsTo(CategoriaDisciplina::class, 'categoria_id', 'id');
    }
    
    public function escola() 
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function ano() 
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }
    
    public function curso() 
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }
    
    public function disciplina() 
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }
}
