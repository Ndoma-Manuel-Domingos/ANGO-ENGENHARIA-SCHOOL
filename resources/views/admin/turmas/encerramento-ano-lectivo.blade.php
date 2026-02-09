@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Encerramento do Ano Lectivo: {{ $turma->turma ?? "" }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($turma->id)) }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Turma</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if ($disciplinas)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('pedagogicos.lancamento-notas') }}" class="btn btn-info mx-1 float-left"><i class="fas fa-redo-alt"></i> Actualizar notas</a>
                        <a href="{{ route('web.turmas-encerramento-ano-lectivo', [Crypt::encrypt($turma->id), 'encerrar']) }}" class="btn btn-dark mx-1 float-left"><i class="fas fa-power-off"></i> Concluir Encerramento</a>
                        <a href="{{ route('pedagogicos.estatistica-turmas-unica-pdf', ['turmas_id' => $turma->id ?? '', 'ano_lectivos_id' => $requests['ano_lectivos_id'] ?? '']) }}" class="btn btn-danger mx-1 float-right" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir PDF</a>
                        <a href="{{ route('pedagogicos.estatistica-turmas-unica-excel', ['turmas_id' => $turma->id ?? '', 'ano_lectivos_id' => $requests['ano_lectivos_id'] ?? '']) }}" class="btn btn-success mx-1 float-right" target="_blink"><i class="fas fa-file-excel"></i> Imprimir Excel</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table  table-bordered table-striped table-striped">
                            <thead>
                                <tr>
                                    <th colspan="{{ count($disciplinas) * 5 + 6 }}">{{ $turma->classe->classes }}</th>
                                </tr>
                                <tr>
                                    <th colspan="{{ count($disciplinas) * 5 + 6 }}" class="text-uppercase">CURSO: {{ $turma->curso->curso }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">TURMA: {{ $turma->turma }}</th>
                                    {{-- disciplinas --}}
                                    @foreach ($disciplinas as $disciplina)
                                        <th colspan="5" class="text-center"> {{ $disciplina->disciplina->abreviacao }}</th>
                                    @endforeach
                                    <th rowspan="3" class="align-middle bg-info text-center" colspan="2"> Resultados</th>
                                </tr>
    
                                <tr>
                                    <th rowspan="2" class="align-middle">Nº</th>
                                    <th rowspan="2" class="align-middle">Nome Completo</th>
                                    <th rowspan="2">
                                        <span class="align-middle">Processo</span>
                                    </th>
                                    <th rowspan="2">
                                        <span class="align-middle">Sexo</span>
                                    </th>
                                    {{-- disciplinas --}}
    
                                    @foreach ($disciplinas as $disciplina)
                                        <th colspan="5" class="text-center align-middle bg-primary">
                                            {{ $turma->classe->classes }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    {{-- disciplinas --}}
                                    @foreach ($disciplinas as $disciplina)
                                        <th class="align-middle">
                                            <span class="">MTI</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="">MTII</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="">MTIII</span>
                                        </th>
                                        <th class="align-middle">
                                            <span class="">MFD</span>
                                        </th>
                                        <th class="align-middle ">a)</th>
                                    @endforeach
    
                                </tr>
    
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $key => $estudante)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $estudante->estudante->nome }}
                                            {{ $estudante->estudante->sobre_nome }}</td>
                                        <td>{{ $estudante->estudante->numero_processo }}</td>
                                        <td>{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
    
                                        @php
                                            $soma_mfd = 0;
                                            $total_disciplina = count($disciplinas);
                                        @endphp
                                        
                                        {{-- dodos disciplinas --}}
                                        @foreach ($disciplinas as $disciplina)
                                            @php
                                                $notas_t_1 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre1->id, $ano_lectivo->id);
                                                $notas_t_2 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre2->id, $ano_lectivo->id);
                                                $notas_t_3 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre3->id, $ano_lectivo->id);
                                                $notas_t_4 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre4->id, $ano_lectivo->id);
                                                
                                                $soma_mfd += $notas_t_4->mfd;
                                            @endphp
    
                                            <td><strong>{{ $notas_t_1->arredondar($notas_t_1->mt) }}</strong></td>
                                            <td><strong>{{ $notas_t_2->arredondar($notas_t_2->mt) }}</strong></td>
                                            <td><strong>{{ $notas_t_3->arredondar($notas_t_3->mt) }}</strong></td>
                                            <td><strong>{{ $notas_t_4->arredondar($notas_t_4->mfd) }}</strong></td>
                                            <td class=""></td>
                                        @endforeach                                      
    
                                        @if ($notas_t_4->obs == "Não Apto")
                                            <td><span style="color: red;">N/TRANSITA</span></td>
                                            <td><strong>{{ $notas_t_4->arredondar($notas_t_4->mfd) }}</strong></td>
                                        @else
                                            <td><span style="color: blue;">TRANSITA</span></td>
                                            <td><strong>{{ $notas_t_4->arredondar($notas_t_4->mfd) }}</strong></td>
                                        @endif
    
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
</section>
<!-- /.content -->
@endsection
