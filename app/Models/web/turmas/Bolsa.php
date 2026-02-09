<?php

namespace App\Models\web\turmas;

use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bolsa extends Model
{
    use SoftDeletes;

    protected $table = "tb_bolsas";

    protected $fillable = [
        'nome',
        'status',
        'type',
        'desconto',
        'codigo',
        'descricao',
        'shcools_id',
    ];
    
    public function instituicoes()
    {
        return $this->hasMany(BolsaInstituicao::class, 'bolsa_id', 'id');
    }

    public function escola()
    {
        return $this->belongsToMany(Shcool::class, 'shcools_id', 'id');
    }
}
