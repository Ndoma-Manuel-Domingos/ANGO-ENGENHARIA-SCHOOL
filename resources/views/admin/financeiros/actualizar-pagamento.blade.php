@extends('layouts.escolas')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Actualizar data do documento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Painel
                                Financeiro</a></li>
                        <li class="breadcrumb-item active">Financeiro</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <form action="{{ route('financeiros.actualizar-factura-store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 mb-3">
                                    <label for="data_emissao" class="form-label">Data da Emissão Factura</label>
                                    <input type="date" value="{{ $pagamento->data_at ?? old('data_emissao') }}" id="data_emissao" name="data_emissao" class="form-control">
                                    
                                    <input type="hidden" value="{{ $pagamento->id }}" name="pagamento_id">
                                </div>
                                
                                <div class="col-12 col-md-12 mb-3">
                                    <label for="item_id" class="form-label">Selecionar um conjunto de facturas</label>
                                    <select  name="item_id[]" class="form-control select2" multiple id="item_id">
                                        @foreach ($pagamentos as $item)
                                            <option value="{{ $item->id }}">{{ $item->next_factura }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </div>

@endsection
