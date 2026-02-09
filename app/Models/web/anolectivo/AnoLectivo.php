<?php

namespace App\Models\web\anolectivo;

use App\Models\Shcool;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\classes\Classe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnoLectivo extends Model
{
    use SoftDeletes;

    protected $table = "tb_ano_lectivos";

    protected $fillable = [
        'ano',
        'serie',
        'inicio',
        'final',
        'status',
        'ordem',
        'shcools_id',
    ];

    public function escola()
    {
        return $this->hasOne(Shcool::class, 'id', 'shcools_id');
    }

    public function classe()
    {
        return $this->hasOne(AnoLectivoClasse::class, 'id', 'ano_lectivos_id');
    }

    public function classes()
    {
        return $this->hasMany(AnoLectivoClasse::class, 'id', 'ano_lectivos_id');
    }


}
