@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"> Mais informações da Turma: <span class="text-dark">{{ $turma->turma ?? "" }}</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.turmas') }}">Turmas</a></li>
                    <li class="breadcrumb-item active">Detalhes da turma</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if (Auth::user()->can('read: professores'))
                        <a href="{{ route('dow.professores_turmas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Professores</a>
                        @endif

                        @if (Auth::user()->can('read: estudante'))
                        <a href="{{ route('dow.estudantes_turmas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Estudantes</a>
                        <a href="{{ route('estudantes-turmas-excel', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-success p-3 mb-1" target="_black"><i class="fas fa-file-excel"></i> Estudantes</a>
                        <a href="{{ route('dow.estudantes_turmas_gen_nas',  Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Estudantes Por Data Nas. Genero</a>
                        @endif

                        @if (Auth::user()->can('read: matricula'))
                        <a href="{{ route('dow.matriculas-turmas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Matriculas</a>
                        @endif
                        @if (Auth::user()->can('read: confirmacao'))
                        <a href="{{ route('dow.confirmacoes-turmas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Confirmações</a>
                        @endif

                        @if (Auth::user()->can('read: pagamento'))
                        <a href="{{ route('dow.controlo-propinas-turmas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Controle Propinas</a>
                        @endif

                        @if (Auth::user()->can('read: servicos'))
                        <a href="{{ route('turma-servicos-imprmir',  Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Lista Serviços</a>
                        @endif

                        @if (Auth::user()->can('read: horario'))
                        <a href="{{ route('turma-horarios-imprmir', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Lista Horário</a>
                        @endif

                        @if (Auth::user()->can('read: disciplina'))
                        <a href="{{ route('turma-disciplinas-imprmir', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1" target="_black"><i class="fas fa-file-pdf"></i> Lista Disciplinas</a>
                        @endif

                        @if (Auth::user()->can('create: turma'))
                        <a href="{{ route('web.turmas-configuracao', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1"><i class="fas fa-cog"></i> Configurar Turma</a>
                        @endif

                        @if (Auth::user()->can('create: turma'))
                        <a href="{{ route('web.adicionar-estuantes-turmas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1"><i class="fas fa-users"></i> Adicionar Estudante</a>
                        @endif

                        @if (Auth::user()->can('read: turma'))
                        <a href="{{ route('web.turmas-encerramento-ano-lectivo', Crypt::encrypt($turma->id)) }}" title="Encerramento do Ano Lectivo para esta turma" class="btn btn-app bg-danger p-3 mb-1"><i class="fas fa-power-off"></i> Encerrar Ano Lectivo</a>
                        <a href="{{ route('web.turmas-actualizar-multas', Crypt::encrypt($turma->id)) }}" class="btn btn-app bg-info p-3 mb-1"><i class="fas fa-file-pdf"></i> Actualizar Multas/Pagamentos</a>
                        @if ($turma->grade_curricular == true)
                        <a href="#" id="{{ Crypt::encrypt($turma->id) }}" class="btn btn-app bg-danger p-3 mb-1 criar_grade_curricular_pautas"><i class="fas fa-file-pdf"></i>
                            Remover Grade Curricular (Pautas)
                        </a>
                        @endif
                        @if ($turma->grade_curricular == false)
                        <a href="#" id="{{ Crypt::encrypt($turma->id) }}" class="btn btn-app bg-primary p-3 mb-1 criar_grade_curricular_pautas"><i class="fas fa-file-pdf"></i>
                            Criar Grade Curricular (Pautas)
                        </a>
                        @endif
                        <a href="#" id="{{ Crypt::encrypt($turma->id) }}" class="btn btn-app bg-success p-3 mb-1 actualizar_grade_curricular_pautas"><i class="fas fa-file-pdf"></i>
                            Actualizar Grade Curricular (Pautas)
                        </a>

                        <a href="{{ route('pedagogicos.estatistica-turmas-unica-pdf', ['turmas_id' => $turma->id, 'ano_lectivos_id' => $turma->ano_lectivos_id]) }}" target="_blank" class="btn btn-app bg-danger p-3 mb-1"><i class="fas fa-file-pdf"></i>
                            Visualizar Grade Curricular (Pautas)
                        </a>
                        @endif

                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>


        <div class="row">
            @if ($servicos_turma)
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-6"> Lista dos Serviços</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" id="tabela_servicos" class="table table-bordered table-striped">
                            <thead>
                                <th class="text-center">Nº </th>
                                <th>Serviço</th>
                                <th>Estado</th>
                                <th title="Pagamento">Dia Inicial</th>
                                <th title="Pagamento">Dia Final</th>

                                <th title="Pagamento">Data Inicial</th>
                                <th title="Pagamento">Data Final</th>

                                <th title="Pagamento">de:</th>
                                <th title="Pagamento">Multa 1º</th>

                                <th title="Pagamento">de:</th>
                                <th title="Pagamento">Multa 2º</th>

                                <th title="Pagamento">de:</th>
                                <th title="Pagamento">Multa 3º</th>

                                <th>Preço</th>
                                <th title="Tipo de pagamento">T. Pag</th>
                                <th>Acções</th>
                            </thead>
                            <tbody>
                                @foreach ($servicos_turma as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }} </td>
                                    <td>{{ $item->servico->servico ?? "" }} </td>
                                    <td>{{ $item->status }} </td>
                                    <td>dia {{ $item->intervalo_pagamento_inicio }} </td>
                                    <td>até {{ $item->intervalo_pagamento_final }} </td>
                                    <td>{{ $item->data_inicio }} </td>
                                    <td>{{ $item->data_final }} </td>

                                    <td>{{ $item->taxa_multa1_dia }} dia(s) </td>
                                    <td>{{ $item->taxa_multa1 }} % do preço</td>

                                    <td>{{ $item->taxa_multa2_dia }} dia(s) </td>
                                    <td>{{ $item->taxa_multa2 }} % do preço</td>

                                    <td>{{ $item->taxa_multa3_dia }} dia(s) </td>
                                    <td>{{ $item->taxa_multa3 }} % do preço</td>

                                    <td>{{ number_format($item->preco, 2, ',', '.') }} Kz</td>
                                    <td>{{ $item->pagamento }}</td>

                                    <td>
                                        @if (Auth::user()->can('delete: servicos'))
                                        <a href="#" title="Remover serviço turma" id="{{ $item->id }}" class="deleteModal text-danger btn-sm"><i class="fa fa-times"></i></a>
                                        @endif
                                        @if (Auth::user()->can('update: servicos'))
                                        <a href="{{ route('financeiros.listagem-servicos-edit', Crypt::encrypt($item->id)) }}" title="Editar serviço turma" class="text-success btn-sm"><i class="fa fa-edit"></i></a>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if ($estudantes)
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-6"> Lista dos Estudantes</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <th class="text-center">Nº </th>
                                <th>Codigo</th>
                                <th>Nome Completo</th>
                                <th>Genero</th>
                                <th>Nascimento</th>
                                <th>Finalista</th>
                                <th>Telefone</th>
                                <th class="text-right" style="width: 350px">Acções</th>
                            </thead>
                            <tbody>
                                @php $numero = 0; @endphp
                                @foreach ($estudantes as $key => $item)
                                @php $numero = $numero + 1; @endphp
                                <tr>
                                    <td class="text-center">{{ $numero }} </td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{$item->estudante->id}}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{$item->estudante->nome}} {{$item->estudante->sobre_nome}} </a></td>
                                    <td>{{$item->estudante->genero}} </td>
                                    <td>{{$item->estudante->nascimento}}</td>
                                    <td>
                                        @if ($item->estudante->finalista == "N")
                                        <span class="badge bg-danger">NÃO</span>
                                        @endif
                                        @if ($item->estudante->finalista == "Y")
                                        <span class="badge bg-success">SIM</span>
                                        @endif
                                    </td>
                                    <td>{{$item->estudante->telefone_estudante}}</td>
                                    <td style="width: 400px">
                                        @if ($item->estudante->finalista == "N")
                                        <a href="{{ route('web.definir-como-finalista', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-success mx-2"><i class="fa fa-edit"></i> Definir Como Finalista</a>
                                        @endif
                                        @if ($item->estudante->finalista == "Y")
                                        <a href="{{ route('web.definir-como-finalista', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-danger mx-2"><i class="fa fa-edit"></i> Definir Como Não Finalista</a>
                                        @endif
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" class="btn-primary btn"><i class="fas fa-info"></i> Infor.</a>
                                        <a href="{{ route('web.remover-estuantes-turmas', ['turma_id' => Crypt::encrypt($turma->turmas_id), 'estudante_id' => Crypt::encrypt($item->estudante->id)] ) }}" class="btn-danger btn"><i class="fas fa-trash"></i> Remover</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        @endif

        @if ($disciplinas)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-6">Disciplinas</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Codigo</th>
                                <th>Disciplinas</th>
                                <th>Abreviação</th>
                                @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                <th>Semestre</th>
                                @endif
                                <th>Status</th>
                                <th class="text-right">Mini Pautas</th>
                            </thead>
                            <tbody>
                                @foreach ($disciplinas as $key => $item)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$item->disciplina->code}}</td>
                                    <td>{{$item->disciplina->disciplina}}</td>
                                    <td>{{$item->disciplina->abreviacao}}</td>
                                    @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                    <td>{{$item->trimestre->trimestre}}</td>
                                    @endif
                                    <td>{{$item->status}}</td>
                                    <td width="250px">
                                        <a href="{{ route('ficha-mini-pauta-geral', [ 'turma' => Crypt::encrypt($turma->id), 'disciplina' => Crypt::encrypt($item->disciplina->id), ]) }}" target="_blank" class="btn btn-danger float-end mx-1"><i class="fas fa-file-pdf"></i> PDF</a>
                                        <a href="{{ route('ficha-mini-pauta-geral-excel', [ 'turma' => Crypt::encrypt($turma->id), 'disciplina' => Crypt::encrypt($item->disciplina->id), ]) }}" target="_blank" class="btn btn-success float-end mx-1"><i class="fas fa-file-excel"></i> EXCEL</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        @endif

        @if ($disciplinasProfessores)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-6">Disciplinas com professores</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <th>Codigo</th>
                                <th>Disciplinas</th>
                                <th>Abreviação</th>
                                <th>Professor</th>
                            </thead>
                            <tbody>
                                @foreach ($disciplinasProfessores as $item)
                                <tr>
                                    <td> {{ $item->code }} </td>
                                    <td> {{ $item->disciplina->disciplina }} </td>
                                    <td> {{ $item->abreviacao }} </td>
                                    <td> {{ $item->professor->nome }} {{ $item->professor->sobre_nome }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        @endif

        @if ($professores)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-6">Professores</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <th>Codigo</th>
                                <th>Professor</th>
                                <th>Cargo</th>
                                <th>Genero</th>
                            </thead>
                            <tbody>
                                @foreach ($professores as $key => $item)
                                <tr>
                                    <td> {{ $key + 1 }} </td>
                                    <td> {{ $item->professor->nome }} {{ $item->professor->sobre_nome }} </td>
                                    <td> {{ $item->cargo_turma }} </td>
                                    <td> {{ $item->genero }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection


@section('scripts')
<script>
    const tabelas = [
        "#carregarTabela",
        "#tabela_servicos", 
    ];
    tabelas.forEach(inicializarTabela);

    bindStatusUpdate('.criar_grade_curricular_pautas', `{{ route('web.criar-grade-curricular-turmas', ':id') }}`);
    bindStatusUpdate('.actualizar_grade_curricular_pautas', `{{ route('web.actualizar-grade-curricular-turmas', ':id') }}`);

    excluirRegistro('.deleteModal', `{{ route('web.remover-servico-turma', ':id') }}`);

</script>
@endsection
