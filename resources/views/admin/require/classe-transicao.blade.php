
@if ($turma->curso->tipo === "Técnico")
<div class="row mt-3">
    <div class="col-12 col-md-12 table-responsive">
        <table class="table  table-bordered table-striped" style="background-color: #ffffff">
            <thead>
                <tr>
                    <th style="color: #000000;border: 1px solid #000000; font-size: 12px;" colspan="2">Disciplina:</th>
                    <th style="color: #000000;border: 1px solid #000000; font-size: 12px;text-transform: uppercase" colspan="2">@if ( isset($disciplina) ) {{ $disciplina->disciplina }} @else desc @endif</th>
                    <th style="color: #000000;border: 1px solid #000000; font-size: 12px;" colspan="16">Prof.(a):</th>
                    <th style="color: #000000;border: 1px solid #000000; font-size: 12px;" colspan="2">Turno: @if ( isset($turno) ) {{ $turno->turno }} @else desc @endif</th>
                    <th style="color: #000000;border: 1px solid #000000; font-size: 12px;" colspan="8">Ano Lectivo: <span> @if ( isset($anoLectivo) ) {{ $anoLectivo->ano }} @else desc @endif</span></th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;color: green;font-size: 16px" rowspan="2" colspan="4">TURMA: @if ( isset($turma) ) {{ $turma->turma }} @else desc @endif</th>
                    <th style="border: 1px solid #000000;text-align: center;color: blue;font-size: 16px" colspan="26">CLASSIFICAÇÃO</th>
                </tr>
                <tr>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="3">Idade</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="3">Genero</th>
                    <th style="color: #000000;border: 1px solid #000000;text-align: center;" colspan="6"> 1 º Trimestre</th>
                    <th style="color: #000000;border: 1px solid #000000;text-align: center;" colspan="6"> 2 º Trimestre</th>
                    <th style="color: #000000;border: 1px solid #000000;text-align: center;" colspan="6"> 3 º Trimestre</th>
                    <th style="color: #000000;border: 1px solid #000000;text-align: center;" colspan="4">Resultados Finais</th>
                    <th style="color: #000000;border: 1px solid #000000;text-align: center;" rowspan="3" colspan="2">OBS</th>
                </tr>
                
                <tr>
                    <th style="color: #000000;text-align: center;border: 1px solid #000000" rowspan="2">Nº</th>
                    <th style="color: #000000;border: 1px solid #000000" colspan="3" rowspan="2">Nome do(a) aluno(a)</th>
                    
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">P1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">P2</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">PT</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MT1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" colspan="2">Faltas</th>
                    
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">P1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">P2</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">PT</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MT1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" colspan="2">Faltas</th>
                    
                    
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">P1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">P2</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">PT</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MT1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" colspan="2">Faltas</th>
                    
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MT1</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MT2</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MT3</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;" rowspan="2">MFD</th>
                    
                </tr>
                
                <tr>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;">FNJ</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;">FJ</th>
                    
                    <th style="color: #000000;text-align: center; border: 1px solid #000;">FNJ</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;">FJ</th>
                    
                    <th style="color: #000000;text-align: center; border: 1px solid #000;">FNJ</th>
                    <th style="color: #000000;text-align: center; border: 1px solid #000;">FJ</th>
                </tr>
            </thead>
            
            <tbody>
            
                @if (isset($estudantes))
                    @php $contador = 0; @endphp
                    @foreach ($estudantes as $estudante)
                        @php
                            $contador++;
                            $notas1 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre1->id, $anoLectivo->id);
                            $notas2 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre2->id, $anoLectivo->id);
                            $notas3 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre3->id, $anoLectivo->id);
                            $notas4 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre4->id, $anoLectivo->id);
                        @endphp
                        
                        
                    <tr>
                        <td style="text-align: center;border: 1px solid #000000">{{ $contador }}</td>
                        <td style="border: 1px solid #000000" colspan="3">{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $estudante->estudante->idade($estudante->estudante->nascimento) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
                        
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas1->arredondar($notas1->mac) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas1->arredondar($notas1->npt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas1->arredondar($notas1->npp) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas1->arredondar($notas1->mt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000"></td>
                        <td style="text-align: center;border: 1px solid #000000"></td>
                        
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas2->arredondar($notas2->mac) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas2->arredondar($notas2->npt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas2->arredondar($notas2->npp) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas2->arredondar($notas2->mt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000"></td>
                        <td style="text-align: center;border: 1px solid #000000"></td>
                        
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas3->arredondar($notas3->mac) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas3->arredondar($notas3->npt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas3->arredondar($notas3->npp) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas3->arredondar($notas3->mt) }}</td>
                       <td style="text-align: center;border: 1px solid #000000"></td>
                        <td style="text-align: center;border: 1px solid #000000"></td>
                        
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas1->arredondar($notas1->mt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas2->arredondar($notas2->mt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas3->arredondar($notas3->mt) }}</td>
                        <td style="text-align: center;border: 1px solid #000000">{{ $notas3->arredondar($notas3->mfd) }}</td>
                        
                        <td style="text-align: center;border: 1px solid #000000" colspan="2">
                        @if ( $notas4->obs == "Não Apto")
                            <span style="color: red;">N/TRANSITA</span>
                        @else
                            <span style="color: blue;">TRANSITA</span>
                        @endif
                        </td>
                    </tr>
                    
                    @endforeach
                @endif
            </tbody>
        </table>
        
        <table class="table  table-bordered table-striped" style="margin-top: 60px;background-color: #ffffff">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;" rowspan="2" colspan="2">Matriculados</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">1ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">2ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">3ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;" colspan="3">Assinatura do(a) Prof.</th>
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Desistente</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Avaliados</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">C/Aproveit.</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">S/Aproveit.</th>
              
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Desistente</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Avaliados</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">C/Aproveit.</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">S/Aproveit.</th>
              
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Desistente</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Avaliados</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">C/Aproveit.</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">S/Aproveit.</th>
                    
                    <th style="border: 1px solid #000000;" rowspan="2" colspan="3"></th>
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td style="border: 1px solid #000000;text-align: center;">23</td>
                    <td style="border: 1px solid #000000;text-align: center;">23</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    
                    <td style="border: 1px solid #000000;" rowspan="2" colspan="3"></td>
                </tr>
                
                <tr>
                    <td style="text-align: center;border: 1px solid #000000;" colspan="2">0</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>     
@endif

@if ($turma->curso->tipo === "Punível")
<div class="row mt-3">
    <div class="col-12 col-md-12 table-responsive">
        <table style="width: 100%;border: 1px solid #000;" class="table  table-bordered table-striped">
            <thead>
                <tr>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Iº Trimestre</th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">IIº Trimestre</th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">IIIº Trimestre</th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Resultados Finais</th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                </tr>
                <tr>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">Nº</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">Nº Processo</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;width: 200px">Nome Completo</th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Sexo</th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Data de Nascimento</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">PT1</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">PT2</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT1</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">F</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">PT1</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">PT2</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT2</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">F</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">PT1</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">PT2</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT3</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">F</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT1</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT2</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT3</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MFD</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;width: 200px">Observação</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($estudantes))
                @php $contador = 0; @endphp
                @foreach ($estudantes as $estudante)
                @php
                    $contador++;
                    $notas1 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre1->id, $anoLectivo->id);
                    $notas2 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre2->id, $anoLectivo->id);
                    $notas3 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre3->id, $anoLectivo->id);
                    $notas4 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre4->id, $anoLectivo->id);
                @endphp
                <tr>
                    <td style="padding: 3px;">{{ $contador }}</td>
                    <td style="padding: 3px;">{{ $estudante->estudante->numero_processo }}</td>
                    <td style="padding: 3px;">{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $estudante->estudante->nascimento }}</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas1->arredondar($notas1->mac) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas1->arredondar($notas1->npt) }}</td>
                    <td class="text-info" style="text-align: center;background-color: #eaeaea;padding: 3px;">{{ $notas1->arredondar($notas1->mt) }}</td>
                    <td style="text-align: center;padding: 3px;">-</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas2->arredondar($notas2->mac) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas2->arredondar($notas2->npt) }}</td>
                    <td class="text-info" style="text-align: center;background-color: #eaeaea;padding: 3px;">{{ $notas2->arredondar($notas2->mt) }} </td>
                    <td style="text-align: center;padding: 3px;">-</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas3->arredondar($notas3->mac) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas3->arredondar($notas3->npt) }}</td>
                    <td class="text-info" style="text-align: center;background-color: #eaeaea;padding: 3px;">{{ $notas3->arredondar($notas3->mt) }} </td>
                    <td style="text-align: center;padding: 3px;">-</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas1->arredondar($notas1->mt) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas2->arredondar($notas2->mt) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas3->arredondar($notas3->mt) }}</td>
                    <td style="text-align: center;background-color: #eaeaea;padding: 3px;"> {{ $notas4->arredondar($notas4->mfd) }} </td>
                    <td style="padding: 3px;">
                        @if ( $notas4->obs == "Não Apto")
                            <span style="color: red;">N/TRANSITA</span>
                        @else
                            <span style="color: blue;">TRANSITA</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                @endif
            </tbody>
        </table>
    </div>
