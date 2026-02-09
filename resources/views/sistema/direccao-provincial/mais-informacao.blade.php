@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Informações Gerais da Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('listagem-escola-provincial', Crypt::encrypt(null)) }}">Voltar à escolas</a></li>
                    <li class="breadcrumb-item active">Detalhe</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $matriculas }}</h3>

                        <p>Total Estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('app.listagem-estudantes-provincial', Crypt::encrypt($escola->id)) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $funcionarios }}</h3>

                        <p>Total Funcionários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('app.listagem-professores-provincial', Crypt::encrypt($escola->id)) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $turmas }}</h3>

                        <p>Total Turmas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ $utilizadores }}</h3>
            <p>Total ....</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div> --}}

<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h3>{{ $laboratorios }}</h3>
            <p>Laboratórios</p>
        </div>
        <div class="icon">
            <i class="fas fa-flask"></i>
        </div>
        <a href="{{ route('web.laboratorios-escolas', ['loyout'=> 'provinciais', 'shcool_id' => $escola->id]) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>


<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h3>{{ $cursos }}</h3>
            <p>Cursos</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>


<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h3>{{ $classes ?? 0 }}</h3>
            <p>Classes</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>


<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h3>{{ $salas ?? 0 }}</h3>
            <p>Salas</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>


<div class="col-lg-3 col-12 col-md-12">
    <!-- small box -->
    <div class="small-box bg-light">
        <div class="inner">
            <h3>{{ $turnos ?? 0 }}</h3>
            <p>Turnos</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

</div>

