@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Fechamento do TPA</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Fechamento do TPA</li>
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
                    <h5><i class="fas fa-info"></i><strong class="text-danger">Ao proceder o fecho do caixa! Aconselhamos sempre a comparar a receita que o sistema gerou com os recebimentos em numerário e o fecho do TPA</strong></h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <!-- form start -->
                    <form>
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
                                        <label>Selecionar o banco</label>
                                        <select class="form-control select2 banco_id" style="width: 100%;" name="banco_id">
                                            @if ($banco)
                                            <option selected="selected" value="{{ $banco->id }}">{{ $banco->conta }} - {{ $banco->banco }}</option>
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
                            <button type="submit" class="btn btn-primary fechamento_banco">Fechar o TPA</button>
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
    $(function() {

        // Cadastrar
        $(document).on('click', '.fechamento_banco', function(e) {
            e.preventDefault();

            var data = {
                'valor_tpa': $('.valor_tpa').val()
                , 'valor_retirado1': $('.valor_retirado1').val()
                , 'valor_retirado2': $('.valor_retirado2').val()
                , 'valor_retirado3': $('.valor_retirado3').val()
                , 'observacao': $('.observacao').val()
                , 'banco_id': $('.banco_id').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas Fechar o TPA? É importante que faça o Lançamento dos dados Verdadeiro!"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Desejo Fechar o TPA!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST"
                        , url: "{{ route('web.fechamento-bancos-store') }}"
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

                }
            });

        });

    });

    $(function() {
        $("#carregarTabelaSalas").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });


    function numeroContemLetra(numero) {
        // Expressão regular para verificar se a string contém alguma letra (maiúscula ou minúscula)
        var regex = /[a-zA-Z]/;

        // Testa se a expressão regular encontra alguma letra na string
        return regex.test(numero);
    }

</script>
@endsection
