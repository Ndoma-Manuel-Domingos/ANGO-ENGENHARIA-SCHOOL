@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Isentar Pagamento do estudante - <span class="text-danger">{{ $estudante->nome ?? '' }} {{ $estudante->sobre_nome ?? '' }}</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id)) }}">Voltar</a>
                    </li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                    <div class="card-header">
                        <h6>Ficha de pagamentos</h6>
                    </div>
                    <div class="card-body">
                        <table style="width: 100%" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Serviço</th>
                                    <th>Mês</th>
                                    <th>Multa</th>
                                    <th>Status</th>
                                    <th>Status Multa</th>
                                    <th style="width: 250px" class=" text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartoes as $cartao)
                                <tr>
                                    <td>{{ $cartao->id }}</td>
                                    <td>{{ $cartao->servico->servico ?? "" }}</td>
                                    <td>{{ $cartao->mes($cartao->month_name) }}</td>
                                    <td>{{ number_format($cartao->multa, 2, ',', '.') }} kz</td>
    
                                    @if ($cartao->status == "Pago")
                                    <td><span class="badge badge-success">{{ $cartao->status }}</span></td>
                                    @endif
                                    @if ($cartao->status == "Nao Pago")
                                    <td><span class="badge badge-danger">{{ $cartao->status }}</span></td>
                                    @endif
                                    @if ($cartao->status == "divida")
                                    <td><span class="badge badge-warning">{{ $cartao->status }}</span></td>
                                    @endif
                                    @if ($cartao->status == "Isento")
                                    <td><span class="badge badge-info">{{ $cartao->status }}</span></td>
                                    @endif
                                    @if ($cartao->status == "excepto")
                                    <td><span class="badge badge-dark">{{ $cartao->status }}</span></td>
                                    @endif
    
                                    @if ($cartao->status_multa == 'I')
                                    <td title="{{ $cartao->motivo_isencao_multa }}"> <span class="badge bg-success">Isento</span> <br>
                                        <small>Motivo: {{ $cartao->motivo_isencao_multa }}</small>
                                    </td>
                                    @else
                                    <td> <span class="badge bg-danger">Não Pago</span></td>
                                    @endif
    
                                    <td class="bg-infos text-right" style="width: 500px">
                                        @if (Auth::user()->can('create: isentar propina') || Auth::user()->can('update: isentar propina'))
                                        @if ($cartao->status == 'Isento')
                                        <button type="button" id="{{ $cartao->id }}" class="btn btn-danger remover_isentar_mensalidade_id"> <i class="fa fa-times"></i> Remover Isenção no mês</button>
                                        @else
                                        <button type="button" id="{{ $cartao->id }}" class="btn btn-info isentar_mensalidade_id"> <i class="fa fa-times"></i> Isentar o mês</button>
                                        @endif
                                        @endif
    
                                        @if (Auth::user()->can('create: isentar multa') || Auth::user()->can('update: isentar multa'))
                                        @if ($cartao->status_multa == 'I')
                                        <button type="button" id="{{ $cartao->id }}" class="btn btn-danger remover_isentar_multa_mes_id"> <i class="fa fa-times"></i> Remover Isenção da multa</button>
                                        @else
                                        <button type="button" id="{{ $cartao->id }}" class="btn btn-info isentar_multa_mes_id"> <i class="fa fa-times"></i> Isentar a multa</button>
                                        @endif
                                        @endif
    
                                        @if (Auth::user()->can('create: isentar multa') || Auth::user()->can('update: isentar multa'))
                                        <button type="button" id="{{ $cartao->id }}" data-valor="{{ $cartao->multa }}" class="btn btn-dark editar_multa_mes_id"> <i class="fa fa-times"></i> Editar a multa</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    
        {{-- model isentar mês --}}
        <div class="modal fade" id="modelIsentarMensalidade">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Motivo da Isenção do Mês</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" value="" class="cartao_id">
                            <div class="form-group col-md-12">
                                <label for="motivo_isencao">Motivo Isenção</label>
                                <textarea placeholder="Informe o motívo da isenção do Mês!" name="motivo_isencao" id="motivo_isencao" cols="30" rows="4" class="form-control motivo_isencao"></textarea>
                                <span class="text-danger error-text motivo_isencao_error"></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success editar_salas_form">Confirmar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    
        {{-- model isentar mês --}}
        <div class="modal fade" id="modelRemoverIsentarMensalidade">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Motivo da Remoção da Isenção do Mês</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" value="" class="remover_cartao_id">
                            <div class="form-group col-md-12">
                                <label for="remover_motivo_isencao">Motivo Isenção</label>
                                <textarea placeholder="Informe o motívo da isenção do Mês!" name="remover_motivo_isencao" id="remover_motivo_isencao" cols="30" rows="4" class="form-control remover_motivo_isencao"></textarea>
                                <span class="text-danger error-text remover_motivo_isencao_error"></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success remover_editar_salas_form">Confirmar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    
    
        {{-- model isentar multar mês --}}
        <div class="modal fade" id="modelIsentarMultaMensalidade">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Motivo da Isenção da Multa do Mês</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" value="" class="cartao_multa_id">
                            <div class="form-group col-md-12">
                                <label for="motivo_isencao_multa">Motivo Isenção</label>
                                <textarea placeholder="Informe o motívo da isenção da multa do Mês!" name="motivo_isencao_multa" id="motivo_isencao_multa" cols="30" rows="4" class="form-control motivo_isencao_multa"></textarea>
                                <span class="text-danger error-text motivo_isencao_multa_error"></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success form_isentar_multa_mes">Confirmar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    
        {{-- model isentar multar mês --}}
        <div class="modal fade" id="modelRemoverIsentarMultaMensalidade">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Motivo da Remoção da Isenção da Multa do Mês</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" value="" class="remover_cartao_multa_id">
                            <div class="form-group col-md-12">
                                <label for="remover_motivo_isencao_multa">Motivo da Remoção da Isenção</label>
                                <textarea placeholder="Informe o motívo da remoção da isenção da multa do Mês!" name="remover_motivo_isencao_multa" id="remover_motivo_isencao_multa" cols="30" rows="4" class="form-control remover_motivo_isencao_multa"></textarea>
                                <span class="text-danger error-text remover_motivo_isencao_multa_error"></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success form_remover_isentar_multa_mes">Confirmar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    
        {{-- model isentar multar mês --}}
        <div class="modal fade" id="modelEditarMultaMensalidade">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar a Multa do Estudante para este Mês!</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" value="" class="cartao_editar_multa_id">
                            <div class="form-group col-md-12">
                                <label for="motivo_isencao_multa">Nova Multa</label>
                                <input type="text" name="nova_multa" value="0" id="nova_multa" oninput="validateInput(this)" class="nova_multa form-control">
                                <span class="text-danger error-text nova_multa_error"></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success form_editar_multa_mes">Confirmar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    
    </div>
