<?php

namespace App\Models\web\financeiros;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoInternet extends Model
{
    use SoftDeletes;
    protected $table = "tb_cartao_internet";
}
