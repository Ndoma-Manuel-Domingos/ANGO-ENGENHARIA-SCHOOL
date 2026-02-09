@extends('layouts.provinciais')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Estatisticas de pautas gerais</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                        <li class="breadcrumb-item active">Estatística</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12-col-md-12">
                    <form action="{{ route('app.provincial-estatistica-turmas-unica') }}" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="municipio_id" class="form-label">Municípios</label>
                                        <select name="municipio_id" id="municipio_id"
                                            class="form-control municipio_id select2">
                                            <option value="">Selecione Município</option>
                                            @foreach ($municipios as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['municipio_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('municipio_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="distrito_id" class="form-label">Distritos</label>
                                        <select name="distrito_id" id="distrito_id"
                                            class="form-control distrito_id select2">
                                            <option value="">Selecione Distritos</option>
                                            @foreach ($distritos as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['distrito_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('distrito_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group pt-4 col-md-3 col-12">
                                        <label for="shcools_id" class="form-label">Escolas</label>
                                        <select name="shcools_id" id="shcools_id" class="form-control shcools_id select2">
                                            <option value="">Escola</option>
                                            @foreach ($escolas as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['shcools_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('shcools_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                        <select name="ano_lectivo_id" id="ano_lectivo_id"
                                            class="form-control ano_lectivo_id select2">
                                            <option value="">Selecione Ano Lectivo</option>
                                            @foreach ($ano_lectivos as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['ano_lectivo_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group pt-4 col-md-2 col-12">
                                        <label for="turmas_id" class="form-label">Turmas</label>
                                        <select name="turmas_id" id="turmas_id" class="form-control turmas_id select2">
                                            <option value="">Selecione Turmas</option>
                                            @foreach ($turmas as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['turmas_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->turma }}</option>
                                            @endforeach
                                        </select>
                                        @error('turmas_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer pt-4">
                                <button type="submit" class="btn btn-primary"> Filtrar</button>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="width: 20px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @include('admin.require.estatistica-pautas')

        </div><!-- /.container-fluid -->
    </div>
@endsection


@section('scripts')
    <script>

        $("#provincia_id").change(() => {
            let id = $("#provincia_id").val();
            $.get('../carregar-municipios/' + id, function(data) {
                $("#municipio_id").html("")
                $("#municipio_id").html(data)
            })
        })

        $("#municipio_id").change(() => {
            let id = $("#municipio_id").val();
            $.get('../carregar-distritos/' + id, function(data) {
                $("#distrito_id").html("")
                $("#distrito_id").html(data)
            })
        })

        $("#municipio_id").change(() => {
            let id = $("#municipio_id").val();
            $.get('../carregar-escolas-municipio/' + id, function(data) {
                $("#shcools_id").html("")
                $("#shcools_id").html(data)
            })
        })

        $("#distrito_id").change(() => {
            let id = $("#distrito_id").val();
            $.get('../carregar-escolas-distrito/' + id, function(data) {
                $("#shcools_id").html("")
                $("#shcools_id").html(data)
            })
        })

        $("#shcools_id").change(() => {
            let id = $("#shcools_id").val();
            $.get('../carregar-ano-lectivos-escolas/' + id, function(data) {
                $("#ano_lectivo_id").html("")
                $("#ano_lectivo_id").html(data)
            })
        })

        $("#ano_lectivo_id").change(() => {
            let id = $("#ano_lectivo_id").val();
            $.get('../carregar-todas-turmas-anolectivos-escolas/' + id, function(data) {
                $("#turmas_id").html("")
                $("#turmas_id").html(data)
            })
        })
        
        
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
