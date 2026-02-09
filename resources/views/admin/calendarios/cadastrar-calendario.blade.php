@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Serviço</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.calendarios') }}">Serviços</a></li>
                    <li class="breadcrumb-item active">Cadastrar</li>
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
                    <h5><i class="fas fa-info"></i> Cadastro de novos serviços.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 card p-3">

                <form class="row">

                    <div class="form-group col-md-6">
                        <label for="servico">Serviço <span class="text-danger">*</span></label>
                        <input type="text" name="servico" class="form-control servico" id="servico" placeholder="Informe o serviço">
                        <span class="text-danger error-text servico_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="contas">Contas <span class="text-danger">*</span></label>
                        <select name="contas" id="contas" class="form-control contas select2">
                            {{-- <option value="">Selecione Contas</option> --}}
                            <option value="despesa">Contas a Pagar ou Dispesa</option>
                            <option value="receita">Contas a Receber ou Receitas</option>
                        </select>
                        <span class="text-danger error-text contas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tipo">Tipo <span class="text-danger">*</span></label>
                        <select name="tipo" id="tipo" class="form-control tipo select2">
                            {{-- <option value="">Selecione tipo</option> --}}
                            <option value="S">Serviço</option>
                            <option value="P">Produto</option>
                        </select>
                        <span class="text-danger error-text tipo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="taxa_id">Taxas <span class="text-danger">*</span></label>
                        <select name="taxa_id" id="taxa_id" class="form-control taxa_id select2">
                            {{-- <option value="">Selecione</option> --}}
                            @foreach ($taxas as $taxa)
                            <option value="{{ $taxa->id }}">{{ $taxa->taxa }} %</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text taxa_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="unidade">Unidade <span class="text-danger">*</span></label>
                        <input type="text" name="unidade" value="uni" class="form-control unidade" id="unidade" placeholder="Informe a unidade">
                        <span class="text-danger error-text unidade_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="motivo_id">Motivo <span class="text-danger">*</span></label>
                        <select name="motivo_id" id="motivo_id" class="form-control motivo_id select2">
                            {{-- <option value="">Selecione</option> --}}
                            @foreach ($motivos as $motivo)
                            <option value="{{ $motivo->id }}" {{ $motivo->codigo === "M04" ? 'selected' : '' }}>{{ $motivo->descricao }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text motivo_id_error"></span>
                    </div>


                    <div class="form-group col-md-3">
                        <label for="status">Status do Serviço <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control status select2">
                            {{-- <option value="">Selecione status</option> --}}
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <button type="button" class="btn btn-primary cadastrar_servico">Salvar</button>
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
    $(function() {
        // CAdastrar
        $(document).on('click', '.cadastrar_servico', function(e) {
            e.preventDefault();

            var data = {
                'servico': $('.servico').val()
                , 'status': $('.status').val()
                , 'contas': $('.contas').val()
                , 'tipo': $('.tipo').val()
                , 'taxa_id': $('.taxa_id').val()
                , 'motivo_id': $('.motivo_id').val()
                , 'unidade': $('.unidade').val()
            , }

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-servico') }}"
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
    });

</script>
@endsection
