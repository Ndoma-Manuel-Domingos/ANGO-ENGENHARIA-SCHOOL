<?php

namespace App\Models\web\biblioteca;

use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livro extends Model
{
    use SoftDeletes;

    protected $table = "tb_livros";

    protected $fillable = [
        'nome',
        'subtitulo',
        'isbn',
        'codigo_interno',
        'autor_id',
        'editora_id',
        'edicao',
        'volume',
        'numero_paginas',
        'idioma',
        'localizacao',
        'status',
        'genero_id',
        'tipo_material_id',
        'data_publicacao',
        'data_aquisicao',
        'capa',
        'descricao',
        'shcools_id',
    ];
    
    public function autor() 
    {
        return $this->belongsTo(Autor::class, 'autor_id', 'id');
    }
    
    public function editora() 
    {
        return $this->belongsTo(Editora::class, 'editora_id', 'id');
    }
    
    
    public function tipo_material() 
    {
        return $this->belongsTo(TipoMeterial::class, 'tipo_material_id', 'id');
    }
    
    public function genero() 
    {
        return $this->belongsTo(GeneroLivro::class, 'genero_id', 'id');
    }
    
    public function escola() 
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    

}