</div>  
@endif

@if ($turma->curso->tipo === "Outros")
<div class="row mt-3">
    <div class="col-12 col-md-12 table-responsive">
        <table style="width: 100%;border: 1px solid #000;" class="table  table-bordered table-striped">
            <thead>
                <tr>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Iº Trimestre</th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">IIº Trimestre</th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">IIIº Trimestre</th>
                    <th colspan="4" class="text-center" style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Resultados Finais</th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;"></th>
                </tr>
                <tr>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">Nº</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">Nº Processo</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;width: 200px">Nome Completo</th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Sexo</th>
                    <th style="text-align: center;background-color: #336699;color: #ffffff;padding: 3px;">Data de Nascimento</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MAC</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">NPT</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">F</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MAC</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">NPT</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">F</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MAC</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">NPT</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">F</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT1</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT2</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MT3</th>
                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;">MFD</th>

                    <th style="text-align: left;background-color: #336699;color: #ffffff;padding: 3px;width: 200px">Observação</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($estudantes))
                @php $contador = 0; @endphp
                @foreach ($estudantes as $estudante)
                @php
                    $contador++;
                    $notas1 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre1->id, $anoLectivo->id);
                    $notas2 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre2->id, $anoLectivo->id);
                    $notas3 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre3->id, $anoLectivo->id);
                    $notas4 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->id, $trimestre4->id, $anoLectivo->id);
                @endphp
                <tr>
                    <td style="padding: 3px;">{{ $contador }}</td>
                    <td style="padding: 3px;">{{ $estudante->estudante->numero_processo }}</td>
                    <td style="padding: 3px;">{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $estudante->estudante->nascimento }}</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas1->arredondar($notas1->mac) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas1->arredondar($notas1->npt) }}</td>
                    <td class="text-info" style="text-align: center;background-color: #eaeaea;padding: 3px;">{{ $notas1->arredondar($notas1->mt) }}</td>
                    <td style="text-align: center;padding: 3px;">-</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas2->arredondar($notas2->mac) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas2->arredondar($notas2->npt) }}</td>
                    <td class="text-info" style="text-align: center;background-color: #eaeaea;padding: 3px;">{{ $notas2->arredondar($notas2->mt) }} </td>
                    <td style="text-align: center;padding: 3px;">-</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas3->arredondar($notas3->mac) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas3->arredondar($notas3->npt) }}</td>
                    <td class="text-info" style="text-align: center;background-color: #eaeaea;padding: 3px;">{{ $notas3->arredondar($notas3->mt) }} </td>
                    <td style="text-align: center;padding: 3px;">-</td>

                    <td style="text-align: center;padding: 3px;">{{ $notas1->arredondar($notas1->mt) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas2->arredondar($notas2->mt) }}</td>
                    <td style="text-align: center;padding: 3px;">{{ $notas3->arredondar($notas3->mt) }}</td>
                    <td style="text-align: center;background-color: #eaeaea;padding: 3px;"> {{ $notas4->arredondar($notas4->mfd) }} </td>
                    <td style="padding: 3px;">
                        @if ( $notas4->obs == "Não Apto")
                            <span style="color: red;">N/TRANSITA</span>
                        @else
                            <span style="color: blue;">TRANSITA</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endif
