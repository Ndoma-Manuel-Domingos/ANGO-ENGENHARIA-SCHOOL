<?php

namespace App\Models\web\turmas;

use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituicaoEducacional extends Model
{
    use SoftDeletes;

    protected $table = "tb_instituicoes_educacionais";

    protected $fillable = [
        'nif',
        'status',
        'tipo',
        'type', // E & B
        'nome',
        'email',
        'endereco',
        'director',
        'shcools_id',
    ];

    public function bolsas()
    {
        return $this->hasMany(BolsaInstituicao::class, 'instituicao_id', 'id');
    }

    public function escola()
    {
        return $this->belongsToMany(Shcool::class, 'shcools_id', 'id');
    }
}