</section>
@endsection



@section('scripts')
<script>
    var cartao_id;

    $(function() {

        function validateInput(input) {
            // Expressão regular para aceitar apenas números e pontos
            input.value = input.value.replace(/[^0-9.]/g, '');

            // Evita múltiplos pontos
            if ((input.value.match(/\./g) || []).length > 1) {
                input.value = input.value.slice(0, -1);
            }
        }

        // editar
        $(document).on('click', '.isentar_mensalidade_id', function(e) {
            e.preventDefault();
            cartao_id = $(this).attr('id');
            $('.cartao_id').val($(this).attr('id'));
            $("#modelIsentarMensalidade").modal("show");
        });

        // editar
        $(document).on('click', '.remover_isentar_multa_mes_id', function(e) {
            e.preventDefault();
            $('.remover_cartao_multa_id').val($(this).attr('id'));
            $("#modelRemoverIsentarMultaMensalidade").modal("show");
        });

        $(document).on('click', '.isentar_multa_mes_id', function(e) {
            e.preventDefault();
            $('.cartao_multa_id').val($(this).attr('id'));
            $("#modelIsentarMultaMensalidade").modal("show");
        });

        // editar
        $(document).on('click', '.remover_isentar_mensalidade_id', function(e) {
            e.preventDefault();
            cartao_id = $(this).attr('id');
            $('.remover_cartao_id').val($(this).attr('id'));
            $("#modelRemoverIsentarMensalidade").modal("show");
        });

        $(document).on('click', '.editar_multa_mes_id', function(e) {
            e.preventDefault();

            let valor = this.getAttribute('data-valor');
            let id = $('.cartao_editar_multa_id').val($(this).attr('id'));
            $('.nova_multa').val(valor);

            $("#modelEditarMultaMensalidade").modal("show");
        });
    });

    $(document).on('click', '.form_isentar_multa_mes', function(e) {
        e.preventDefault();

        var id = $('.cartao_multa_id').val();
        var data = {
            'motivo_isencao_multa': $('.motivo_isencao_multa').val()
        , }

        if ($('.motivo_isencao_multa').val() == "") {
            showMessage('Informação!', 'Deve Informar o motivo da isenção do mês!', 'error');
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: `/financeiro/isentar-multa/${id}`
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

    // actualizar
    $(document).on('click', '.remover_editar_salas_form', function(e) {
        e.preventDefault();
        var id = $('.remover_cartao_id').val();
        var data = {
            'remover_motivo_isencao': $('.remover_motivo_isencao').val()
        , }

        if ($('.remover_motivo_isencao').val() == "") {
            showMessage('Informação!', 'Deve Informar o motivo da remoção da isenção!', 'error');
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: `/financeiro/remover-isentar-propina/${id}`
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

    // actualizar
    $(document).on('click', '.editar_salas_form', function(e) {
        e.preventDefault();
        var id = $('.cartao_id').val();
        var data = {
            'motivo_isencao': $('.motivo_isencao').val()
        , }

        if ($('.motivo_isencao').val() == "") {
            showMessage('Informação!', 'Deve Informar o motivo da isenção!', 'error');
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: `/financeiro/isentar-propina/${id}`
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

    $(document).on('click', '.form_remover_isentar_multa_mes', function(e) {
        e.preventDefault();

        var id = $('.remover_cartao_multa_id').val();
        var data = {
            'remover_motivo_isencao_multa': $('.remover_motivo_isencao_multa').val()
        , }

        if ($('.remover_motivo_isencao_multa').val() == "") {
            showMessage('Informação!', 'Deve Informar o motivo da isenção do mês', 'error');
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: `/financeiro/remover-isentar-multa/${id}`
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

    $(document).on('click', '.form_editar_multa_mes', function(e) {
        e.preventDefault();

        var id = $('.cartao_editar_multa_id').val();
        var data = {
            'nova_multa': $('.nova_multa').val()
        , }

        if ($('.nova_multa').val() == "") {
            showMessage('Informação!', 'Preencher um valor da multa. Exemplo: 0', 'error');
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: `/financeiro/editar-multa/${id}`
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
