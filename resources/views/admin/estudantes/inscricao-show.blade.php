@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detalhe da Inscrição</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.estudantes-inscricao') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Inscrições</li>
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
            <div class="col-12 col-md-3 mb3">
                <div class="card">
                    <div class="card-body">
                        <div class="callout callout-info">
                            <h5 class="mb-5"><i class="fas fa-info"></i> Mais informações sobre da inscrição do estudante, classe, curso, turno, turmas.</h5>
                            <h6>Média: 
                                
                                @if ($matricula->media >= 14)
                                    <span class="text-success">{{ $matricula->media }}</span>
                                @else
                                    <span class="text-danger">{{ $matricula->media }}</span>
                                @endif
                            </h6>
                            <h6>Idade: {{ $matricula->estudante->idade($matricula->estudante->nascimento) }}</h6>
                            <h6>Resultado: 
                                @if ($matricula->status_inscricao == 'Admitido')
                                    <span class="text-success">{{ $matricula->status_inscricao }}</span>
                                @else
                                    <span class="text-danger">{{ $matricula->status_inscricao }}</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p class="">Requesito para Admissão</p>
                        <p class="">Média: >= 14</p>
                        <p class="">Idade: >= 14</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>Mudar o estudo da Inscrição para</h5>
                    </div>
                    <div class="card-body">
                        
                        @if ($matricula->status_inscricao == 'Admitido')
                        <a href="{{ route('web.estudantes-inscricao-status', Crypt::encrypt($matricula->id)) }}" class="btn btn-danger">Não Admitir</a>
                        @else
                        <a href="{{ route('web.estudantes-inscricao-status', Crypt::encrypt($matricula->id)) }}" class="btn btn-success">Admitir</a>
                       @endif
                    
                    </div>
                </div>
                <div class="card">
                    <div class="card-header p-2">
                        <div class="float-left">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#dados_pessoas"
                                        data-toggle="tab">Dados
                                        Pessoais</a></li>
                                <li class="nav-item"><a class="nav-link" href="#dados_pessoas_pai"
                                        data-toggle="tab">Informações do
                                        Pai</a></li>
                                <li class="nav-item"><a class="nav-link" href="#dados_pessoas_mae"
                                        data-toggle="tab">Informações da
                                        Mãe</a></li>
                                <li class="nav-item"><a class="nav-link" href="#academico"
                                        data-toggle="tab">Académicos/Matricula</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#responsavel"
                                        data-toggle="tab">Responsável</a></li>
                                <li class="nav-item"><a class="nav-link" href="#documentos"
                                        data-toggle="tab">Documentos</a></li>
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
                                            <input type="email" class="form-control"
                                                value="{{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}"
                                                id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control"
                                                value="{{ $matricula->estudante->nascimento }}" id="inputEmail"
                                                placeholder="Data do Nascimento do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $matricula->estudante->genero }}"
                                                id="inputName2" placeholder="Genero do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Bilhete/Cédula
                                            Pessoal</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $matricula->estudante->bilheite }}"
                                                id="inputName2"
                                                placeholder="Estudante"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Bilhete/Cédula
                                            Pessoal</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $matricula->estudante->data_emissao ?? "" }}"
                                                id="inputName2"
                                                placeholder="Estudante"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Nacionalidade</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->nacionalidade }}" id="inputName2"
                                                placeholder="Nacionalidade do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Província</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->provincia->nome }}" id="inputName2"
                                                placeholder="Província do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Munícipio</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->municipio->nome }}" id="inputName2"
                                                placeholder="Munícipio do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Naturalidade</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->naturalidade }}" id="inputName2"
                                                placeholder="Naturalidade do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Dificiência</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->dificiencia }}" id="inputSkills"
                                                placeholder="Alguma dificiência do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Tel. Estudante</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->telefone_estudante }}" id="inputSkills"
                                                placeholder="Número telefonico do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Endereço</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience"
                                                placeholder="Endereço do Estudante"
                                                disabled>{{ $matricula->estudante->endereco }}</textarea>
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
                                            <input type="text" class="form-control" value="{{ $matricula->estudante->pai }}"
                                                id="inputName2" placeholder="Nome Completo do Pai do Estudante" disabled>
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
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->telefone_pai }}" id="inputSkills"
                                                placeholder="Número telefonico do Pai" disabled>
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
                                            <input type="text" class="form-control" value="{{ $matricula->estudante->mae }}"
                                                id="inputName2" placeholder="Nome Completo da Mãe do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="Femenino" id="inputName2"
                                                placeholder="Genero do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Tel Mãe</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $matricula->estudante->telefone_mae }}" id="inputSkills"
                                                placeholder="Número telefonico da Mãe" disabled>
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
                                            <input type="email" class="form-control"
                                                value="{{ $encarregado->nome }} {{ $encarregado->sobre_nome }}"
                                                id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control"
                                                value="{{ $encarregado->nascimento }}" id="inputEmail"
                                                placeholder="Data do Nascimento do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $encarregado->genero }}"
                                                id="inputName2" placeholder="Genero do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Estado Cívil</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $encarregado->estado_civil }}" id="inputName2"
                                                placeholder="Nome Completo do Pai do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Profissão</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $encarregado->profissao }}" id="inputName2"
                                                placeholder="Nome Completo da Mãe do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Grau Parentesco</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $encarregado->grau_parentesco }}" id="inputName2"
                                                placeholder=""
                                                disabled>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Telefone</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="{{ $encarregado->telefone }}"
                                                id="inputSkills" placeholder="Número telefonico do Estudante" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Telefone 2</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                value="{{ $encarregado->telefone2 }}" id="inputSkills"
                                                placeholder="Número telefonico do Estudante" disabled>
                                        </div>
                                    </div>

                                </div>
                                @endif
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="academico">
                                <div class="form-horizontal">

                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-2">
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">Estado da
                                                    Matricula: </label>
                                                <div class="col-sm-10">
                                                    @if ( $matricula->status_matricula == 'inactivo')
                                                    <input type="email" class="form-control text-uppercase text-danger"
                                                        value="{{ $matricula->status_matricula }}" disabled>
                                                    @else
                                                    <input type="email" class="form-control text-uppercase text-success"
                                                        value="{{ $matricula->status_matricula }}" disabled>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">Nº Processo:
                                                </label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control"
                                                        value="{{ $matricula->estudante->numero_processo }}" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">Sala</label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control"
                                                        value="{{ $matricula->sala($matricula->estudantes_id) }}"
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 col-form-label">Curso</label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control"
                                                        value="{{ $matricula->curso->curso }}" id="inputEmail"
                                                        placeholder="Data do Nascimento do Estudante" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Classe</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control"
                                                        value="{{ $matricula->classe->classes }}" id="inputName2"
                                                        placeholder="Genero do Estudante" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Turno</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control"
                                                        value="{{ $matricula->turno->turno }}" id="inputName2"
                                                        placeholder="Nome Completo do Pai do Estudante" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Turma</label>
                                                <div class="col-sm-10">
                                                    @if ($matricula->turma($matricula->cursos_id, $matricula->turnos_id,
                                                    $matricula->classes_id,
                                                    $matricula->shcools_id, $matricula->ano_lectivos_id))
                                                    <div class="form-control"><a
                                                            href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($matricula->turma_id($matricula->estudantes_id))) }}">{{
                                                            $matricula->turma($matricula->estudantes_id) }}</a></div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Ano
                                                    Lectivo</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ $matricula->ano_lectivo->ano }}</div>
                                                </div>
                                            </div>

                                            {{-- @if ($estudante->registro == 'falecido' OR $estudante->registro ==
                                            'desistente')
                                            <a href="{{ route('web.estudantes-marcar-vivo', Crypt::encrypt($item->id=) }}"
                                                class="btn btn-success float-right mx-2"><i class="fa fa-user"></i>
                                                Marcar como Vivo</a>
                                            @endif

                                            @if ($estudante->registro != 'falecido' OR $estudante->registro !=
                                            'desistente')
                                            <a href="{{ route('web.estudantes-marcar-falecido', Crypt::encrypt($item->id) }}"
                                                class="btn btn-danger float-right mx-2"><i class="fa fa-user"></i>
                                                Marcar como
                                                Falencido</a>
                                            <a href="{{ route('web.estudantes-marcar-desistente', Crypt::encrypt($item->id)) }}"
                                                class="btn btn-warning float-right mx-2"><i class="fa fa-user"></i>
                                                Marcar como
                                                Desistente</a>
                                            @endif

                                            @if ( $item->status_matricula == 'inactivo')
                                            @if (Auth::user()->can('update: estado'))
                                            <a href="{{ route('web.activar-matricula-estudante', [$item->id, " return"])
                                                }}" class="btn btn-success float-right mx-1">Activar Matricula</a>
                                            @endif
                                            @else

                                            @if ($item->turma($item->cursos_id, $item->turnos_id, $item->classes_id,
                                            $item->shcools_id, $item->ano_lectivos_id) == "Sem Turma")
                                            @if ($estudante->registro != 'falecido' OR $estudante->registro !=
                                            'desistente')
                                            @if (Auth::user()->can('create: distribuicao de estudante') ||
                                            Auth::user()->can('update: distribuicao de estudante'))
                                            <a href="{{ route('web.adicionar-matricula-turma', Crypt::encrypt($item->id)) }}"
                                                class="btn btn-primary float-right">Adicionar Estudante na Turma</a>
                                            @endif
                                            @endif
                                            @endif

                                            @if (Auth::user()->can('update: estado'))
                                            <a href="{{ route('web.activar-matricula-estudante', [$item->id, " return"])
                                                }}" class="btn btn-danger float-right mx-1">Inactivor Matricula</a>
                                            @endif
                                            @endif --}}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane" id="documentos">
                                <div class="form-horizontal">

                                    @if ($documentos)
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Bilhete de
                                            Identidade</label>
                                        <div class="col-sm-10">
                                            <div type="email" class="form-control"><a
                                                    href='{{ asset("/assets/arquivos/$documentos->bilheite") }}'
                                                    target="_blink">{{
                                                    $documentos->bilheite ?? 'sem documento' }}</a></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Atestado de
                                            Médico</label>
                                        <div class="col-sm-10">
                                            <div type="email" class="form-control"><a
                                                    href='{{ asset("/assets/arquivos/$documentos->atestado") }}'
                                                    target="_blink">{{
                                                    $documentos->atestado ?? 'sem documento' }}</a></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Certificado</label>
                                        <div class="col-sm-10">
                                            <div type="email" class="form-control"><a
                                                    href='{{ asset("/assets/arquivos/$documentos->certificado") }}'
                                                    target="_blink">{{
                                                    $documentos->certificado ?? 'sem documento'}}</a></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Outros
                                            Documentos</label>
                                        <div class="col-sm-10">
                                            <div type="email" class="form-control"><a
                                                    href='{{ asset("/assets/arquivos/$documentos->outros") }}'
                                                    target="_blink">{{
                                                    $documentos->outros ?? 'sem documento' }}</a></div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection