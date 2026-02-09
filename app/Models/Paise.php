<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paise extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "countries";

    protected $fillable = [
        'name',
        'short_name',
        'flag_img',
        'country_code'
    ];

    public function provincia()
    {
        return $this->hasOne(Provincia::class, 'country_id');
    }
}
