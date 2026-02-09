@extends('layouts.provinciais')

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
                        <form action="{{ route('web.controlo-lancamento-notas.escolas') }}" method="get">
                            <div class="card-body">
                                <div class="row">
                                    @csrf

                                    <div class="form-group col-md-4">
                                        <label for="escola_id">Escolas</label>
                                        <select name="escola_id" class="form-control escola_id select2" id="escola_id">
                                            <option value="">Todas</option>
                                            @foreach ($escolas_list as $item)
                                            <option value="{{ $item->id }}" {{ $requests['escola_id'] == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('escola_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="lancamento_id" value="{{ $requests['lancamento_id'] }}">

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
                                        <th>Escola</th>
                                        <th>Contacto Escola</th>
                                        <th>Contacto Alternativo</th>
                                        <th>Ano Lectivo</th>
                                        <th>Trimestre</th>
                                        <th>Total</th>
                                        <th>Lançados</th>
                                        <th>Restante</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($escolas as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><a href="{{ route('web.informacao-escola-provincial', Crypt::encrypt($item->escola->id) ) }}">{{ $item->escola->nome }}</a></td>
                                        <td>{{ $item->escola->telefone1 }}</td>
                                        <td>{{ $item->escola->telefone2 }}</td>
                                        <td>{{ $item->ano->ano }}</td>
                                        <td>{{ $item->lancamento->trimestre->trimestre }}</td>
                                        <td>{{ $item->total_estudantes }}</td>
                                        <td>{{ $item->total_lancados }}</td>
                                        <td>{{ $item->total_restantes }}</td>
                                        <td>
                                            @if ($item->status == 'activo')
                                            <span class="text-success text-uppercase">{{ $item->status }}</span>
                                            @else
                                            <span class="text-danger text-uppercase">{{ $item->status }}</span>
                                            @endif
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
                        , url: "status-controlo-lancamento/" + id
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
