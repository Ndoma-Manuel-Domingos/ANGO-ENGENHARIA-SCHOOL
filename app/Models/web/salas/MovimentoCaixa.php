<?php

namespace App\Models\web\salas;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentoCaixa extends Model
{
    use SoftDeletes;

    protected $table = 'tb_movimento_caixas';
    
    // protected $primaryKey = 'id';
    
    protected $fillable = [
        'caixa_id',
        'usuario_id',
        'valor_abrir',
        'valor_fechar',
        'data_abrir',
        'data_fechar',
        'status',
        'qtd_ites',
        'valor_tpa',
        'valor_cache',
        'valor_outro',
        'valor_retirado1',
        'valor_retirado2',
        'valor_retirado3',
        'motivo_retirar1',
        'motivo_retirar2',
        'motivo_retirar3',
        'observacao',
        'shcools_id',
    ];
    
    public function user_abrir()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
    
    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
