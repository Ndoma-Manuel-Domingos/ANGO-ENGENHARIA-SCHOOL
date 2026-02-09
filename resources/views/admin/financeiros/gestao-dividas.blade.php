@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel Financeiro Gestão de dívidas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Divídas</li>
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
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Painel financeiro para gestão de dívidas, imprimir lista de
                        estudantes devedores por turma, individual e geral.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('financeiros.financeiro-gestao-dividas') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                
                                <div class="form-group col-12 col-md-4">
                                    <label for="input_estudante" class="form-label">Pesquisar por Estudante</label>
                                    <input type="text" name="input_estudante" value="{{ old('inpu_estudante') ?? $requests['input_estudante'] }}" placeholder="Informe o número do bilheite ou cedula" class="form-control input_estudante" id="input_estudante">
                                </div>
                            
                                <div class="form-group col-12 col-md-2">
                                    <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                    <select name="ano_lectivos_id" class="form-control ano_lectivos_id select2" id="ano_lectivos_id">
                                        <option value="">Todos</option>
                                        @foreach ($anos_lectivos as $item)
                                            <option value="{{ $item->id }}" {{ $requests['ano_lectivos_id'] == $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                </div>
            
                                <div class="form-group col-12 col-md-2">
                                    <label for="servico" class="form-label">Serviços</label>
                                    <select name="servico" id="servico" class="form-control servico select2">
                                        <option value="">Todos</option>
                                        @if (count($servicos) != 0)
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['servico'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                @php
                                    // Verifica se o campo 'mes' existe e se é um array
                                    $mesesSelecionados = isset($requests['mes']) && is_array($requests['mes']) ? $requests['mes'] : [];
                                @endphp
                                
                               
                                <div class="form-group col-12 col-md-4">
                                    <label for="mes" class="form-label">Meses </label>
                                    <select name="mes[]" id="mes" multiple="multiple" class="form-control mes select2">
                                        <option value="">Todos</option>
                                        <option value="Jan" {{ in_array("Jan", $mesesSelecionados) ? 'selected' : '' }}>Janeiro</option>
                                        <option value="Feb" {{ in_array("Feb", $mesesSelecionados) ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="Mar" {{ in_array("Mar", $mesesSelecionados) ? 'selected' : '' }}>Março</option>
                                        <option value="Apr" {{ in_array("Apr", $mesesSelecionados) ? 'selected' : '' }}>Abril</option>
                                        <option value="May" {{ in_array("May", $mesesSelecionados) ? 'selected' : '' }}>Maio</option>
                                        <option value="Jun" {{ in_array("Jun", $mesesSelecionados) ? 'selected' : '' }}>Junho</option>
                                        <option value="Jul" {{ in_array("Jul", $mesesSelecionados) ? 'selected' : '' }}>Julho</option>
                                        <option value="Aug" {{ in_array("Aug", $mesesSelecionados) ? 'selected' : '' }}>Agosto</option>
                                        <option value="Sep" {{ in_array("Sep", $mesesSelecionados) ? 'selected' : '' }}>Setembro</option>
                                        <option value="Oct" {{ in_array("Oct", $mesesSelecionados) ? 'selected' : '' }}>Outrobro</option>
                                        <option value="Nov" {{ in_array("Nov", $mesesSelecionados) ? 'selected' : '' }}>Novembro</option>
                                        <option value="Dec" {{ in_array("Dec", $mesesSelecionados) ? 'selected' : '' }}>Deszembro</option>
                                    </select>
                                </div>
            
                                <div class="form-group col-12 col-md-2">
                                    <label for="condicao" class="form-label">Estado</label>
                                    <select name="condicao" id="condicao" class="form-control condicao select2">
                                        <option value="">Todos</option>
                                        <option value="Nao Pago" {{ $requests['condicao'] == "Nao Pago" ? 'selected' : '' }}>Não Pagos</option>
                                        <option value="Pago" {{ $requests['condicao'] == "Pago" ? 'selected' : '' }}>Pagos</option>
                                        <option value="divida" {{ $requests['condicao'] == "divida" ? 'selected' : '' }}>Divida</option>
                                    </select>
                                </div>
                                
                                
                                <div class="form-group col-12 col-md-2">
                                    <label for="cursos_id" class="form-label">Cursos</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control cursos_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->curso->id }}" {{ $requests['cursos_id'] == $item->curso->id ? 'selected' : '' }}>{{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-12 col-md-2">
                                    <label for="classes_id" class="form-label">Classes</label>
                                    <select name="classes_id" id="classes_id" class="form-control classes_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->classe->id }}" {{ $requests['classes_id'] == $item->classe->id ? 'selected' : '' }}>{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-12 col-md-2">
                                    <label for="turnos_id" class="form-label">Turnos</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control turnos_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($turnos as $item)
                                        <option value="{{ $item->turno->id }}" {{ $requests['turnos_id'] == $item->turno->id ? 'selected' : '' }}>{{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary imprimir_lista"><i class="fas fa-filter"></i> Filtra</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        @if ($cartoes)
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Listagem estudantes com Extratos</h3>
                            <a href="{{ route('estudantes-devedores-imprmir', ['input_estudante' => $requests['input_estudante'] ?? "", 'ano_lectivos_id' => $requests['ano_lectivos_id'] ?? "", 'servico' => $requests['servico'] ?? "", 'mes' => $requests['mes'] ?? "", 'condicao' => $requests['condicao'] ?? "", 'cursos_id' => $requests['cursos_id'] ?? "", 'classes_id' => $requests['classes_id'] ?? "", 'turnos_id' => $requests['turnos_id'] ?? ""]) }}" target="_blink" class="btn btn-primary float-right"><i class="fas fa-print"></i> Imprimir</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                        <table id="carregarTabelaEstudantes" style="width: 100%" class="table  table-bordered table-striped  ">
                            <thead>
                                <tr>
                                  <th>Nº</th>
                                  <th>Nome</th>
                                  <th>Bilhete</th>
                                  <th>Mês</th>
                                  <th>Estado</th>
                                  <th>Multa</th>
                                  <th>Preço</th>
                                  <th>Total</th>
                                  <th>Serviço</th>
                                  <th>Curso</th>
                                  <th>Classe</th>
                                  <th>Turno</th>
                                  <th style="width: 100px">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $multas = 0;
                                    $preco = 0;
                                    $total = 0;
                                @endphp
                                @foreach ($cartoes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    <td>{{ $item->mes($item->month_name) }}</td>
                                    @if ($item->status == "divida")
                                     <td class="text-warning">{{ $item->status }}</td> 
                                    @else
                                        @if ($item->status == "Pago")
                                        <td class="text-success">{{ $item->status }}</td> 
                                        @else
                                            @if ($item->status == "Nao Pago")
                                            <td class="text-danger">{{ $item->status }}</td>  
                                            @else
                                            <td class="text-info">{{ $item->status }}</td>   
                                            @endif
                                        @endif
                                    @endif
                                    <td>{{ number_format($item->multa ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->preco_unitario ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ number_format((($item->preco_unitario ?? 0) + ($item->multa ?? 0)), 2, ',', '.') }}</td>
                                    <td>{{ $item->servico->servico ?? "" }}</td>
                                    <td>{{ $item->estudante->matricula->curso->curso ?? '' }}</td>
                                    <td>{{ $item->estudante->matricula->classe->classes ?? '' }}</td>
                                    <td>{{ $item->estudante->matricula->turno->turno ?? '' }}</td>
                                    <td>
                                        <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-info"><i class="fas fa-plus"></i> Detalhe</a>
                                    </td>
                                    
                                    @php
                                        $multas += $item->multa ?? 0;
                                        $preco += $item->preco_unitario ?? 0;
                                        $total += (($item->preco_unitario ?? 0) + ($item->multa ?? 0)); 
                                    @endphp
                                </tr>
                                @endforeach
                                
                                <tfoot>
                                    <tr>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>{{ number_format($multas ?? 0, 2, ',', '.') }}</th>
                                        <th>{{ number_format($preco ?? 0, 2, ',', '.') }}</th>
                                        <th>{{ number_format($total ?? 0, 2, ',', '.') }}</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                        </div>
                        <!-- /.card-body -->
                  </div>
                  
                   <!-- /.card -->
                </div>
            </div>
        @endif

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection


@section('scripts')

  <script>
    $(function () {
      $("#carregarTabelaEstudantes").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

    });
    
  </script>
  
@endsection