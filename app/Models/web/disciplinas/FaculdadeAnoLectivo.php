<?php

namespace App\Models\web\disciplinas;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\funcionarios\Funcionarios;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaculdadeAnoLectivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_ano_lectivo_faculdades";
    
    protected $fillable = [
        'ano_lectivos_id',
        'faculdades_id',
        'decano_id',
        'shcools_id',
    ];

    public function faculdade()
    {
        return $this->belongsTo(Faculdade::class, 'faculdades_id', 'id');
    }
    
    public function decano()
    {
        return $this->belongsTo(Funcionarios::class, 'decano_id', 'id');
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
