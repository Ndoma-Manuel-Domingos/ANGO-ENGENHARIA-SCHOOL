<?php

namespace App\Models;

use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use SoftDeletes;

    protected $table = "tb_departamentos";

    protected $fillable = [
        'departamento',
        'status',
        'level',
        'shcools_id',
    ];
    
    
    public function total_funcionarios_departamento_activo($level, $departamento, $instituicao = "")
    {
        return FuncionariosControto::where('shcools_id', $instituicao)->where('level', $level)->where('status','activo')->where('departamento_id', $departamento)->count();
    }
    
    public function total_funcionarios_departamento_desactivo($level, $departamento, $instituicao = "")
    {
        return FuncionariosControto::where('shcools_id', $instituicao)->where('level', $level)->where('status','desactivo')->where('departamento_id', $departamento)->count();
    }
}
