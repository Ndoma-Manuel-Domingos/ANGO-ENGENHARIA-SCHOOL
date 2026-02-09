@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Faltas Estudantes</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Faltas</a></li>
            <li class="breadcrumb-item active">Turmas</li>
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
                <h5><i class="fas fa-info"></i>  Lista de presença para os estudantes</h5>
            </div>
        </div>
      </div>

      <div class="row">
          <div class="col-12 col-md-12">
              <div class="card p-2">
                <form action="{{ route('web.faltas-turmas-estudantes-post') }}" method="post" class="row">
 
                    <div class="form-group col-md-4">
                        <label for="turma">Turma</label>
                        <select name="turma" id="turma" class="form-control turma">
                            @if (count($turmas) != 0)
                              @foreach ($turmas as $item)
                                  <option value="{{ $item->id }}">{{ $item->turma }}</option>
                              @endforeach
                            @endif
                        </select>
                        <span class="text-danger error-text turma_error"></span>
                    </div>    


                    <div class="form-group col-md-4">
                      <label for="list_professores">Funcionários</label>
                      <select name="funcionario" id="list_professores" class="form-control funcionario">
                      </select>
                      <span class="text-danger error-text funcionario_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="list_disciplinas">Disciplinas</label>
                        <select name="disciplina" id="list_disciplinas" class="form-control disciplina">
                        </select>
                        <span class="text-danger error-text disciplina_error"></span>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary float-start gerar_lista_presenca">Gerar Lista</button>
                    </div>
                </form>
          </div>
      </div>

    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('scripts')

  <script>

    $(function(){

    $(document).on('click', '.gerar_lista_presenca', function(e){
        e.preventDefault();

        var data = {
          'funcionario' : $('.funcionario').val(),
          'turma' : $('.turma').val(),
          'disciplina' : $('.disciplina').val(),
        }

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('web.faltas-turmas-estudantes-post') }}",
            data: data,
            dataType: "json",
            beforeSend: function () {
              // Você pode adicionar um loader aqui, se necessário
    progressBeforeSend();
            },
            success: function (response){
              Swal.close();
              if(response.status == 300){
                Swal.fire({
                  title: "Oooooops....",
                  text: response.message,
                  icon: "error",
                  timer: "2000",
                }); 
              }else
              if(response.status == 400){
                $.each(response.errors, function(key, err_values){
                  $('span.'+ key + '_error').text(err_values[0]);
                });
              
              }else{
                
                Swal.fire({
                  title: "Bom trabalho",
                  text: response.message,
                  icon: "success",
                  button: "Great!",
                }); 

                window.location.reload();

              }
            }
        });
        
    });
    
    $(document).on('change', '.turma', function(e){
      e.preventDefault();
      var id = $(this).val();
      $('.id_turmas_add').val(id);
      $.ajax({
          type: "GET",
          url: "carregar-disciplinas-turma/"+id, 
          dataType: "json",
          beforeSend: function () {
              // Você pode adicionar um loader aqui, se necessário
    progressBeforeSend();
          },
          success: function(response){

            Swal.close();
              if (response.status == 200) {
                  $('#list_disciplinas').html("");
                  $('#list_professores').html("");
                    for (let index = 0; index < response.disciplinasTurma.length; index++) {
                        $('#list_disciplinas').append('<option value="'+response.disciplinasTurma[index].id +'">'+response.disciplinasTurma[index].disciplina +'</option>');             
                  }

                    for (let index = 0; index < response.resultado.length; index++) {
                        $('#list_professores').append('<option value="'+response.resultado[index].idFuncionario +'"> '+response.resultado[index].nome +' '+response.resultado[index].sobre_nome +'</option>');             
                  }
              }
          }
      });

    });

  });
 
  </script>

@endsection