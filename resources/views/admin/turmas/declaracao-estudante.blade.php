@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Declaração Estudante</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.documentacao-estudantes') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Declarações</li>
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
                    <h5><i class="fas fa-user"></i> Declaração do estudante </h5>
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
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Tipo de declaração</label>
                                    <div class="col-sm-10">
                                        <select class="form-select condicao">
                                            <option value="{{ Crypt::encrypt('declarcao-sem-nota') }}">Sem Notas</option>
                                            <option value="{{ Crypt::encrypt('declaracao-nota') }}">Com Notas</option>
                                            <option value="{{ Crypt::encrypt('classificacao-final') }}">Classificação Final</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="efeito_id" class="col-sm-2 col-form-label">Efeito da Declaração</label>
                                    <div class="col-sm-10">
                                        <select class="form-select efeito_id select2" style="width: 100%" name="efeito_id" id="efeito_id">
                                            <option value="">Para que Efeito</option>
                                            @foreach ($efeitos as $item)
                                            <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Ano Lectivo</label>
                                    <div class="col-sm-10">
                                      @if ($anolectivos)
                                        <select class="form-select ano_lectivos_id">
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
                                
                              <button type="submit" class="btn btn-info imprimir_documento"><i class="fas fa-print"></i> Imprimir</button>
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
        window.open(`../../download/pauta-estudante?id=${$('.estudantes_id').val()}&ano=${$('.ano_lectivos_id').val()}&condicao=${$('.condicao').val()}&condicao2=${$('.efeito_id').val()}`, "_blank");
      });
    });

  </script>
@endsection