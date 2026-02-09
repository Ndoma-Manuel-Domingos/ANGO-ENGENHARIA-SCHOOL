<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaDisciplina extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_disciplinas_categoria";

    protected $fillable = [
        'nome',
        'sigla',
        'level',
        'shcools_id',
    ];
}
