<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferenciaEscolaProfessor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_transferencias_escolar_professores";

    protected $fillable = [
        'professor_id',
        'org_shcools_id',
        'des_shcools_id',
        'status',
        'documento',
        'motivo',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }

    public function origem()
    {
        return $this->belongsTo(Shcool::class, 'org_shcools_id', 'id');
    }

    public function destino()
    {
        return $this->belongsTo(Shcool::class, 'des_shcools_id', 'id');
    }
}
