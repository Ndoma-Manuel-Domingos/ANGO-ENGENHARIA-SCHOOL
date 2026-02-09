<?php

namespace App\Models;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControloLancamentoNotas extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_controlo_lancamento_notas";

    protected $fillable = [
        'inicio',
        'final',
        'level',
        'direccao_id',
        'status',
        'ano_lectivo_global_id',
        'trimestre_id'
    ];

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id', 'id');
    }
    
    public function ano_global()
    {
        return $this->belongsTo(AnoLectivoGlobal::class, 'ano_lectivo_global_id', 'id');
    }
    
    public function direccao($id, $level)
    {
        if($level == '2'){
            $direccao = DireccaoProvincia::findOrFail($id);
        }else {
            if($level == '3'){
                $direccao = DireccaoMunicipal::findOrFail($id);
            }
       }
       
       return $direccao->nome;
    }
}
