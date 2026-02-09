<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'usuario',
        'password',
        'acesso',
        'numero_avaliacoes',
        'status',
        'funcionarios_id',
        'shcools_id',
    ];
}
