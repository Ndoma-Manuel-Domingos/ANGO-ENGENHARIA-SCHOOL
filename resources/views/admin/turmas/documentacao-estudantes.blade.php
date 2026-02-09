@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Documentação Estudantes</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
          <li class="breadcrumb-item active">Documentação</li>
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
          <h5><i class="fas fa-user"></i> Documentos dos estudantes ano lectivo {{ $ano_lectivo->ano }}</h5>
        </div>
      </div>
    </div>

    @if ($estudantes)

    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <table id="carregarTabelaEstudantes" style="width: 100%"
              class="table table-bordered  ">
              <thead>
                <tr>
                  <th>Nº</th>
                  <th>Nome Completo</th>
                  <th>Bilhete</th>
                  <th>Curso</th>
                  <th>Classe</th>
                  <th>Turno</th>
                  <th class="text-right">Acções</th>
                </tr>
              </thead>

              <tbody id="">
                @foreach ($estudantes as $item)
                <tr>
                  <td>{{ $item->documento }}</td>
                  <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                  <td>{{ $item->bilheite }}</td>
                  <td>{{ $item->curso }}</td>
                  <td>{{ $item->classes }}</td>
                  <td>{{ $item->turno }}</td>
                  <td class="text-right">
                    <a href="{{ route('web.declaracao-estudantes', Crypt::encrypt($item->id) ) }}" title="Declarações" id=""
                      class="activar_turmas_id btn-info btn"><i class="fa fa-book"></i> Declarações</a>
                    <a href="{{ route('dow.ficha-tecnica-estudante', ["code" => Crypt::encrypt($item->id), "ano" => Crypt::encrypt(null)] ) }}" target="_blick"
                      title="Imprimir ficha técnica" id="" class="activar_turmas_id btn-success btn"><i
                        class="fa fa-book"></i> Ficha técnica</a>
                    <a href="{{ route('web.notas-estudante-turmas', Crypt::encrypt($item->id)) }}" title="Visualizar notas" id=""
                      class="activar_turmas_id btn-primary btn"><i class="fa fa-book"></i> Notas</a>
                  </td>
                </tr>
                @endforeach

              </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- /.container-fluid -->
</section>
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
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });


</script>


@endsection