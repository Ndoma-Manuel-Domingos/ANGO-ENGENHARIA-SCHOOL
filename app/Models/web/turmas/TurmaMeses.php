<?php

namespace App\Models\web\turmas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurmaMeses extends Model
{
    use SoftDeletes;

    protected $table = "tb_meses_turmas";
    
}
