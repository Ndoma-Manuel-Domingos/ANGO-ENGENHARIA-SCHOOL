@extends('layouts.escolas')

@section('content')

<style>
    address span{
        border: 1px solid rgb(197, 197, 197);
        padding: 5px 10px;
        display: inline-block;
        margin-bottom: 2px;
        border-radius: 5px;
        width: 100%;
    }
</style>

  <!-- Main content -->
  <section class="content pt-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> Resultado da Pesquisar Realizada</h5>
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
      
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            
            <div class="card-header">
              <h5>
                <i class="fas fa-graduation-cap"></i> {{ $estudantes->nome }}  {{ $estudantes->sobre_nome }}
              </h5>
            </div>
          
            <div class="card-body">
              <form action="">
                <div class="row">
                
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Genero:</label>
                    <input type="text" value="{{ $estudantes->genero }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Nascimento:</label>
                    <input type="text" value="{{ $estudantes->nascimento }}" disabled class="form-control">
                  </div>
                  
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Nacionalidade:</label>
                    <input type="text" value="{{ $estudantes->nacionalidade }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">B.I:</label>
                    <input type="text" value="{{ $estudantes->bilheite }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Tel Estudante:</label>
                    <input type="text" value="{{ $estudantes->telefone_estudante }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Turma:</label>
                    <input type="text" value="{{ $turma->turma ?? 'Sem Turma' }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Curso:</label>
                    <input type="text" value="{{ $curso->curso }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Classe:</label>
                    <input type="text" value="{{ $classe->classes }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Turno:</label>
                    <input type="text" value="{{ $turno->turno }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-2 mb-3">
                    <label for="" class="form-label">Processo Nº:</label>
                    <input type="text" value="{{ $matriculas->numero_estudante }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-4 mb-3">
                    <label for="" class="form-label">Pai:</label>
                    <input type="text" value="{{ $estudantes->pai }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-4 mb-3">
                    <label for="" class="form-label">Mãe:</label>
                    <input type="text" value="{{ $estudantes->mae }}" disabled class="form-control">
                  </div>
                  
                  <div class="col-12 col-md-4 mb-3">
                    <label for="" class="form-label">Telefone Pai e Mãe:</label>
                    <input type="text" value="{{ $estudantes->telefone_pai }} {{ $estudantes->telefone_mae }}" disabled class="form-control">
                  </div>
                  
                </div>
              </form>
            </div>
            
            <div class="card-footer">
              @if (Auth::user()->can('read: mini pautas'))
              <a href="{{ route('web.mini-pauta-estudante', Crypt::encrypt($estudantes->id)) }}" class="btn btn-primary">Mini Pauta</a>
              @endif
            
              @if (Auth::user()->can('read: pautas'))
              <a href="{{ route('web.pauta-estudante', Crypt::encrypt($estudantes->id)) }}" class="btn btn-primary">Pauta</a>
              @endif
            
              @if (Auth::user()->can('create: pagamento'))
              <a href="{{ route('web.estudantes-pagamento-propina', Crypt::encrypt($estudantes->id)) }}" class="btn btn-primary">Fazer Pagamentos</a>
              @endif
              
              @if (Auth::user()->can('read: pagamento'))
              <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($estudantes->id)) }}" class="btn btn-primary">Ver Extrato Financeiro</a>
              @endif
            
              @if (Auth::user()->can('create: factura'))
              <a href="{{ route('web.facturar-pagamento-servico', $estudantes->id) }}" class="btn btn-primary">Facturar</a>
              @endif
              
              @if (Auth::user()->can('create: factura'))
              <a href="{{ route('web.liquidar-factura') }}" class="btn btn-primary">Liquidar Facturas</a>
              @endif
                
            </div>
          </div>
        </div>
      </div>
      
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
