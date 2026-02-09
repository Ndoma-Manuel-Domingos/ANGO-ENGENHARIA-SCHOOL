<?php

namespace App\Models\web\classes;

use App\Models\EnsinoClasse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    use SoftDeletes;

    protected $table = "tb_classes";

    protected $fillable = [
        'classes',
        'status',
        'tipo',
        'tipo_avaliacao_nota',
        'categoria',
        'shcools_id',
        'ensino_id',
    ];
    
    public function ensino()
    {
        return $this->belongsTo(EnsinoClasse::class, 'ensino_id', 'id');
    }


}
