@extends('layouts.provinciais')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Detalhes do Funcianário</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">Voltar</a></li>
          <li class="breadcrumb-item active">Funcionários</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
  <div class="container-fluid">
  
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Mais informações sobre do funcionários <a href="">{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}</a>, dados pessoais, dados académicos, turmas etc.</h5>
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">

              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="{{ public_path('assets/images/user.png') }}"
                     alt="imagem do perfil">
              </div>

              <h3 class="profile-username text-center">{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}</h3>
              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Data Nascimento</b> <a class="float-right">{{ $funcionario->nascimento }}</a>
                </li>
                <li class="list-group-item">
                  <b>Genero</b> <a class="float-right">{{ $funcionario->genero }}</a>
                </li>
                <li class="list-group-item">
                  <b>Nacionalidade</b> <a class="float-right">{{ $funcionario->nacionalidade->name }}</a>
                </li>
                
                <li class="list-group-item">
                    <b>Funcionário</b> <a class="float-right">{{ $funcionario->status }}</a>
                </li>
                
                
                <li class="list-group-item">
                    <b>Cargo</b> <a class="float-right">{{ $contrato->cargos->cargo }}</a>
                </li>
                
                <li class="list-group-item">
                    <b>Departamento</b> <a class="float-right">{{ $contrato->departamento->departamento }}</a>
                </li>
                
              </ul>   
              
                @if (Auth::user()->can('update: professores'))
                  <a href="{{ route('web.funcionarios-provincial-edit', Crypt::encrypt($funcionario->id)) }}" title="Editar Funcionarios" class="btn btn-primary"><i class="fa fa-edit"></i> Editar</a>
                  @if ($funcionario->status == 'activo')
                      <a href="{{ route('web.funcionarios-provincial-status', Crypt::encrypt($funcionario->id)) }}" title="Editar Funcionarios" class="btn btn-danger"><i class="fa fa-check"></i> Desactivar</a>
                  @endif
                  @if ($funcionario->status == 'desactivo')
                      <a href="{{ route('web.funcionarios-provincial-status', Crypt::encrypt($funcionario->id)) }}" title="Editar Funcionarios" class="btn btn-success"><i class="fa fa-check"></i> Activar</a>
                  @endif
                @endif
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#dados_pessoais" data-toggle="tab">Dados Pessoais</a></li>
                <li class="nav-item"><a class="nav-link" href="#dados_academicos" data-toggle="tab">Dados Académicos</a></li>
                {{-- <li class="nav-item"><a class="nav-link" href="#contratos" data-toggle="tab">Contrato</a></li> --}}
                <li class="nav-item"><a class="nav-link" href="#documentos" data-toggle="tab">Documentos</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
              
                <div class="active tab-pane" id="dados_pessoais">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Nome Completo</label>
                            <div class="col-sm-10">
                            <input type="email" class="form-control" value="{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}" id="inputName" placeholder="Name" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Nome Pai</label>
                            <div class="col-sm-10">
                            <input type="email" class="form-control" value="{{ $funcionario->pai }}" id="inputName" placeholder="Name" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Nome Completo da Mãe</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $funcionario->mae }}" id="inputEmail" placeholder="Email" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Data Nascimento</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $funcionario->nascimento }}" id="inputEmail" placeholder="Email" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Genero</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $funcionario->genero }}" id="inputEmail" placeholder="Email" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Estado Cívil</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" value="{{ $funcionario->estado_civil }}" placeholder="Name" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Bilhete</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" value="{{ $funcionario->bilheite }}" placeholder="Name" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Data Emissão Bilhete</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" value="{{ $funcionario->emissiao_bilheite }}" placeholder="Name" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputExperience" class="col-sm-2 col-form-label">Província</label>
                            <div class="col-sm-10">
                            <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $funcionario->provincia->nome }}">
                            </div>
                        </div>
                        
                        
                        <div class="form-group row">
                            <label for="inputExperience" class="col-sm-2 col-form-label">Municpio</label>
                            <div class="col-sm-10">
                            <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $funcionario->municipio->nome }}" >
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputExperience" class="col-sm-2 col-form-label">Distrito</label>
                            <div class="col-sm-10">
                            <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $funcionario->distrito->nome }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputExperience" class="col-sm-2 col-form-label">Endereço</label>
                            <div class="col-sm-10">
                            <textarea class="form-control" id="inputExperience" placeholder="Experience" disabled>{{ $funcionario->endereco }}</textarea>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="dados_academicos">
                    <form class="form-horizontal">
                    
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Cargo</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->cargos->cargo }}" placeholder="Name">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Departamento</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->departamento->departamento }}" placeholder="Name">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Universidade</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" value="{{ $funcionario->academico->universidade->nome }}" disabled placeholder="Name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Especialidade</label>
                            <div class="col-sm-10">
                            <input type="email" class="form-control" value="{{ $funcionario->academico->especialidade->nome }}" id="inputName" disabled placeholder="Name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Categoria</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $funcionario->academico->categoria->nome }}" id="inputEmail" disabled placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Nível Academico</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" value="{{ $funcionario->academico->escolaridade->nome }}" disabled placeholder="Name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Formação</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" value="{{ $funcionario->academico->formacao_academica->nome }}" disabled placeholder="Name">
                            </div>
                        </div>

                    </form>
                </div>
                {{-- GERAL --}}                
                <div class="tab-pane" id="contratos">
                    @if (isset($contrato) && $contrato != null)
                        <form class="form-horizontal">

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Cargo</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->cargos->cargo }}" placeholder="Name">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Departamento</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->departamento->departamento }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Status contrato</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->status_contrato }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->status }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">NIF</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->nif }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Conta Bancária</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->conta_bancaria }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">IBAN</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->iban }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Número contrato</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ $contrato->documento }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Data Inicio Contrato</label>
                                <div class="col-sm-10">
                                <input type="email" class="form-control" disabled value="{{ $contrato->data_inicio_contrato }}" id="inputName" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Data Final Contrato</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{{ $contrato->data_final_contrato }}" id="inputEmail" placeholder="Email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Hora Entrada</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" disabled id="inputName2" value="{{ $contrato->hora_entrada_contrato }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Hora Saída</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" disabled id="inputName2" value="{{ $contrato->hora_saida_contrato }}" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Salário Base</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" disabled id="inputName2" value="{{ number_format($contrato->salario, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Subcídio Transporte</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" disabled id="inputName2" value="{{ number_format($contrato->subcidio_transporte, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Subcídio Alimentação</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format($contrato->subcidio_alimentacao, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Subcídio Ferias</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format($contrato->subcidio_ferias, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Subcídio Natal</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format($contrato->subcidio_natal, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Subcídio Abono Famíliar</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format($contrato->subcidio_abono_familiar, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Outros Subcídios</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format($contrato->subcidio, '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Total Subcídios</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format(($contrato->subcidio + $contrato->subcidio_abono_familiar + $contrato->subcidio_ferias + $contrato->subcidio_natal + $contrato->subcidio_alimentacao + $contrato->subcidio_transporte), '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Total Sslário</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName2" disabled value="{{ number_format(($contrato->salario + $contrato->subcidio_abono_familiar + $contrato->subcidio_ferias + $contrato->subcidio_natal + $contrato->subcidio + $contrato->subcidio_alimentacao + $contrato->subcidio_transporte), '2', ',', '.') }} Kz" placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputExperience" class="col-sm-2 col-form-label">Clausula Contrato</label>
                                <div class="col-sm-10">
                                <textarea class="form-control" id="inputExperience" disabled placeholder="Experience">{{ $contrato->clausula }}</textarea>
                                </div>
                            </div>

                        </form>
                    @endif
                </div>
                
                <div class="tab-pane" id="documentos">
                    <div class="form-horizontal">
  
                      @if ($documentos)
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Bilhete de Identidade</label>
                            <div class="col-sm-10">
                              <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->bilheite") }}' target="_blink">{{ $documentos->bilheite ?? 'sem documento' }}</a></div>
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label"> Atestado Médico </label>
                            <div class="col-sm-10">
                              <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->atestado") }}' target="_blink">{{ $documentos->atestado ?? 'sem documento' }}</a></div>
                            </div>
                        </div>    
  
                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Certificado</label>
                          <div class="col-sm-10">
                            <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->certificado") }}' target="_blink">{{ $documentos->certificado ?? 'sem documento'}}</a></div>
                          </div>
                        </div> 
  
                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Outros Documentos</label>
                          <div class="col-sm-10">
                            <div type="email" class="form-control"><a href='{{ asset("/assets/arquivos/$documentos->outros") }}' target="_blink">{{ $documentos->outros ?? 'sem documento' }}</a></div>
                          </div>
                        </div> 
                      @endif
    
                    </div>
                </div>
  
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>

        
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
    
    
  </div>
</section>
<!-- /.content -->
@endsection
