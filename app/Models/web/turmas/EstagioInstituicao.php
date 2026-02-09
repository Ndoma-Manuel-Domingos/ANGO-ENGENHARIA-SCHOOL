<?php

namespace App\Models\web\turmas;

use App\Models\Instituicao;
use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstagioInstituicao extends Model
{
    use SoftDeletes;

    protected $table = "tb_estagios_instituicaoes";

    protected $fillable = [
        'estagio_id',
        'instituicao_id',
        'desconto',
        'shcools_id',
    ];

    public function instituicao()
    {
        return $this->hasOne(InstituicaoEducacional::class, 'id', 'instituicao_id');
    }
    
    public function estagio()
    {
        return $this->hasOne(Bolsa::class, 'id', 'estagio_id');
    }

    public function escola()
    {
        return $this->belongsToMany(Shcool::class, 'shcools_id', 'id');
    }
}
