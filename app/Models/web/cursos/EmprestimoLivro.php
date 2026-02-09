<?php

namespace App\Models\web\cursos;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmprestimoLivro extends Model
{
    use SoftDeletes;

    protected $table = "tb_emprestimo_livros";

    protected $fillable = [
        'origem_id',
        'destino_id',
        'livro_id',
        'data_emprestimo',
        'data_prevista_devolucao',
        'status',
        'descricao',
        'shcools_id',
    ];
    
    // quem deu imprestado
    public function origem() 
    {
        return $this->belongsTo(User::class, 'origem_id', 'id');
    }
    
    // quem emprestou
    public function destino() 
    {
        return $this->belongsTo(User::class, 'destino_id', 'id');
    }
    
    public function livro() 
    {
        return $this->belongsTo(Livro::class, 'livro_id', 'id');
    }
    
    public function escola() 
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    

}