<div class="row">
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body table-responsive">
                <table style="width: 100%" class="table  table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td rowspan="4">
                                <img src='{{ public_path("assets/images/user.png") }}' style="height: 100px; width: 100px;" class="img-circle" alt="Logotipo da instituição">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="bg-light">Informações Director</td>
                        </tr>
                        <tr>
                            <td colspan="2">Nome do Director: <strong>{{ $director->nome ?? '' }}</strong> </td>
                            <td>Bilheite Identidade: <strong>{{ $director->bilheite ?? ''  }}</strong> </td>
                            <td colspan="1">Genero: <strong>{{ $director->genero ?? '' }}</strong> </td>
                            <td>Estado Cívil: <strong>{{ $director->estado_civil ?? ''  }}</strong></td>
                        </tr>

                        <tr>
                            <td>Especialidade: <strong>{{ $director->especialidade ?? ''  }}</strong></td>
                            <td>Curso: <strong>{{ $director->curso ?? ''  }}</strong></td>
                            <td colspan="3">Perfil: <strong>{{ $director->descricao ?? ''  }}</strong></td>
                        </tr>


                        <tr>
                            <td colspan="6" class="bg-light">Informações Gerais</td>
                        </tr>
                        <tr>
                            <td colspan="3">Nome da Escola: <strong>{{ $escola->nome }}</strong> </td>
                            <td>Número da Escola: <strong>{{ $escola->numero_escola }}</strong> </td>
                            <td>Decreto de Criação: <strong>{{ $escola->decreto }}</strong></td>
                            <td>Número de Idetificação Fiscal: <strong>{{ $escola->documento }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">Sector: <strong>{{ $escola->categoria }}</strong> </td>
                            <td colspan="2">Subsistema ou sistema de Ensino: <strong>{{ $escola->ensino->nome ?? "" }}</strong> </td>
                            <td colspan="2">Tipo Regime do IVA:

                                @if($escola->tipo_regime_id == "regime_exclusao")
                                <strong>REGIME DE EXCLUSÃO</strong>
                                @endif
                                @if($escola->tipo_regime_id == "regime_geral")
                                <strong>REGIME GERAL</strong>
                                @endif
                                @if($escola->tipo_regime_id == "regime_simplificado")
                                <strong>REGIME SIMPLIFICADO</strong>
                                @endif

                            </td>
                        </tr>

                        <tr>
                            <td colspan="6" class="bg-light">Contactos</td>
                        </tr>
                        <tr>
                            <td>Telefone 1: <strong>{{ $escola->telefone1 }}</strong> </td>
                            <td>Telefone 2: <strong>{{ $escola->telefone2 }}</strong> </td>
                            <td>Telefone 4: <strong>{{ $escola->telefone3 }}</strong></td>
                            <td colspan="3">E-mail ou Site <strong>{{ $escola->site }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="6" class="bg-light">Localização</td>
                        </tr>
                        <tr>
                            <td>Provincia: <strong>{{ $escola->provincia->nome ?? ""}}</strong> </td>
                            <td>Municipio: <strong>{{ $escola->municipio->nome ?? "" }}</strong> </td>
                            <td>Distrito: <strong>{{ $escola->distrito->nome ?? "" }}</strong></td>
                            <td colspan="3">Endereço: <strong>{{ $escola->endereco }}</strong></td>
                        </tr>


                        <tr>
                            <td colspan="6" class="bg-light">Infraestrutura</td>
                        </tr>
                        <tr>
                            <td>Cantina: <strong>{{ $escola->cantina }}</strong> </td>
                            <td colspan="">Campo Disportivo: <strong>{{ $escola->campo_desportivo }}</strong> </td>
                            <td colspan="">Computadores: <strong>{{ $escola->computadores }}</strong> </td>
                            <td>Água Potavel: <strong>{{ $escola->agua }}</strong></td>
                            <td>Electricidade: <strong>{{ $escola->electricidade }}</strong></td>
                            <td>Transporte: <strong>{{ $escola->transporte }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="">Internet: <strong>{{ $escola->internet }}</strong> </td>
                            <td colspan="">Bibioteca: <strong>{{ $escola->biblioteca }}</strong> </td>
                            <td colspan="">Farmácia: <strong>{{ $escola->farmacia }}</strong> </td>
                            <td colspan="">Laboratório: <strong>{{ $escola->laboratorio }}</strong> </td>
                            <td colspan="">ZIP: <strong>{{ $escola->zip }}</strong> </td>
                            <td colspan="">Casas de Banhos: <strong>{{ $escola->casas_banho }}</strong> </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@if ($lancamento)

<div class="row">
    <div class="col-12 col-md-3 mb3">
        <div class="callout callout-info">
            <h5><i class="fas fa-info"></i>Total Notas</h5>
            <h3>{{ $results->total }}</h3>
        </div>
    </div>

    <div class="col-12 col-md-3 mb3">
        <div class="callout callout-success">
            <h5><i class="fas fa-info"></i>Total Notas Lançadas</h5>
            <h5> Número: {{ $results->total_lancada }}</h5>
            <h5> Percentagem: {{ number_format($results->percentual_lancada, 2, ',', '.') }}%</h5>
        </div>
    </div>

    <div class="col-12 col-md-3 mb3">
        <div class="callout callout-danger">
            <h5><i class="fas fa-info"></i>Total Notas Não Lançadas</h5>
            <h5>Número: {{ $results->total_nao_lancada }}</h5>
            <h5>Percentagem: {{ number_format($results->percentual_nao_lancada, 2, ',', '.') }}%</h5>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Disciplina</th>
                            <th>Status</th>
                            <th>Trimestre</th>
                            <th>Ano Lectivo</th>
                            <th>MAC</th>
                            <th>NPP</th>
                            <th>NPT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notas as $item)
                        <tr>
                            <td>{{ $item->turma->turma }}</td>
                            <td>{{ $item->disciplina->disciplina }}</td>
                            <td>
                                @if ($item->status_nota != '0')
                                <span class="text-success">Sim</span>
                                @else
                                <span class="text-danger">Não</span>
                                @endif
                            </td>
                            <td>{{ $item->trimestre->trimestre }}</td>
                            <td>{{ $item->ano->ano }}</td>
                            <td>{{ $item->mac }}</td>
                            <td>{{ $item->npp }}</td>
                            <td>{{ $item->npt }}</td>
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
</div>

@endsection



@section('scripts')
<script>
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

</script>
@endsection
