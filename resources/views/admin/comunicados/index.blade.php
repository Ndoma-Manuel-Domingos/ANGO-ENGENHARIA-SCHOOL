@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem Comunicados</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('comunicados.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">comunicados</li>
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
                    <h5><i class="fas fa-info"></i> Preencha todos os campos para inscrever estudante.</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="card-title">
                            @if (Auth::user()->can('create: comunicados'))
                            <a href="{{ route('comunicados.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Novo Comunicado
                            </a>
                            @endif
                        </div>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-bordered table-striped" style="width: 100%" id="table_load">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Author</th>
                                        <th>Titulo</th>
                                        <th>Descrição</th>
                                        <th>Tipo Comunicado</th>
                                        <th>Data</th>
                                        <th>Para</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comunicados as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->user->nome }}</td>
                                            <td class="mailbox-star">{{ $item->titulo }}</td>
                                            <td class="mailbox-name">{{ Str::limit($item->descricao, 50, ' (...)') }}</td>
                                            <td class="mailbox-subject">{{ $item->tipo_comunicado }}</td>
                                            <td class="mailbox-date">{{ date("Y-m-d", strtotime($item->created_at)) }}</td>
                                            <td class="mailbox-subject">{{ $item->to_escola }}</td>
                                            <td class="">
                                                @if (Auth::user()->can('read: comunicados'))
                                                <a href="{{ route('comunicados.show', $item->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                                @endif
                                                @if (Auth::user()->can('update: comunicados'))
                                                <a href="{{ route('comunicados.edit', $item->id) }}" class="btn btn-success"><i class="fas fa-edit"></i></a>
                                                @endif
                                                @if (Auth::user()->can('delete: comunicados'))
                                                
                                                <form action="{{ route('comunicados.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                </form>
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
          //Enable check and uncheck all functionality
          $('.checkbox-toggle').click(function () {
            var clicks = $(this).data('clicks')
            if (clicks) {
              //Uncheck all checkboxes
              $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
              $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
            } else {
              //Check all checkboxes
              $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
              $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
            }
            $(this).data('clicks', !clicks)
          })
    
          //Handle starring for font awesome
          $('.mailbox-star').click(function (e) {
            e.preventDefault()
            //detect type
            var $this = $(this).find('a > i')
            var fa = $this.hasClass('fa')
    
            //Switch states
            if (fa) {
              $this.toggleClass('fa-star')
              $this.toggleClass('fa-star-o')
            }
          })
        })
        
        $(function () {
          $("#table_load").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            },
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    
        });
    </script>
    
    
@endsection