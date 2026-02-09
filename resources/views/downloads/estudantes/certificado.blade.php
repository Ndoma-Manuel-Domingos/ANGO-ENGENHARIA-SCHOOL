<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Certificado e Habilidade</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #ffffff;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
            margin: auto;
            max-width: 800px;
            border: 2px solid #ffffff;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header img {
            height: 60px;
            width: 60px;
            margin-bottom: 0px;
        }

        header h6 {
            text-transform: uppercase;
            font-size: 11pt;
            margin: 5px 0;
        }

        header h1 {
            font-size: 22pt;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .content {
            text-align: justify;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 11pt;
            line-height: 1.6;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #303030;
            padding: 0 5px;
            font-size: 11pt;
            text-align: left;
        }

        table th {
            background-color: #f8f8f8;
        }

        table td {
            text-align: left;
        }

        table th[colspan] {
            text-align: left;
        }

        .footer {
            text-align: center;
            font-size: 11pt;
            color: red;
        }

        .paragrafo-center {
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="assets/images/insigna.png" alt="Insígnia">
            <h6>REPÚBLICA DE ANGOLA</h6>
            <h6>MINISTÉRIO DA EDUCAÇÃO</h6>
            <h6>ENSINO SECUNDÁRIO TÉCNICO-PROFISSIONAL</h6>
            <h1>CERTIFICADO</h1>
        </header>
        
        @if ($curso->tipo == "Outros")
            @php
                $curso_disciplinas_id_componentes = App\Models\web\cursos\DisciplinaCurso::with(['categoria'])
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)
                    ->distinct()
                    ->pluck('categoria_id');   
                $soma_medias_finais = 0;
                $total_disciplinas_curso = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)
                    ->count();
            @endphp
            @foreach ($curso_disciplinas_id_componentes as $componente)
               @php
                    $componente_cursos = App\Models\CategoriaDisciplina::findOrFail($componente);
                    $disciplinas = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                        ->where('ano_lectivos_id', $anoLectivo->id)
                        ->where('cursos_id', $turma->cursos_id)
                        ->where('categoria_id', $componente)
                        ->get();
                @endphp
                @foreach ($disciplinas as $disc)
                        @php
                           $total_nota = 0;
                        @endphp
                        @foreach ($turmas_estudante as $item)
                            @php
                                $turma_disciplinas_estudantes = App\Models\web\turmas\DisciplinaTurma::with(['disciplina'])
                                    ->where('disciplinas_id', $disc->disciplina->id)
                                    ->where('turmas_id', $item->turmas_id)
                                    ->first();
                            @endphp 
                            @if ($turma_disciplinas_estudantes)
                                @php
                                    $nota = App\Models\web\turmas\NotaPauta::where('disciplinas_id', $disc->disciplina->id)
                                        ->where('turmas_id', $item->turmas_id)
                                        ->where('ano_lectivos_id', $item->turma->ano_lectivos_id)
                                        ->where('estudantes_id', $estudante->id)
                                        ->where('controlo_trimestres_id', $trimestre4->id)
                                        ->first();
                                    $total_nota += $nota->mfd;
                                @endphp
                            @endif
                        @endforeach
                        @php
                            $resultado_nota = floor(($total_nota ?? 0) / 3);
                            $soma_medias_finais += floor(($total_nota ?? 0) / 3);
                        @endphp
                @endforeach
            @endforeach 
        
            {{-- DADOS DO ESTUDANTE --}}
            <div class="content">
                <p><strong style="text-transform: uppercase;">SILVESTRE AUGUSTO FRANCISCO</strong>, Director do Instituto Politécnico Industrial nº 1119-Simione Mucune, criado pelo Decreto Executivo n.º 176/21 de 6 de Julho, no Distrito Urbano da Maianga;</p>
                <p><strong>Certifica</strong>, que <strong style="color: red; text-transform: uppercase;">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong> ..........................................................................................................</p>
                <p>Filho de {{ $estudante->pai ?? "" }} e de {{ $estudante->mae ?? "" }}, natural de {{ $estudante->municipio->nome ?? "" }}, Província de {{ $estudante->provincia->nome ?? "" }},
                    nascido aos {{ date("d", strtotime($estudante->nascimento)) }} de {{ $estudante->descricao_mes(date("M", strtotime($estudante->nascimento))) }} de {{ date("Y", strtotime($estudante->nascimento)) }}, portador do Bilhete de Identidade n.º 006936719LA047, passado pelo Arquivo de Identificação Nacional, aos {{ date("d", strtotime($estudante->data_emissao)) }} de {{ $estudante->descricao_mes(date("M", strtotime($estudante->data_emissao))) }} de {{ date("Y", strtotime($estudante->data_emissao)) }}
                    concluiu em regime <strong>Diurno</strong>, no Ano Lectivo de 2022/2023, sob o processo nº {{ $estudante->numero_processo }}, o <strong style="text-transform: uppercase;">{{ $curso->curso }}</strong>, na área de formação de <span style="text-transform: uppercase;">{{ $curso->area_formacao }}</span>, conforme o disposto na alínea f) do artigo 109.0 da LBSEE 32/20 de 12 de Agosto, com a média final de <strong>{{ floor($soma_medias_finais / $total_disciplinas_curso) }}</strong> valores obtida nas seguintes classificações por disciplina:</p>
            </div>
        
            @if ($matricula->classe->classes == "matricula")
                {{-- NOTAS DO ESTUDANTE --}}
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: center">Disciplinas</th>
                            @foreach ($turmas_estudante as $item)
                            <th style="text-align: center">{{ $item->turma->classe->classes }}</th>
                            @endforeach
                            <th style="text-align: center">Média Final</th>
                            <th style="text-align: center">Média por extenso  </th>
                        </tr>
                    </thead>
                    @php
                        $curso_disciplinas_id_componentes = App\Models\web\cursos\DisciplinaCurso::with(['categoria'])
                            ->where('cursos_id', $turma->cursos_id)
                            ->where('shcools_id', $escola->id)
                            ->where('ano_lectivos_id', $anoLectivo->id)
                            ->distinct()
                            ->pluck('categoria_id');   
                    @endphp
                    
                    <tbody>
                        @php
                            $soma_medias_finais = 0;
                            $total_disciplinas_curso = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                                ->where('cursos_id', $turma->cursos_id)
                                ->where('shcools_id', $escola->id)
                                ->where('ano_lectivos_id', $anoLectivo->id)
                                ->count();
                        @endphp
                       @foreach ($curso_disciplinas_id_componentes as $componente)
                           @php
                                $componente_cursos = App\Models\CategoriaDisciplina::findOrFail($componente);
                                $disciplinas = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                                    ->where('ano_lectivos_id', $anoLectivo->id)
                                    ->where('cursos_id', $turma->cursos_id)
                                    ->where('categoria_id', $componente)
                                    ->get();
                            @endphp
                           <tr>
                               <th colspan="6">{{ $componente_cursos->nome }}</th>
                           </tr>
                            @foreach ($disciplinas as $disc)
                               <tr>
                                   <td>{{ $disc->disciplina->disciplina }}</td>
                                    @php
                                       $total_nota = 0;
                                    @endphp
                                    @foreach ($turmas_estudante as $item)
                                        @php
                                            $turma_disciplinas_estudantes = App\Models\web\turmas\DisciplinaTurma::with(['disciplina'])
                                                ->where('disciplinas_id', $disc->disciplina->id)
                                                ->where('turmas_id', $item->turmas_id)
                                                ->first();
                                        @endphp 
                                        @if ($turma_disciplinas_estudantes)
                                            @php
                                                $nota = App\Models\web\turmas\NotaPauta::where('disciplinas_id', $disc->disciplina->id)
                                                    ->where('turmas_id', $item->turmas_id)
                                                    ->where('ano_lectivos_id', $item->turma->ano_lectivos_id)
                                                    ->where('estudantes_id', $estudante->id)
                                                    ->where('controlo_trimestres_id', $trimestre4->id)
                                                    ->first();
                                                $total_nota += $nota->mfd;
                                                // $soma_medias_finais += $nota->mfd;
                                            @endphp
                                            <td style="text-align: center;">{{ floor($nota->mfd ?? 0)  }}</td>
                                        @else
                                            <td style="text-align: center;background-color: #333333;color: #ffffff">-------------</td>
                                        @endif
                                    @endforeach
                                    @php
                                        $resultado_nota = floor(($total_nota ?? 0) / 3);
                                        $soma_medias_finais += floor(($total_nota ?? 0) / 3);
                                    @endphp
                                    <td style="text-align: center">{{ floor(($total_nota ?? 0) / 3) }}</td>
                                    <td style="text-align: center">{{ $nota->valor_por_extenso($resultado_nota) }} Valores</td>
                               </tr>
                            @endforeach
                       @endforeach 
                   
                    </tbody>
                </table>
            @else    
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: left;">Disciplinas</th>
                            @if ($todos_anos_lectivos_estudante)
                            @foreach ($todos_anos_lectivos_estudante as $item)
                            <th style="text-align: center;">{{ $item->classe->classes }}</th>
                            @endforeach
                            @endif
                            <th style="text-align: center;">Média Final</th>
                            <th style="text-align: center;">Média por Extenso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disciplinas_cursos as $item_)
                        <tr>
                            <td style="text-align: left;">{{ $item_->disciplina->disciplina }}</td>
                            @php
                            $total = 0;
                            @endphp
                            @if ($todos_anos_lectivos_estudante)
                            @foreach ($todos_anos_lectivos_estudante as $item)
                            @php
                            $nota = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', '=', $trimestre4->id)
                            ->where('estudantes_id', '=', $estudante->id)
                            ->where('ano_lectivos_id', '=', $item->ano_lectivos_id)
                            ->where('disciplinas_id', '=', $item_->disciplina->id)
                            ->with(['disciplina'])
                            ->first();
                            @endphp
                            <td style="text-align: center;">{{ ceil($nota->mfd) }}</td>
                            @php
                            $total = $total + ceil($nota->mfd);
                            @endphp
                            @endforeach
                            @endif
                            <td style="text-align: center;">{{ ceil($total / 3) }}</td>
                            <td style="text-align: center;">{{ $nota->valor_por_extenso(ceil($total / 3)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        
        @endif
        
        @if ($curso->tipo == "Técnico")
        
            <div class="content">
                <p><strong style="text-transform: uppercase;">SILVESTRE AUGUSTO FRANCISCO</strong>, Director do Instituto Politécnico Industrial nº 1119-Simione Mucune, criado pelo Decreto Executivo n.º 176/21 de 6 de Julho, no Distrito Urbano da Maianga;</p>
                <p><strong>Certifica</strong>, que <strong style="color: red; text-transform: uppercase;">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong> ..........................................................................................................</p>
                <p>Filho de {{ $estudante->pai ?? "" }} e de {{ $estudante->mae ?? "" }}, natural de {{ $estudante->municipio->nome ?? "" }}, Província de {{ $estudante->provincia->nome ?? "" }},
                    nascido aos {{ date("d", strtotime($estudante->nascimento)) }} de {{ $estudante->descricao_mes(date("M", strtotime($estudante->nascimento))) }} de {{ date("Y", strtotime($estudante->nascimento)) }}, portador do Bilhete de Identidade n.º 006936719LA047, passado pelo Arquivo de Identificação Nacional, aos {{ date("d", strtotime($estudante->data_emissao)) }} de {{ $estudante->descricao_mes(date("M", strtotime($estudante->data_emissao))) }} de {{ date("Y", strtotime($estudante->data_emissao)) }}
                    concluiu em regime <strong>Diurno</strong>, no Ano Lectivo de {{ $anoLectivo->ano }}, sob o processo nº {{ $estudante->numero_processo }}, o <strong style="text-transform: uppercase;">{{ $curso->curso }}</strong>, na área de formação de <span style="text-transform: uppercase;">{{ $curso->area_formacao }}</span>, conforme o disposto na alínea f) do artigo 109.0 da LBSEE 32/20 de 12 de Agosto, com a média final de <strong>10</strong> valores obtida nas seguintes classificações por disciplina:</p>
            </div>
      
            @php
                $curso_disciplinas_id_componentes = App\Models\web\cursos\DisciplinaCurso::with(['categoria'])->where('cursos_id', $turma->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)
                    ->distinct()
                    ->pluck('categoria_id');   
                $soma_medias_finais = 0;
                $total_disciplinas_curso = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)
                    ->count();
            @endphp
            
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center">Componentes de formação</th>
                        <th>Média Final</th>
                        <th>Média por Extenso</th>
                    </tr>
                </thead>
                @php
                    $curso_disciplinas_id_componentes = App\Models\web\cursos\DisciplinaCurso::with(['categoria'])->where('cursos_id', $turma->cursos_id)
                        ->distinct()
                        ->pluck('categoria_id');   
                @endphp
                <tbody>
                   @foreach ($curso_disciplinas_id_componentes as $componente)
                       @php
                            $componente_cursos = App\Models\CategoriaDisciplina::findOrFail($componente);
                            $disciplinas = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])->where('ano_lectivos_id', $anoLectivo->id)->where('categoria_id', $componente)->get();
                        @endphp
                       <tr>
                           <th colspan="3">{{ $componente_cursos->nome }}</th>
                       </tr>
                        @foreach ($disciplinas as $disc)
                           <tr>
                               <td>{{ $disc->disciplina->disciplina }}</td>
                                @php
                                   $total_nota = 0;
                                @endphp
                                @foreach ($turmas_estudante as $item)
                                    @php
                                        $turma_disciplinas_estudantes = App\Models\web\turmas\DisciplinaTurma::with(['disciplina'])
                                            ->where('disciplinas_id', $disc->disciplina->id)
                                            ->where('turmas_id', $item->turmas_id)
                                            ->first();
                                    @endphp 
                                    @if ($turma_disciplinas_estudantes)
                                        @php
                                            $nota = App\Models\web\turmas\NotaPauta::where('disciplinas_id', $disc->disciplina->id)
                                                ->where('turmas_id', $item->turmas_id)
                                                ->where('ano_lectivos_id', $item->turma->ano_lectivos_id)
                                                ->where('estudantes_id', $estudante->id)
                                                ->where('controlo_trimestres_id', $trimestre4->id)
                                                ->first();
                                            $total_nota += $nota->mfd;
                                        @endphp
                                    @endif
                                @endforeach
                                @php
                                    $resultado_nota = floor(($total_nota ?? 0) / 3);
                                    $soma_medias_finais += floor(($total_nota ?? 0) / 3);
                                @endphp
                                <td>{{ $resultado_nota }}</td>
                                <td>{{ $nota->valor_por_extenso($resultado_nota) }} Valores</td>
                           </tr>
                        @endforeach
                   @endforeach 
                </tbody>
                <tfoot>
                    <tr>
                        @php
                            $media_plano_curricular = floor($soma_medias_finais / $total_disciplinas_curso);
                        @endphp
                        <th>Classificação Final do Plano Curricular (PC)</th>
                        <td>{{ $media_plano_curricular }}</td>
                        <td>{{ $estudante->valor_por_extenso($media_plano_curricular) }} Valores</td>
                    </tr>
                    <tr>
                        <th>Nota do Estágio Curricular (NEC)</th>
                        <td>{{ $turmasEstudante->nota_estagio }}</td>
                        <td>{{ $estudante->valor_por_extenso($turmasEstudante->nota_estagio) }} Valores</td>
                    </tr>
                    <tr>
                        <th>Prova de Aptidão Profissional (PAP)</th>
                        <td>{{ $turmasEstudante->nota_pap }}</td>
                        <td>{{ $estudante->valor_por_extenso($turmasEstudante->nota_pap) }} Valores</td>
                    </tr>
                    <tr>
                        <th>Classificação Final MFC = (4*PC+PAP+NEC)/6</th>
                            @php
                                $final = floor((4*$media_plano_curricular + $turmasEstudante->nota_pap + $turmasEstudante->nota_estagio ) / 6);
                            @endphp
                        <td>{{ $final  }}</td>
                        <td>{{ $estudante->valor_por_extenso($final) }} Valores</td>
                    </tr>
                </tfoot>
            </table>
            
            
        @endif
        
        @if ($curso->tipo == "Punível")
            @php
                $curso_disciplinas_id_componentes = App\Models\web\cursos\DisciplinaCurso::with(['categoria'])->where('cursos_id', $turma->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)
                    ->distinct()
                    ->pluck('categoria_id');   
                $soma_medias_finais = 0;
                $total_disciplinas_curso = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                    ->where('cursos_id', $turma->cursos_id)
                    ->where('shcools_id', $escola->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)
                    ->count();
            @endphp
            
            @foreach ($curso_disciplinas_id_componentes as $componente)
                @php
                    $componente_cursos = App\Models\CategoriaDisciplina::findOrFail($componente);
                    $disciplinas = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])
                        ->where('ano_lectivos_id', $anoLectivo->id)
                        ->where('shcools_id', $escola->id)
                        ->where('categoria_id', $componente)
                        ->get();
                @endphp
                @foreach ($disciplinas as $disc)
                        @php
                           $total_nota = 0;
                        @endphp
                        @foreach ($turmas_estudante as $item)
                            @php
                                $turma_disciplinas_estudantes = App\Models\web\turmas\DisciplinaTurma::with(['disciplina'])
                                    ->where('disciplinas_id', $disc->disciplina->id)
                                    ->where('turmas_id', $item->turmas_id)
                                    ->first();
                            @endphp 
                            @if ($turma_disciplinas_estudantes)
                                @php
                                    $nota = App\Models\web\turmas\NotaPauta::where('disciplinas_id', $disc->disciplina->id)
                                        ->where('turmas_id', $item->turmas_id)
                                        ->where('ano_lectivos_id', $item->turma->ano_lectivos_id)
                                        ->where('estudantes_id', $estudante->id)
                                        ->where('controlo_trimestres_id', $trimestre4->id)
                                        ->first();
                                    $soma_medias_finais += $nota->mfd;
                                @endphp
                            @else
                            @endif
                        @endforeach
                        @php
                            $resultado_nota = floor(($total_nota ?? 0) / 3);
                            $soma_medias_finais += floor(($total_nota ?? 0) / 3);
                        @endphp
                @endforeach
            @endforeach 
            
            {{-- DADOS DO ESTUDANTE --}}
            <div class="content">
                <p><strong style="text-transform: uppercase;">SILVESTRE AUGUSTO FRANCISCO</strong>, Director do Instituto Politécnico Industrial nº 1119-Simione Mucune, criado pelo Decreto Executivo n.º 176/21 de 6 de Julho, no Distrito Urbano da Maianga;</p>
                <p><strong>Certifica</strong>, que <strong style="color: red; text-transform: uppercase;">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong> ..........................................................................................................</p>
                <p>Filho de {{ $estudante->pai ?? "" }} e de {{ $estudante->mae ?? "" }}, natural de {{ $estudante->municipio->nome ?? "" }}, Província de {{ $estudante->provincia->nome ?? "" }},
                    nascido aos {{ date("d", strtotime($estudante->nascimento)) }} de {{ $estudante->descricao_mes(date("M", strtotime($estudante->nascimento))) }} de {{ date("Y", strtotime($estudante->nascimento)) }}, portador do Bilhete de Identidade n.º 006936719LA047, passado pelo Arquivo de Identificação Nacional, aos {{ date("d", strtotime($estudante->data_emissao)) }} de {{ $estudante->descricao_mes(date("M", strtotime($estudante->data_emissao))) }} de {{ date("Y", strtotime($estudante->data_emissao)) }}
                    concluiu em regime <strong>Diurno</strong>, no Ano Lectivo de {{ $anoLectivo->ano }}, sob o processo nº {{ $estudante->numero_processo }}, o <strong style="text-transform: uppercase;">{{ $curso->curso }}</strong>, na área de formação de <span style="text-transform: uppercase;">{{ $curso->area_formacao }}</span>, conforme o disposto na alínea f) do artigo 109.0 da LBSEE 32/20 de 12 de Agosto, com a média final de <strong>{{ floor(($soma_medias_finais ?? 0) / ($total_disciplinas_curso ?? 0)) }}</strong> valores obtida nas seguintes classificações por disciplina:</p>
            </div>
            {{-- NOTAS DO ESTUDANTE --}}
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center">Disciplinas</th>
                        @foreach ($turmas_estudante as $item)
                        <th style="text-align: center">{{ $item->turma->classe->classes }}</th>
                        @endforeach
                    </tr>
                </thead>
                @php
                    $curso_disciplinas_id_componentes = App\Models\web\cursos\DisciplinaCurso::with(['categoria'])->where('cursos_id', $turma->cursos_id)
                        ->distinct()
                        ->pluck('categoria_id');   
                @endphp
                
                <tbody>
                    @php
                        $soma_medias_finais = 0;
                        $total_disciplinas_curso = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])->where('cursos_id', $turma->cursos_id)->where('ano_lectivos_id', $anoLectivo->id)->count();
                    @endphp
                   @foreach ($curso_disciplinas_id_componentes as $componente)
                       @php
                            $componente_cursos = App\Models\CategoriaDisciplina::findOrFail($componente);
                            $disciplinas = App\Models\web\cursos\DisciplinaCurso::with(['disciplina'])->where('ano_lectivos_id', $anoLectivo->id)->where('categoria_id', $componente)->get();
                        @endphp
                       <tr>
                           <th colspan="4">{{ $componente_cursos->nome }}</th>
                       </tr>
                        @foreach ($disciplinas as $disc)
                           <tr>
                               <td>{{ $disc->disciplina->disciplina }}</td>
                                @php
                                   $total_nota = 0;
                                @endphp
                                @foreach ($turmas_estudante as $item)
                                    @php
                                        $turma_disciplinas_estudantes = App\Models\web\turmas\DisciplinaTurma::with(['disciplina'])
                                            ->where('disciplinas_id', $disc->disciplina->id)
                                            ->where('turmas_id', $item->turmas_id)
                                            ->first();
                                    @endphp 
                                    @if ($turma_disciplinas_estudantes)
                                        @php
                                            $nota = App\Models\web\turmas\NotaPauta::where('disciplinas_id', $disc->disciplina->id)
                                                ->where('turmas_id', $item->turmas_id)
                                                ->where('ano_lectivos_id', $item->turma->ano_lectivos_id)
                                                ->where('estudantes_id', $estudante->id)
                                                ->where('controlo_trimestres_id', $trimestre4->id)
                                                ->first();
                                            $soma_medias_finais += $nota->mfd;
                                        @endphp
                                        <td style="text-align: center;">{{ floor($nota->mfd ?? 0)  }}</td>
                                    @else
                                        <td style="text-align: center;background-color: #333333;color: #ffffff">-------------</td>
                                    @endif
                                @endforeach
                           </tr>
                        @endforeach
                      
                   @endforeach 
                </tbody>
            </table>
        @endif

        <div class="content">
            <p>Para efeitos legais lhe é passado o presente <strong>CERTIFICADO</strong>, que consta no livro de registo n.º 1/2023 folha n.º 04, assinado por mim e autenticado com o selo branco em uso neste estabelecimento de ensino. <span class="footer">(Complexo Escolar Privado João Claudio)</span></p>

            <p class="paragrafo-center">Instituto Politécnico Industrial nº 1119-Simione Mucune, em Luanda {{ date("d") }} de {{ date("M") }} de {{ date("Y") }}.</p>
        </div>

        <div class="content">
            <div style="display: inline-block;width: 100%;float: left;text-align: center">
                <div style="width: 50%;float: left;font-style: italic">
                    <h4>CONFERIDCO POR:</h4>
                    <h5 style="margin-bottom: 10px">________________________________</h5>
                    <h4>João Gaila, PhD</h4>
                </div>
                <div style="width: 50%;float: right;">
                    <h4>O DIRECTOR</h4>
                    <h5 style="margin-bottom: 10px">________________________________</h5>
                    <h4>SILVESTRE AUGUSTO FRANCISCO</h4>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
