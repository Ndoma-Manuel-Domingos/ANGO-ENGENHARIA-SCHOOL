@if ($disciplinas)
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center" colspan="{{ count($disciplinas) * 4 + 6 }}">REPÚBLICA DE ANGOLA</th>
            </tr>
            <tr>
                <th style="text-align: center" colspan="{{ count($disciplinas) * 4 + 6 }}">GOVERNO PROVINCIAL DE LUANDA</th>
            </tr>
            <tr>
                <th style="text-align: center" colspan="{{ count($disciplinas) * 4 + 6 }}">{{ $escola->nome }}</th>
            </tr>
            <tr><th colspan="{{ count($disciplinas) * 4 + 6 }}"></th></tr>
            <tr>
                <th style="border: 1px solid #000;" colspan="{{ count($disciplinas) * 4 + 6 }}"><strong>{{ $turma->classe->classes }}</strong></th>
            </tr>
            <tr>
                <th style="border: 1px solid #000;" colspan="{{ count($disciplinas) * 4 + 6 }}"><strong>CURSO: {{ $turma->curso->curso }}</strong></th>
            </tr>
            <tr>
                <th style="border: 1px solid #000;" colspan="5"><strong>TURMA: {{ $turma->turma }}</strong></th>
                @foreach ($disciplinas as $disciplina)
                    <th colspan="4" style="border: 1px solid #000;text-align: center;background-color: #004500;color: white"><strong>{{ $disciplina->disciplina->abreviacao }}</strong></th>
                @endforeach
                <th rowspan="3" style="border: 1px solid #000;text-align: center;background-color: #004500;color: white"><strong>Resultados</strong></th>
            </tr>
    
            <tr>
                <th style="border: 1px solid #000;" rowspan="2"><strong>Nº</strong></th>
                <th style="border: 1px solid #000;" rowspan="2"><strong>Nome Completo</strong></th>
                <th style="border: 1px solid #000;text-align: center" rowspan="2"><strong>Processo</strong></th>
                <th style="border: 1px solid #000;text-align: center" rowspan="2"><strong>Genero</strong></th>
                <th style="border: 1px solid #000;text-align: center" rowspan="2"><strong>Nascimento</strong></th>
                @foreach ($disciplinas as $disciplina)
                    <th colspan="4" style="border: 1px solid #000;text-align: center;background-color: #004500;color: white"><strong>{{ $turma->classe->classes }}</strong></th>
                @endforeach
            </tr>
            <tr>
                @foreach ($disciplinas as $disciplina)
                    <th style="border: 1px solid #000;background-color: #004500;color: white">
                        <strong>MTI</strong>
                    </th>
                    <th style="border: 1px solid #000;background-color: #004500;color: white">
                        <strong>MTII</strong>
                    </th>
                    <th style="border: 1px solid #000;background-color: #004500;color: white">
                        <strong>MTIII</strong>
                    </th>
                    <th style="border: 1px solid #000;background-color: yellow;color: #000;">
                        <strong>MFD</strong>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $contador = 0;
            @endphp
            @foreach ($estudantes as $key => $estudante)
                @php $contador++; @endphp
                <tr {{ $contador % 2 == 0 ? 'style=background-color:#6a6a6a' : 'style=background-color:#434343' }}>
                    <td style="border: 1px solid #000;text-align: center;">{{ $contador }}</td>
                    <td style="border: 1px solid #000;width: 270px">{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                    <td style="border: 1px solid #000;text-align: center;width: 100px">{{ $estudante->estudante->numero_processo }}</td>
                    <td style="border: 1px solid #000;text-align: center;">{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
                    <td style="border: 1px solid #000;text-align: center;width: 120px">{{ $estudante->estudante->nascimento }}</td>
                    @php
                        $soma_mfd = 0;
                        $total_disciplina = count($disciplinas);
                    @endphp
                    
                    @foreach ($disciplinas as $disciplina)
                        @php
                            $notas_t_1 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre1->id, $ano_lectivo->id);
                            $notas_t_2 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre2->id, $ano_lectivo->id);
                            $notas_t_3 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre3->id, $ano_lectivo->id);
                            $notas_t_4 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre4->id, $ano_lectivo->id);
                            $soma_mfd += $notas_t_4->mfd;
                        @endphp
                        <td style="border: 1px solid #000;text-align: center;color: {{ $notas_t_1->arredondar($notas_t_1->mt1) < 9.6 ? 'red' : 'blue' }}">{{ $notas_t_1->arredondar($notas_t_1->mt1) }}</td>
                        <td style="border: 1px solid #000;text-align: center;color: {{ $notas_t_2->arredondar($notas_t_2->mt2) < 9.6 ? 'red' : 'blue' }}">{{ $notas_t_2->arredondar($notas_t_2->mt2) }}</td>
                        <td style="border: 1px solid #000;text-align: center;color: {{ $notas_t_3->arredondar($notas_t_3->mt3) < 9.6 ? 'red' : 'blue' }}">{{ $notas_t_3->arredondar($notas_t_3->mt3) }}</td>
                        <td style="border: 1px solid #000;text-align: center;background-color: yellow;color: {{ $notas_t_4->arredondar($notas_t_4->mfd) < 9.6 ? 'red' : 'blue' }}">{{ $notas_t_4->arredondar($notas_t_4->mfd) }}</td>
                    @endforeach
                    
                    @if ( ($soma_mfd / $total_disciplina) < $turma->classe->tipo_avaliacao_nota)
                        <td style="color: red;border: 1px solid #000;text-align: center;width: 130px">N/TRANSITA</td>
                    @else
                        <td style="color: blue;border: 1px solid #000;text-align: center;width: 130px">TRANSITA</td>
                    @endif
                
                </tr>
            @endforeach
        </tbody>
    </table>
@endif