<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUrlHasParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $parametro = 'req_id'; // Nome do parâmetro fixo
        $valorFixo = '2RJ5IKTQFG'; // Valor fixo que o parâmetro deve ter
        
        // Se o parâmetro estiver ausente ou com valor diferente, redireciona corrigindo
        if (!$request->has($parametro) || $request->query($parametro) !== $valorFixo) {
            return redirect()->route($request->route()->getName(), array_merge($request->all(), [$parametro => $valorFixo]));
        }

        return $next($request);
    }
}
