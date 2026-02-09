<?php

namespace App\Models\web\anolectivo;

use App\Models\Shcool;
use App\Models\User;
use App\Models\web\calendarios\Servico;
use App\Models\web\estudantes\Estudante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValidacaoRupe extends Model
{
    use SoftDeletes;

    protected $table = "tb_validacao_rupes";

    protected $fillable = [
        'rupe_id',
        'estudantes_id',
        'servicos_id',
        'tipo_documento',
        'status',
        'status_servico',
        'user_id',
        'ano_lectivos_id',
        'shcools_id',
    ];

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }
    
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'ano_lectivos_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
