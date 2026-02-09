<?php

namespace App\Models\web\salas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivoSala extends Model
{
    use SoftDeletes;

    protected $table = "tb_ano_lectivo_salas";

    protected $fillable = [
        'ano_lectivos_id',
        'salas_id',
        'shcools_id',
        'total_vagas',
    ];

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'salas_id', 'id');
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
