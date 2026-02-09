@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Provas de Exames de Acesso</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.exames-acesso.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Inscrições</li>
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
                    <form action="{{ route('web.estudantes-inscricao-exameAcesso') }}" method="get">
                        @csrf
                        <div class="card-header">
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-2 col-12">
                                    <label for="status">Estados Estudante</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">Todos</option>
                                        <option value="Nao Admitido" {{ $filtros['status'] == "Nao Admitido" ? 'selected' : '' }}>Não Admitido(a)</option>
                                        <option value="Admitido" {{ $filtros['status'] == "Admitido" ? 'selected' : '' }}>Admitido(a)</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="cursos_id">Cursos</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->curso->id }}" {{ $filtros['cursos_id'] == $item->curso->id ? 'selected' : '' }}>{{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="classes_id">Classes</label>
                                    <select name="classes_id" id="classes_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->classe->id }}" {{ $filtros['classes_id'] == $item->classe->id ? 'selected' : '' }}>{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="turnos_id">Turnos</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($turnos as $item)
                                        <option value="{{ $item->turno->id }}" {{ $filtros['turnos_id'] == $item->turno->id ? 'selected' : '' }}>{{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="media">Média</label>
                                    <select name="media" id="media" class="form-control select2">
                                        <option value="">Todos</option>
                                        @for ($i = 0; $i <= 20; $i++) <option value="{{ $i }}" {{ $filtros['media'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="idade">Idades</label>
                                    <select name="idade" id="idade" class="form-control select2">
                                        <option value="">Todos</option>
                                        @for ($i = 5; $i <= 35; $i++) <option value="{{ $i }}" {{ $filtros['idade'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.estudantes-inscricao-exameAcesso-post') }}" method="POST" id="add-students-form">
                  @csrf
                  <div class="card">
                      <div class="card-header">
                        <div class="row">
                        
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="">Nota do Exame dos estudantes Selecionados</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input type="text" placeholder="Informe aqui a nota para os estudantes selecionados" value="0" class="form-control" name="nota">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                @if (Auth::user()->can('create: matricula') || Auth::user()->can('create: estudante'))
                                <a href="{{ route('web.estudantes-inscricao-create') }}" class="btn btn-primary float-end">Nova Inscrição</a>
                                @endif
                                <a href="{{ route('estudantes-inscricoes-imprmir-exame-acesso', ['cursos_id' => $filtros['cursos_id'] ?? "",'classes_id' => $filtros['classes_id'] ?? "",'turnos_id' => $filtros['turnos_id'] ?? "",'status' => $filtros['status'] ?? "",'idade' => $filtros['idade'] ?? "", 'media' => $filtros['media'] ?? ""  ]) }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                            </div>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body table-responsive">
                          <table id="carregarTabelaInscricao" style="width: 100%" class="table  table-bordered table-striped">
                              <thead>
                                  <tr>
                                      <th>Nº</th>
                                      <th>Nome</th>
                                      <th>Bilhete</th>
                                      <th>Genero</th>
                                      <th>Curso</th>
                                      <th>Classe</th>
                                      <th>Turno</th>
                                      <th>Idade</th>
                                      <th>Nota Exame</th>
                                      <th>Exame</th>
                                      <th>Resultado</th>
                                      <th>
                                          Acções
                                      </th>
                                  </tr>
                              </thead>
                              <tbody class="tableEstudanes">
                                  @if (count($matriculas) != 0)
                                  @foreach ($matriculas as $key => $item)
                                  <tr>
                                    <td><input type="checkbox" name="estudantes_id[]" class="estudantes_id" style="cursor: pointer;" value="{{ $item->id }}"></td>
                                    <td>
                                        @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                        <a href="{{ route('web.estudantes-inscricao-show', Crypt::encrypt($item->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }} </a>
                                        @else
                                        {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                                        @endif
                                    </td>
                                    <td>{{ $item->estudante->bilheite }} </td>
                                    <td>{{ $item->estudante->genero }}</td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>{{ $item->estudante->idade($item->estudante->nascimento) }}</td>

                                    <td>{{ number_format($item->media, 1, ',', '.') }}</td>

                                    <td>{{ $item->exame_acesso == 'Y' ? 'Feito' : 'Não Feito' }}</td>

                                    <td>
                                        @if ( $item->status_inscricao == 'Admitido' )
                                        <span class="text-success">Admitido(a)</span>
                                        @else
                                        <span class="text-danger">Não Admitido(a)</span>
                                        @endif
                                    </td>
  
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: estudante') || Auth::user()->can('update: matricula'))
                                                <a href="{{ route('web.estudantes-matricula-edit', Crypt::encrypt($item->id)) }}" title="Editar inscrição" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                                <a href="{{ route('web.estudantes-inscricao-show', Crypt::encrypt($item->id)) }}" title="Visualizar Candidatura" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
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
                      
                      <div class="card-footer">
                        <button type="submit" form="add-students-form" class="btn btn-primary">Lançar Notas</button>
                      </div>
                      <!-- /.card-body -->
                  </div>
                </form>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')

<script>
    $(function() {
        $("#carregarTabelaInscricao").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>

@endsection
