@if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
<table style="width: 100%" class="table table-bordered">
    <thead>
        <tr>
            <th>Disciplina</th>
            <th>Semestre</th>
            <th>P1</th>
            <th>P2</th>
            <th>P3</th>
            <th>P4</th>
            <th>Média</th>
            <th>Obs</th>
            <th>Exame</th>
            <th>Resultado</th>
            <th>Recurso</th>
            <th>Exame Especial</th>
            <th>NF</th>
            <th>Estado</th>
        </tr>
    </thead>
    @php
    $somaNotasFinal = 0;
    @endphp
    <tbody>
        @if ($turmaDisciplinas)
        @foreach($turmaDisciplinas as $item)
        @php
        $notas1 = (new App\Models\web\turmas\NotaPauta)::where('estudantes_id', $estudante->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->where('disciplinas_id', $item->disciplinas_id)
        ->with(['ano', 'disciplina','trimestre'])
        ->orderBy('controlo_trimestres_id', 'desc')
        ->get();

        @endphp

        @foreach ($notas1 as $item1)
        @php
        $somaNotasFinal = $somaNotasFinal + $item1->resultado_final;
        @endphp
        <tr>
            <td>{{ $item1->disciplina->disciplina }} </td>
            <td>{{ $item1->trimestre->trimestre }} </td>

            <td>{{ $item1->p1 }}</td>
            <td>{{ $item1->p2 }}</td>
            <td>{{ $item1->p3 }}</td>
            <td>{{ $item1->p4 }}</td>
            <td>{{ $item1->med }}</td>
            <td>{{ $item1->obs1 }}</td>
            <td>{{ $item1->exame_1_especial }}</td>
            <td>{{ $item1->obs2 }}</td>
            <td>{{ $item1->recurso }}</td>
            <td>{{ $item1->exame_especial }}</td>
            <td>{{ $item1->resultado_final }}</td>
            @if ($item1->obs3 == 'Apto')
            <td class="text-success text-uppercase">{{ $item1->obs3 }}</td>
            @else
            <td class="text-danger text-uppercase">{{ $item1->obs3 }}</td>
            @endif
        </tr>
        @endforeach
        @endforeach
        @endif
    </tbody>

    <tfoot>
        @if ($turmaDisciplinas)
        <th colspan="11" class="text-center">Media Final CA <br> ({{ ($somaNotasFinal /  $totalDisciplinas) }})</th>
        <th class="text-center">Resultado Final <br>
            (@if (($somaNotasFinal / $totalDisciplinas) >= 10)
            <span class="text-info">Aprovado</span>
            @else
            <span class="text-danger">Reprovado</span>
            @endif)
        </th>
        @endif
    </tfoot>
</table>
@else
<table style="width: 100%" class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th colspan="3" class="text-center">Iª Trimestre</th>
            <th colspan="3" class="text-center">IIª Trimestre</th>
            <th colspan="3" class="text-center">IIIª Trimestre</th>
            <th></th>
            <th></th>
        </tr>

        <tr>
            <th>Disciplina</th>

            <th>MAC</th>
            <th>NPT</th>
            <th class="text-info">MT</th>

            <th>MAC</th>
            <th>NPT</th>
            <th class="text-info">MT</th>

            <th>MAC</th>
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
        $notas1 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre1->id)
        ->where('estudantes_id', $estudante->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->where('disciplinas_id', $item->disciplinas_id)
        ->with(['disciplina'])
        ->get();

        $notas2 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre2->id)
        ->where('estudantes_id', $estudante->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->where('disciplinas_id', $item->disciplinas_id)
        ->with(['disciplina'])
        ->get();


        $notas3 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre3->id)
        ->where('estudantes_id', $estudante->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->where('disciplinas_id', $item->disciplinas_id)
        ->with(['disciplina'])
        ->get();

        $notas4 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre4->id)
        ->where('estudantes_id', $estudante->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->where('disciplinas_id', $item->disciplinas_id)
        ->with(['disciplina'])
        ->get();

        @endphp

        @foreach ($notas1 as $item1)
        @foreach ($notas2 as $item2)
        @foreach ($notas3 as $item3)
        @foreach ($notas4 as $item4)
        @if ($item1->conf_pro == "nao" OR $item1->conf_ped == "nao")
        <tr class="bg-danger">
            <td>{{ $item1->disciplina->disciplina }} </td>

            <td>{{ $item1->mac }}</td>
            <td>{{ $item1->npt }}</td>
            <td class="bg-warning">{{ $item1->mt }}</td>

            <td>{{ $item2->mac }}</td>
            <td>{{ $item2->npt }}</td>
            <td class="bg-warning">{{ $item2->mt }}</td>

            <td>{{ $item3->mac }}</td>
            <td>{{ $item3->npt }}</td>
            <td class="bg-warning">{{ $item3->mt }}</td>

            <td class="bg-success">{{ $item4->mfd }}</td>
            @if ($item4->mfd >= 10)
            <td class="text-info">Transita</td>
            @else
            <td class="text-danger">N/Transita</td>
            @endif
        </tr>
        @else
        <tr class="">
            <td>{{ $item1->disciplina->disciplina }} </td>

            <td>{{ $item1->mac }}</td>
            <td>{{ $item1->npt }}</td>
            <td class="bg-warning">{{ $item1->mt }}</td>

            <td>{{ $item2->mac }}</td>
            <td>{{ $item2->npt }}</td>
            <td class="bg-warning">{{ $item2->mt }}</td>

            <td>{{ $item3->mac }}</td>
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
        @if ($turmaDisciplinas)
        <th colspan="10" class="text-center">Media Final CA <br> ({{ $totalDisciplinas != 0 ? ($somaMFD /  $totalDisciplinas) : 0 }})</th>
        <th class="text-center">Resultado Final <br>
            @if ($totalDisciplinas != 0)
            (@if ((($somaMFD / $totalDisciplinas) >= 10))
            <span class="text-info">Aprovado</span>
            @else
            <span class="text-danger">Reprovado</span>
            @endif)
            @else
            <span class="text-danger">Indefinido</span>
            @endif
        </th>
        @endif
    </tfoot>
</table>
@endif
