@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transferências de Estudantes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Transferências</li>
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
            <form action="" method="post" class="card p-4 mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="turma_origem">Selecione a turma de Origem</label>
                        <select class="form-select turma_origem" name="turma_origem" id="turma_origem" aria-label="Default select example">
                            <option selected>Selecione</option>
                            @if ($turmas)
                            @foreach ($turmas as $item)
                            <option value="{{ $item->id }}">{{ $item->turma }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="turma_destino">Selecione</label>
                        <select class="form-select turma_destino" id="turma_destino" name="turma_destino" aria-label="Default select example">
                            <option selected>Selecione</option>
                            @if ($turmas)
                            @foreach ($turmas as $item2)
                            <option value="{{ $item2->id }}">{{ $item2->turma }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <select class="duallistbox carregarEstudanteCombox" multiple="multiple" name="estudantesTurma[]">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(function() {
        $(document).on('change', '.turma_origem', function(e) {
            e.preventDefault();
            var id = $(this).val();

            $.ajax({
                type: "GET"
                , url: "carregar-turmas-estudante/" + id
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    $('.carregarEstudanteCombox').html("");
                    for (let index = 0; index < response.estudantesTurmas.length; index++) {
                        $('.carregarEstudanteCombox').append('<option value="' + response.estudantesTurmas[index].id + '">' + response.estudantesTurmas[index].nome + '</option>');
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });
    });

</script>

<script>
    $(function() {
        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function(event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })
    });

</script>

@endsection
