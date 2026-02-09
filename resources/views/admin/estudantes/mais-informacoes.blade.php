@extends('layouts.escolas')

@section('content')

<style>
    .video-container {}

    #background-video {}

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-12">
                    @if ($estudante->registro == 'falecido')
                    <h1 class="m-0 text-dark">Perfil do Estudante -- <span class="text-danger">Estudante Felecido</span> </h1>
                    @endif

                    @if ($estudante->registro == 'desistente')
                    <h1 class="m-0 text-dark">Perfil do Estudante -- <span class="text-warning">Estudante Desistente</span></h1>
                    @endif

                    @if ($estudante->registro == 'confirmado')
                    <h1 class="m-0 text-dark">Perfil do Estudante
                        @if ($estudante->finalista == "Y")
                        <span class="text-success">Estudante Finalista</span>
                        @endif
                    </h1>
                    @endif
                </div>
                <div class="col-sm-6 col-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('web.estudantes') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Perfil</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    @if ($estudante->bolseiro($estudante->id))
                    <div class="callout callout-success">
                        <h5><i class="fas fa-info"></i>
                            Este é um estudante Bolseiro com a Bolsa: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->bolsa->nome ?? ''  }}</strong>
                            da instituição: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->instituicao->nome ?? ''  }} </strong>
                            com o desconto de: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto ?? ''  }}% </strong>
                            .Período da Bolsa: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->periodo->trimestre ?? ''  }}. </strong>
                        </h5>
                    </div>
                    @else
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i>Mais informações sobre com estudante, classe, curso, turno, turmas,
                            informações do encarregado, mini pautas, pautas e o seu extrato financeiro.</h5>
                    </div>
                    @endif
                    @if (!$matricula)
                    <div class="callout callout-warning bg-warning">
                        <h5><i class="fas fa-info"></i> Este estudante ainda não fez a confirmação para este Ano lectivo, só poder visualizar os dados dos anos passados</h5>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-12">

                    @if ($escola->modulo != "Basico")
                    <div class="card">
                        <div class="card-body text-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                    @endif
                    <!-- /.card -->

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if (empty($estudante->image))
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}" alt="User profile picture">
                                @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset("/assets/images/estudantes/$estudante->image") }}" alt="User profile picture">
                                @endif
                            </div>

                            <h3 class="profile-username text-center">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</h3>
                            <h4 class="profile-username text-center">Proc..: {{ $estudante->numero_processo }} </h4>
                            @if ($matricula)
                            <p class="text-muted text-center">{{ $matricula->curso->curso }}</p>
                            @endif
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Nascimento</b> <a class="float-right">{{ $estudante->nascimento }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Genero</b> <a class="float-right">{{ $estudante->genero }}</a>
                                </li>
                            </ul>

                            @if ($escola->modulo != "Basico")
                            @if ($estudante->registro != 'falecido')
                            <form action="{{ route('web.estudantes-foto-perfil') }}" method="post" class="row" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="fotografiaEstudante" id="exampleInputFile">
                                                <label class="custom-file-label" for="exampleInputFile">Escolher Foto</label>
                                            </div>
                                            <input type="hidden" value="{{ $estudante->id }}" name="estudanteFoto">
                                        </div>
                                    </div>
                                    @error('fotografiaEstudante')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    @if (Auth::user()->can('update: estudante'))
                                    <button type="submit" class="btn btn-primary">Editar Foto do Estudante</button>
                                    @endif
                                </div>
                            </form>
                            @endif
                            @endif

                        </div>
                        <!-- /.card-body -->
                    </div>

                    @if ($escola->modulo != "Basico")
                    <div class="card">
                        <div class="card-body">
                            <h4>Saldo do(a) estudante</h4>
                            <h2 class="text-success">{{ number_format($estudante->saldo, 2, ',', '.')  }} Kz</h2>
                        </div>

                        <div class="card-footer">
                            @if (Auth::user()->can('create: deposito'))
                            <a href="{{ route('shcools.actualizar-saldo', Crypt::encrypt($estudante->id)) }}" class="btn btn-dark">Fazer Novo Saldo</a>

                            <a href="{{ route('shcools.remover-saldo', Crypt::encrypt($estudante->id)) }}" class="btn btn-danger">Retirar Saldo</a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- /.card -->
                </div>

                <!-- /.col -->
                <div class="col-md-9">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            @if ($matricula)
                            @if ($estudante->registro != 'falecido' OR $estudante->registro != 'desistente')
                            @if (Auth::user()->can('update: estudante') || Auth::user()->can('update: matricula'))
                            <a href="{{ route('web.estudantes-matricula-edit', Crypt::encrypt($matricula->id)) }}" class="btn bg-success btn-app float-right mx-2"><i class="fa fa-edit"></i> Editar</a>
                            @if ($estudante->finalista == "N")
                            <a href="{{ route('web.definir-como-finalista', Crypt::encrypt($estudante->id)) }}" class="btn bg-dark btn-app float-right mx-2"><i class="fa fa-edit"></i> Definir Como Finalista</a>
                            @endif
                            @if ($estudante->finalista == "Y")
                            <a href="{{ route('web.definir-como-finalista', Crypt::encrypt($estudante->id)) }}" class="btn bg-dark btn-app float-right mx-2"><i class="fa fa-edit"></i> Definir Como Não Finalista</a>
                            @endif
                            @endif
                            @endif
                            @else

                            @endif

                            @if (Auth::user()->can('read: nota'))
                            @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                            <a href="{{ route('web.pauta-estudante', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-sticky-note"></i> Pauta</a>
                            @else
                            <a href="{{ route('web.pauta-estudante', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-sticky-note"></i> Pauta</a>
                            <a href="{{ route('web.mini-pauta-estudante', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-sticky-note"></i> Mini Pauta</a>
                            <a href="{{ route('web.declaracao-estudantes', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-file-signature"></i> Emitir Declarações</a>
                            @if ($estudante->finalista == "Y")
                            <a href="{{ route('web.certificado-estudante', ['id' => Crypt::encrypt($estudante->id)]) }}" target="_blink" class="btn bg-primary float-left btn-app"><i class="fas fa-certificate"></i> Certificado</a>
                            @endif
                            @endif
                            @endif

                            @if ($escola->modulo != "Basico")
                            @if($escola->categoria == 'Privado')
                            @if (Auth::user()->can('read: pagamento'))
                            <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"> <i class="fas fa-wallet"></i> Painel Financeiro</a>
                            <a href="{{ route('web.estudantes-pagamento-propina', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-credit-card"></i> Efectuar Pagamentos</a>
                            @endif

                            @if (Auth::user()->can('read: deposito'))
                            <a href="{{ route('shcools.listar-depositos-estudante', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-piggy-bank"></i> Depositos</a>
                            @endif
                            @endif

                            @if (Auth::user()->can('create: distribuicao de estudante') || Auth::user()->can('update: distribuicao de estudante'))
                            <a href="{{ route('web.transferencia-turma-estudante', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-user-shield"></i> Fazer Transferência de Turma</a>
                            @endif

                            @if (Auth::user()->can('create: isentar multa') || Auth::user()->can('update: isentar multa'))
                            <a href="{{ route('web.financeiro-isentar-pagamento', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-check-circle"></i> Isentar</a>
                            @endif

                            @if (Auth::user()->can('read: matricula'))
                            <a href="{{ route('web.historicos-estudante', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-history"></i>Historicos</a>
                            @endif
                            @if (Auth::user()->can('create: bolseiro') || Auth::user()->can('read: bolseiro'))
                            <a href="{{ route('web.estudante-atribuir-bolsa', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-award"></i> Atribuir Bolsa</a>
                            @endif

                            @if (Auth::user()->can('read: matricula') || Auth::user()->can('read: estudante'))
                            <a href="{{ route('web.estudante.carregar-foto', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-camera"></i> Carregar Foto</a>
                            <a href="{{ route('web.estudante.ver-cartao', Crypt::encrypt($estudante->id)) }}" class="btn bg-primary float-left btn-app"><i class="fas fa-address-card"></i> Ver Cartão do Estudante</a>
                            @endif

                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header p-2">
                            <div class="float-left">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#dados_pessoas" data-toggle="tab">Dados Pessoais</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#dados_pessoas_pai" data-toggle="tab">Informações do Pai</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#dados_pessoas_mae" data-toggle="tab">Informações da Mãe</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#academico" data-toggle="tab">Académicos/Matricula</a></li>
                                    @if ($escola->modulo != "Basico")
                                    <li class="nav-item"><a class="nav-link" href="#responsavel" data-toggle="tab">Responsável</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#documentos" data-toggle="tab">Documentos</a></li>
                                    @endif
                                </ul>
                            </div>

                            @if ($estudante->registro != 'falecido' OR $estudante->registro != 'desistente')
                            @if (Auth::user()->can('create: transeferencia estudante') || Auth::user()->can('update: transeferencia estudante'))
                            <a class="float-right btn btn-success" href="{{ route('web.transferencia-escola-estudante', Crypt::encrypt($estudante->id)) }}">Fazer
                                Transferência Escolar</a>
                            @endif
                            @endif

                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="dados_pessoas">

                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name Completo</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->nome }} {{ $estudante->sobre_nome }}" id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->nascimento }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->genero }}" id="inputName2" placeholder="Genero do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Bilhete/Cédula</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->bilheite }}" id="inputName2" placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Data de Emissão do Bilhete</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->data_emissao }}" id="inputName2" placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Nacionalidade</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->nacionalidade }}" id="inputName2" placeholder="Nacionalidade do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Província</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->provincia->nome }}" id="inputName2" placeholder="Província do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Munícipio</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->municipio->nome }}" id="inputName2" placeholder="Munícipio do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Naturalidade</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->naturalidade }}" id="inputName2" placeholder="Naturalidade do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Dificiência</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->dificiencia }}" id="inputSkills" placeholder="Alguma dificiência do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Tel. Estudante</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->telefone_estudante }}" id="inputSkills" placeholder="Número telefonico do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputExperience" class="col-sm-2 col-form-label">Endereço</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" id="inputExperience" placeholder="Endereço do Estudante" disabled>{{ $estudante->endereco }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>

                                <div class="tab-pane" id="dados_pessoas_pai">

                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Nome Pai</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->pai }}" id="inputName2" placeholder="Nome Completo do Pai do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="Masculino" id="inputName2" placeholder="Genero do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Tel. Pai</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->telefone_pai }}" id="inputSkills" placeholder="Número telefonico do Pai" disabled>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>

                                <div class="tab-pane" id="dados_pessoas_mae">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Nome Mãe</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->mae }}" id="inputName2" placeholder="Nome Completo da Mãe do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="Femenino" id="inputName2" placeholder="Genero do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Tel Mãe</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $estudante->telefone_mae }}" id="inputSkills" placeholder="Número telefonico da Mãe" disabled>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>

                                <div class="tab-pane" id="academico">
                                    <div class="form-horizontal">
                                        @foreach ($matriculas as $item)
                                        <div class="row">
                                            <div class="col-12 col-md-12 mb-2">
                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-2 col-form-label">Estado da Matricula: </label>
                                                    <div class="col-sm-10">
                                                        @if ( $item->status_matricula == 'inactivo')
                                                        <input type="text" class="form-control text-uppercase text-danger" value="{{ $item->status_matricula }}" disabled>
                                                        @else
                                                        <input type="text" class="form-control text-uppercase text-success" value="{{ $item->status_matricula }}" disabled>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-2 col-form-label">Nº Processo: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" value="{{ $estudante->numero_processo }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-2 col-form-label">Sala</label>
                                                    @if ($item->turma_id($item->estudantes_id, $item->ano_lectivos_id))
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" value="{{ $item->sala($item->estudantes_id, $item->ano_lectivos_id) }}" disabled>
                                                    </div>
                                                    @else
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" value="Sem Sala" disabled>
                                                    </div>
                                                    @endif
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputEmail" class="col-sm-2 col-form-label">Curso</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" value="{{ $item->curso->curso }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputName2" class="col-sm-2 col-form-label">Classe</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" value="{{ $item->classe->classes }}" id="inputName2" placeholder="Genero do Estudante" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputName2" class="col-sm-2 col-form-label">Turno</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" value="{{ $item->turno->turno }}" id="inputName2" placeholder="Nome Completo do Pai do Estudante" disabled>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputName2" class="col-sm-2 col-form-label">Turma</label>
                                                    <div class="col-sm-10">

                                                        @if ($item->turma_id($item->estudantes_id, $item->ano_lectivos_id) != "Sem Turma")
                                                        <div class="form-control">
                                                            <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($item->turma_id($item->estudantes_id, $item->ano_lectivos_id))) }}"> {{ $item->turma($item->estudantes_id, $item->ano_lectivos_id) }}</a>
                                                            <a href="{{ route('web.remover-estuantes-turmas', ['turma_id' => Crypt::encrypt($item->turma_id($item->estudantes_id, $item->ano_lectivos_id)), 'estudante_id' => Crypt::encrypt($item->estudantes_id)]) }}" class="text-danger"> Remover estudante da turma: {{ $item->turma($item->estudantes_id, $item->ano_lectivos_id) }}</a>
                                                        </div>
                                                        @else
                                                        <div class="form-control">
                                                            <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($item->turma_id($item->estudantes_id, $item->ano_lectivos_id))) }}"> Estudante sem turma, Sugerimos a turma {{ $item->turma($item->estudantes_id, $item->ano_lectivos_id) }}</a>
                                                        </div>
                                                        @endif

                                                    </div>
                                                </div>

                                                <div class="form-group row mb-5">
                                                    <label for="inputName2" class="col-sm-2 col-form-label">Ano Lectivo</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ $item->ano_lectivo->ano }}</div>
                                                    </div>
                                                </div>



                                                @if (Auth::user()->can('read: matricula'))
                                                @if ($estudante->registro == 'falecido' OR $estudante->registro == 'desistente')
                                                <a href="{{ route('web.estudantes-marcar-vivo', Crypt::encrypt($item->id)) }}" class="btn btn-success float-right mx-2"><i class="fa fa-user"></i> Marcar como Vivo</a>
                                                @endif

                                                @if ($estudante->registro != 'falecido' OR $estudante->registro != 'desistente')
                                                <a href="{{ route('web.estudantes-marcar-falecido', Crypt::encrypt($item->id)) }}" class="btn btn-danger float-right mx-2"><i class="fa fa-user"></i> Marcar como
                                                    Falencido</a>
                                                <a href="{{ route('web.estudantes-marcar-desistente', Crypt::encrypt($item->id)) }}" class="btn btn-warning float-right mx-2"><i class="fa fa-user"></i> Marcar como
                                                    Desistente</a>
                                                @endif
                                                @if ( $item->status_matricula == 'inactivo' || $item->status_matricula == 'nao_confirmado')
                                                @if (Auth::user()->can('update: estado'))
                                                <a href="{{ route('web.activar-matricula-estudante', [$item->id, " return"]) }}" class="btn btn-success float-right mx-1">Activar Matricula</a>
                                                @endif
                                                @else

                                                @if ($item->turma_id($item->estudantes_id, $item->ano_lectivos_id) == "Sem Turma")
                                                @if ($estudante->registro != 'falecido' OR $estudante->registro != 'desistente')
                                                @if (Auth::user()->can('create: distribuicao de estudante') || Auth::user()->can('update: distribuicao de estudante'))
                                                <a href="{{ route('web.adicionar-matricula-turma', Crypt::encrypt($item->id)) }}" class="btn btn-primary float-right">Adicionar Estudante na Turma</a>
                                                @endif
                                                @endif
                                                @endif
                                                @endif


                                                @if (Auth::user()->can('read: matricula'))
                                                @if (Auth::user()->can('update: estado'))
                                                <a href="{{ route('web.activar-matricula-estudante', [$item->id, " return"]) }}" class="btn btn-danger float-right mx-1">Inactivor Matricula</a>
                                                @endif
                                                @endif

                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($escola->modulo != "Basico")
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="responsavel">
                                    @if (isset($encarregado) AND !empty($encarregado))
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name Completo</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->nome }} {{ $encarregado->sobre_nome }}" id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->data_nascimento }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->genero }}" id="inputName2" placeholder="Genero do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Estado Cívil</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->estado_civil }}" id="inputName2" placeholder="Nome Completo do Pai do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Profissão</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->profissao }}" id="inputName2" placeholder="Nome Completo da Mãe do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Grau Parentesco</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->grau_parentesco }}" id="inputName2" placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Telefone</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->telefone }}" id="inputSkills" placeholder="Número telefonico do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Telefone 2</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $encarregado->telefone2 }}" id="inputSkills" placeholder="Número telefonico do Estudante" disabled>
                                            </div>
                                        </div>

                                    </div>
                                    @endif
                                </div>
                                <!-- /.tab-pane -->


                                <div class="tab-pane" id="documentos">
                                    <div class="form-horizontal">
                                        @if ($documentos)
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Bilhete de Identidade</label>
                                            <div class="col-sm-10">
                                                <div type="text" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->bilheite") }}' target="_blink">{{ $documentos->bilheite ?? 'sem documento' }}</a></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label"> Atestado Médico </label>
                                            <div class="col-sm-10">
                                                <div type="text" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->atestado") }}' target="_blink">{{ $documentos->atestado ?? 'sem documento' }}</a></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Certificado</label>
                                            <div class="col-sm-10">
                                                <div type="text" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->certificado") }}' target="_blink">{{ $documentos->certificado ?? 'sem documento'}}</a></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Outros Documentos</label>
                                            <div class="col-sm-10">
                                                <div type="text" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->outros") }}' target="_blink">{{ $documentos->outros ?? 'sem documento' }}</a></div>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                @endif

                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                        <div class="card-footer"></div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection
