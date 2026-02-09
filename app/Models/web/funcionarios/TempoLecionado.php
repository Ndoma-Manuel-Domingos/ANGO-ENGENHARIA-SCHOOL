<?php

namespace App\Models\web\funcionarios;

use App\Models\Professor;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempoLecionado extends Model
{
    use SoftDeletes;

    protected $table = "tb_tempos_lecionados";

    protected $fillable = [
        'observacao',
        'data',
        'mes',
        'ano',
        'tempos_dados',
        'professor_id',
        'ano_lectivos_id',
        'shcools_id',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
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
