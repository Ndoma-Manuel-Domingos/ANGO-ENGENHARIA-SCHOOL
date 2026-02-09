<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    protected $table = "tb_stock_mercadorias";

    protected $fillable = [
        'user_id',
        'status',
        'quantidade',
        'unidade',
        'mercadoria_id',
        'fornecedor_id',
        'shcools_id',
        'level',
        'descricao',
    ];
    
    public function entrada($id)
    {
        $dados = Fornecedor::findOrFail($id);
        
        return $dados->nome;
    }
    
    public function saida($level, $instituicao)
    {
        if($level == "2"){
            $destino = DireccaoProvincia::findOrFail($instituicao);
        }
        
        if($level == "3"){
            $destino = DireccaoMunicipal::findOrFail($instituicao);
        } 
        
        if($level == "4"){
            $destino = Shcool::findOrFail($instituicao);
        } 
            
        return $destino->nome;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function mercadoria()
    {
        return $this->belongsTo(Mercadoria::class, 'mercadoria_id', 'id');
    }
    
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id', 'id');
    }
    
    public function direccao_provincia()
    {
        return $this->belongsTo(DireccaoProvincia::class, 'shcools_id', 'id');
    }
    
    public function direccao_municipal()
    {
        return $this->belongsTo(DireccaoMunicipal::class, 'shcools_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }

}
