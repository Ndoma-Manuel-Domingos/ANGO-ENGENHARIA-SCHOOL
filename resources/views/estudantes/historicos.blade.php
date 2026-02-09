@extends('layouts.estudantes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Meus Históricos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
              <li class="breadcrumb-item active">Histórico</li>
            </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">

        <div class="col-md-3 col-12">
            <form action="{{ route('est.historicos') }}" method="get">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="form-group">
                            <label for="" class="form-label">Selecione Ano Lectivo</label>
                            <select name="ano_lectivo" id="" class="form-control">
                                <option value="">TODOS ANO LECTIVO</option>
                                @foreach ($anos as $item)
                                    <option value="{{ Crypt::encrypt($item->id) }}" {{ $requests['ano_lectivo'] == $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- /.col -->
        <div class="col-md-9 col-12">

            <div class="card">
                <div class="card-header p-2">
                    <div class="float-left">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#dados_pessoas" data-toggle="tab">Transferências</a></li>
                            @if (Auth::user()->can('read: nota'))
                            <li class="nav-item"><a class="nav-link" href="#dados_pessoas_pai" data-toggle="tab" >Notas</a></li>
                            @endif
                            <li class="nav-item"><a class="nav-link active" href="#dados_pessoas_mae" data-toggle="tab">Matriculas/Confirmações</a></li>
                            @if (Auth::user()->can('read: pagamento'))
                                @if ($estudante->escola->categoria == "Privado")
                                    <li class="nav-item"><a class="nav-link" href="#academico" data-toggle="tab">Cartões</a></li>   
                                @endif
                            @endif
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane" id="dados_pessoas">
                            @if (count($transferencias) > 0)
                                @foreach ($transferencias as $key => $item)
                                <div class="card">
                                    <div class="card-header">
                                        Transferência Nº: 00{{ $key+1 }}
                                    </div>
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-12 col-form-label">Nº Processo: </label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="email" class="form-control"
                                                    value="{{ $item->id }} " id="inputName" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-12 col-form-label">Escola de Origem</label>
                                                <div class="col-sm-10 col-12">
                                                <input type="text" class="form-control" value="{{ $item->origem->nome }}" id="inputName2"
                                                    placeholder="Número do Bilhete/Cédula do Estudante" disabled>
                                                </div>
                                            </div>
                        
                        
                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-12 col-form-label">Escola de Destino</label>
                                                <div class="col-sm-10 col-12">
                                                <input type="text" class="form-control" value="{{ $item->destino->nome }}" disabled>
                                                </div>
                                            </div>
                            
                                            
                                            <div class="form-group row">
                                                    <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Estado da Transferência</label>
                                                    <div class="col-sm-10 col-12">
                                                        <input type="text" class="form-control" value="{{ $item->status }}"  disabled>
                                                    </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Data da Transferência</label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="text" class="form-control" value="{{ date("d-m-Y", strtotime($item->created_at))  }}"  disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Data da Expiração</label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="text" class="form-control" value="{{ $item->data_final  }}"  disabled>
                                                </div>
                                            </div>

                                            @if ($item->status == "aceita")
                                                <div class="form-group row">
                                                    <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Data da aceitação da Transferência</label>
                                                    <div class="col-sm-10 col-12">
                                                        <input type="text" class="form-control" value="{{ date("d-m-Y", strtotime($item->updated_at))  }}"  disabled>
                                                    </div>
                                                </div>   
                                            @endif
                                            
                                        </div>
                                    </div>

                                    <div class="card-header">
                                        <a href="{{ route('web.transferencia-escolares-imprimir', $item->id) }}" target="_blink" class="float-right btn btn-primary mx-1">Imprimir Transferência</a>
                                        <a href="{{ route('est.eliminar-transferencia', $item->id) }}" target="_blink" class="float-right btn btn-primary mx-1">Eliminar Transferência</a>
                                    </div>
                                </div>
                                @endforeach    
                            @endif   
                        </div>

                        <div class="tab-pane" id="dados_pessoas_pai">
                            @if ($cartao && $cartao->status == 'Pago')
                                @include('admin.require.estudantes.notas')
                            @else
                                <div class="p-3">
                                    <h4 class="text-danger">Infelizmente não podemos disponibilizar as notas, pela inregularidade das propinas</h4>
                                </div>
                            @endif
                        </div>

                        <div class="active tab-pane" id="dados_pessoas_mae">
                            @if (count($matriculas) > 0)
                                @foreach ($matriculas as $key => $item)
                                <div class="card">
                                    <div class="card-header">
                                        Matricula/Confirmações Nº: 00{{ $key+1 }}
                                    </div>
                                    <div class="card-body">
                                        <div class="form-horizontal">

                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-12 col-form-label">Ano Lectivo: </label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="email" class="form-control" value="{{ $item->ano_lectivo->ano }} " disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-12 col-form-label">Nº Processo: </label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="email" class="form-control" value="{{ $item->numero_estudante }} " disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-12 col-form-label">Nº Processo Matricula: </label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="email" class="form-control" value="{{ $item->id }} " disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-12 col-form-label">Classe Anterior</label>
                                                <div class="col-sm-10 col-12">
                                                <input type="text" class="form-control" value="{{ $item->classe_at->classes }}" disabled>
                                                </div>
                                            </div>
                        
                        
                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-12 col-form-label">Classe</label>
                                                <div class="col-sm-10 col-12">
                                                <input type="text" class="form-control" value="{{ $item->classe->classes }}" disabled>
                                                </div>
                                            </div>
                            
                                            
                                            <div class="form-group row">
                                                    <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Curso</label>
                                                    <div class="col-sm-10 col-12">
                                                        <input type="text" class="form-control" value="{{ $item->curso->curso }}"  disabled>
                                                    </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Turno</label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="text" class="form-control" value="{{ $item->turno->turno }}"  disabled>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-12 col-form-label">Data Matricula</label>
                                                <div class="col-sm-10 col-12">
                                                    <input type="text" class="form-control" value="{{ date("d-m-Y", strtotime($item->created_at))  }}"  disabled>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="card-header">
                                        <a href="{{ route('ficha-matricula-segunda-via', Crypt::encrypt($item->ficha)) }}" target="_blink" class="float-right btn btn-primary">Imprimir Matricula</a>
                                    </div>
                                </div>
                                @endforeach    
                            @endif  
                        </div>

                        <div class="tab-pane" id="academico">
                            @if ($servicosTurma)
                                @foreach ($servicosTurma as $item)
                                @if ($item->pagamento == "mensal")
                                @php
                                    $servicos =(new App\Models\web\estudantes\CartaoEstudante())::where([
                                        ['servicos_id', '=', $item->servicos_id],
                                        ['estudantes_id', '=', $estudante->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                    ])
                                    ->join('tb_servicos', 'tb_cartao_estudantes.servicos_id', '=', 'tb_servicos.id')
                                    ->select('tb_cartao_estudantes.month_name', 'tb_cartao_estudantes.data_exp', 'tb_servicos.servico','tb_cartao_estudantes.status','tb_cartao_estudantes.id')
                                    ->get();
                                @endphp 

                                    <table style="width: 100%" class="table table-bordered  ">
                                        <thead>
                                            <tr>
                                            <th colspan="8" class="text-start fs-6">Ficha Extratos do serviço de {{ $item->servico }}</th>
                                            </tr>
                                            <tr class="bg-light">
                                            <th>Codigo</th>
                                            <th>Meses</th>
                                            <th>Data Final Pagamento</th>
                                            <th>Valor Unitário</th>
                                            <th>Multa</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicos as $item2)
                                            <tr class="bg-light">
                                                <td>{{ $item2->id }}</td>
                                                <td>{{ $item2->mes($item2->month_name) }}</td>
                                                <td>{{ $item2->data_exp }}</td>
                                                <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                <td>{{ number_format($item->multa, '2', ',', '.')  }} kz</td>
                                                <td>{{ $item2->status }}</td>
    
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('NULL'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($anoLectivo->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Extrato</a>
                                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Pago'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($anoLectivo->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Pago</a>
                                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Nao_Pago'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($anoLectivo->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Não Pago</a>
                                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Devendo'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($anoLectivo->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Devendo</a>
                                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Bloqueado'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($anoLectivo->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Bloqueado</a>
                                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Obrigatorios'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($anoLectivo->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Obrigatórios</a>
                                        </div>
                                    </div>            
                                
                                @else
                                @if ($item->pagamento == "unico")
                                    @php
                                        $servicos =(new App\Models\web\estudantes\CartaoEstudante())::where([
                                        ['servicos_id', '=', $item->servicos_id],
                                        ['estudantes_id', '=', $estudante->id],
                                        ['ano_lectivos_id', '=', $anoLectivo->id],
                                        ])->select('tb_cartao_estudantes.status', 'tb_cartao_estudantes.id', 'tb_cartao_estudantes.data_exp')
                                        ->get();
                                    @endphp 

                                    <table style="width: 100%" class="table table-bordered  ">
                                        <thead>
                                            <tr>
                                                <td colspan="6" class="text-start fs-6">Ficha Extratos do serviço de {{ $item->servico }}</td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Codigo</th>
                                                <th>Data Final do Pagamento</th>
                                                <th>Valor</th>
                                                <th>Multa</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicos as $item2)
                                            <tr class="bg-light">
                                                <td>oo{{ $item2->id }}</td>
                                                <td>{{ $item2->data_exp }}</td>
                                                <td>{{ number_format($item->preco, '2', ',', '.') }} Kz</td>
                                                <td>{{ number_format($item->multa, '2', ',', '.') }} Kz</td>
                                                <td>{{ $item2->status }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @endif

                                @endforeach
                            @endif
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
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