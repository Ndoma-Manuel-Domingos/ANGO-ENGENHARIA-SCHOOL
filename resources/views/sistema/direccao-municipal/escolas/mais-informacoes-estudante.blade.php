@extends('layouts.municipal')

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
            <li class="breadcrumb-item"><a href="{{ route('app.listagem-estudantes-municipal-geral') }}">Voltar</a></li>
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
          <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> Mais informações sobre do estudante, classe, curso, turno, turmas, informações do encarregado.</h5>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                @if ($estudante->image == null)
                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/estudantes/user.png') }}" alt="User profile picture">
                @else
                <img class="profile-user-img img-fluid img-circle" src="{{ asset("assets/images/estudantes/$estudante->image") }}" alt="User profile picture">
                @endif
              </div>

              <h3 class="profile-username text-center">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</h3>

              <p class="text-muted text-center">{{ $curso->curso }}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Nascimento</b> <a class="float-right">{{ $estudante->nascimento }}</a>
                </li>
                <li class="list-group-item">
                  <b>Genero</b> <a class="float-right">{{ $estudante->genero }}</a>
                </li>
              </ul>
             
            </div>
            <div class="card-footer"></div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>

        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#dados_pessoas" data-toggle="tab">Dados
                    Pessoais</a></li>
                <li class="nav-item"><a class="nav-link" href="#dados_pessoas_pai" data-toggle="tab">Informações do
                    Pai</a></li>
                <li class="nav-item"><a class="nav-link" href="#dados_pessoas_mae" data-toggle="tab">Informações da
                    Mãe</a></li>
                <li class="nav-item"><a class="nav-link" href="#academico" data-toggle="tab">Académicos</a></li>
                <li class="nav-item"><a class="nav-link" href="#responsavel" data-toggle="tab">Responsável</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">

                <div class="active tab-pane" id="dados_pessoas">

                  <div class="form-horizontal">

                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Ficha Matricula</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $estudante->numero_processo }}" id="inputName"
                          placeholder="Nome Completo do Pai Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Name Completo</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control"
                          value="{{ $estudante->nome }} {{ $estudante->sobre_nome }}" id="inputName"
                          placeholder="Nome Completo do Pai Estudante" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $estudante->nascimento }}" id="inputEmail"
                          placeholder="Data do Nascimento do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->genero }}" id="inputName2"
                          placeholder="Genero do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Bilhete/Cédula</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->bilheite }}" id="inputName2"
                          placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Data de Emissão do Bilhete</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->data_emissao ?? "" }}" id="inputName2"
                          placeholder="" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Nacionalidade</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->nacionalidade }}" id="inputName2"
                          placeholder="Nacionalidade do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Província</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->provincia->nome }}" id="inputName2"
                          placeholder="Província do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Munícipio</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->municipio->nome }}" id="inputName2"
                          placeholder="Munícipio do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Naturalidade</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->naturalidade }}" id="inputName2"
                          placeholder="Naturalidade do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Dificiência</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->dificiencia }}" id="inputSkills"
                          placeholder="Alguma dificiência do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Tel. Estudante</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->telefone_estudante }}"
                          id="inputSkills" placeholder="Número telefonico do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputExperience" class="col-sm-2 col-form-label">Endereço</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="inputExperience" placeholder="Endereço do Estudante"
                          disabled>{{ $estudante->endereco }}</textarea>
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
                        <input type="text" class="form-control" value="{{ $estudante->pai }}" id="inputName2"
                          placeholder="Nome Completo do Pai do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="Masculino" id="inputName2"
                          placeholder="Genero do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Tel. Pai</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $estudante->telefone_pai }}" id="inputSkills"
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
                        <input type="text" class="form-control" value="{{ $estudante->mae }}" id="inputName2"
                          placeholder="Nome Completo da Mãe do Estudante" disabled>
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
                        <input type="text" class="form-control" value="{{ $estudante->telefone_mae }}" id="inputSkills"
                          placeholder="Número telefonico da Mãe" disabled>
                      </div>
                    </div>

                  </div>
                  <!-- /.post -->
                </div>

                <div class="tab-pane" id="academico">
                  <div class="form-horizontal">

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nome Escola</label>
                        <div class="col-sm-10 form-control">
                          <span><a href="{{ route('web.informacao-escola-municipal', $escola->id) }}">{{ $escola->nome }}</a></span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Escola Província</label>
                      <div class="col-sm-10 form-control">
                        <span><a href="{{ route('web.informacao-escola-municipal', $escola->id) }}">{{ $escola->provincia->nome ?? ""}}</a></span>
                      </div>
                    </div>
                  
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Escola Município</label>
                    <div class="col-sm-10 form-control">
                      <span><a href="{{ route('listagem-escola-municipal', $escola->municipio->id) }}">{{ $escola->municipio->nome ?? "" }}</a></span>
                    </div>
                  </div>
                    
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Endereço da Escola</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" value="{{ $escola->endereco }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Sala</label>
                      <div class="col-sm-10">
                        @if ($sala)
                            <input type="email" class="form-control" value="{{ $sala->salas }}" disabled>
                        @else 
                            <input type="email" class="form-control" value=" ---- " disabled>
                        @endif
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Curso</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $curso->curso }}" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Classe</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $classe->classes }}" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Turno</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $turno->turno }}" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Turma</label>
                      <div class="col-sm-10">
                        @if ($turma)
                            <input type="email" class="form-control" value="{{ $turma->turma }}" disabled>
                        @else 
                            <input type="email" class="form-control" value=" ---- " disabled>
                        @endif
                      </div>
                    </div>

                  </div>
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
                          value="{{ $encarregado->nome }} {{ $encarregado->sobre_nome }}" id="inputName"
                          placeholder="Nome Completo do Pai Estudante" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $encarregado->nascimento }}" id="inputEmail"
                          placeholder="Data do Nascimento do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $encarregado->genero }}" id="inputName2"
                          placeholder="Genero do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Estado Cívil</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $encarregado->estado_civil }}" id="inputName2"
                          placeholder="Nome Completo do Pai do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Profissão</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $encarregado->profissao }}" id="inputName2"
                          placeholder="Nome Completo da Mãe do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Grau Parentesco</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $encarregado->grau_parentesco }}"
                          id="inputName2" placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                      </div>
                    </div>


                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Telefone</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $encarregado->telefone }}" id="inputSkills"
                          placeholder="Número telefonico do Estudante" disabled>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Telefone 2</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $encarregado->telefone2 }}" id="inputSkills"
                          placeholder="Número telefonico do Estudante" disabled>
                      </div>
                    </div>

                  </div>
                  @endif
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