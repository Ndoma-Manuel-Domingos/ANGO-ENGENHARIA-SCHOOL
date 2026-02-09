<?php

namespace App\Models;

use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunicado extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tb_comunicados";

    protected $fillable = [
        'titulo',
        'descricao',
        'user_id',
        'shcools_id',
        'ano_lectivo_id',
        'to_escola',
        'tipo_comunicado', // comunicado , noticia
        'tipo_acesso_comunicado', // interno , externos
        'documento',
        'to',
        'level_to',
        'level', // escola, municipio, provincial, ministerio
        'status',
        'read',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivo_id', 'id');
    }
}
