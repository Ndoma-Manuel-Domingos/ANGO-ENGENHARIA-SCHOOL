<?php

namespace App\Models;

use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\anolectivo\Trimestre;
use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoDocumento extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tb_solicitacao_documentos";

    protected $fillable = [
        'user_id',
        'user_final_id',
        'type_model',
        'tipo_documento',
        'efeito_id',
        'trimestre_id',
        'links',
        'descricao',
        'status',
        'ano_lectivos_id',
        'shcools_id'
    ];
    
    public function efeito()
    {
        return $this->belongsTo(Efeito::class, 'efeito_id', 'id');
    }
    
    public function finalizador()
    {
        return $this->belongsTo(User::class, 'user_final_id', 'id');
    }
    
    public function enviador()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }   

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id', 'id');
    }
    
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'user_id', 'id');
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'user_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
