@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Professores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('recursos_humanos') }}">Recursos humanos</a></li>
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
                    <h5><i class="fas fa-info"></i> Cadastrar, listar, editar, eliminar e Mais informações dos funcionários. Busca
                        avançada para melhorar na navegação do software.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.funcionarios') }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body row">

                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="status" class="form-label">Funcionários</label>
                                <select name="status" id="status" class="form-control status select2">
                                    <option value="">Todos</option>
                                    <option value="activo" {{ $requests['status']=='activo' ? 'selected' : '' }}>Activo
                                    </option>
                                    <option value="desactivo" {{ $requests['status']=='desactivo' ? 'selected' : '' }}>
                                        Desactivo
                                    </option>
                                </select>
                                @error('status')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="universidade_id" class="form-label">Universidade</label>
                                <select name="universidade_id" id="universidade_id" class="form-control universidade_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($universidades as $item)
                                    <option value="{{ $item->id }}" {{ $requests['universidade_id']==$item->id ?
                    'selected' : '' }}>{{
                    $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('universidade_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="especialidade_id" class="form-label">Especialidades</label>
                                <select name="especialidade_id" id="especialidade_id" class="form-control especialidade_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($especialidades as $item)
                                    <option value="{{ $item->id }}" {{ $requests['especialidade_id']==$item->id ?
                    'selected' : '' }}>{{
                    $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('especialidade_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="categora_id" class="form-label">Categorias</label>
                                <select name="categora_id" id="categora_id" class="form-control categora_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($categorias as $item)
                                    <option value="{{ $item->id }}" {{ $requests['categora_id']==$item->id ? 'selected'
                    : '' }}>{{
                    $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('categora_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="escolaridade_id" class="form-label">Nível Academico</label>
                                <select name="escolaridade_id" id="escolaridade_id" class="form-control escolaridade_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($escolaridade as $item)
                                    <option value="{{ $item->id }}" {{ $requests['escolaridade_id']==$item->id ?
                    'selected' : '' }}>{{
                    $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('escolaridade_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-12 col-md-2">
                                <label for="formacao_id" class="form-label">Formação</label>
                                <select name="formacao_id" id="formacao_id" class="form-control formacao_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($formacao_academicos as $item)
                                    <option value="{{ $item->id }}" {{ $requests['formacao_id']==$item->id ? 'selected'
                    : '' }}>{{
                    $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('formacao_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- @if ($escola->categoria == 'Privado') --}}
                        @if (Auth::user()->can('read: professores'))
                        <a href="{{ route('shcools.professores-create') }}" class="btn btn-primary float-end mx-1">Novo Docente</a>
                        @endif
                        {{-- @endif --}}
                        <a href="{{ route('funcionarios-imprmir') }}" target="_blink" class="btn btn-primary float-end mx-1">Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaFuncionarios" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Nome Completo</th>
                                    <th>Nascimento</th>
                                    <th>Genero</th>
                                    <th>Status</th>
                                    <th>Bilhete</th>
                                    <th>Especialidade</th>
                                    <th>Categoria</th>
                                    <th>Nível Academicos</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($funcionarios) != 0)
                                @foreach ($funcionarios as $item)
                                <tr>
                                    <td>{{ $item->funcionario->id }}</td>
                                    <td><a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($item->funcionario->id)) }}">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</a></td>
                                    <td>{{ $item->funcionario->nascimento }}</td>
                                    <td>{{ $item->funcionario->genero }}</td>
                                    <td>{{ $item->funcionario->status }}</td>
                                    <td>{{ $item->funcionario->bilheite }}</td>
                                    <td>{{ $item->funcionario->academico->especialidade->nome }}</td>
                                    <td>{{ $item->funcionario->academico->categoria->nome }}</td>
                                    <td>{{ $item->funcionario->academico->formacao_academica->nome }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('read: professores') || Auth::user()->can('update: estado'))
                                                <a title="Actualizar Tempo de Edição de notas" href="{{ route('web.funcionarios-actualizar-prazo-notas', Crypt::encrypt($item->funcionario->id) ) }}" class=" dropdown-item"><i class="fa fa-times"></i> Tempo de Edição de Notas </a>
                                                @endif

                                                @if ($escola->categoria == 'Privado')
                                                @if (Auth::user()->can('read: professores') || Auth::user()->can('update: estado'))
                                                <a title="Editar Funcionarios" id="{{ $item->funcionario->id }}" style="cursor: pointer" class="activar_funcionarios_id dropdown-item"><i class="fa fa-edit"></i> Activar/Desativar
                                                </a>
                                                @endif

                                                @if (Auth::user()->can('update: professores'))
                                                <a title="Editar Funcionarios" href="{{ route('shcools.professores-edit', Crypt::encrypt($item->funcionario->id)) }}" class="editar_funcionarios_id dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                                @endif

                                                @if (Auth::user()->can('delete: professores'))
                                                <a title="excluir Funcionarios" id="{{ $item->funcionario->id }}" class="delete_funcionarios dropdown-item"><i class="fa fa-trash" style="cursor: pointer"></i> Excluir</a>
                                                @endif
                                                @endif

                                                @if (Auth::user()->can('read: professores'))
                                                <a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($item->funcionario->id)) }}" title="Visualizar Funcionarios" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
                                                @endif

                                                @if (Auth::user()->can('create: contrato'))
                                                @if ($escola->categoria == 'Privado')
                                                <a href="{{ route('web.funcionarios-criar-contrato', Crypt::encrypt($item->funcionario->id)) }}" title="Criar um Controto Funcionarios" id="{{ $item->funcionario->id }}" class="criar_contrato dropdown-item"><i class="fa fa-eye"></i> Criar Contrato</a>

                                                @endif
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
                    <!-- /.card-body -->
                </div>
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

        // editar update
        $(document).on('click', '.delete_funcionarios', function(e) {
            e.preventDefault();

            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas remover esta informação"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Apagar Estes dados!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "DELETE"
                        , url: "excluir-professores/" + novo_id
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });

        });

        $(document).on('click', '.activar_funcionarios_id', function(e) {
            e.preventDefault();

            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas Activar este Professor"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Desejo Activa-ló!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "GET"
                        , url: "activar-professores/" + novo_id
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });

        });

    });

    $(function() {
        $("#carregarTabelaFuncionarios").DataTable({
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
