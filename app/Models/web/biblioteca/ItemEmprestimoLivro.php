<?php

namespace App\Models\web\biblioteca;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemEmprestimoLivro extends Model
{
    use SoftDeletes;

    protected $table = "tb_items_emprestimo_livros";

    protected $fillable = [
        'emprestimo_id',
        'livro_id',
        'shcools_id',
    ];
    
    // quem deu imprestado
    public function emprestimo() 
    {
        return $this->belongsTo(EmprestimoLivro::class, 'emprestimo_id', 'id');
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
