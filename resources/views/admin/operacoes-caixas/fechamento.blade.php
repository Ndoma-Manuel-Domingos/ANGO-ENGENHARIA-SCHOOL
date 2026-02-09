@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Fechamento do Caixas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Fechamento Caixas</li>
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
                    <h5><i class="fas fa-info"></i> <strong class="text-danger">Ao proceder o fecho do caixa! Aconselhamos sempre a comparar a receita que o sistema gerou com os recebimentos em numerário e o fecho do TPA</strong></h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <!-- form start -->
                    <form action="{{ route('operacoes-caixas.fechamento-caixas') }}" method="post" >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-3 mb3">
                                    <div class="form-group">
                                        <label for="valor_abrir">Valor Abertura</label>
                                        <input type="number" class="form-control valor_abrir" value="{{ $movimento ? $movimento->valor_abrir : '' }}" id="valor_abrir" name="valor_abrir" placeholder="Valor de abertura">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb3">
                                    <div class="form-group">
                                        <label for="valor_tpa">Valor Processado Por TPA</label>
                                        <input type="number" class="form-control valor_tpa" value="{{ $movimento ? $movimento->valor_tpa : '' }}" id="valor_tpa" name="valor_tpa" placeholder="Salado Processado Por CACHE">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="valor_cache">Faturação em numerário</label>
                                        <input type="number" class="form-control valor_cache" value="{{ $movimento ? $movimento->valor_cache : '' }}" id="valor_cache" name="valor_cache" placeholder="Recebimentos em numerário">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb3">
                                    <div class="form-group">
                                        <label for="valor_retirado1">1º Registrar saída de caixa</label>
                                        <input type="number" class="form-control valor_retirado1" value="{{ $movimento ? $movimento->valor_retirado1 : '' }}" id="valor_retirado1" name="valor_retirado1">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb3">
                                    <div class="form-group">
                                        <label for="valor_retirado2">2º Registrar saída de caixa</label>
                                        <input type="number" class="form-control valor_retirado2" value="{{ $movimento ? $movimento->valor_retirado2 : '' }}" id="valor_retirado2" name="valor_retirado2">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb3">
                                    <div class="form-group">
                                        <label for="valor_retirado3">3º Registrar saída de caixa</label>
                                        <input type="number" class="form-control valor_retirado3" value="{{ $movimento ? $movimento->valor_retirado3 : '' }}" id="valor_retirado3" name="valor_retirado3">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb3">
                                    <div class="form-group">
                                        <label>Selecionar o Caixa</label>
                                        <select class="form-control select2 caixa_id" style="width: 100%;" name="caixa_id">
                                            @if ($caixas)
                                            <option selected="selected" value="{{ $caixas->id }}">{{ $caixas->caixa }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="observacao">Observação</label>
                                        <textarea class="form-control observacao" placeholder="Observação" rows="3" id="observacao" name="observacao">{{ $movimento ? $movimento->observacao : '' }}</textarea>
                                    </div>
                                </div>

                            </div>


                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @if (Auth::user()->can('fecho caixa'))
                            <button type="submit" class="btn btn-primary fechamento_caixa">Fechar o Caixa</button>
                            @endif
                        </div>
                    </form>
                </div>
                <!-- /.card -->
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
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário
     
            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                  // Exibe uma mensagem de sucesso
                  showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                  window.location.href = "imprimir-movimento-caixa/" + response.movimento_id;
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });
    });
</script>
@endsection
