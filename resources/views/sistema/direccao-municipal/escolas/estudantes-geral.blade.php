@extends("layouts.municipal")

@section("content")

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Estudantes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route("home-municipal") }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                    <h5><i class="fas fa-info"></i> Listagem geral dos estudantes da província.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route("app.listagem-estudantes-municipal-geral") }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-12 col-md-3 mb3">
                                <label for="shcools_id" class="form-label">Escolas</label>
                                <select name="shcools_id" id="shcools_id" class="form-control select2" style="width: 100%">
                                    <option value="">Todos</option>
                                    @foreach ($escolas as $item)
                                    <option value="{{ $item->id }}" {{ $requests["shcools_id"] == $item->id ? "selected" : ""  }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="ano_lectivos_id" class="form-label">Ano Lectivo</label>
                                <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control select2" style="width: 100%">
                                    <option value="">Todos</option>
                                    @foreach ($anos_lectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ $requests["ano_lectivos_id"] == $ano->id ? "selected" : ""  }}>{{ $ano->ano }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="genero" class="form-label">Generos</label>
                                <select name="genero" id="genero" class="form-control select2" style="width: 100%">
                                    <option value="">Todos</option>
                                    <option value="Masculino" {{ $requests["genero"] == "Masculino" ? "selected" : ""  }}>Masculino</option>
                                    <option value="Femenino" {{ $requests["genero"] == "Femenino" ? "selected" : ""  }}>Femenino</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route("print.listagem-todos-estudantes-imprmir", ["ano_lectivos_id" => $requests["ano_lectivos_id"]]) }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaMatricula" style="width: 100%" class="table  table-bordered table-striped  ">
                            <thead>
                                <tr>
                                    {{-- <th>Nº</th> --}}
                                    <th>Nome</th>
                                    <th>Bilhete</th>
                                    <th>Genero</th>
                                    <th>Status</th>
                                    <th>Escola</th>
                                    <th>Ano Lectivo</th>
                                    <th>Data Inscrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $item)
                                  <tr>
                                    <td>
                                        @if (Auth::user()->can("read: estudante"))
                                        <a href="{{ route("app.informacao-estudante-municipal", $item->id) }}" class="text-primary">{{ $item->nome }} {{ $item->sobre_nome }}</a>
                                        @else
                                        {{ $item->nome }} {{ $item->sobre_nome }}
                                        @endif
                                    </td>
                                    <td>{{ $item->bilheite }}</td>
                                    <td>{{ $item->genero }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->escola->nome }}</td>
                                    <td>{{ $item->escola->ano->ano }}</td>
                                    <td>{{ $item->created_at }}</td>
                                  </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section("scripts")
<script>
    $(function() {
        $("#carregarTabelaMatricula").DataTable({
            language: {
                url: "{{ asset("plugins/datatables/pt_br.json") }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo("#example1_wrapper .col-md-6:eq(0)");

    });

</script>
@endsection
