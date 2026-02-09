@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Listagem de todas as Escolas @if($municipio) da província de {{ $municipio->nome }} @endif </h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                    <li class="breadcrumb-item active">Escolas</li>
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
                    <form action="{{ route('listagem-escola-provincial', Crypt::encrypt(null)) }}" method="get">
                        <div class="card-body">
                            <div class="row">
                                @csrf

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="status" class="form-label">Estado</label>
                                    <select name="status" id="status" class="form-control status select2">
                                        <option value="">Todas</option>
                                        <option value="activo" {{ $requests['status'] == 'activo' ? : '' }}>Activo</option>
                                        <option value="desactivo" {{ $requests['status'] == 'desactivo' ? : '' }}>Desactivo</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="categoria" class="form-label">Sector</label>
                                    <select name="categoria" id="categoria" class="form-control categoria select2">
                                        <option value="">Todas</option>
                                        <option value="Publico" {{ $requests['categoria'] == 'Publico' ? : '' }}>Publico</option>
                                        <option value="Publico-Privado" {{ $requests['categoria'] == 'Publico-Privado' ? : '' }}>Público Privado</option>
                                    </select>
                                    @error('categoria')
                                    <span class="text-danger error-text">{{ $message }}</span>
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

                                <div class="form-group col-md-2">
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
                        <a href="{{ route('print.provincial-listagem-escola-imprmir', ['categoria' => $requests['categoria'], 'status' => $requests['status'], 'ensino_id' => $requests['ensino_id'], 'distrito_id' => $requests['distrito_id'], 'municipio_id' => $requests['municipio_id']]) }}" target="_blink" class="btn btn-primary float-end">Imprimir</a>
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
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($escolas)
                                @foreach ($escolas as $key => $escola)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $escola->documento }}</td>
                                    <td><a href="{{ route('web.informacao-escola-provincial', Crypt::encrypt($escola->id)) }}">{{ $escola->nome }}</a></td>
                                    <td>{{ $escola->director }}</td>
                                    <td>{{ $escola->provincia->nome ?? ""}}</td>
                                    <td>{{ $escola->municipio->nome ?? "" }}</td>
                                    <td>{{ $escola->distrito->nome ?? "" }}</td>
                                    <td>{{ $escola->ensino->nome ?? "" }}</td>
                                    <td>{{ $escola->total_estudantes($escola->id) }}</td>
                                    <td>{{ $escola->total_professores($escola->id) }}</td>
                                    <td>{{ $escola->status }}</td>
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
    $(function() {
        $("#provincia_id").change(() => {
            let country_id = $("#provincia_id").val();
            $.get('../carregar-municipios/' + country_id, function(data) {
                $("#municipio_id").html("")
                $("#municipio_id").html(data)
            })
        })
    });

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
