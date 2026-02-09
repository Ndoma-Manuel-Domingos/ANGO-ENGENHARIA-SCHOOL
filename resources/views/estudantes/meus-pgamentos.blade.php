@extends('layouts.estudantes')

@section('content')

<div class="content">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Minhas Contas de Pagamentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Pagamento</li>
                  </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <form action="{{ route('est.meus-pagamento-estudante') }}" method="get" id="formulario">
                    @csrf
                    <div class="row">
                      <div class="col-12 col-md-3 mb3">
                        <label for="" class="form-label">Data Inicio</label>
                        <input type="date" name="data_inicio" class="form-control">
                      </div>
                      
                      <div class="col-12 col-md-3 mb3">
                        <label for="" class="form-label">Data Final</label>
                        <input type="date" name="data_final" class="form-control">
                      </div>
                      
                      <div class="col-12 col-md-3 mb3">
                        <label for="" class="form-label">Serviço</label>
                        <select id="" name="servico" class="form-control">
                          <option value="">TODOS</option>
                          @foreach ($servicos as $item)
                            <option value="{{ $item->id }}">{{ $item->servico }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="card-footer">
                  <button type="submit" form="formulario" class="btn btn-primary">Filtrar</button>
                </div>
              </div>
            </div>        
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-header">
                  <h5>Meus pagamentos 
                    @if (Auth::user()->can('create: pagamento'))
                    <a href="{{ route('est.efectuar-pagamento-estudante') }}" class="btn btn btn-primary float-right">Efecturar Pagamentos</a>
                    @endif
                  </h5>
                </div>
                <div class="card-body">
                  <table id="carregarTabelaMatricula" style="width: 100%" class="table table-bordered  ">
                    <thead>
                      <tr>
                          <th>Nº Ficha</th>
                          <th>Serviços</th>
                          {{-- <th>Nome Completo</th> --}}
                          <th title="Valores">Valor</th>
                          <th title="Quantidade">Qtd.</th>
                          <th title="Descontos">Desconto.</th>
                          <th>Total</th>
                          <th title="Funcionário">Operador</th>
                          <th>Data</th>
                          <th style="width: 100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pagamentos as $item)
                      <tr>
                          <td>{{ $item->next_factura }}</td>
                          <td>{{ $item->pago_at }}</td>
                          {{-- <td>{{ $item->model($item->model, $item->estudantes_id) }}</td> --}}
                          <td>{{ number_format($item->valor, 2, ',', '.')  }} <small>kz</small></td>
                          <td>{{ number_format($item->quantidade, 2, ',', '.') }} <small>kz</small></td>
                          <td>{{ number_format($item->desconto, 2, ',', '.') }} <small>kz</small></td>
                          <td>{{ number_format( ($item->valor * $item->quantidade) - $item->desconto , 2, ',', '.') }} <small>kz</small></td>
                          <td>{{ $item->operador->nome ?? "" }}</td>
                          <td>{{ $item->data_at }}</td>
                          <td class="text-end">
                            @if (Auth::user()->can('read: pagamento'))
                            <a href='{{ route('est.meus-pagamento-estudante-detalhe', Crypt::encrypt($item->ficha) ) }}' class="btn btn-primary mx-1">
                              <i class="fas fa-plus"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('read: pagamento'))
                            <a href='{{ route('ficha-pagamento-propina', $item->ficha) }}' class="btn btn-primary mx-1" target="_blink">
                              <i class="fas fa-print"></i>
                            </a>
                            @endif
                          </td>
                        </tr> 
                      @endforeach
                    </tbody>
    
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@endsection

@section('scripts')
<script>
  $(function () {
    $("#carregarTabelaMatricula").DataTable({
      language: {
          url: "{{ asset('plugins/datatables/pt_br.json') }}"
      },
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection