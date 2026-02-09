@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Lançar efectividade dos professores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Faltas</a></li>
                    <li class="breadcrumb-item active">Turmas</li>
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
                <div class="callout callout-info">
                    <h5>
                        <i class="fas fa-info"></i> Marcar faltas para professores
                        <a href="{{ route('web.faltas-turmas-funcionarios-get') }}" class="float-end btn btn-primary text-white text-decoration-none">Carregar Lista de Hoje</a>
                    </h5>
                </div>
            </div>
        </div>

        @if ($mapaEfectividade)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Funcionário</th>
                                    {{-- <th>Telefone</th> --}}
                                    <th>Dia de Semana</th>
                                    <th>Dia</th>
                                    <th>Mês</th>
                                    <th>Faltas</th>
                                    <th>Status</th>
                                    <th>Ano Lectivo</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>

                            <tbody id="">
                                @foreach ($mapaEfectividade as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                                    <td>{{ $item->dia_semana }}</td>
                                    <td>{{ $item->dia }}</td>
                                    <td>{{ $item->mes }}</td>
                                    <td>{{$item->faltas}}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->ano }}</td>
                                    <td class="text-right">
                                        <a href="#" id="{{$item->id}}" title="Actualizar o novo status" class="activar_novo_Status btn-success btn">Actualizar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="modal fade" id="modalFormEditarEstudantes">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Informe status</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="controlo_faltas">Faltas</label>
                                <input type="text" name="controlo_faltas" class="form-control controlo_faltas">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="controlo_status">Status</label>
                                <select name="genero" id="genero" class="form-control controlo_status">
                                    <option value="Presente">Presente</option>
                                    <option value="Ausente">Ausente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        {{-- <button type="button" class="btn btn-default" data-dismiss="modal"></button> --}}
                        <button type="button" class="btn btn-success float-end editar_estudantes_form">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(function() {

        var idIDID;

        $(document).on('click', '.activar_novo_Status', function(e) {
            e.preventDefault();
            idIDID = $(this).attr('id');
            $("#modalFormEditarEstudantes").modal("show");
        });



        $(document).on('click', '.editar_estudantes_form', function(e) {
            e.preventDefault();

            var data = {
                'controlo_faltas': $('.controlo_faltas').val()
                , 'controlo_status': $('.controlo_status').val()
                , 'controlo_id': idIDID
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.faltas-turmas-funcionarios-post') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , 
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

        });

    });

</script>

@endsection
