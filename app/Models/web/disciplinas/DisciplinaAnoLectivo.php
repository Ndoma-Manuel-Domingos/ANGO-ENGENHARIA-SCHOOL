<?php

namespace App\Models\web\disciplinas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisciplinaAnoLectivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_ano_lectivo_disciplinas";
    
    protected $fillable = [
        'ano_lectivos_id',
        'disciplinas_id',
        'shcools_id',
    ];


    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }

    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
