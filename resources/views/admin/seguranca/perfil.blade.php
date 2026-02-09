@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Meu Perfil</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Perfil</a></li>
                    <li class="breadcrumb-item active">Perfil</li>
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
                    <h5><i class="fas fa-info"></i> Configuração do perfil do usuário e visualização das suas informações.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/images/user.png') }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ $usuario->nome }}</h3>

                        <p class="text-muted text-center text-uppercase">TIPO DE ACESSO: <strong>{{ $usuario->acesso }}</strong></p>

                        <a href="#" class="btn btn-primary btn-block"><b>Editar</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                
                @if ($escola->modulo != "Basico")
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h6>Cor do Fundo</h6>
                        </div>
                        <div class="card-body">
                            <div>
                                <a href="{{ route('web.actualizar-cor-fundo', 'bg-info') }}" class="bg-info px-4 py-2 mx-2 border-dark"></a>
                                <a href="{{ route('web.actualizar-cor-fundo', 'bg-danger') }}" class="bg-danger px-4 py-2 mx-2 border-dark"></a>
                                <a href="{{ route('web.actualizar-cor-fundo', 'bg-warning') }}" class="bg-warning px-4 py-2 mx-2 border-dark"></a>
                                <a href="{{ route('web.actualizar-cor-fundo', 'bg-primary') }}" class="bg-primary px-4 py-2 mx-2 border-dark"></a>
                                <a href="{{ route('web.actualizar-cor-fundo', 'bg-dark') }}" class="bg-dark px-4 py-2 mx-2 border-dark"></a>
                                <a href="{{ route('web.actualizar-cor-fundo', 'bg-success') }}" class="bg-success px-4 py-2 mx-2 border-dark"></a>
                            </div>
                        </div>
    
                        <div class="card-header">
                            <h6>Cor do Fundo actual</h6>
                        </div>
    
                        <div class="card-body">
                            <span href="" class="{{ Auth::user()->color_fundo }} px-4 py-2 mx-2"></span>
                        </div>
    
                        <div class="card-footer"></div>
                    </div>
                @endif
                
                @if ($escola->modulo != "Basico")
                    <div class="card">
                        <div class="card-header">
                            <h6>Definir cor do cartão de estudante</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <p>Cor do Fundo</p>
                                    <span style="background-color: {{ $escola->cor_cartao }};border: 1px solid #000" class="px-4 py-2 mx-2"></span>
                                </div>
                                <div class="col-12 col-md-4">
                                    <p>Cor da Letra</p>
                                    <span style="background-color: {{ $escola->cor_letra_cartao }};border: 1px solid #000" class="px-4 py-2 mx-2"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button data-toggle="modal" data-target="#modalDefinirCorCartao" class="btn btn-primary btn-block">Definir Cor</button>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h6>Definir Tipo de Impressão(Para documentos)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <p>Tipo Impressão Actual</p>
                                @if (Auth::user()->impressora == "Normal")
                                <span class="text-uppercase">IMPRESSÃO A4 ou {{ Auth::user()->impressora }}</span>
                                @else
                                <span class="text-uppercase">IMPRESSÃO A {{ Auth::user()->impressora }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button data-toggle="modal" data-target="#modalDefinirTipoImpressao" class="btn btn-primary btn-block">Mudar Tipo Impressão</button>
                    </div>
                </div>

                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#dadosEncarregado" data-toggle="tab">Dodos Pessoas</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="dadosEncarregado">
                                        <form class="form-horizontal" id="formulario_editacao_de_dados" action="{{ route('web.editar-perfil') }}" method="post">
                                            @csrf
                                            <div class="form-group row">
                                                <label for="nome" class="col-sm-2 col-form-label text-end">Nome Completo</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control nome" name="nome" value="{{ $usuario->nome }}" id="nome" placeholder="Nome Completo">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="email" class="col-sm-2 col-form-label text-end">E-mail</label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control email" name="email" value="{{ $usuario->email }}" id="email" placeholder="E-mail">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="usuario" class="col-sm-2 col-form-label text-end">Usuário</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control usuario" name="usuario" value="{{ $usuario->nome }}" id="usuario" placeholder="Telefone principal">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="telefone" class="col-sm-2 col-form-label text-end">Telefone</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control telefone" name="telefone" value="{{ $usuario->telefone }}" id="telefone" placeholder="Telefone">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 col-form-label text-end">Acesso</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" disabled value="{{ $usuario->acesso }}" id="inputEmail" placeholder="data nascimento">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 col-form-label text-end">Número Avaliações</label>
                                                <div class="col-sm-10">
                                                    <select name="numero_avaliacoes" id="" class="form-control">
                                                        <option value="1" {{ $usuario->numero_avaliacoes == '1' ? 'selected' : ''  }}>1</option>
                                                        <option value="2" {{ $usuario->numero_avaliacoes == '2' ? 'selected' : ''  }}>2</option>
                                                        <option value="3" {{ $usuario->numero_avaliacoes == '3' ? 'selected' : ''  }}>3</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" form="formulario_editacao_de_dados" class="btn btn-primary">Actualizar dados</button>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    
                    @if ($escola->modulo != "Basico")
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Definir o processo de pagamento de Serviços</h6>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th width="100px">Id</th>
                                                <th>Designação</th>
                                                <th width="200px">Estado</th>
                                                <th style="width: 150px">Acções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#</td>
                                                <td>SECRETÁRIA</td>
                                                @if ($escola->processo_pagamento_servico == 'Secretaria')
                                                <td><i class="fas fa-check text-success"></i></td>
                                                @else
                                                <td><i class="fas fa-times text-danger"></i></td>
                                                @endif
                                                <td><a href="{{ route('web.definir-processo-pagamento', ['estado' => 'Secretaria']) }}" class="btn btn-primary">Mudar Processo</a></td>
                                            </tr>
                                            <tr>
                                                <td>#</td>
                                                <td>FINANCEIRA ou TESORARIA</td>
                                                @if ($escola->processo_pagamento_servico == 'Financeira')
                                                <td><i class="fas fa-check text-success"></i></td>
                                                @else
                                                <td><i class="fas fa-times text-danger"></i></td>
                                                @endif
                                                <td><a href="{{ route('web.definir-processo-pagamento', ['estado' => 'Financeira']) }}" class="btn btn-primary">Mudar Processo</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if ($escola->modulo != "Basico")
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Definir o processo de aprovação de estudantes</h6>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th width="100px">Id</th>
                                                <th>Designação</th>
                                                <th width="200px">Estado</th>
                                                <th style="width: 150px">Acções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#</td>
                                                <td>FAZEREM PROVAS DE EXAME DE ACESSO</td>
                                                @if ($escola->processo_admissao_estudante == 'Prova')
                                                <td><i class="fas fa-check text-success"></i></td>
                                                @else
                                                <td><i class="fas fa-times text-danger"></i></td>
                                                @endif
                                                <td><a href="{{ route('web.definir-processo-admissao-estudante', ['estado' => 'Prova']) }}" class="btn btn-primary">Mudar Processo</a></td>
                                            </tr>
                                            <tr>
                                                <td>#</td>
                                                <td>PROCESSO NORMAL (SEM PROVAS)</td>
                                                @if ($escola->processo_admissao_estudante == 'Normal')
                                                <td><i class="fas fa-check text-success"></i></td>
                                                @else
                                                <td><i class="fas fa-times text-danger"></i></td>
                                                @endif
                                                <td><a href="{{ route('web.definir-processo-admissao-estudante', ['estado' => 'Normal']) }}" class="btn btn-primary">Mudar Processo</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
    
                                </div>
    
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <!-- /.col -->
        </div>

        {{-- cadastrar principal Ano Lectivo --}}
        <div class="modal fade" id="modalDefinirCorCartao">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Definir nova cor do cartão de Estudante</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="cor_fundo">Escolher cor do fundo</label>
                                <input type="color" name="cor_fundo" value="{{ $escola->cor_cartao }}" class="form-control cor_fundo">
                                <span class="text-danger error-text cor_fundo"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="cor_letra">Cor das Letras</label>
                                <input type="color" name="cor_letra" value="{{ $escola->cor_letra_cartao }}" class="form-control cor_letra">
                                <span class="text-danger error-text cor_letra"></span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvarNovaCor">Salvar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        {{-- cadastrar principal Ano Lectivo --}}
        <div class="modal fade" id="modalDefinirTipoImpressao">
            <div class="modal-dialog modal-xs">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Definir Tipo de Impressão</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label for="impressora" class="form-label">Impressão Principal <span class="text-danger">*</span></label>
                        <div class="form-group mb-3 col-md-12 col-12">
                            <select name="impressora" id="impressora" class="select2 form-control impressora" style="width: 100%">
                                <option value="Normal" {{ Auth::user()->impressora == 'Normal' ? 'selected' : '' }}>Normal ou A4</option>
                                <option value="Ticket" {{ Auth::user()->impressora == 'Ticket' ? 'selected' : '' }}>Ticket</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvarTipoImpressora">Salvar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </div><!-- /.container-fluid -->
</section>

@endsection


@section('scripts')
<script>
    $(function() {

        // Cadastrar
        $(document).on('click', '.salvarNovaCor', function(e) {
            e.preventDefault();

            var data = {
                'cor_fundo': $('.cor_fundo').val()
                , 'cor_letra': $('.cor_letra').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.definir-cor-cartao') }}"
                , data: data
                , dataType: "json"
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
        });

        // Cadastrar
        $(document).on('click', '.salvarTipoImpressora', function(e) {
            e.preventDefault();

            var data = {
                'impressora': $('.impressora').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.definir-tipo-impressao') }}"
                , data: data
                , dataType: "json"
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
        });

    });

</script>
@endsection
