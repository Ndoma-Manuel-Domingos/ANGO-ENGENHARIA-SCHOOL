{{-- table 03 --}}
<div class="col-12 col-md-12">
    <div class="card">
    
        <div class="card-header text-end">
            <a href="{{ route('web.formulario.imprimir-turmas-turnos-relatorio', 
                ['ano_lectivo' => $anolectivoactual, 'ensino' => $ensino->id]) }}" class="btn btn-danger" target="_blink"><i class="fas fa-file-pdf"></i> PDF</a>
        </div>
        
        <div class="card-body">
            <table id="example1" style="width: 100%"
                class="table table-bordered  ">
                <thead>
                    <tr>
                        <th colspan="8" class="text-center bg-info">Quadro 3. Turnos, Turma por classe</th>
                    </tr>
                    <tr>
                        <th rowspan="2"></th>
                        @foreach ($turnos as $item)
                        <th colspan="2" class="text-center">{{ $item->turno->turno }}</th>
                        @endforeach
                        <th rowspan="2" class="text-center">Total Turmas</th>
                    </tr>

                    <tr>
                        @foreach ($turnos as $item)
                        <th>Turmas</th>
                        <th>Turnos</th>
                        @endforeach
                    </tr>

                </thead>
                <tbody id="">
                    @php
                    $total_geral = 0;
                    @endphp
                    @foreach ($classes as $classe)
                    <tr>
                        @php
                        $total_turma_final = 0;
                        @endphp
                        <td>{{ $classe->classes }}</td>
                        @foreach ($turnos as $turno)
                        @php

                        $total_turno = App\Models\web\turmas\Turma::where('shcools_id',
                        $escola->id)->where('ano_lectivos_id', $anolectivoactual)
                        ->where('turnos_id', $turno->turno->id)
                        ->where('classes_id', $classe->id)
                        ->distinct('turnos_id')
                        ->count();

                        $total_turma = App\Models\web\turmas\Turma::where('shcools_id',
                        $escola->id)->where('ano_lectivos_id', $anolectivoactual)
                        ->where('turnos_id', $turno->turno->id)
                        ->where('classes_id', $classe->id)
                        ->distinct('id')
                        ->count();

                        $total_turma_final += $total_turma;
                        @endphp
                        <td>{{ $total_turma }}</td>
                        <td>{{ $total_turno }}</td>
                        @endforeach
                        <td>{{ $total_turma_final }}</td>
                    </tr>
                    @php
                    $total_geral += $total_turma_final;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>