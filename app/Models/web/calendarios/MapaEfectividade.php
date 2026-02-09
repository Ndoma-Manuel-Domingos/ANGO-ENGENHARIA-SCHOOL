<?php

namespace App\Models\web\calendarios;

use App\Models\Professor;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapaEfectividade extends Model
{
    use SoftDeletes;

    protected $table = "tb_mapa_efectividade";

    protected $fillable = [
        'mes',
        'dia',
        'dia_semana',
        'data_at',
        'funcionarios_id',
        'faltas',
        'status',
        'ano_lectivos_id',
        'shcools_id',
    ];
        
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'funcionarios_id', 'id');
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
