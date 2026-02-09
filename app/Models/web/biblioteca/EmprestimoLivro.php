<?php

namespace App\Models\web\biblioteca;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmprestimoLivro extends Model
{
    use SoftDeletes;

    protected $table = "tb_emprestimo_livros";

    protected $fillable = [
        'codigo_referencia',
        'emprestado_por_id',
        'emprestado_para_id',
        'tipo_pessoa_para',
        'data_emprestimo',
        'data_prevista_devolucao',
        'hora_emprestimo',
        'hora_devolucao',
        'status',
        'descricao',
        'shcools_id',
    ];
    
    // quem deu imprestado
    public function items() 
    {
        return $this->hasMany(ItemEmprestimoLivro::class, 'emprestimo_id', 'id');
    }
    
    // quem deu imprestado
    public function emprestado_por() 
    {
        return $this->belongsTo(User::class, 'emprestado_por_id', 'id');
    }
    
    // quem emprestou
    public function emprestado_para() 
    {
        return $this->belongsTo(User::class, 'emprestado_para_id', 'id');
    }
 
    public function escola() 
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    

}
