@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Actualizar Calendário de Pagamentos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Calendário</li>
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
                    <form action="{{ route('web.sincronizacao-actualizar-calendario-post') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="ano_lectivos_id">Ano Lectivos</label>
                                    <select name="ano_lectivos_id" class="form-control select2 editar_ano_lectivos_id" id="ano_lectivos_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($anos as $item)
                                        <option value="{{ $item->id }}" {{ $item->status == "activo" ? 'selected': "" }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="servico_id">Serviços</label>
                                    <select name="servico_id" class="form-control select2 editar_servico_id" id="servico_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}">{{ $item->servico }}</option>
                                        @endforeach
                                    </select>
                                    @error('servico_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-2 col-12">
                                    <label for="mes_id">Mês</label>
                                    <select name="mes_id" class="form-control select2" id="mes_id">
                                        <option value="">Selecionar</option>
                                        <option value="Sep">Setembro</option>
                                        <option value="Oct">Outobro</option>
                                        <option value="Nov">Novembro</option>
                                        <option value="Dec">Dezembro</option>
                                        <option value="Jan">Janeiro</option>
                                        <option value="Feb">Fevereiro</option>
                                        <option value="Mar">Março</option>
                                        <option value="Apr">Abril</option>
                                        <option value="May">Maio</option>
                                        <option value="Jun">Junho</option>
                                        <option value="Jul">Julho</option>
                                        <option value="Aug">Agosto</option>
                                    </select>
                                    @error('mes_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-2 col-12">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control data_inicio" id="data_inicio" name="data_inicio" placeholder="Data início">
                                    @error('data_inicio')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-2 col-12">
                                    <label for="data_final">Data Final</label>
                                    <input type="date" class="form-control data_final" id="data_final" name="data_final" placeholder="Data Final">
                                    @error('data_final')
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