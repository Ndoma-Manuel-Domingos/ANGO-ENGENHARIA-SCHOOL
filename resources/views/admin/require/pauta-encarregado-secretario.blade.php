<div class="row mt-5">
    <div class="col-12 col-md-12">
    <!-- Main content -->
    <div class="invoice p-3 mb-3">
        <div class="row invoice-info">
            
            <div class="col-sm-12 col-md-12 invoice-col text-center">
            
                <h1 class="fs-6"><strong>GERAR MINI PAUTA/PAUTA GERAL DO ESTUDANTE</strong></h1>
            </div>

            <div class="col-sm-12 invoice-col bg-light mb-2">
            
                <ul class="fs-5 d-flex py-2 px-0">
                    <li><strong>Turma: </strong> <span class="span_turma">
                        @if (isset($turma))
                            @php
                                echo $turma->turma;
                            @endphp 
                        @else
                            desc
                        @endif </span>. &nbsp; </li>
                    <li><strong>Classe: </strong> <span class="span_classe">
                        @if (isset($classe))
                            @php
                                echo $classe->classes;
                            @endphp 
                        @else
                            desc
                        @endif </span>. &nbsp; </li>
                    <li><strong>Curso: </strong> <span class="span_curso">
                        @if (isset($curso))
                            @php
                                echo $curso->curso;
                            @endphp 
                        @else
                            desc
                        @endif </span>. &nbsp; </li>
                    <li><strong>Turno: </strong> <span class="span_turno">
                        @if (isset($turno))
                            @php
                                echo $turno->turno;
                            @endphp 
                        @else
                            desc
                        @endif </span>. &nbsp; </li>
                    <li><strong>Ano Lectivo </strong> <span class="span_ano_lectivo"> 
                        @if (isset($anoLectivo))
                            @php
                                echo $anoLectivo->ano;
                            @endphp 
                        @else
                            desc
                        @endif </span>. </li>
                    <li><strong>Estudante: </strong><span class="span_estudante"> 
                        @if (isset($estudantes))
                            @php
                                echo "{$estudantes->nome} {$estudantes->sobre_nome}";
                            @endphp 
                        @else
                            desc
                        @endif </span>. &nbsp; </li>
                </ul>
            </div>
            <!-- /.col -->
        </div>

        @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
        
            <div class="col-12 col-md-12">
                <table style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Semestre</th>
                            <th>P1</th>
                            <th>P2</th>
                            <th>P3</th>
                            <th>P4</th>
                            <th>Med</th>
                            <th>Exame de 1ª Esp.</th>
                            <th>MF</th>
                            <th>Recurso</th>
                            <th>Exame Especial</th>
                            <th>Resultado Final</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if ($turmaDisciplinas)
                            @foreach($turmaDisciplinas as $item)
                                @php
                                    $notas1 = (new App\Models\web\turmas\NotaPauta)::where([
                                        ['estudantes_id', '=', $estudantes->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                        ['disciplinas_id', '=', $item->id],
                                    ])
                                    ->with('ano', 'disciplina','trimestre')
                                    ->orderBy('controlo_trimestres_id', 'desc')
                                    ->get();
    
                                @endphp
                                
                                @foreach ($notas1 as $item1)
                                <tr>
                                    <td>{{ $item1->disciplina->disciplina }} </td>
                                    <td>{{ $item1->trimestre->trimestre }} </td>
    
                                    <td>{{ $item1->p1 }}</td>
                                    <td>{{ $item1->p2 }}</td>
                                    <td>{{ $item1->p3 }}</td>
                                    <td>{{ $item1->p4 }}</td>
                                    <td>{{ $item1->med }}</td>
                                    <td>{{ $item1->exame_1_especial }}</td>
                                    <td>{{ $item1->media_final }}</td>
                                    <td>{{ $item1->recurso }}</td>
                                    <td>{{ $item1->exame_especial }}</td>
                                    
                                    @if ($item1->resultado_final >= 10)
                                        <td class="text-info">Aprovado</td>
                                    @else 
                                        @if($item1->resultado_final <= 6) 
                                            <td class="text-danger">Reprovado</td>
                                        @else
                                            <td class="text-danger">Recurso</td>
                                        @endif
                                    @endif  
                                   
                                </tr> 
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                    
                    <tfoot>   
                        <th colspan="14" class="text-center">Media Final CA <br>  ({{ ($somaMFD??0 /  $totalDisciplinas??0) }})</th>
                        <th class="text-center">Resultado Final <br>
                            (@if (($somaMFD??0 /  $totalDisciplinas??0) >= 10)
                                <span class="text-info">Aprovado</span>    
                            @else
                                <span class="text-danger">Reprovado</span> 
                            @endif)
                        </th>
                    </tfoot>
                </table>  
            </div>
            
            @if (Auth::user()->can('read: nota'))
            <div class="col-12 col-md-12 mt-5">
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("simestre1")]) }}" target="_blink" class="btn btn-primary">Iª Semestre</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("simestre2")]) }}" target="_blink" class="btn btn-primary">IIª Semestre</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declaracao-nota") ]) }}" target="_blink" class="btn btn-primary">Declaração Com notas</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declarcao-sem-nota")]) }}" target="_blink" class="btn btn-primary">Declaração Sem notas</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("classificacao-final")]) }}" target="_blink" class="btn btn-primary">Classificação Final</a>
            </div> 
            @endif
            
        @else
            @if (isset($trimestre1) AND isset($trimestre2) AND isset($trimestre3) AND isset($trimestre4) )
            <div class="col-12 col-md-12 table-responsive">
                <table style="width: 100%" class="table  table-bordered table-striped  ">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th colspan="4" class="text-center">Iª Trimestre</th>
                            <th colspan="4" class="text-center">IIª Trimestre</th>
                            <th colspan="4" class="text-center">IIIª Trimestre</th>
                            <th></th>
                            <th></th>
                        </tr>
    
                        <tr>
                            <th>Disciplina</th>
    
                            <th>MAC</th>
                            <th>NPP</th>
                            <th>NPT</th>
                            <th class="text-info">MT</th>
    
                            <th>MAC</th>
                            <th>NPP</th>
                            <th>NPT</th>
                            <th class="text-info">MT</th>
    
                            <th>MAC</th>
                            <th>NPP</th>
                            <th>NPT</th>
                            <th class="text-info">MT</th>
    
                            <th class="bg-success">MDF</th>
                            <th>Obsevação</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @if ($turmaDisciplinas)
                            @foreach($turmaDisciplinas as $item)
                                @php
                                    $notas1 = (new App\Models\web\turmas\NotaPauta)::where([
                                        ['controlo_trimestres_id', '=', $trimestre1->id],
                                        ['estudantes_id', '=', $estudantes->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                        ['disciplinas_id', '=', $item->id],
                                    ])
                                    ->join('tb_disciplinas', 'tb_notas_pautas.disciplinas_id', '=', 'tb_disciplinas.id')
                                    ->select('tb_notas_pautas.conf_ped','tb_notas_pautas.conf_pro','tb_notas_pautas.mac','tb_notas_pautas.npp','tb_notas_pautas.npt', 'tb_disciplinas.disciplina', 'tb_notas_pautas.mt')
                                    ->get();
    
                                    $notas2 = (new App\Models\web\turmas\NotaPauta)::where([
                                        ['controlo_trimestres_id', '=', $trimestre2->id],
                                        ['estudantes_id', '=', $estudantes->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                        ['disciplinas_id', '=', $item->id],
                                    ])
                                    ->join('tb_disciplinas', 'tb_notas_pautas.disciplinas_id', '=', 'tb_disciplinas.id')
                                    ->select('tb_notas_pautas.mac','tb_notas_pautas.npp','tb_notas_pautas.npt', 'tb_disciplinas.disciplina', 'tb_notas_pautas.mt')
                                    ->get();
    
    
                                    $notas3 = (new App\Models\web\turmas\NotaPauta)::where([
                                        ['controlo_trimestres_id', '=', $trimestre3->id],
                                        ['estudantes_id', '=', $estudantes->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                        ['disciplinas_id', '=', $item->id],
                                    ])
                                    ->join('tb_disciplinas', 'tb_notas_pautas.disciplinas_id', '=', 'tb_disciplinas.id')
                                    ->select('tb_notas_pautas.mac','tb_notas_pautas.npp','tb_notas_pautas.npt', 'tb_disciplinas.disciplina', 'tb_notas_pautas.mt')
                                    ->get();
    
                                    $notas4 = (new App\Models\web\turmas\NotaPauta)::where([
                                        ['controlo_trimestres_id', '=', $trimestre4->id],
                                        ['estudantes_id', '=', $estudantes->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                        ['disciplinas_id', '=', $item->id],
                                    ])
                                    ->join('tb_disciplinas', 'tb_notas_pautas.disciplinas_id', '=', 'tb_disciplinas.id')
                                    ->select('tb_disciplinas.disciplina', 'tb_notas_pautas.mfd')
                                    ->get();
    
                                @endphp
    
                                @foreach ($notas1 as $item1)
                                    @foreach ($notas2 as $item2)
                                        @foreach ($notas3 as $item3)
                                            @foreach ($notas4 as $item4)
                                                @if ($item1->conf_pro == "nao" OR $item1->conf_ped == "nao")
                                                    <tr class="bg-danger">
                                                        <td>{{ $item1->disciplina }} </td>
    
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
    
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
    
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
    
                                                        <td>---</td>
                                                        <td>---</td>
                                                    </tr>     
                                                @else
                                                    <tr class="">
                                                        <td>{{ $item1->disciplina }} </td>
    
                                                        <td>{{ $item1->mac }}</td>
                                                        <td>{{ $item1->npp }}</td>
                                                        <td>{{ $item1->npt }}</td>
                                                        <td class="bg-warning">{{ $item1->mt }}</td>
    
                                                        <td>{{ $item2->mac }}</td>
                                                        <td>{{ $item2->npp }}</td>
                                                        <td>{{ $item2->npt }}</td>
                                                        <td class="bg-warning">{{ $item2->mt }}</td>
    
                                                        <td>{{ $item3->mac }}</td>
                                                        <td>{{ $item3->npp }}</td>
                                                        <td>{{ $item3->npt }}</td>
                                                        <td class="bg-warning">{{ $item3->mt }}</td>
    
                                                        <td class="bg-success">{{ $item4->mfd }}</td>
    
                                                        @if ($item4->mfd >= 10)
                                                            <td class="text-info">Transita</td>
                                                        @else
                                                            <td class="text-danger">N/Transita</td>
                                                        @endif    
                                                        
                                                    </tr> 
                                                @endif
                                                
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
    
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        
                        <th colspan="14" class="text-center">Media Final CA <br>  ({{ ($somaMFD /  $totalDisciplinas) }})</th>
                        <th class="text-center">Resultado Final <br>
                            (@if (($somaMFD /  $totalDisciplinas) >= 10)
                                <span class="text-info">Aprovado</span>    
                            @else
                                <span class="text-danger">Reprovado</span> 
                            @endif)
                        </th>
                    </tfoot>
                </table>
            </div>   
            
            <div class="col-12 col-md-12">
                <h5>As linhas vermelha indicam que as notas ainda foram confirmadas pelo pedagógico</h5>
            </div>
                
            @if (Auth::user()->can('read: nota'))
            <div class="col-12 col-md-12">
                {{-- <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id) ]) }}" rel="noopener" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</a> --}}
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre1")]) }}" target="_blink" class="btn btn-primary">Iª Trimestre</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre2")]) }}" target="_blink" class="btn btn-primary">IIª Trimestre</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre3") ]) }}" target="_blink" class="btn btn-primary">IIIª Trimestre</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre4")]) }}" target="_blink" class="btn btn-primary">Geral</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declaracao-nota") ]) }}" target="_blink" class="btn btn-primary">Declaração Com notas</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declarcao-sem-nota")]) }}" target="_blink" class="btn btn-primary">Declaração Sem notas</a>
                <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("classificacao-final")]) }}" target="_blink" class="btn btn-primary">Classificação Final</a>
            </div>    
            @endif
                    
            @endif
        @endif


    </div>
    <!-- /.invoice -->
    </div><!-- /.col -->


</div><!-- /.row -->