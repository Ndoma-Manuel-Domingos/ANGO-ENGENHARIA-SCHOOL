@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Notas Estudante</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.documentacao-estudantes') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Notas</li>
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
                    <h5><i class="fas fa-book"></i> Notas do estudante </h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form class="form-horizontal">
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="trimestre_id" class="col-sm-2 col-form-label">Trimestre</label>
                                    <div class="col-sm-10">
                                        <select class="form-select trimestre_id" name="trimestre">
                                        
                                          @if($escola->ensino->nome == "Ensino Superior")
                                            <option value="{{ Crypt::encrypt("simestre1") }}">Iº Semestre</option> 
                                            <option value="{{ Crypt::encrypt("simestre2") }}">IIº Semestre</option> 
                                            <option value="{{ Crypt::encrypt("anual") }}">Anual</option> 
                                          @else
                                            <option value="{{ Crypt::encrypt("trimestre1") }}">Iº Trimestre</option>
                                            <option value="{{ Crypt::encrypt("trimestre2") }}">IIº Trimestre</option>
                                            <option value="{{ Crypt::encrypt("trimestre3") }}">IIIº Trimestre</option>
                                            <option value="{{ Crypt::encrypt("trimestre4") }}">Geral</option> 
                                          @endif
                                         
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Ano Lectivo</label>
                                    <div class="col-sm-10">
                                        @if ($anolectivos)
                                        <select name="" id="" class="form-select ano_lectivos_id">
                                            @foreach ($anolectivos as $item)
                                              <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                      @endif
                                    </div>
                                </div>

                                <input type="hidden" class="estudantes_id" value="{{ Crypt::encrypt($estudante->id) }}">

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info imprimir_documento"> <i class="fas fa-print"></i> Imprimir</button>
                                <a type="submit" href="{{ route('web.documentacao-estudantes') }}" class="btn btn-default float-right">Cancelar</a>
                            </div>
                            <!-- /.card-footer -->
                        </form>

                    </div>
                </div>
            </div>
        </div>   

    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('scripts')
  <script>

      $(function () {
        $(document).on('click', '.imprimir_documento', function(e){
          e.preventDefault();
          
          window.open(`../../download/pauta-estudante?id=${$('.estudantes_id').val()}&ano=${$('.ano_lectivos_id').val()}&condicao=${$('.trimestre_id').val()}`, "_blank");
          
        });
      });
  </script>
@endsection