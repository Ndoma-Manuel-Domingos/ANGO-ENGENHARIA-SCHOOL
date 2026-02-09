@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Candidaturas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
            <li class="breadcrumb-item active">Candidaturas</li>
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
                <div class="card-header">
                  @if (Auth::user()->can('create: candidatura'))
                  <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalClasses">Nova Candidatura</a>
                  @endif
                  <a href="{{ route('web.classes-pdf-ano-lectivo') }}" class="btn-danger btn float-end mx-1" target="_blink"> <i class="fas fa-pdf"></i> Imprimir PDF</a>
                  <a href="{{ route('web.classes-excel-ano-lectivo') }}" class="btn-success btn float-end mx-1" target="_blink"> Imprimir Excel</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <table id="carregarTabelaCursos"  style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th style="width: 170px;"> Acções </th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @if (count($candidaturas))
                            @foreach ($candidaturas as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->candidatura->nome }}</td>
                                <td>{{ $item->candidatura->status }}</td>
                                <td>
                                    <div class="btn-group">
                                    <button type="button" class="btn btn-info">Opções</button>
                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                      @if (Auth::user()->can('update: candidatura'))
                                      <a title="Editar Candidatura" id="{{ $item->id }}" class="editar dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                      @endif
                                      @if (Auth::user()->can('delete: candidatura'))
                                      <a href="{{ route('web.candidaturas-eliminar-ano-lectivo', $item->id) }}" title="Excluir Candidatura" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                      @endif
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="#">Outros</a>
                                    </div>
                                    </div>
                                </td>
                            </tr>    
                            @endforeach
                        @endif
                        
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


<div class="modal fade" id="modalClasses">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('web.cadastrar-candidatura-ano-lectivo') }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Cadastrar Candidatura</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                          <label for="candidaturas_ids">Candidatura <span class="text-danger">*</span></label>
                          <select name="candidaturas_id[]" class="form-control candidaturas_id select2" id="candidaturas_ids" style="width: 100%;" data-placeholder="Selecione um conjunto de Candidaturas" multiple="multiple" >
                              <option value="">Selecione Candidatura</option>
                              @foreach ($lista_candidaturas as $item)
                                  <option value="{{ $item->id }}">{{ $item->nome }}</option>    
                              @endforeach
                          </select>
                          <span class="text-danger error-text candidaturas_id_error"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="ano_lectivo_ids">Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivo_id" class="form-control ano_lectivo_id select2" id="ano_lectivo_ids" style="width: 100%;">
                            @if ($ano_lectivo)
                                <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option> 
                            @endif
                            </select>
                            <span class="text-danger error-text ano_lectivo_id_error"></span>
                        </div>
                    </div> 
                </div>

                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalClassesUpdate">
  <div class="modal-dialog modal-xl">
      <form action="{{ route('web.candidatura-update-ano-lectivo') }}" method="post">
          <div class="modal-content">
              <div class="modal-header">
              <h4 class="modal-title">Editar Candidatura</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              </div>

              <div class="modal-body">
                  @csrf
                  <div class="row">
                      <div class="form-group col-md-6">
                        <label for="candidaturas_id">Candidatura <span class="text-danger">*</span></label>
                        <select name="candidaturas_id" class="form-control classes_id_edit select2" id="turnos_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Classes" >
                            <option value="">Selecione Candidatura</option>
                            @foreach ($lista_candidaturas as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>    
                            @endforeach
                        </select>
                        <span class="text-danger error-text classes_id_error"></span>
                      </div>

                      <input type="hidden" name="id" class="id">

                      <div class="form-group col-md-6">
                          <label for="ano_lectivo_id">Ano Lectivo <span class="text-danger">*</span></label>
                          <select name="ano_lectivo_id" class="form-control ano_lectivo_id select2" id="ano_lectivo_id" style="width: 100%;">
                          @if ($ano_lectivo)
                              <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option> 
                          @endif
                          </select>
                          <span class="text-danger error-text ano_lectivo_id_error"></span>
                      </div>
                  </div> 
              </div>

              <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
          </div>
      </form>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


  <!-- /.content -->
@endsection


@section('scripts')
  <script>

    $(function () {
      
      $(document).on('click', '.editar', function(e){
        e.preventDefault();
        var novo_id = $(this).attr('id');
        $("#modalClassesUpdate").modal("show");   
        $.ajax({
            type: "GET",
            url: "candidaturas-ano-lectivo/"+novo_id+"/editar/", 
            beforeSend: function () {
            // Você pode adicionar um loader aqui, se necessário
    progressBeforeSend();
            },
            success: function(response){ 
              $('.classes_id_edit').html("");
              $('.classes_id_edit').append('<option value="'+ response.dados.candidatura.id +'" selected>'+ response.dados.candidatura.nome +'</option>');
              for (let index = 0; index < response.candidaturas.length; index++) {
                  $('.classes_id_edit').append('<option value="'+ response.candidaturas[index].id +'">'+ response.candidaturas[index].nome +'</option>');
              }
              $('.id').val(response.dados.id)
            }
        });
      });

      $("#carregarTabelaCursos").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

  </script>
@endsection
