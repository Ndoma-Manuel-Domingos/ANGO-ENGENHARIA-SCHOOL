<?php

namespace App\Models;

use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_municipios";

    protected $fillable = [
        'nome',
        'status',
        'provincia_id',
    ];
    
    
    public function provincia()
    {
       return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }
    
    public function total_escola_municipio($municipio)
    {
        return Shcool::where('municipio_id', $municipio)->count();
    }   

    public function total_estudante_municipio($municipio)
    {
        $total = 0;
        $ids = Shcool::where('municipio_id', $municipio)->select('id')->get();
        if(count($ids) > 0){
            $total = Estudante::where('registro', 'confirmado')->whereIn('shcools_id', $ids)->count();
        }

        return $total;
    } 

    public function total_professores_municipio($municipio)
    {
        $total = FuncionariosControto::where('level', '4')->where('municipio_id', $municipio)->where('status', 'activo')->count();
        return $total;
    } 
}
