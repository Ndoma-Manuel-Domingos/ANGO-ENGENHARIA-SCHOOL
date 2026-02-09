<?php

namespace App\Models\web\biblioteca;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneroLivro extends Model
{
    use SoftDeletes;

    protected $table = "tb_genero_livros";

    protected $fillable = [
        'nome',
        'shcools_id',
    ];

}
