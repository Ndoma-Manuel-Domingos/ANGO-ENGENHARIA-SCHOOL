<?php

namespace App\Models;

use App\Models\web\estudantes\Estudante;
use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_provincias";

    protected $fillable = [
        'nome',
        'status',
        'capital',
        'abreviacao',
    ];
    
    
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'provincia_id', 'id');
    }
    
    public function provincia()
    {
        return $this->belongsTo(Shcool::class);
    }

    public function total_escola_provincia($provincia)
    {
        return Shcool::where('provincia_id', $provincia)->count();
    }   

    public function total_estudante_provincia($provincia)
    {
        $total = 0;
        $ids = Shcool::where('provincia_id', $provincia)->select('id')->get();
        if(count($ids) > 0){
            $total = Estudante::where('registro', 'confirmado')->whereIn('shcools_id', $ids)->count();
        }

        return $total;
    } 

    public function total_professores_provincia($provincia)
    {
        $total = FuncionariosControto::where('provincia_id', $provincia)->where('level', '4')->where('status', 'activo')->count();
        return $total;
    } 

}
