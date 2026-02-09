<table style="border: 1px solid #000">
    <thead>
        <tr>
            <th colspan="11">
                <img src="{{ $logotipo ?? 'assets/images/insigna.png' }}" width="60px" height="60px">
            </th>
        </tr>
        <tr>
            <th style="text-align: center;text-transform: uppercase;padding-top: 10px;" colspan="11">República de Angola</th>
        </tr>
        <tr>
            <th style="text-align: center;text-transform: uppercase;padding-top: 10px;" colspan="11">Governo Provincial de Luanda</th>
        </tr>
        <tr>
            <th style="text-align: center;text-transform: uppercase;padding-top: 10px;font-size: 15pt" colspan="11">{{ $escola->nome }}</th>
        </tr>
        <tr>
            <th style="text-align: center;text-transform: uppercase;padding-top: 10px;color: red;font-size: 15pt" colspan="11">PAUTA</th>
        </tr>
        
        <tr>
            <th colspan="5" style="border: 1px solid #000000;text-transform: uppercase">TURMA: {{ $turma->turma }}</th>
            <th colspan="6" style="border: 1px solid #000000;text-transform: uppercase">DISCIPLINA: {{ $disciplina->disciplina }}</th>
        </tr>
        <tr>
            <th colspan="3" style="border: 1px solid #000000;text-transform: uppercase">PERÍODO: {{ $trimestre->trimestre }}</th>
            <th colspan="2" style="border: 1px solid #000000;text-transform: uppercase">ANO LECTIVO: {{ $anoLectivo->ano }}</th>
            <th colspan="3" style="border: 1px solid #000000;text-transform: uppercase">TURNO: {{ $turma->turno->turno }}</th>
            <th colspan="3" style="border: 1px solid #000000;text-transform: uppercase">SALA: {{ $turma->classe->classes }}</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000;">Nº</th>
            <th style="border: 1px solid #000000;">Nº Processo</th>
            <th style="border: 1px solid #000000;">Nome Completo</th>
            <th style="border: 1px solid #000000;text-align: center;">Genero</th>
            <th style="border: 1px solid #000000;text-align: center;">Data Nascimento</th>
            <th style="border: 1px solid #000000;text-align: center;">Idade</th>
            @if ($turma->curso->tipo === "Técnico")
                <th style="border: 1px solid #000000;text-align: center;">MAC</th>
                <th style="border: 1px solid #000000;text-align: center;">NPP</th>
                <th style="border: 1px solid #000000;text-align: center;">NPT</th>
            @endif
            
            @if ($turma->curso->tipo === "Punível")
                <th style="border: 1px solid #000000;text-align: center;">P1</th>
                <th style="border: 1px solid #000000;text-align: center;">P2</th>
                <th style="border: 1px solid #000000;text-align: center;">PT</th>
            @endif
            
            @if ($turma->curso->tipo === "Outros")
                <th style="border: 1px solid #000000;text-align: center;">MAC</th>
                <th style="border: 1px solid #000000;text-align: center;">NPT</th>
            @endif
            <th style="border: 1px solid #000000;">MT</th>
            <th style="border: 1px solid #000000;">Observação</th>
        </tr>

    </thead>
    <tbody>
        @php $contador = 0; @endphp
        @if ($notas)
            @foreach ($notas as $item)
            @php $contador++; @endphp
            <tr>
                <td style="border: 1px solid #000000;">{{ $contador }}</td>
                <td style="border: 1px solid #000000;">{{ $item->estudante->numero_processo }}</td>
                <td style="border: 1px solid #000000;" width="400px">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                <td style="border: 1px solid #000000;text-align: center;">{{ $item->estudante->sigla_genero($item->estudante->genero) }}</td>
                <td style="border: 1px solid #000000;text-align: center;">{{ $item->estudante->nascimento }}</td>
                <td style="border: 1px solid #000000;text-align: center;">{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                
                @if ($turma->curso->tipo === "Técnico")
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->mac }}</td>
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->npp }}</td>
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->npt }}</td>
                @endif
                
                @if ($turma->curso->tipo === "Punível")
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->mac }}</td>
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->npp }}</td>
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->npt }}</td>
                @endif
                
                @if ($turma->curso->tipo === "Outros")
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->mac }}</td>
                    <td style="border: 1px solid #000000;text-align: center;">{{ $item->npt }}</td>
                @endif
                
                
                @if ($item->controlo_trimestres_id == $trimestre1->id)
                    @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                        <td style="border: 1px solid #000000;text-align: center;color: #006699;">{{ $item->mt }}</td>
                        <td style="border: 1px solid #000000;text-align: center;color: #006699;">TRANSITA</td>
                    @else
                        <td style="border: 1px solid #000000;text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                        <td style="border: 1px solid #000000;text-align: center;color: #ff0000;">N/TRANSITA</td>
                    @endif
                @else

                    @if ($item->controlo_trimestres_id == $trimestre2->id)
                        @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                            <td style="border: 1px solid #000000;text-align: center;color: #006699;">{{ $item->mt }}</td>
                            <td style="border: 1px solid #000000;text-align: center;color: #006699;">TRANSITA</td>
                        @else
                            <td style="border: 1px solid #000000;text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                            <td style="border: 1px solid #000000;text-align: center;color: #ff0000;">N/TRANSITA</td>
                        @endif
                @else
                    @if ($item->controlo_trimestres_id == $trimestre3->id)
                            @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                <td style="border: 1px solid #000000;text-align: center;color: #006699;">{{ $item->mt }}</td>
                                <td style="border: 1px solid #000000;text-align: center;color: #006699;">TRANSITA</td>
                            @else
                                <td style="border: 1px solid #000000;text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                <td style="border: 1px solid #000000;text-align: center;color: #ff0000;">N/TRANSITA</td>
                            @endif
                        @endif
                    @endif
                @endif
                
            </tr>
            @endforeach
        @endif
        
        <tr> <td colspan="11"></td></tr>
        <tr> <td colspan="11"></td></tr>
        <tr> <td colspan="11"></td></tr>
        <tr> <td colspan="11"></td></tr>
        
        <tr>
            <td colspan="11" style="text-align: center;text-transform: uppercase;padding-top: 10px;font-size: 12pt">Luanda aos, {{ \Carbon\Carbon::now()->format("Y/m/d") }}</td>
        </tr>
        
        <tr>
            <td colspan="5" style="text-align: center;text-transform: uppercase;padding-top: 10px;font-size: 12pt">O (A) PROFESSOR DA DISCIPLINA</td>
            <td colspan="6" style="text-align: center;text-transform: uppercase;padding-top: 10px;font-size: 12pt">O SUBDIRECTOR PEDAGOGICO</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;text-transform: uppercase;padding-top: 10px;font-size: 12pt">____________________________________</td>
            <td colspan="6" style="text-align: center;text-transform: uppercase;padding-top: 10px;font-size: 12pt">____________________________________</td>
        </tr>
    </tbody>
</table>
