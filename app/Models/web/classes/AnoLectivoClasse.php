<?php

namespace App\Models\web\classes;

use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivoClasse extends Model
{
    use SoftDeletes;

    protected $table = "tb_ano_lectivo_classes";

    protected $fillable = [
        'ano_lectivos_id',
        'classes_id',
        'shcools_id',
        'total_vagas',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id', 'id');
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
