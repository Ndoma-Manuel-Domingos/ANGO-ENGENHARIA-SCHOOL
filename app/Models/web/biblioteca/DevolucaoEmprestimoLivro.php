<?php

namespace App\Models\web\biblioteca;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevolucaoEmprestimoLivro extends Model
{
    use SoftDeletes;

    protected $table = "tb_devolucoes_emprestimos_livros";

    protected $fillable = [
        'emprestimo_id',
        'data_devolucao',
        'status',
        'multa',
        'observacao',
        'shcools_id',
    ];
    
    // quem deu imprestado
    public function emprestimo() 
    {
        return $this->belongsTo(EmprestimoLivro::class, 'emprestimo_id', 'id');
    }

    public function escola() 
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    

}
