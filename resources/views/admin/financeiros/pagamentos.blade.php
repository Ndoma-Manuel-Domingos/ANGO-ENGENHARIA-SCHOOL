@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Pagamentos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Todos os Pagamentos</li>
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
                <h5><i class="fas fa-info"></i> Relatório de todos os pagamentos, entradas e saídas.</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                  @if ($pagamentos)
                    <table id="carregarTabelaPagamentos"  style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                              <th>Referência</th>
                              <th>Factura</th>
                              <th>Pagamento</th>
                              <th>Nome Completo</th>
                              <th>Feito</th>
                              <th nowrap>Data</th>
                              <th>Total</th>
                              <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php
                              $valorArrecadado = 0;
                          @endphp
                          @foreach ($pagamentos as $item)
                            
                              <tr>
                                <td>{{ $item->ficha }}</td>
                                <td>{{ $item->next_factura }}</td>
                                <td>{{ $item->servico->servico ?? "" }}</td>
                                <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                <td>{{ $item->operador_pagamento }}</td>
                                <td nowrap>{{ $item->data_at }}</td>
                                <td>{{ number_format($item->valor2, 2, ',', '.') }} <small>kz</small></td>
                                <td class="text-end">
                                    <a href= "{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}" class="btn btn-primary mx-1">
                                      <i class="fas fa-eye"></i>
                                    </a>
                                    <a href= "{{ route('ficha-pagamento-propina', $item->ficha) }}" target="_blink" class="btn btn-primary mx-1">
                                      <i class="fas fa-print"></i>
                                    </a>
                                </td>
                              </tr>    
                              @php
                                $valorArrecadado += $item->valor2;
                              @endphp
                          @endforeach
                        </tbody>
                    </table>    

                    <table  style="width: 100%" class="table table-bordered ">
                      <tbody>
                        <tr>
                            <td>----------</td>
                            <td>----------</td>
                            <td>----------</td>
                            <td>----------</td>
                            <td>----------</td>
                            <td>----------</td>
                            <td >{{ number_format($valorArrecadado , 2, ',', '.') }} Kz</td>
                          </tr>
                      </tbody>
                    </table>    


                  @endif
                </div>
                <!-- /.card-body -->
            </div>
          <!-- /.card -->
        </div>

        <div class="col-12 mb-5">
          <a href="{{ route('ficha-pagamentos') }}" class="btn btn-primary" target="_blink">Imprimir <i class="fas fa-print"></i></a>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection


@section('scripts')
  <script>
    $(function () {
      $("#carregarTabelaPagamentos").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  </script>
@endsection