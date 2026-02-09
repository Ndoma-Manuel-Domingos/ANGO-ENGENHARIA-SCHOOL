<?php

namespace App\Models\web\seguranca;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControloSistema extends Model
{
    use SoftDeletes;

    protected $table = "tb_controlo_sistema";

    protected $fillable = [
        'inicio',
        'final',
        'level',
        'status',
        'tipo',
        'user_id',
        'shcools_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
