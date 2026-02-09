@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Listagem de todas as Escolas @if($provincia) da província de {{ $provincia->name }} @endif </h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">sistema</a></li>
                    <li class="breadcrumb-item active">geral</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('listagem-escola') }}" method="get">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-3">
                                    <label for="ano_lectivo">Anos Lectivos</label>
                                    <select name="ano_lectivo" class="form-control select2 editar_status_ano" id="ano_lectivo">
                                        <option value="">Todos</option>
                                        @foreach ($ano_lectivos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['ano_lectivo'] ==  $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivo')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="provincia_id">Províncias</label>
                                    <select name="provincia_id" class="form-control select2 editar_status_ano" id="provincia_id">
                                        <option value="">Todas</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ $requests['provincia_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="municipio_id">Municipios</label>
                                    <select name="municipio_id" class="form-control select2 editar_status_ano" id="municipio_id">
                                        <option value="">Todas</option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ $requests['municipio_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-2">
                                    <label for="ensino_id">Ensinos</label>
                                    <select name="ensino_id" class="form-control select2 editar_status_ano" id="ensino_id">
                                        <option value="">Todas</option>
                                        @foreach ($ensinos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['ensino_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('ensino_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="distrito_id">Distritos</label>
                                    <select name="distrito_id" class="form-control select2 editar_status_ano" id="distrito_id">
                                        <option value="">Todas</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['distrito_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            @if (count($escolas) == 0)
            <div class="col-12 col-md-12">
                <div class="callout callout-danger">
                    <h5 class="text-danger"><i class="fas fa-info"></i> Sem registro Encontrados.</h5>
                </div>
            </div>
            @else
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="text-info  float-start">Registro Encontrados. Total: {{ count($escolas) }}</h5>
                        <a href="{{ route('print.listagem-escola-imprmir', ['ensino_id' => $requests['ensino_id'] ?? "", 'municipio_id' => $requests['municipio_id'] ?? "", 'ano_lectivo' => $requests['ano_lectivo'] ?? "", 'provincia_id' => $requests['provincia_id'] ?? ""]) }}" target="_blink" class="btn btn-primary float-end">Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarEscolas" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Nº</th>
                                    <th style="width: 5%">NIF</th>
                                    <th width="">Escolas</th>
                                    <th width="">Director</th>
                                    <th width="">Província</th>
                                    <th width="">Municipio</th>
                                    <th width="">Distrito</th>
                                    <th width="">Sistema Ensino</th>
                                    <th style="width: 7%">T. Alunos</th>
                                    <th style="width: 7%">T. Professores</th>
                                    <th style="width: 5%">Status</th>
                                    <th style="width: 5%">Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($escolas)
                                @foreach ($escolas as $key => $escola)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $escola->documento }}</td>
                                    <td><a href="{{ route('web.informacao-escola', $escola->id) }}">{{ $escola->nome }}</a></td>
                                    <td>{{ $escola->director }}</td>
                                    <td>{{ $escola->provincia->nome ?? ""}}</td>
                                    <td>{{ $escola->municipio->nome ?? "" }}</td>
                                    <td>{{ $escola->distrito->nome ?? "" }}</td>
                                    <td>{{ $escola->ensino->nome ?? "" }}</td>
                                    <td>{{ $escola->total_estudantes($escola->id) }}</td>
                                    <td>{{ $escola->total_professores($escola->id) }}</td>
                                    <td>{{ $escola->status }}</td>
                                    <td>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('read: escola'))
                                                <a href="{{ route('web.informacao-escola', $escola->id) }}" title="Visualizar escola" id="" class="dropdown-item "><i class=""></i>Visualizar</a>
                                                @endif
                                                @if (Auth::user()->can('delete: escola'))
                                                <a href="{{ route('app.eliminar_escola', $escola->id) }}" value="{{ $escola->id }}" id="{{ $escola->id }}" title="Eliminar escola" id="" class="dropdown-item delete_escola"><i class=""></i>Eliminar</a>
                                                @endif
                                                @if (Auth::user()->can('update: escola'))
                                                <a href="{{ route('web.configurar-escola', $escola->id) }}" title="Visualizar escola" id="" class="dropdown-item "><i class=""></i>Configurar</a>
                                                @endif

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            @endif
        </div>

    </div>
</div>

@endsection


@section('scripts')
<script>
    $("#provincia_id").change(() => {
        let id = $("#provincia_id").val();
        $.get('carregar-municipios/' + id, function(data) {
            $("#municipio_id").html("")
            $("#municipio_id").html(data)
        })
    })

    $("#municipio_id").change(() => {
        let id = $("#municipio_id").val();
        $.get('carregar-distritos/' + id, function(data) {
            $("#distrito_id").html("")
            $("#distrito_id").html(data)
        })
    })

    $("#instituicao_id").change(() => {
        let id = $("#instituicao_id").val();
        $.get('carregar-destino-funcionarios/' + id, function(data) {
            $("#instituicoes_destino").html("")
            $("#instituicoes_destino").html(data)
        })
    })

    $("#departamento_id").change(() => {
        let id = $("#departamento_id").val();
        $.get('carregar-cargos-departamentos/' + id, function(data) {
            $("#cargo_id").html("")
            $("#cargo_id").html(data)
        })
    })

</script>
@endsection

@section('scripts')
<script>
    $(function() {

        // delete
        $(document).on('click', '.delete_escola', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

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
                        , url: "eliminar-escola/" + novo_id
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
        $("#carregarEscolas").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
