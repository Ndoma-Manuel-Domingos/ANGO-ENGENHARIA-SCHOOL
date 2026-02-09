<?php

namespace App\Models\web\disciplinas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidaturaAnoLectivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_ano_lectivo_candidaturas";
    
    protected $fillable = [
        'ano_lectivos_id',
        'candidaturas_id',
        'shcools_id',
    ];

    public function candidatura()
    {
        return $this->belongsTo(Candidatura::class, 'candidaturas_id', 'id');
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
