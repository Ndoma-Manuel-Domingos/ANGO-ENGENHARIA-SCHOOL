@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Configuração do Ano lectivo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.ano-lectivo') }}">Anos Lectivos</a></li>
                    <li class="breadcrumb-item active">Configuração</li>
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
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs p-2" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#formClasses" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Classes</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#formCursos" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Cursos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#formTurnos" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Turnos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#formSalas" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Salas</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#formTrimestre" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Trimestres</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="formClasses" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <form class="row" method="post" action="{{ route('web.cadastrar-classes-ano-lectivo') }}">
                                    @csrf
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="ano_lectivo_classes_id">Classe <span class="text-danger">*</span></label>
                                        <select class="form-select ano_lectivo_classes_id select2" name="classes_id[]" style="width: 100%;" data-placeholder="Selecione um conjunto de Classes" multiple="multiple" aria-label="Default select example">
                                            <option value="">Classes</option>
                                            @if ($classes)
                                            @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}">{{ $classe->classes }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('classes_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                        <select class="form-select classes_ano_lectivos_id" name="ano_lectivo_id" aria-label="Default select example">
                                            {{-- <option value="">Ano Lectivo</option> --}}
                                            @if ($anolectivos)
                                            <option value="{{ $anolectivos->id }}">{{ $anolectivos->ano }}</option>
                                            @endif
                                        </select>
                                        @error('ano_lectivo_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        @if (Auth::user()->can('create: classe'))
                                        <button type="submit" class="btn btn-primary cadastrar_classes_ano_lectivo">Salvar</button>
                                        @endif
                                    </div>
                                </form>

                                <table style="width: 100%" class="table table-bordered  ">
                                    {{-- <h6 class="bg-dark p-2">Classes já Adicionadas</h6> --}}
                                    <thead>
                                        <th>Cod</th>
                                        <th>Classe</th>
                                        <th>Status</th>
                                        <th>Categoria</th>
                                        <th>Acções</th>
                                    </thead>
                                    <tbody id="table_classes_ano_lectivo">
                                        @foreach ($classesAnoLectivos as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->classes }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->categoria }}</td>
                                            <td>
                                                <a type="button" id="{{ $item->id }}" class="excluir_classes_ano_lectivo btn-danger btn"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="formCursos" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <form class="row" action="{{ route('web.cadastrar-cursos-ano-lectivo') }}" method="post">
                                    @csrf
                                    <div class="col-md-6 mb-3">
                                        <label for="ano_lectivo_cursos_id">Curso <span class="text-danger">*</span></label>
                                        <select class="form-select ano_lectivo_cursos_id select2" name="cursos_id[]" style="width: 100%;" data-placeholder="Selecione um conjunto de Cursos" multiple="multiple" id="ano_lectivo_cursos_id">
                                            @if ($cursos)
                                            @foreach ($cursos as $curso)
                                            <option value="{{ $curso->id }}">{{ $curso->curso }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('cursos_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                        <select class="form-select cursos_ano_lectivos_id" name="ano_lectivo_id" aria-label="Default select example">
                                            {{-- <option value="">Ano Lectivo</option> --}}
                                            @if ($anolectivos)
                                            <option value="{{ $anolectivos->id }}">{{ $anolectivos->ano }}</option>
                                            @endif
                                        </select>
                                        @error('ano_lectivo_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        @if (Auth::user()->can('create: curso'))
                                        <button type="submit" class="btn btn-primary cadastrar_cursos_ano_lectivo">Salvar</button>
                                        @endif
                                    </div>
                                </form>

                                <table style="width: 100%" class="table table-bordered  ">
                                    {{-- <h6 class="bg-dark p-2">Classes já Adicionadas</h6> --}}
                                    <thead>
                                        <th>Cod</th>
                                        <th>Curso</th>
                                        <th>Status</th>
                                        <th>Tipo</th>
                                        <th>Área de Formação</th>
                                        <th>Acções</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($cursosAnoLectivos as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->curso }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->tipo }}</td>
                                            <td>{{ $item->area_formacao }}</td>
                                            <td>
                                                <a type="button" id="{{ $item->id }}" class="excluir_cursos_ano_lectivo btn-danger btn"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="formTurnos" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">

                                <form class="row" method="post" action="{{ route('web.cadastrar-turnos-ano-lectivo') }}">
                                    @csrf
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">Turno <span class="text-danger">*</span></label>
                                        <select class="form-select ano_lectivo_turnos_id select2" name="turnos_id[]" style="width: 100%;" data-placeholder="Selecione um conjunto de Turnos" multiple="multiple" id="ano_lectivo_turnos_id">
                                            @if ($turnos)
                                            @foreach ($turnos as $turno)
                                            <option value="{{ $turno->id }}">{{ $turno->turno }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('turnos_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                        <select class="form-select turnos_ano_lectivos_id" name="ano_lectivo_id">
                                            {{-- <option value="">Ano Lectivo</option> --}}
                                            @if ($anolectivos)
                                            <option value="{{ $anolectivos->id }}">{{ $anolectivos->ano }}</option>
                                            @endif
                                        </select>
                                        @error('ano_lectivo_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-">
                                        @if (Auth::user()->can('create: turno'))
                                        <button type="submit" class="btn btn-primary cadastrar_turnos_ano_lectivo">Salvar</button>
                                        @endif
                                    </div>
                                </form>

                                <table style="width: 100%" class="table table-bordered  ">
                                    {{-- <h6 class="bg-dark p-2">Classes já Adicionadas</h6> --}}
                                    <thead>
                                        <th>Cod</th>
                                        <th>Turno</th>
                                        <th>Status</th>
                                        <th>Acções</th>
                                    </thead>
                                    <tbody id="table_turnos_ano_lectivo">
                                        @foreach ($turnosAnoLectivos as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->turno }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>
                                                <a type="button" id="{{ $item->id }}" class="excluir_turnos_ano_lectivo btn-danger btn"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="tab-pane fade" id="formSalas" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                <form class="row" action="{{ route('web.cadastrar-salas-ano-lectivo') }}" method="post">
                                    @csrf
                                    <div class="col-md-6 mb-3">
                                        <label for="">Salas <span class="text-danger">*</span></label>
                                        <select class="form-select ano_lectivo_salas_id select2" name="salas_id[]" id="ano_lectivo_salas_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Salas" multiple="multiple">
                                            @if ($salas)
                                            @foreach ($salas as $sala)
                                            <option value="{{ $sala->id }}">{{ $sala->salas }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('salas_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6  mb-3">
                                        <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                        <select class="form-select salas_ano_lectivos_id" name="ano_lectivo_id">
                                            {{-- <option value="">Ano Lectivo</option> --}}
                                            @if ($anolectivos)
                                            <option value="{{ $anolectivos->id }}">{{ $anolectivos->ano }}</option>
                                            @endif
                                        </select>
                                        @error('ano_lectivo_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        @if (Auth::user()->can('create: sala'))
                                        <button type="submit" class="btn btn-primary cadastrar_salas_ano_lectivo">Salvar</button>
                                        @endif
                                    </div>
                                </form>

                                <table style="width: 100%" class="table table-bordered  ">
                                    {{-- <h6 class="bg-dark p-2">Classes já Adicionadas</h6> --}}
                                    <thead>
                                        <th>Cod</th>
                                        <th>Sala</th>
                                        <th>Status</th>
                                        <th>Tipo</th>
                                        <th>Acções</th>
                                    </thead>
                                    <tbody id="table_salas_ano_lectivo">
                                        @foreach ($salasAnoLectivos as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->salas }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->tipo }}</td>
                                            <td>
                                                <a type="button" id="{{ $item->id }}" class="excluir_salas_ano_lectivo btn-danger btn"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="formTrimestre" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                                <table style="width: 100%" class="table table-bordered  ">
                                    <thead>
                                        <th>Cod</th>
                                        <th>Trimestre</th>
                                        <th>Inicio</th>
                                        <th>Final</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($trimestreAnoLectivos as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->trimestre }}</td>
                                            <td>{{ $item->inicio }}</td>
                                            <td>{{ $item->final }}</td>
                                            <td>{{ $item->status }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(function() {

        // delete classes ano Lectivo
        $(document).on('click', '.excluir_classes_ano_lectivo', function(e) {
            e.preventDefault();
            var ano_lectivo_id = $(this).attr("id");

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas remover esta informação"
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
                        , url: "../excluir-classes-ano-lectivo/" + ano_lectivo_id
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

        // delete turnos ano Lectivo
        $(document).on('click', '.excluir_turnos_ano_lectivo', function(e) {
            e.preventDefault();
            var ano_lectivo_id = $(this).attr("id");

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas remover esta informação"
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
                                , url: "../excluir-turnos-ano-lectivo/" + ano_lectivo_id
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
                            }
                        }
                    });
            }
        });
    });

    // delete turnos ano Lectivo
    $(document).on('click', '.excluir_salas_ano_lectivo', function(e) {
        e.preventDefault();
        var ano_lectivo_id = $(this).attr("id");

        Swal.fire({
            title: "Tens a certeza"
            , text: "Que desejas remover esta informação"
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
                    , url: "../excluir-salas-ano-lectivo/" + ano_lectivo_id
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

    // delete cursos ano Lectivo
    $(document).on('click', '.excluir_cursos_ano_lectivo', function(e) {
      e.preventDefault();
      var ano_lectivo_id = $(this).attr("id");
  
      Swal.fire({
          title: "Tens a certeza"
          , text: "Que desejas remover esta informação"
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
                  , url: "../excluir-cursos-ano-lectivo/" + ano_lectivo_id
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

    $(function() {
        $("#carregarTabelaAnoLectivo").DataTable({
            "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["csv", "excel", "pdf", "print", "colvis"]
            , language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
        , }).buttons().container().appendTo('#carregarTabelaAnoLectivo_wrapper .col-md-6:eq(0)');
    });

</script>

@endsection
