@extends('layouts.municipal')

@section('content')
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Activar controle de Lançamento de Notas.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Voltar</li>
                        <li class="breadcrumb-item active">Notas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('web.municipal-controlo-lancamento-notas.store') }}" method="post">
                            <div class="card-body">
                                <div class="row">
                                    @csrf

                                    <div class="form-group col-md-4">
                                        <label for="escola_id">Escolas</label>
                                        <select name="escola_id[]" class="form-control escola_id select2" multiple id="escola_id">
                                            <option value="">Selecione</option>
                                            <option value="todas">Todas</option>
                                            @foreach ($escolas as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('escola_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="ano_lectivo_id">Ano Lectivo</label>
                                        <select name="ano_lectivo_id" class="form-control ano_lectivo_id select2" id="ano_lectivo_id">
                                            <option value="">Selecione</option>
                                            @foreach ($ano_lectivos as $item)
                                            <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="trimestre_id">Trimestre</label>
                                        <select name="trimestre_id" class="form-control trimestre_id select2" id="trimestre_id">
                                            <option value="">Selecione</option>
                                            @foreach ($trimestres as $trimestre)
                                            <option value="{{ $trimestre->id }}">{{ $trimestre->trimestre }}</option>
                                            @endforeach
                                        </select>
                                        @error('trimestre_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input type="date" name="data_inicio" class="form-control" id="data_inicio">
                                        @error('data_inicio')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="data_final">Data Final</label>
                                        <input type="date" name="data_final" class="form-control" id="data_final">
                                        @error('data_final')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control status select2" id="status">
                                            <option value="activo" selected>Activo</option>
                                            <option value="desactivo">Desactivo</option>
                                        </select>
                                        @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer justify-content-between">
                                <button type="submit" class="btn btn-success">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="row">
                <div class="col-12 col-md-12 mb-4">
                    <div id="poll_div"></div>
                    {!! $lava->render('ColumnChart', 'Grafico', 'poll_div') !!}
                </div>
            </div>             --}}

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="carregarCargos" style="width: 100%" class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th>Cod</th>
                                        <th>Direcção</th>
                                        <th>Escolas</th>
                                        <th>Trimestre</th>
                                        <th>Ano Lectivo</th>
                                        <th>Data Inicio</th>
                                        <th>Data Final</th>
                                        <th>Status</th>
                                        <th nowrap class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($controlos as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->direccao($item->direccao_id, $item->level) }}</td>
                                        <td><a href="{{ route('web.municipal-controlo-lancamento-notas.escolas', ['lancamento_id' => $item->id]) }}">Visualizar Escolas</a></td>
                                        <td>{{ $item->trimestre->trimestre }}</td>
                                        <td>{{ $item->ano_global->ano }}</td>
                                        <td>{{ $item->inicio }}</td>
                                        <td>{{ $item->final }}</td>
                                        <td>
                                            @if ($item->status == 'activo')
                                            <span class="text-success text-uppercase">{{ $item->status }}</span>
                                            @else
                                            <span class="text-danger text-uppercase">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Opções</button>
                                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    <a href="#" title="Editar Cargo" id="{{ $item->id }}" class="dropdown-item mudar_status_lancamento"><i class="fa fa-edit"></i> Reactivar</a>

                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">Outros</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

</div>

@endsection

@section('scripts')
<script>
    $(function() {

        // delete
        $(document).on('click', '.mudar_status_lancamento', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas activar este controle de lançamento de notas?"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, activar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "GET"
                        , url: "municipal-status-controlo-lancamento/" + id
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



        $("#carregarCargos").DataTable({
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
