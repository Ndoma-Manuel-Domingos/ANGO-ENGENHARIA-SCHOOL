<?php

namespace App\Models\web\turnos;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivoTurno extends Model
{
    use SoftDeletes;

    protected $table = "tb_ano_lectivo_turnos";

    protected $fillable = [
        'ano_lectivos_id',
        'turnos_id',
        'shcools_id',
        'total_vagas',
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turnos_id', 'id');
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
