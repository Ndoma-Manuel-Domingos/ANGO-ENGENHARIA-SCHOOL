<?php

namespace App\Models\web\seguranca;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seguranca extends Model
{
    use SoftDeletes;

    protected $table = "tb_usuarios";

}
