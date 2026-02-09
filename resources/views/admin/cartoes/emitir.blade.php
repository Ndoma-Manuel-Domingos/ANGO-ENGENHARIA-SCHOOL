@extends('layouts.escolas')
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Emitir Cartões PVC</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Cartão</li>
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
                <form action="{{ route('web.buscar.estudante-emissao.cartao') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4>Emissões de cartões e capturas de fotos do perfil dos estudantes</h4>
                        </div>
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-12 col-md-3"></div>
                                <div class="col-12 col-md-6">
                                    <div class="input-group">
                                        <input type="search" style="border-top-left-radius: 50px;border-bottom-left-radius: 50px;" name="matricula" class="form-control form-control-lg p-4" placeholder="Digite matrícula">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-lg btn-default px-5" style="border-top-right-radius: 50px;border-bottom-right-radius: 50px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3"></div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

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
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Redirecionar
                    window.location.href = response.redirect;
                    return
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            });
        });
    });

</script>
@endsection
