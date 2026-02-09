<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormacaoAcedemico extends Model
{
    use SoftDeletes;

    protected $table = "tb_formacao_acedemica";

    protected $fillable = [
        'nome',
        'status',
        'shcools_id',
    ];
}
