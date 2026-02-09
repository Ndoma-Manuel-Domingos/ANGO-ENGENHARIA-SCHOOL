<?php

namespace App\Models\Web\calendarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxaSaft extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_taxas";

    protected $fillable = [
        'taxa',
        'designacao',
        'sigla',
    ];
}
