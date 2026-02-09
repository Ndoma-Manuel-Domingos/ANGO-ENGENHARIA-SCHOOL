<?php

namespace App\Models\web\funcionarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funcionario extends Model
{
    use SoftDeletes;

    protected $table = "tb_funcionarios";


}
