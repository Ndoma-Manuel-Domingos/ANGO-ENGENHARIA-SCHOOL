<?php

namespace App\Models\web\turmas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bolseiro extends Model
{
    use SoftDeletes;

    protected $table = "tb_bolseiros";

    protected $fillable = [
        'status',
        'desconto',
        'afectacao',
        'estudante_id',
        'instutuicao_bolsa_id',
        'bolsa_id',
        'instituicao_id',
        'periodo_id',
        'ano_lectivos_id',
        'shcools_id',
    ];
    
    public function instituicao_bolsa()
    {
        return $this->belongsTo(BolsaInstituicao::class, 'instutuicao_bolsa_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }
    
    public function periodo()
    {
        return $this->belongsTo(Trimestre::class, 'periodo_id', 'id');
    }
    
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudante_id', 'id');
    }
    
    public function bolsa()
    {
        return $this->belongsTo(Bolsa::class, 'bolsa_id', 'id');
    }
    
    public function instituicao()
    {
        return $this->belongsTo(InstituicaoEducacional::class, 'instituicao_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
