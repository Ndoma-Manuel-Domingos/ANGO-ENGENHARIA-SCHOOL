<table style="border: 1px solid #000">
    <thead>
        <tr>
            <th colspan="40" align="center"> <img src="{{ $logotipo ?? 'assets/images/insigna.png' }}" width="60px" height="60px" align="center"> </th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;" colspan="40">República de Angola</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;" colspan="40">Governo Provincial de Luanda</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 15pt" colspan="40">{{ $escola->nome }}</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 15pt" colspan="40">MINI PAUTA COM TODAS AS DISCIPLINAS</th>
        </tr>

        <tr>
            <th colspan="40"></th>
        </tr>


        @if ($disciplinasTurma)
        <tr>
            <th style="border: 1px solid #000000;" colspan="7">TURMA: {{ $turma->turma }}</th>
            <th style="border: 1px solid #000000;" colspan="8">PERÍODO: {{ $trimestre->trimestre }}</th>
            <th style="border: 1px solid #000000;" colspan="8">ANO LECTIVO: {{ $anoLectivo->ano }}</th>
            <th style="border: 1px solid #000000;" colspan="8">TURNO: {{ $turma->turno->turno }}</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000;"></th>
            <th style="border: 1px solid #000000;"></th>
            <th style="border: 1px solid #000000;"></th>
            @foreach ($disciplinasTurma as $itemDisciplina)
                @if ($turma->curso->tipo === "Outros")
                    <th colspan="3" style="text-align: center;border: 1px solid #000000;"> {{ $itemDisciplina->disciplina->abreviacao }}</th>
                @else
                    <th colspan="4" style="text-align: center;border: 1px solid #000000;"> {{ $itemDisciplina->disciplina->abreviacao }}</th>
                @endif
            @endforeach
        </tr>

        <tr>
            <th>Nº</th>
            <th>Nome Completo</th>
            <th>Sexo</th>
            @foreach ($disciplinasTurma as $itemDisciplina)
                
                @if ($turma->curso->tipo === "Técnico")
                    <th style="text-align: center;border: 1px solid #000000;">MAC</th>
                    <th style="text-align: center;border: 1px solid #000000;">NPP</th>
                    <th style="text-align: center;border: 1px solid #000000;">NPT</th>
                @endif
                
                @if ($turma->curso->tipo === "Punível")
                    <th style="text-align: center;border: 1px solid #000000;">P1</th>
                    <th style="text-align: center;border: 1px solid #000000;">P2</th>
                    <th style="text-align: center;border: 1px solid #000000;">PT</th>
                @endif
                
                @if ($turma->curso->tipo === "Outros")
                    <th style="text-align: center;border: 1px solid #000000;">MAC</th>
                    <th style="text-align: center;border: 1px solid #000000;">NPT</th>
                @endif
            
            <th style="border: 1px solid #000000;text-align: center;background-color: rgba(0,0,0,.1)">MT</th>
            @endforeach
        </tr>
        @endif

    </thead>
    <tbody>

        @php $soma = 0; @endphp

        @if ($estudantesTurma)
        @foreach ($estudantesTurma as $itemEstudante)
        
        @php $soma ++; @endphp
        <tr>
            <td style="border: 1px solid #000000">{{ $itemEstudante->estudante->numero_processo }}</td>
            <td style="border: 1px solid #000000">{{ $itemEstudante->estudante->nome }} {{ $itemEstudante->estudante->sobre_nome }}</td>
            <td style="border: 1px solid #000000">{{ $itemEstudante->estudante->sigla_genero($itemEstudante->estudante->genero) }}</td>
            
            @foreach ($disciplinasTurma as $itemDisciplina)
                @php
                $notas = (new App\Models\web\turmas\NotaPauta)::where('disciplinas_id', $itemDisciplina->disciplinas_id)
                    ->where('estudantes_id', $itemEstudante->estudantes_id)
                    ->where('controlo_trimestres_id', $trimestre->id)
                    ->where('turmas_id', $turma->id)
                    ->where('ano_lectivos_id', $anoLectivo->id)->get();
                @endphp
                @if ($notas)
                    @foreach ($notas as $itemNota)
                        @if ($turma->curso->tipo === "Técnico")
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->mac }}</td>
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->npp }}</td>
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->npt }}</td>
                        @endif
                        
                        @if ($turma->curso->tipo === "Punível")
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->mac }}</td>
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->npp }}</td>
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->npt }}</td>
                        @endif
                        
                        @if ($turma->curso->tipo === "Outros")
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->mac }}</td>
                            <td style="text-align: center;border: 1px solid #000000;">{{ $itemNota->npt }}</td>
                        @endif
                                                
                        @if ($itemNota->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                            <td style="text-align: center;color: #006699;border: 1px solid #000000;">{{ round($itemNota->mt) }}</td>
                        @else
                            <td style="text-align: center;color: #ff0000;border: 1px solid #000000;">{{ round($itemNota->mt) }}</td>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </tr>
        @endforeach
        @endif

        <tr>
            <td colspan="15" style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 12pt">O (A) DIRECTOR DA TURMA</td>
            <td colspan="15" style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 12pt">O SUBDIRECTOR PEDAGOGICO</td>
        </tr>
        <tr>
            <td colspan="15" style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 12pt">____________________________________</td>
            <td colspan="15" style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 12pt">____________________________________</td>
        </tr>
    </tbody>
</table>
