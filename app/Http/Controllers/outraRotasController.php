<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class outraRotasController extends Controller
{
    public function pesquisaSemResultado(Request $request)
    {
        return view('routers.pesquisa-sem-resultado');
    }
}
