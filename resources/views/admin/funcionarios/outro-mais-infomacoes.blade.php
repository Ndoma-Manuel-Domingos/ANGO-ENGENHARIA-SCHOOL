@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Informações Funcionário</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.outro-funcionarios') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Funcionário</li>
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
                    <h5><i class="fas fa-info"></i> Mais informações sobre do funcionários <a href="">{{ $professor->nome }} {{ $professor->sobre_nome }}</a>, dados pessoais, dados académicos, turmas etc.</h5>
                </div>
            </div>
        </div>

      <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  @if (empty($professor->image))
                  <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}" alt="User profile picture">
                  @else
                  <img class="profile-user-img img-fluid img-circle" src="{{ asset("assets/images/professores/$professor->image") }}" alt="User profile picture">
                  @endif
                </div>
  
                <h3 class="profile-username text-center">{{ $professor->nome }} {{ $professor->sobre_nome }}</h3>
  
                <p class="text-muted text-center">{{ $professor->academico->curso }}</p>
  
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Nascimento</b> <a class="float-right">{{ $professor->nascimento }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Genero</b> <a class="float-right">{{ $professor->genero }}</a>
                  </li>
  
                  <li class="list-group-item">
                    <b>Funcionário</b> <a class="float-right">{{ $professor->status }}</a>
                  </li>
                  
                  
                  <li class="list-group-item">
                      <b>Cargo</b> <a class="float-right">{{ $contrato->cargos->cargo ?? '-----' }}</a>
                  </li>
                  
                  <li class="list-group-item">
                      <b>Departamento</b> <a class="float-right">{{ $contrato->departamento->departamento ?? '-----' }}</a>
                  </li>
                </ul>
  
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
                <li class="nav-item"><a class="nav-link active" href="#dados_pessoas" data-toggle="tab">Dados Pessoais</a></li>
                <li class="nav-item"><a class="nav-link" href="#academico" data-toggle="tab">Académicos</a></li>
                @if ($escola->modulo != "Basico")
                    @if ($escola->categoria == "Privado")
                        <li class="nav-item"><a class="nav-link" href="#contratos" data-toggle="tab">Contrato</a></li>
                    @endif
                @endif
                
                <li class="nav-item"><a class="nav-link" href="#documentos" data-toggle="tab">Documentos</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="dados_pessoas">
                    <div class="form-horizontal">
  
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nº Identificador</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" value="{{ $professor->id ?? ""  }}"
                            placeholder="Nome Completo do Pai Estudante" disabled>
                        </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name Completo</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control"
                            value="{{ $professor->nome }} {{ $professor->sobre_nome ?? ""  }}"
                            placeholder="Nome Completo do Pai Estudante" disabled>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nome Pai</label>
                        <div class="col-sm-10">
                        <input type="email" class="form-control" value="{{ $professor->pai ?? ""  }}" placeholder="Name" disabled>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Nome Completo da Mãe</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->mae ?? ""  }}" placeholder="Email" disabled>
                          </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Nascimento</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" value="{{ $professor->nascimento ?? ""  }}"
                            placeholder="Data do Nascimento do Estudante" disabled>
                        </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Genero</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->genero ?? ""  }}"
                            placeholder="Genero do Estudante" disabled>
                        </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Bilhete/Cédula</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->bilheite ?? ""  }}"
                            placeholder="" disabled>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Data Emissão do Bilhete</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->emissiao_bilheite ?? ""  }}"
                            placeholder="" disabled>
                        </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Nacionalidade</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->nacionalidade->name  ?? "" }}"
                            placeholder="Nacionalidade do Estudante" disabled>
                        </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Província</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->provincia->nome ?? ""  }}"
                            placeholder="Província do Estudante" disabled>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Municpio</label>
                        <div class="col-sm-10">
                        <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $professor->municipio->nome ?? "" }}" >
                        </div>
                      </div>
                      
                      <div class="form-group row">
                          <label for="inputExperience" class="col-sm-2 col-form-label">Distrito</label>
                          <div class="col-sm-10">
                          <input class="form-control" id="inputExperience" placeholder="Experience" disabled value="{{ $professor->distrito->nome ?? ""  }}">
                          </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Tel. Estudante</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->telefone ?? ""  }}" id="inputSkills" placeholder="Número telefonico do Estudante" disabled>
                        </div>
                      </div>
  
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Endereço</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" id="inputExperience" placeholder="Endereço do Estudante" disabled>{{ $professor->endereco ?? ""  }}</textarea>
                        </div>
                      </div>
  
                    </div>
                    <!-- /.post -->
                  </div>
  
                  <div class="tab-pane" id="academico">
                    <div class="form-horizontal">
  
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Cargo</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" disabled value="{{ $contrato->cargos->cargo ?? "" }}" placeholder="Name">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Departamento</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" disabled value="{{ $contrato->departamento->departamento ?? "" }}" placeholder="Name">
                          </div>
                      </div>
  
  
                      <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Universidade</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->academico->universidade->nome ?? "" }}" disabled placeholder="Name">
                          </div>
                      </div>
  
                      <div class="form-group row">
                          <label for="inputName" class="col-sm-2 col-form-label">Especialidade</label>
                          <div class="col-sm-10">
                          <input type="email" class="form-control" value="{{ $professor->academico->especialidade->nome ?? "" }}" disabled placeholder="Name">
                          </div>
                      </div>
    
                      <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Categoria</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->academico->categoria->nome ?? "" }}" disabled placeholder="Email">
                          </div>
                      </div>
  
                      <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Nível Academico</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->academico->escolaridade->nome ?? "" }}" disabled placeholder="Name">
                          </div>
                      </div>
  
                      <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Formação</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" value="{{ $professor->academico->formacao->nome ?? "" }}" disabled placeholder="Name">
                          </div>
                      </div>
  
                    </div>
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
  
            <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>

         
            @if ($escola->modulo != "Basico")
                @if ($escola->categoria == "Privado")
                    <div class="card">
                        <div class="py-3 px-3">
                            <a href="{{ route('ficha-funcionario-extrato', $professor->id) }}" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Extrato</a>
                            <a href="{{ route('ficha-funcionario-contrato', Crypt::encrypt($contrato->id ?? null)) }}" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Contrato</a>
                            <a href="{{ route('ficha-funcionario-geral', $professor->id) }}" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Gerais</a>
                        </div>
                    </div>
                @endif
            @endif
        
        
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->

  <!-- /.content -->
@endsection
