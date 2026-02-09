@extends('layouts.provinciais')

@section('content')
@php
// $escolaId = $escolaId;

if (isset($pesquisa_ano)) {
$ano_value = $pesquisa_ano;
}else{
$ano_value = $ano_lectivo;
}

$trimestre1 = App\Models\web\anolectivo\ControlePeriodico::where('trimestre', '=', 'Iª Trimestre')->first();

$trimestre2 = App\Models\web\anolectivo\ControlePeriodico::where('trimestre', '=', 'IIª Trimestre')->first();

$trimestre3 = App\Models\web\anolectivo\ControlePeriodico::where('trimestre', '=', 'IIIª Trimestre')->first();
@endphp

<div class="container-fluid">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Gestão de Pautas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                        <li class="breadcrumb-item active">Mini-pautas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('app.planificacao-provincial-pesquisa-mini-pauta') }}" method="get" class="row" id="formulario_pesquisa_notas">
                                @csrf
                                <div class="form-group col-md-3">
                                    <label for="escola_id" class="form-label">Escolas</label>
                                    @if ($escolas)
                                    <select name="escola_id" id="escola_id" class="form-control escola_id select2" style="width: 100%">
                                        <option value="">Escolas</option>
                                        @foreach ($escolas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('escola_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                    @endif
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="ano_lectivos_id" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2" style="width: 100%">
                                        <option value="">Ano Lectivo</option>
                                    </select>
                                    @error('escola_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-2">
                                    <label for="turmas_id" class="form-label">Turma</label>
                                    <select name="turmas_id" id="turmas_id" class="form-control turmas_id select2" style="width: 100%">
                                        <option value="">Turma</option>
                                    </select>
                                    @error('turmas_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="disciplinas_id" class="form-label">Disciplinas</label>
                                    <select name="disciplinas_id" id="disciplinas_id" class="form-control disciplinas_id select2" style="width: 100%">
                                        <option value="">Disciplinas</option>
                                    </select>
                                    @error('disciplinas_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="condicao_pesquisar" class="form-label">Condição de Listagem</label>
                                    <select name="condicao_pesquisar" class="form-control condicao_pesquisar select2" style="width: 100%">
                                        <option value="">Condição</option>
                                        <option value="1">Disciplina Selecionada</option>
                                        <option value="2">Todas Disciplina</option>
                                    </select>
                                    @error('condicao_pesquisar')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-1">
                                    <label for="trimestre_id" class="form-label">Trimestres</label>
                                    @if ($trimestres)
                                    <select name="trimestre_id" id="trimestre_id" class="form-control trimestre_id select2" style="width: 100%">
                                        <option value="">Trimestre</option>
                                        @foreach ($trimestres as $item)
                                        <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                        @endforeach
                                    </select>
                                    @error('trimestre_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                    @endif
                                </div>
                            </form>
                        </div>

                        <div class="card-footer">
                            <button type="submit" form="formulario_pesquisa_notas" class="btn btn-primary" id="pesquisarMiniPautaEXMPLOOOOOO "><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->


        @if (isset($pesquisa_condicao))
        @if ($pesquisa_condicao == 2)

        <div class="container-fluid">

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="fs-6 d-flex py-2 px-0">
                            <li>
                                <strong>Turma: </strong> <span class="span_turma">{{ $pesquisa_turma->turma }}</span>. &nbsp;
                            </li>
                            <li>
                                <strong>Período: </strong> <span class="span_trimestre">{{ $pesquisa_trimestre->trimestre }}</span>. &nbsp;
                            </li>
                            <li>
                                <strong>Escola: </strong> <span class="span_trimestre">{{ $pesquisa_escola->nome }}</span>. &nbsp;
                            </li>
                            <li>
                                <strong>Ano Lectivo: </strong> <span class="span_trimestre">{{ $pesquisa_ano_lectivo->ano }}</span>. &nbsp;
                            </li>
                        </ul>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table  table-bordered table-striped table-striped">

                            @if (isset($pesquisa_turma) and isset($pesquisa_trimestre) and isset($pesquisa_disciplina) and isset($pesquisa_condicao))
                            @php
                            $disciplinasTurma = (new App\Models\web\turmas\DisciplinaTurma())::where('turmas_id', $pesquisa_turma->id)->get();
                            $disciplinasTotal = (new App\Models\web\turmas\DisciplinaTurma())::where('turmas_id', $pesquisa_turma->id)->count();
                            $estudantesTurma = (new App\Models\web\turmas\EstudantesTurma())::where('turmas_id', $pesquisa_turma->id)->get();
                            @endphp

                            <thead>
                                @if ($disciplinasTurma)
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    @foreach ($disciplinasTurma as $itemDisciplina)
                                    @php
                                    $disciplina = (new
                                    App\Models\web\disciplinas\Disciplina())::findOrFail($itemDisciplina->disciplinas_id);
                                    @endphp
                                    <th colspan="4" style="text-align: center;background-color: rgba(0,0,0,.1)">
                                        {{ $disciplina->abreviacao }}</th>
                                    @endforeach
                                </tr>

                                <tr>
                                    <th>Nº</th>
                                    <th>Estudante</th>
                                    <th>Sexo</th>
                                    @foreach ($disciplinasTurma as $itemDisciplina)
                                    @php
                                    $disciplina = (new
                                    App\Models\web\disciplinas\Disciplina())::findOrFail($itemDisciplina->disciplinas_id);
                                    @endphp
                                    <th>MAC</th>
                                    <th>NPT</th>
                                    <th style="text-align: center;background-color: rgba(0,0,0,.1)">MT</th>
                                    @endforeach
                                </tr>
                                @endif
                            </thead>

                            <body>
                                @php
                                $soma = 0;
                                @endphp

                                @if ($estudantesTurma)
                                @foreach ($estudantesTurma as $itemEstudante)
                                @if ($itemEstudante->estudantes_id)
                                @php
                                $soma++;
                                $estudante = (new App\Models\web\estudantes\Estudante())::findOrFail($itemEstudante->estudantes_id);
                                @endphp
                                <tr>
                                    <td>0{{ $soma }}</td>
                                    <td>{{ $estudante->nome }} {{ $estudante->sobre_nome }}</td>
                                    @if ($estudante->genero == 'Masculino')
                                    <td>M</td>
                                    @else
                                    <td>F</td>
                                    @endif

                                    @foreach ($disciplinasTurma as $itemDisciplina)
                                    @php
                                    $notas = (new App\Models\web\turmas\NotaPauta())::where('disciplinas_id', $itemDisciplina->disciplinas_id)
                                    ->where('estudantes_id', $itemEstudante->estudantes_id)
                                    ->where('controlo_trimestres_id', $pesquisa_trimestre->id)
                                    ->where('turmas_id', $pesquisa_turma->id)
                                    ->where('ano_lectivos_id', $pesquisa_ano)
                                    ->get();
                                    @endphp
                                    @if ($notas)
                                    @foreach ($notas as $itemNota)
                                    <td>{{ $itemNota->mac }}</td>
                                    <td>{{ $itemNota->npt }}</td>
                                    @if ($itemNota->controlo_trimestres_id == $trimestre1->id)
                                    @if ($itemNota->status_nota1 == 1)
                                    @if ($itemNota->mt >= 10)
                                    <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                    @else
                                    <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                    @endif
                                    @else
                                    <td style="background-color: #a8a8a8;"></td>
                                    @endif
                                    @else
                                    @if ($itemNota->controlo_trimestres_id == $trimestre2->id)
                                    @if ($itemNota->status_nota2 == 1)
                                    @if ($itemNota->mt >= 10)
                                    <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                    @else
                                    <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                    @endif
                                    @else
                                    <td style="background-color: #a8a8a8;"></td>
                                    @endif
                                    @else
                                    @if ($itemNota->controlo_trimestres_id == $trimestre3->id)
                                    
                                    @if ($itemNota->status_nota3 == 1)
                                    @if ($itemNota->mt >= 10)
                                    <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                    @else
                                    <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                    @endif
                                    @else
                                    <td style="background-color: #a8a8a8;"></td>
                                    @endif
                                    @endif
                                    @endif
                                    
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tr>
                                @endif
                                @endforeach
                                @endif
                            </body>
                        </table>
                        <h1><a href="{{ route('ficha-mini-pauta-todas', ['turma' => $pesquisa_turma->id, 'trimestre' => $pesquisa_trimestre->id]) }}" class="btn btn-primary" target="_blink"><i class="fas fa-print"></i> Imprimir</a></h1>
                    </div>
                </div>
            </div>

            @endif

        </div>
        @else
        @php
        if (isset($pesquisa_disciplina->id) and isset($pesquisa_trimestre->id) and isset($pesquisa_ano) and isset($pesquisa_turma->id)) {
        $notas = (new App\Models\web\turmas\NotaPauta())
        ::where([
        ['tb_notas_pautas.disciplinas_id', '=', $pesquisa_disciplina->id],
        ['tb_notas_pautas.controlo_trimestres_id', '=', $pesquisa_trimestre->id],
        ['tb_notas_pautas.ano_lectivos_id', '=', $pesquisa_ano],
        ['tb_notas_pautas.turmas_id', '=', $pesquisa_turma->id]
        ])
        ->join('users', 'tb_notas_pautas.funcionarios_id', '=', 'users.id')
        ->join('tb_estudantes', 'tb_notas_pautas.estudantes_id', '=', 'tb_estudantes.id')
        ->select('tb_notas_pautas.status_nota1','tb_notas_pautas.status_nota2','tb_notas_pautas.status_nota3','tb_notas_pautas.controlo_trimestres_id','tb_notas_pautas.id',
        'tb_notas_pautas.av1', 'tb_notas_pautas.av2', 'tb_notas_pautas.av3', 'tb_notas_pautas.av4',
        'tb_notas_pautas.av5', 'tb_notas_pautas.av6', 'tb_notas_pautas.av7', 'tb_notas_pautas.av8',
        'tb_notas_pautas.av9', 'tb_notas_pautas.status', 'tb_notas_pautas.mac', 'tb_notas_pautas.npp',
        'tb_notas_pautas.npt', 'tb_notas_pautas.mt', 'tb_estudantes.nome', 'tb_estudantes.sobre_nome',
        'tb_estudantes.genero', 'users.usuario')
        ->get();
        }
        @endphp
        <div class="container-fluid">
            @if ($notas)
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="fs-6 d-flex py-2 px-0">
                            <li><strong>Turma: </strong> <span class="span_turma">{{ $pesquisa_turma->turma }}</span>.
                                &nbsp; </li>
                            <li><strong>Disciplina: </strong><span class="span_disciplina">{{
                                                $pesquisa_disciplina->disciplina }}</span>. &nbsp; </li>
                            <li><strong>Período: </strong> <span class="span_trimestre">{{ $pesquisa_trimestre->trimestre
                                                }}</span>. &nbsp; </li>
                            <li><strong>Escola: </strong> <span class="span_trimestre">{{ $pesquisa_escola->nome
                                            }}</span>. &nbsp; </li>
                            <li><strong>Ano Lectivo: </strong> <span class="span_trimestre">{{ $pesquisa_ano_lectivo->ano
                                            }}</span>. &nbsp; </li>
                        </ul>
                    </div>

                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table  table-bordered table-striped table-striped">
                            <thead>

                                <tr>
                                    <th>Nº</th>
                                    <th>Nome Completo</th>
                                    <th>Sexo</th>

                                    <th>MAC</th>
                                    <th>NPP</th>
                                    <th>NPT</th>
                                    <th>MT</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($notas as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                                    @if ($item->genero == 'Masculino')
                                    <td>M</td>
                                    @else
                                    <td>F</td>
                                    @endif
                                    <td>{{ $item->mac }}</td>
                                    <td>{{ $item->npp }}</td>
                                    <td>{{ $item->npt }}</td>

                                    @if ($item->controlo_trimestres_id == $trimestre1->id)
                                    @if ($item->status_nota1 == 1)
                                    @if ($item->mt >= 10)
                                    <td style="color: #006699;">{{ $item->mt }}</td>
                                    @else
                                    <td style="color: #ff0000;">{{ $item->mt }}</td>
                                    @endif
                                    @else
                                    <td style="background-color: #a8a8a8;"></td>
                                    @endif
                                    @else
                                    @if ($item->controlo_trimestres_id == $trimestre2->id)
                                    @if ($item->status_nota2 == 1)
                                    @if ($item->mt >= 10)
                                    <td style="color: #006699;">{{ $item->mt }}</td>
                                    @else
                                    <td style="color: #ff0000;">{{ $item->mt }}</td>
                                    @endif
                                    @else
                                    <td style="background-color: #a8a8a8;"></td>
                                    @endif
                                    @else
                                    @if ($item->controlo_trimestres_id == $trimestre3->id)
                                    @if ($item->status_nota3 == 1)
                                    @if ($item->mt >= 10)
                                    <td style="color: #006699;">{{ $item->mt }}</td>
                                    @else
                                    <td style="color: #ff0000;">{{ $item->mt }}</td>
                                    @endif
                                    @else
                                    <td style="background-color: #a8a8a8;"></td>
                                    @endif
                                    @endif
                                    @endif

                                    @endif

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h1><a href="{{ route('ficha-mini-pauta', [ 'turma' => $pesquisa_turma->id, 'disciplina' => $pesquisa_disciplina->id,  'trimestre' => $pesquisa_trimestre->id, ]) }}" class="btn btn-primary" target="_blink"> <i class="fas fa-print"></i> Imprimir</a></h1>
                    </div>

                </div>
            </div>
            @endif
        </div>
        @endif
        @endif

    </section>


</div>

@endsection


@section('scripts')
<script>
    $("#escola_id").change(() => {
        let id = $("#escola_id").val();
        $.get('../carregar-todos-anolectivos-escolas/' + id, function(data) {
            $("#ano_lectivos_id").html("")
            $("#ano_lectivos_id").html(data)
        })
    })

    $("#ano_lectivos_id").change(() => {
        let id = $("#ano_lectivos_id").val();
        $.get('../carregar-todas-turmas-anolectivos-escolas/' + id, function(data) {
            $("#turmas_id").html("")
            $("#turmas_id").html(data)
        })
    })

    $("#turmas_id").change(() => {
        let id = $("#turmas_id").val();
        $.get('../carregar-disciplinas-turma/' + id, function(data) {
            $("#disciplinas_id").html("")
            $("#disciplinas_id").html(data)
        })
    })

</script>
@endsection
