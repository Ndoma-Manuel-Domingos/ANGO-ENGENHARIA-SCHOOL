<?php

namespace App\Models\web\turmas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estagiario extends Model
{
    use SoftDeletes;

    protected $table = "tb_estagiarios";

    protected $fillable = [
        'pago_at',
        'status',
        'estudante_id',
        'instutuicao_estagio_id',
        'estagio_id',
        'instituicao_id',
        'data_inicio',
        'data_final',
        'ano_lectivos_id',
        'shcools_id',
    ];
    
    public function instituicao_estagio()
    {
        return $this->belongsTo(EstagioInstituicao::class, 'instutuicao_estagio_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }
    

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudante_id', 'id');
    }
    
    public function estagio()
    {
        return $this->belongsTo(Bolsa::class, 'estagio_id', 'id');
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
