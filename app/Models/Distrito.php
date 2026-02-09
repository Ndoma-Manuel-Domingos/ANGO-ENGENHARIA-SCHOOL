<?php

namespace App\Models;

use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distrito extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tb_distritos";

    protected $fillable = [
        'nome',
        'status',
        'municipio_id',
    ];
    
    
    public function municipio()
    {
       return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }
    
    public function total_escola_distrito($municipio)
    {
        return Shcool::where('distrito_id', $municipio)->count();
    }   

    public function total_estudante_distrito($municipio)
    {
        $total = 0;
        $ids = Shcool::where('distrito_id', $municipio)->select('id')->get();
        if(count($ids) > 0){
            $total = Estudante::where('registro', 'confirmado')->whereIn('shcools_id', $ids)->count();
        }

        return $total;
    } 

    public function total_professores_distrito($municipio)
    {
        $total = FuncionariosControto::where('level', '4')->where('distrito_id', $municipio)->where('status', 'activo')->count();
        return $total;
    } 
}
