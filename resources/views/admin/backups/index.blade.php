@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Backup e Restauração do Banco</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Painel Principal</a></li>
                    <li class="breadcrumb-item active">Backup e Restauração</li>
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

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-cloud-upload-alt"></i></h3>
                        <h3>Backup</h3>
                        <p>
                            O backup é essencial para proteger os dados contra perdas acidentais ou falhas no sistema, permitindo recuperar informações de forma rápida, segura e confiável em qualquer situação de emergência.
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <button type="button" id="btnExportar" class="btn btn-outline-primary w-100 d-block my-4">Exportar</button>

                        <p>Faça-o sempre nas primeiras horas e nas horas finais do dia</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-cloud-download-alt"></i></h3>
                        <h3>Restauração</h3>
                        <p>
                            A restauração de um banco de dados permite recuperar informações a partir de um backup previamente realizado, garantindo continuidade das operações e minimizando impactos de falhas ou exclusões acidentais.
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <button type="button" class="btn btn-outline-primary w-100 d-block my-4" data-toggle="modal" data-target="#modalImportar">Importar</button>
                        <p>Faça-o sempre nas primeiras horas e nas horas finais do dia</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Modal Importar -->
<div class="modal fade" id="modalImportar" tabindex="-1" role="dialog" aria-labelledby="modalImportarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formImportar" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportarLabel">Importar Banco de Dados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Selecione o arquivo <b>.zip</b> para importar.</p>
                    <input type="file" name="arquivo" class="form-control" accept=".zip" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Importar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Exportar
    $('#btnExportar').on('click', function() {
        $.ajax({
            url: "{{ route('backups-exportar') }}", 
            method: 'GET', 
            xhrFields: {
                responseType: 'blob'
            }, 
            beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }, 
            success: function(data) {
                const blob = new Blob([data], {
                    type: 'application/zip'
                });
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'backup.zip';
                link.click();

                Swal.close();
                // Exibe uma mensagem de sucesso
                showMessage('Sucesso!', 'Backup concluído!', 'success');
            }, 
            error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    });

    $(document).ready(function() {
        $("#formImportar").on("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('backups-importar') }}"
                , type: "POST"
                , data: formData
                , processData: false
                , contentType: false
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {

                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', response.message, 'success');
                    $("#modalImportar").modal("hide");

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
