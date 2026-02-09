@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Processos Financeiros de estudantes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.processos-estudantes') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Estudantes</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Pesquisas dos processos dos estudantes, propinas, transportes, confirmações etc...</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="">
                        <div class="row">

                            <div class="col-4">

                              <select name="numero_processo" id="numero_processo" class="select2 form-control numero_processo" 
                              data-placeholder="pesquisar o número do processo" style="width: 100%;">
                                @if ($matriculas_passadas)
                                  @foreach ($matriculas_passadas as $matricula)
                                  <option value="{{ Crypt::encrypt($matricula->estudantes_id) }}">{{ $matricula->numero_estudante }} - {{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</option>  
                                  @endforeach
                                @endif
                              </select> 

                            </div>

                            <div class="col-12 col-md-3">

                                <select name="servicos_id" id="servicos_id" class="select2 form-control servicos_id" 
                                data-placeholder="pesquisar o serviço" style="width: 100%;">
                                  @if ($servicos)
                                    @foreach ($servicos as $servico)
                                      <option value="{{ Crypt::encrypt($servico->id) }}">{{ $servico->servico }}</option>  
                                    @endforeach
                                  @endif
                                </select> 
  
                              </div>

                            <div class="col-12 col-md-3">
                              <select name="ano_lectivos_ids"   id="ano_lectivos_ids" class="select2 form-control ano_lectivos_ids"
                              data-placeholder="Selecione o Ano Lectivo" style="width: 100%;">
                                <option value="">Selecione o Ano Lectivo</option>  
                                @if ($ano_lectivos)
                                  @foreach ($ano_lectivos as $ano)
                                    <option value="{{ Crypt::encrypt($ano->id) }}">{{ $ano->ano }}</option>  
                                  @endforeach
                                @endif
                              </select> 
                            </div>

                            <div class="col-2">
                              <button type="submit" class="btn btn-primary pesquisarEstudante"><i class="fas fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
@endsection

@section('scripts')

  <script>
    $(function(){
      // ver dados
      $(document).on('click', '.pesquisarEstudante', function(e){
        e.preventDefault();
        var numero_processo = $('.numero_processo').val();
        var servicos_id = $('.servicos_id').val();
        var ano_lectivos_ids = $('.ano_lectivos_ids').val();

        if( numero_processo == '' ){
          showMessage('Alerta!', 'preencha o campo numero do processo!', 'error');
          window.location.reload();
          
        }else if( servicos_id == '' ){
          showMessage('Alerta!', 'preencha o campo serviços!', 'error');
          window.location.reload();
        }else if( ano_lectivos_ids == '' ){
          showMessage('Alerta!', 'preencha o campo Ano lectivo', 'error');
          window.location.reload();
        }else{
          window.open(`../download/extrato-estudante?id=${numero_processo}&servico=${servicos_id}&ano=${ano_lectivos_ids}`, "_blank");
        }
      });
    });
                   
  </script>
  
@endsection