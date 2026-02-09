@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if ($matricula->status_matricula == 'rejeitado')
                    <h1 class="m-0 text-warning">Candidatura/Inscrição/Matrícula Rejeitada</h1>
                    @else
                    <h1 class="m-0 text-danger">Candidatura/Inscrição/Matrícula Não Confirmada</h1>
                    @endif
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Voltar</a></li>
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
                    @if ($matricula->status_matricula == 'rejeitado')
                    <div class="callout callout-warning">
                        <h5><i class="fas fa-cancel"></i> Mais informações sobre a Candidatura/Inscrição.</h5>
                    </div>
                    @else
                    <div class="callout callout-danger">
                        <h5><i class="fas fa-info"></i> Mais informações sobre a Candidatura/Inscrição.</h5>
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
                                @if (empty($estudante->image))
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}" alt="User profile picture">
                                @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ public_path("
                  assets/images/recursosHumanos/$estudante->image") }}" alt="User profile picture">
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
                                <li class="nav-item"><a class="nav-link" href="#candidatura" data-toggle="tab">Candidatura/Inscrição</a></li>
                                <li class="nav-item"><a class="nav-link" href="#documentos" data-toggle="tab">Documentos</a></li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="dados_pessoas">

                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Ficha Matricula</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $matricula->documento }}" id="inputName" placeholder="Nome Completo do Pai Estudante" disabled>
                                            </div>
                                        </div>

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
                                                <input type="text" class="form-control" value="{{ $estudante->data_emissao }}" id="inputName2" placeholder="Número do Bilhete" disabled>
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

                                <div class="tab-pane" id="candidatura">
                                    <div class="form-horizontal">

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Nº Candidatura</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $matricula->numeracao }}" id="inputEmail" placeholder="Númeração da candidatura" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Estado</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $matricula->status }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Curso</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" value="{{ $curso->curso }}" id="inputEmail" placeholder="Data do Nascimento do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Classe</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $classe->classes }}" id="inputName2" placeholder="Genero do Estudante" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Turno</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $turno->turno }}" id="inputName2" placeholder="Nome Completo do Pai do Estudante" disabled>
                                            </div>
                                        </div>

                                    </div>
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
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @if (Auth::user()->can('delete: matricula') || Auth::user()->can('delete: estudante'))
                            <a class="btn btn-danger m-1 delete_matricula_estudane" id="{{ $matricula->id }}" style="cursor: pointer">Excluir</a>
                            @endif

                            @if ($matricula->status_matricula == 'rejeitado')
                            @if (Auth::user()->can('update: estado'))
                            <a class="btn btn-success m-1 reiaceite_matricula_estudane" id="{{ $matricula->id }}" style="cursor: pointer">Reaceitar</a>
                            @endif
                            @else
                            @if (Auth::user()->can('update: estado'))
                            <a class="btn btn-success m-1" href="{{ route('web.finalizar-aprovar-candidatura', $matricula->id) }}" style="cursor: pointer">Aprovar</a>
                            <a class="btn btn-warning m-1 rejeitar_matricula_estudane" id="{{ $matricula->id }}" style="cursor: pointer">Rejeitar</a>
                            @endif
                            @endif
                        </div>
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

@section('scripts')

<script>
    $(function() {
        // delete
        $(document).on('click', '.delete_matricula_estudane', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas remover esta Inscrição/Candidatura/Matrícula"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Apagar Estes dados!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "DELETE"
                        , url: `../../estudantes/excluir-matricula-estudantes/${novo_id}`
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.rejeitar_matricula_estudane', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas Rejeitar esta Inscrição/Candidatura/Matrícula"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, rejeitar estes dados!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "DELETE"
                        , url: `../../estudantes/rejeitar-matricula-estudantes/${novo_id}`
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.reiaceite_matricula_estudane', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas Reaceitar esta Inscrição/Candidatura/Matrícula"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, reaceitar estes dados!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "DELETE"
                        , url: `../../estudantes/reiaceitar-matricula-estudantes/${novo_id}`
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });
    });

</script>
@endsection
