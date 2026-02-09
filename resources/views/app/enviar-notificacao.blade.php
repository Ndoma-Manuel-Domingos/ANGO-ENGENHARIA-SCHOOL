@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Enviar Notificações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Notificações</li>
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
            <div class="col-md-3">
                <a href="{{ route('web.entradas-notificao') }}" class="btn btn-primary btn-block mb-3">Entrada de Notificações</a>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Arquivos</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            {{-- <li class="nav-item active">
                        <a href="{{ route('web.entradas-notificao') }}" class="nav-link">
                            <i class="fas fa-inbox"></i> Entradas
                            <span class="badge bg-primary float-right">0</span>
                            </a>
                            </li>
                            --}}
                            <li class="nav-item">
                                <a href="{{ route('web.enviadas-notificao') }}" class="nav-link">
                                    <i class="far fa-envelope"></i> Enviadas
                                    <span class="badge bg-primary float-right">{{ $notificacaoEnviadas }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('web.reciclagem-notificao') }}" class="nav-link">
                                    <i class="far fa-envelope"></i> Reciclagem
                                    <span class="badge bg-primary float-right">{{ $notificacaoReciclagem }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Mensagem para Enviar</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="encarregados">Encarregado</label>
                                    <select class="select2 encarregados" multiple="multiple" id="encarregados" data-placeholder="Selecione o encarregado" style="width: 100%;">
                                        @if ($encarregados)
                                        @foreach ($encarregados as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger error-text encarregados_error"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Titulo</label>
                                    <textarea class="form-control titulo" id="titulo" style="height: 50px"></textarea>
                                    <span class="text-danger error-text titulo_error"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea class="form-control descricao" id="descricao" style="height: 100px"></textarea>
                                    <span class="text-danger error-text descricao_error"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary enviar_boletins_submit"><i class="far fa-envelope"></i> Enviar</button>
                            <button type="submit" class="btn btn-primary enviar_boletins_submit_sms"><i class="far fa-envelope"></i> Por Mensagem</button>
                        </div>
                        <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Cancelar</button>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('scripts')
<script>
    // Cadastrar
    $(document).on('click', '.enviar_boletins_submit', function(e) {
        e.preventDefault();

        var data = {
            'encarregados': $('.encarregados').val()
            , 'titulo': $('.titulo').val()
            , 'descricao': $('.descricao').val()
        , }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST"
            , url: "{{ route('web.enviar-notificacao-post') }}"
            , data: data
            , dataType: "json"
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
    });

    $(document).on('click', '.enviar_boletins_submit_sms', function(e) {
        e.preventDefault();

        var data = {
            'encarregados': $('.encarregados').val()
            , 'titulo': $('.titulo').val()
            , 'descricao': $('.descricao').val()
        , }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST"
            , url: "{{ route('web.enviar-notificacao-post') }}"
            , data: data
            , dataType: "json"
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
    });

</script>
@endsection
