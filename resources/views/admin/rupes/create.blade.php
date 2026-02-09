@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Registrar Rupe</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.rupes.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Registrar</li>
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
                <div class="card">
                    <form action="{{ route('web.rupes.store') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="rupe_id">Rupe <span class="text-danger">*</span></label>
                                    <input type="text" name="rupe_id" class="form-control" id="rupe_id" placeholder="Rupe">
                                    @error('rupe_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="estudantes_id">Estudante <span class="text-danger">*</span></label>
                                    <select name="estudantes_id" class="form-control editar_estudantes_id select2" id="estudantes_id">
                                        <option value="">Selecione</option>
                                        @foreach ($estudantes as $item)
                                        <option value="{{ $item->id }}" {{ old('estudantes_id') == $item->id ? : '' }}>{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('estudantes_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group mb-3 col-md-4 col-12">
                                    <label for="servicos_id" class="form-label">Serviços</label>
                                    <select name="servicos_id" id="servicos_id" class="form-control servicos_id select2" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ old('servicos_id') == $item->id ? : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                    </select>
                                    @error('servicos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-4 col-12">
                                    <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="select2 form-control ano_lectivos_id" style="width: 100%">
                                        @foreach ($anos as $item)
                                        <option value="{{ $item->id }}" {{ old('ano_lectivos_id') == $item->id ? : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="data_at">Data Pagamento <span class="text-danger">*</span></label>
                                    <input type="date" name="data_at" class="form-control" value="{{ date("Y-m-d") }}" id="data_at" placeholder="Rupe">
                                    @error('data_at')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                              

                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
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

    var turmasServico;

    // Eventos
    $("#provincia_id").change(function () {
      carregarDados({
        origem: "#provincia_id",
        destino: "#municipio_id",
        rota: rotas.carregarMunicipios,
        mensagemSucesso: "Municípios carregados"
      });
    });
    
    $("#municipio_id").change(function () {
      carregarDados({
        origem: "#municipio_id",
        destino: "#distrito_id",
        rota: rotas.carregarDistritos,
        mensagemSucesso: "Distritos carregados"
      });
    });


</script>

@endsection