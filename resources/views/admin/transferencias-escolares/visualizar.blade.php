@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Detalhe da Transferência</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Voltar</a></li>
            <li class="breadcrumb-item active">Perfil</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <!-- /.col -->

        
        <div class="col-12 col-md-12">

          @if ($transferencia->status == "aceita")
            <div class="alert alert-success">
              Esta transferência já foi aceita, porque cumpriu com todos os requisitos solicitados.
            </div>
          @else
            <div class="alert alert-warning">
              Esta transferência ainda foi aceita, encontra-se no estado de {{ $transferencia->status }} ou analise...
            </div>
          @endif
          
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('web.transferencia-escolares-imprimir', $transferencia->id) }}" target="_blink" class="float-right btn btn-primary">Imprimir Transferência</a>
                </div>

                <div class="card-body">
                  <div class="form-horizontal">
                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Escola de Orgigem</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $transferencia->origem->nome }}" id="inputName2"
                          placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                      </div>
                    </div>


                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Escola de Destino</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $transferencia->destino->nome }}" disabled>
                      </div>
                    </div>

                    
                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Estado da Transferência</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $transferencia->status }} ..."  disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Condição da Transferência</label>
                      <div class="col-sm-10">

                        @if ($transferencia->condicao == "concluir_ano_lectivo")
                            <input type="text" class="form-control" value="Para Concluir o Ano Lectivo"  disabled>
                        @else
                            <input type="text" class="form-control" value="Para Estudar Novo Ano"  disabled>
                        @endif
                        
                      </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Usuário Responsável pela transferência</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $transferencia->user->nome }}"  disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Data da transferência</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ date("d-m-Y", strtotime($transferencia->created_at)) }} As {{ date("H:i:s", strtotime($transferencia->created_at)) }}"  disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Ler Documentação</label>
                        <div class="col-sm-10">
                          <div class="form-control">  
                            <a href="{{ asset("assets/arquivos/$transferencia->documento") }}" target="_blink">Clica aqui para ler o documento {{ $transferencia->documento }}</a>
                          </div>
                        </div>
                    </div>

                    <div class="bg-light py-3 px-2 mb-2">
                        <h6 class="text-uppercase">Dados do Estudante</h6>
                    </div>

                    <div class="form-group row">
                      <label for="inputSkills" class="col-12  col-sm-2 col-form-label">Estudante</label>
                      <div class="col-12 col-sm-10">
                        <div class="row">
                          <div class="col-12 col-sm-8">
                            <label class="col-form-label">Nome Completo</label>
                            <input type="text" class="form-control" value="{{ $transferencia->estudante->nome }} {{ $transferencia->estudante->sobre_nome }}" disabled>
                          </div>
    
                          <div class="col-12 col-sm-2">
                            <label class="col-form-label">Idade</label>
                            <input type="text" class="form-control" value="{{ $transferencia->estudante->idade($transferencia->estudante->nascimento) }}" disabled>
                          </div>
    
                          <div class="col-12 col-sm-2">
                            <label class="col-form-label">Genero</label>
                            <input type="text" class="form-control" value="{{ $transferencia->estudante->genero }}" disabled>
                          </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputSkills" class="col-12 col-sm-2 col-form-label"></label>
                        <div class="col-12 col-sm-10">
                            <div class="row">
                                <div class="col-12 col-12 col-md-4">
                                    <label class="col-form-label">Classe</label>
                                    <input type="text" class="form-control" value="{{ $transferencia->classe->classes }}" disabled>   
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="col-form-label">Curso</label> 
                                    <input type="text" class="form-control" value="{{ $transferencia->curso->curso }}" disabled>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="col-form-label">Turno</label> 
                                      <input type="text" class="form-control" value="{{ $transferencia->turno->turno }}" disabled>
                                  </div>
                            </div>
                        </div>

                    </div>


                    @if ($transferencia->condicao == "concluir_ano_lectivo")
                      <div class="bg-light py-3 px-2 mb-2">
                        <h6 class="text-uppercase text-danger">Obervações Importante</h6>

                        <p>Estes não os requisito para aceitar esta transferência: <br>A escola tem que ter uma turma com os dados da transferência do estudante que são:</p>
                        <p>Classe: {{ $transferencia->classe->classes }};</p>
                        <p>Curso: {{ $transferencia->curso->curso }};</p>
                        <p>Turno: {{ $transferencia->turno->turno }}. <span class="text-danger">Obs: turno não obrigatório</span> </p>
                      </div>
                    @endif

                  </div>
                </div>
                @if ($transferencia->status != "aceita")
                  <div class="card-footer">
                      @if (Auth::user()->can('read: transeferencia estudante') || Auth::user()->can('update: estado'))
                        <a href="{{ route('web.transferencia-escolares-cancelar', $transferencia->id) }}" class="btn btn-danger">Cancelar Transferência</a>
                        <a href="{{ route('web.transferencia-escolares-aceitar', $transferencia->id) }}" class="btn btn-success">Aceitar Transferência</a>
                        <a href="{{ route('web.transferencia-escolares-rejeitar', $transferencia->id) }}" class="btn btn-warning">Rejeitar Transferência</a>
                      @endif
                      
                      @if (Auth::user()->can('delete: transeferencia estudante'))
                        <a href="{{ route('web.transferencia-escolares-eliminar', $transferencia->id) }}" class="btn btn-danger">Eliminar Transferência</a>
                      @endif
                  </div>    
                @endif
                
            </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection