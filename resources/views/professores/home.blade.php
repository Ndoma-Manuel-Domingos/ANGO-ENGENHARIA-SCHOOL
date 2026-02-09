@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Perfil do Professor</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
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
            <h5><i class="fas fa-info"></i> Mais informações sobre do Professor</h5>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
          @if(session()->has('message'))
          <div class="alert alert-success">
            {{ session()->get('message') }}
          </div>
          @endif
        </div>
      </div>

      <div class="row">

        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                @if (empty($professor->image))
                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}"
                  alt="User profile picture">
                @else
                <img class="profile-user-img img-fluid img-circle" src="{{ public_path("assets/images/professores/$professor->image") }}" alt="User profile picture">
                @endif
              </div>

              <h3 class="profile-username text-center">{{ $professor->nome }} {{ $professor->sobre_nome }}</h3>
              <h3 class="profile-username text-center">Nº de Ordem: {{ $professor->id }}</h3>

              <p class="text-muted text-center">{{ $professor->academico->curso }}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Nascimento</b> <a class="float-right">{{ $professor->nascimento }}</a>
                </li>
                <li class="list-group-item">
                  <b>Genero</b> <a class="float-right">{{ $professor->genero }}</a>
                </li>

                <li class="list-group-item">
                  <b>Funcionário</b> <a class="float-right">{{ $professor->status }}</a>
                </li>

                <li class="list-group-item">
                  <b>Cargo</b> <a class="float-right">{{ $contrato->cargos->cargo ?? "" }}</a>
                </li>

                <li class="list-group-item">
                  <b>Departamento</b> <a class="float-right">{{ $contrato->departamento->departamento ?? "" }}</a>
                </li>

                <li class="list-group-item">
                  <b>Total Escolas</b> <a class="float-right" href="">{{ count($escolas) }}</a>
                </li>
              </ul>

              <form action="{{ route('web.professor-foto-perfil') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" name="foto"  id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Clica para escolher Foto</label>
                      </div>
                      <input type="hidden" name="professor_id" value="{{ $professor->id }}">
                    </div>
                  </div>
                  @error('fotografiaEstudante')
                  <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                  <button type="submit" class="btn btn-primary">Editar Foto do Perfil</button>
                </div>

              </form>

            </div>
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
                <li class="nav-item"><a class="nav-link" href="#academico" data-toggle="tab">Académicos</a></li>
                @if (Auth::user()->can('read: escola'))
                <li class="nav-item"><a class="nav-link" href="#dados_pessoas_pai" data-toggle="tab">Escolas</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="#documentos" data-toggle="tab">Documentos</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">

                <div class="active tab-pane" id="dados_pessoas">
                  <form class="form-horizontal">
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nome Completo</label>
                        <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $professor->nome }} {{ $professor->sobre_nome }}" id="inputName" placeholder="Name" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nome Pai</label>
                        <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $professor->pai }}" id="inputName" placeholder="Name" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Nome Completo da Mãe</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $professor->mae }}" id="inputEmail" placeholder="Email" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Data Nascimento</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $professor->nascimento }}" id="inputEmail" placeholder="Email" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Genero</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $professor->genero }}" id="inputEmail" placeholder="Email" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Estado Cívil</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" value="{{ $professor->estado_civil }}" placeholder="Name" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Bilhete</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" value="{{ $professor->bilheite }}" placeholder="Name" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Data Emissão Bilhete</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" value="{{ $professor->emissiao_bilheite }}" placeholder="Name" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Província</label>
                        <div class="col-sm-10">
                        <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $professor->provincia->nome }}">
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Municpio</label>
                        <div class="col-sm-10">
                        <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $professor->municipio->nome }}" >
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Distrito</label>
                        <div class="col-sm-10">
                        <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $professor->distrito->nome }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Endereço</label>
                        <div class="col-sm-10">
                        <textarea class="form-control" id="inputExperience" placeholder="Experience" disabled>{{ $professor->endereco }}</textarea>
                        </div>
                    </div>

                </form>
                </div>

                <div class="tab-pane" id="dados_pessoas_pai">

                  <div class="form-horizontal">

                    @if (count($infor_escola) > 0)
                    @foreach ($infor_escola as $item)
                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label"><a
                          href="{{ route('prof.informacao-turma-professores', [Crypt::encrypt($professor->id), Crypt::encrypt($item->id)]) }}">Mais
                          informação</a></label>
                      <div class="col-sm-10">
                        <div class="form-control">{{ $item->nome }}</div>
                      </div>
                    </div>
                    @endforeach
                    @endif

                  </div>
                  <!-- /.post -->
                </div>

                <div class="tab-pane" id="academico">
                  <form class="form-horizontal">

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Cargo</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" disabled
                          value="{{ $contrato->cargos->cargo ?? "" }}" placeholder="Name">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Departamento</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" disabled
                          value="{{ $contrato->departamento->departamento ?? "" }}" placeholder="Name">
                      </div>
                    </div>


                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Universidade</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2"
                          value="{{ $professor->academico->universidade->nome }}" disabled placeholder="Name">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Especialidade</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control"
                          value="{{ $professor->academico->especialidade->nome }}" id="inputName" disabled
                          placeholder="Name">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Categoria</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $professor->academico->categoria->nome }}"
                          id="inputEmail" disabled placeholder="Email">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Nível Academico</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2"
                          value="{{ $professor->academico->escolaridade->nome }}" disabled placeholder="Name">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Formação</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2"
                          value="{{ $professor->academico->formacao_academica->nome }}" disabled placeholder="Name">
                      </div>
                    </div>

                  </form>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="documentos">
                  <div class="form-horizontal">

                    @if ($documentos)
                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Bilhete de Identidade</label>
                      <div class="col-sm-10">
                        <div type="email" class="form-control"><a
                            href='{{ asset("/assets/arquivos/$documentos->bilheite") }}' target="_blink">{{
                            $documentos->bilheite ?? 'sem documento' }}</a></div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label"> Atestado Médico </label>
                      <div class="col-sm-10">
                        <div type="email" class="form-control"><a
                            href='{{ asset("/assets/arquivos/$documentos->atestado") }}' target="_blink">{{
                            $documentos->atestado ?? 'sem documento' }}</a></div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Certificado</label>
                      <div class="col-sm-10">
                        <div type="email" class="form-control"><a
                            href='{{ asset("/assets/arquivos/$documentos->certificado") }}' target="_blink">{{
                            $documentos->certificado ?? 'sem documento'}}</a></div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Outros Documentos</label>
                      <div class="col-sm-10">
                        <div type="email" class="form-control"><a
                            href='{{ asset("/assets/arquivos/$documentos->outros") }}' target="_blink">{{
                            $documentos->outros ?? 'sem documento' }}</a></div>
                      </div>
                    </div>
                    @endif

                  </div>
                </div>

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