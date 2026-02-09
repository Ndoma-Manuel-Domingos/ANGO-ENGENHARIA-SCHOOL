@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Notifacações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            </div><!-- /.col -->
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header"></div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Designação</th>
                                    <th>Operador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notificacoes as $item)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                    <td>{{ $item->notificacao }}</td>
                                    <td>{{ $item->user->nome }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer"></div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

@endsection

@section('scripts')
<script>
   
    const tabelas = [
        "#carregarTabela", 
    ];
    tabelas.forEach(inicializarTabela);
  
</script>
@endsection
