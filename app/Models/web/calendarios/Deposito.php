<?php

namespace App\Models\web\calendarios;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposito extends Model
{
    use SoftDeletes;

    protected $table = "tb_depositos";

    protected $fillable = [
      'status',
      'saida_valor_id',
      'valor',
      'valor_anterior',
      'descricao',
      'date_at',
      'forma_de_pagamento',
      'funcionarios_id',
      'estudantes_id',
      'ano_lectivos_id',
      'shcools_id',
    ];
 

    public function escola()
    {
       return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    
    public function estudante()
    {
       return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }
    
    public function ano()
    {
       return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function operador()
    {
       return $this->belongsTo(User::class, 'funcionarios_id', 'id');
    }

}
