<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratorioEscola extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tb_laboratorios_escolas";

    protected $fillable = [
        'descricao',
        'laboratorio_id',
        'shcools_id',
        'status',
    ];
    
    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
