@extends('layouts.estudantes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Perfil do Estudante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
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

                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if (empty($estudante->image))
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}" alt="User profile picture">
                                @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset("assets/images/recursosHumanos/$estudante->image") }}" alt="User profile picture">
                                @endif
                            </div>

                            <h3 class="profile-username text-center">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</h3>
                            <h4 class="profile-username text-center">Proc..: {{ $estudante->numero_processo }} </h4>
                            {{-- <p class="text-muted text-center">{{ $curso->curso }}</p> --}}
                            <p class="profile-username text-center mt-4">{{ $estudante->escola->nome }}</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Nascimento</b> <a class="float-right">{{ $estudante->nascimento }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Genero</b> <a class="float-right">{{ $estudante->genero }}</a>
                                </li>
                            </ul>
                            <form action="{{ route('est.editar-foto-perfil') }}" method="post" class="row" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input  @error('fotografiaEstudante') is-invalid @enderror" name="fotografiaEstudante" id="exampleInputFile">
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
                                    <button type="submit" class="btn btn-primary">Editar Foto do Estudante</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Saldo do(a) estudante</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">{{ number_format($estudante->saldo, 2, ',', '.')  }} Kz</h2>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="py-3 px-3">
                            <form action="{{ route('est.home-estudante') }}" method="get">
                                @csrf
                                <div class="row">
                                    <select name="ano_lectivo_selecionado_id" id="" class="form-control col-12 col-md-3">
                                        <option value="">Selecionar o Ano Lectivo</option>
                                        @foreach ($todos_anos_lectivos as $item)
                                        @if ($ano_lectivo_estudante)
                                        <option value="{{  $item->id  }}" {{ $item->id == $ano_lectivo_estudante->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                        @else
                                        <option value="{{  $item->id  }}">{{ $item->ano }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="ano_selecionado_por_estudante" value="true">

                                    <button type="submit" class="btn btn-primary col-12 col-md-1 mx-2">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($estudante->bolseiro($estudante->id))
                    <div class="callout callout-success">
                        <h5><i class="fas fa-info"></i>
                            O Senhor(a) é um estudante Bolseiro com a Bolsa: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->bolsa->nome ?? ''  }}</strong>
                            da instituição: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->instituicao->nome ?? ''  }} </strong>
                            com o desconto de: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto ?? ''  }}% </strong>
                            .Período da Bolsa: <strong style="border-bottom: 1px solid #005467"> {{ $estudante->bolseiro($estudante->id)->periodo->trimestre ?? ''  }}. </strong>
                        </h5>
                    </div>
                    @else

                    @endif

                    @if (session()->has('ano_lectivo_selecionado_estudante'))
                    <div class="card">
                        <div class="py-3 px-3">
                            @if (Auth::user()->can('read: nota'))
                            <a href="{{ route('est.pauta-estudante') }}" class="btn btn-primary">Pauta</a>
                            @endif
                            @if (Auth::user()->can('read: pagamento'))
                            @if ($estudante->escola->categoria == "Privado")
                            <a href="{{ route('est.estudante-pagamentos') }}" class="btn btn-primary">Pagamentos/Cartão</a>
                            @endif
                            @endif
                            <a href="{{ route('est.historicos') }}" class="btn btn-primary">Historicos</a>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header p-2">
                            <div class="float-left">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#dados_pessoas" data-toggle="tab">Dados
                                            Pessoais</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#dados_pessoas_pai" data-toggle="tab">Informações do
                                            Pai</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#dados_pessoas_mae" data-toggle="tab">Informações da
                                            Mãe</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#academico" data-toggle="tab">Académicos</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#responsavel" data-toggle="tab">Responsável</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#documentos" data-toggle="tab">Documentos</a></li>
                                </ul>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="dados_pessoas">

                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name Completo</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $estudante->nome }} {{ $estudante->sobre_nome }}" id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $estudante->nascimento }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
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
                                                <input type="text" class="form-control" value="{{ $estudante->data_emissao ?? "" }}" id="inputName2" placeholder="" disabled>
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

                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="responsavel">
                                    <!-- The timeline -->
                                    @if (isset($encarregado) AND !empty($encarregado))
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name Completo</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $encarregado->nome }} {{ $encarregado->sobre_nome }}" id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $encarregado->nascimento }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
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
                                                <input type="text" class="form-control" value="{{ $encarregado->grau_parentesco }}" id="inputName2" placeholder="" disabled>
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

                                <div class="tab-pane" id="academico">
                                    @foreach ($matriculas as $key => $item)
                                    @if ($item->ano_lectivo->status == 'activo')
                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Ano Lectivo: </label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control text-uppercase" value="{{ $item->ano_lectivo->ano }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Estado da Matricula: </label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control text-uppercase" value="{{ $item->status_matricula }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Nº Processo: </label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $item->estudante->numero_processo }}" disabled>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Curso</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $item->curso->curso }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
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
                                                <input type="text" class="form-control" value="{{ $item->turno->turno }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Turma</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $item->turma($item->estudantes_id) }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Sala</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $item->sala($item->estudantes_id) }}" disabled>
                                            </div>
                                        </div>
                                        <hr>

                                    </div>
                                    @else
                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Ano Lectivo: </label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control text-uppercase" value="{{ $item->ano_lectivo->ano }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Estado da Matricula: </label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control text-uppercase" value="{{ $item->status_matricula }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Nº Processo: </label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $item->estudante->numero_processo }}" disabled>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Curso</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $item->curso->curso }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
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
                                                <input type="text" class="form-control" value="{{ $item->turno->turno }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Turma</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $item->turma($item->estudantes_id) }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Sala</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $item->sala($item->estudantes_id) }}" disabled>
                                            </div>
                                        </div>
                                        <hr>

                                    </div>
                                    @endif
                                    @endforeach
                                </div>

                                <div class="tab-pane" id="documentos">
                                    <div class="form-horizontal">
                                        @if ($documentos)
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Bilhete de Identidade</label>
                                            <div class="col-sm-10">
                                                <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->bilheite") }}' target="_blink">{{ $documentos->bilheite ?? 'sem documento' }}</a></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label"> Atestado Médico </label>
                                            <div class="col-sm-10">
                                                <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->atestado") }}' target="_blink">{{ $documentos->atestado ?? 'sem documento' }}</a></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Certificado</label>
                                            <div class="col-sm-10">
                                                <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->certificado") }}' target="_blink">{{ $documentos->certificado ?? 'sem documento'}}</a></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Outros Documentos</label>
                                            <div class="col-sm-10">
                                                <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->outros") }}' target="_blink">{{ $documentos->outros ?? 'sem documento' }}</a></div>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
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
