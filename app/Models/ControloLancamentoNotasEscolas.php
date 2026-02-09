<?php

namespace App\Models;

use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControloLancamentoNotasEscolas extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_controlo_lancamento_notas_escolas";

    protected $fillable = [
        'shcools_id',
        'lancamento_id',
        'ano_lectivo_id',
        'total_estudantes',
        'total_lancados',
        'total_restantes',
        'status',
    ];
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function lancamento()
    {
        return $this->belongsTo(ControloLancamentoNotas::class, 'lancamento_id', 'id');
    }
}
