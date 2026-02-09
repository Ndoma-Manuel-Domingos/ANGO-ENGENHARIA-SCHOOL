@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Registro de Efetividades</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                    <li class="breadcrumb-item active">Tempos</li>
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
                <form action="{{ route('tempos-lecionados.index') }}" method="GET" class="mb-4">
                <div class="card">
                        <div class="card-body">
                            @csrf
                            <div class="row">   
                                {{-- <div class="col-md-3 col-12">
                                    <label class="form-label">Mês</label>
                                    <select name="mes" id="mes" class="form-control select2">
                                        <option value="">Escolher</option>
                                        <option value="Jan" {{ $requests['mes'] == "Jan" }}>Janeiro</option>
                                        <option value="Feb">Fevereiro</option>
                                        <option value="Mar">Março</option>
                                        <option value="Apr">Abril</option>
                                        <option value="May">Maio</option>
                                        <option value="Jun">Junho</option>
                                        <option value="Jul">Julho</option>
                                        <option value="Aug">Agosto</option>
                                        <option value="Set">Setembro</option>
                                        <option value="Oct">Outombro</option>
                                        <option value="Nov">Novembro</option>
                                        <option value="Dec">Dezembro</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 col-12">
                                    <label class="form-label">Ano</label>
                                    <select name="ano" id="ano" class="form-control select2">
                                        <option value="">Escolher</option>
                                        @for ($i = 2023; $i < 2031; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div> --}}
                            
                                <div class="col-md-3 col-12">
                                    <label class="form-label">Data Início</label>
                                    <input type="date" name="data_inicio" value="{{ $requests['data_inicio'] ?? "" }}" class="form-control">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label class="form-label">Data Final</label>
                                    <input type="date" name="data_final" value="{{ $requests['data_final'] ?? "" }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
        <div class="row">
            <div class="col-12 col-md-12">
                
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <a target="_blink" href="{{ route('relatorio.tempos-lecionados', ['data_inicio'=> $requests['data_inicio'] ?? date('Y-m-d'), 'data_final' => $requests['data_final'] ?? date('Y-m-d') ]) }}" class="btn btn-danger mb-3"><i class="fas fa-file-pdf"></i> Imprimir</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="carregarTabela" >
                                    <thead>
                                        <tr>
                                            <th>Professor</th>
                                            <th>Tempos Previsto</th>
                                            <th>Tempos Dados</th>
                                            <th>Tempos Não Dados</th>
                                            <th>Mês</th>
                                            <th>Ano</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dados as $linha)
                                            <tr>
                                                <td><a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($linha['id'])) }}">{{ $linha['nome'] }}</a></td>
                                                <td>{{ $linha['tempos_previstos'] }}</td>
                                                <td>{{ $linha['tempos_dados'] }}</td>
                                                <td>{{ $linha['tempos_nao_dados'] }}</td>
                                                <td>{{ $linha['mes'] }}</td>
                                                <td>{{ $linha['ano'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-success mb-3" id="btnAdd"><i class="fas fa-plus"></i> Nova Efetividade</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="efetividadesTable">
                                    <thead>
                                        <tr>
                                            <th>Professor</th>
                                            <th>Data</th>
                                            <th>Tempos Dados</th>
                                            {{-- <th>Observação</th> --}}
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($efetividades as $ef)
                                        <tr data-id="{{ $ef->id }}">
                                            <td><a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($ef->funcionario->id)) }}">{{ $ef->funcionario->nome }} {{ $ef->funcionario->sobre_nome }}</a></td>
                                            <td>{{ $ef->data }}</td>
                                            <td>{{ $ef->tempos_dados }}º Tempos</td>
                                            {{-- <td>{{ $ef->observacao }}</td> --}}
                                            <td>
                                                <button class="btn btn-sm btn-primary btnEdit">Editar</button>
                                                <button class="btn btn-sm btn-danger btnDelete">Eliminar</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                <div class="modal fade" id="modalEfetividade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="formEfetividade">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST">
                                <input type="hidden" name="id" id="efetividade_id">

                                <div class="modal-header">
                                    <h5 class="modal-title">Registrar Efetividade</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Professor</label>
                                        <select name="professor_id" id="professor_id" class="form-control" required>
                                            <option value="">-- Selecione --</option>
                                            @foreach($professores as $prof)
                                            <option value="{{ $prof->funcionario->id }}">{{ $prof->funcionario->nome }} {{ $prof->funcionario->sobre_nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Data</label>
                                        <input type="date" name="data" id="data" value="{{ date("Y-m-d") }}" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Tempos Dados</label>
                                        <input type="number" name="tempos_dados" id="tempos_dados" class="form-control" required min="0">
                                    </div>

                                    <div class="form-group">
                                        <label>Observação</label>
                                        <textarea name="observacao" id="observacao" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Salvar</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    // Add
    $('#btnAdd').click(function() {
        $('#formEfetividade')[0].reset();
        $('#formMethod').val("POST");
        $('#efetividade_id').val('');
        $('#modalEfetividade').modal('show');
    });
    
        // Edit
    $(document).on('click', '.btnEdit', function() {
        let row = $(this).closest('tr');

        let id = row.data('id');

        $.ajax({
            url: `/tempos-lecionados/${id}/edit`, 
            type: "GET", 
            data: {
                _token: "{{ csrf_token() }}"
            }, 
            beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            },
            success: function(res) {
                
                Swal.close();
            
                $("#professor_id").val(res.professor_id).trigger("change");
                $('#data').val(res.data);
                $('#tempos_dados').val(res.tempos_dados);
                $('#observacao').val(res.observacao);
                $('#efetividade_id').val(res.id);

                $('#formMethod').val("PUT");
                $('#modalEfetividade').modal('show');
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    });
    
    
    // Save (Add/Edit)
    $('#formEfetividade').submit(function(e) {
        e.preventDefault();

        let id = $('#efetividade_id').val();
        let method = $('#formMethod').val();
        let url = (method === "POST") ? "{{ route('tempos-lecionados.store') }}" : "/tempos-lecionados/" + id;

        $.ajax({
            url: url, 
            type: method === "POST" ? "POST" : "PUT",
            data: $(this).serialize(), 
            beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }, success: function(res) {
                Swal.close();
                // Exibe uma mensagem de sucesso
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                window.location.reload();
            }, error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    });


    // Delete
    $(document).on('click', '.btnDelete', function(e) {
        e.preventDefault();
        let id = $(this).closest('tr').data('id');

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
                    url: `/tempos-lecionados/${id}` , 
                    type: "DELETE", 
                    data: {
                        _token: "{{ csrf_token() }}"
                    } 
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


    $(function() {
        $("#carregarTabela").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
    
    $(function() {
        $("#efetividadesTable").DataTable({
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
