@extends('layouts.escolas')

@section('content')

<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Configurações</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">turnos de classes</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de turnos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            <button id="openModal"
                class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">add</span>
                <span>Criar Novo Turno</span>
            </button>
            <a href="{{ route('paineis.administrativo') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Voltar</span>
            </a>
        </div>
    </div>

      
    <div class="bg-white dark:bg-sidebar-dark rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row gap-4 lg:items-center justify-between">
            <div class="flex flex-wrap gap-3">
                <div class="relative min-w-[240px]">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input class="w-full h-10 pl-9 pr-4 text-sm bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary" id="designacao_geral" placeholder="Search by name or code..." type="text" />
                </div>
                <select id="status_data" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="desactivo">Desactivo</option>
                </select>
                <select id="paginacao" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
                    <option value="">Registros</option>
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button id="btnExportExcel" type="button" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-green-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-success/30 hover:bg-green-500/90 hover:-translate-y-0.5 transition-all">
                    <span class="material-symbols-outlined">table_view</span>
                    <span>Imprimir Excel</span>
                </button>
                <button id="btnExportPDF" type="button" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-red-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-success/30 hover:bg-red-500/90 hover:-translate-y-0.5 transition-all">
                    <span class="material-symbols-outlined">picture_as_pdf</span>
                    <span>Imprimir PDF</span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Designação</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Vagas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Ano Lectivo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider text-right"> Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="tbody">
                    {{-- carregar dados --}}
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs text-slate-500 font-medium">
                Mostrando <span id="from" class="text-slate-900 dark:text-white">0</span> para <span id="to" class="text-slate-900 dark:text-white">0</span> de <span id="total" class="text-slate-900 dark:text-white">0</span> resultados
            </p>
            <div id="pagination"></div>
        </div>
    </div>
    
    <!-- modal -->
    <div id="courseModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div
                class="relative bg-white dark:bg-sidebar-dark w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitle">Criar Novo Turno</h3>
                    <button id="closeModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form class="p-6 space-y-5" action="{{ route('ano-lectivo-turnos.store') }}" method="POST">
                    @csrf
                   
                    <div>
                        <div class="flex flex-col gap-1">
                            <label class="text-sm font-semibold text-[#111318] dark:text-slate-200">Ano Lectivo</label>
                            <select name="ano_lectivo_id" class="form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10">
                                @if ($ano_lectivo)
                                <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="vagas" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Número de Vagas</label>
                            <input id="vagas" name="vagas" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Informe número de vagas" type="text" />
                        </div>
                    </div>
                    
                    <input type="hidden" id="data_id" name="data_id">
                    
                    <div>
                        <label for="turnos_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Turnos</label>
                        <select id="turnos_id" name="turnos_id[]" style="width: 100%" multiple class="select2 w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Selecione Turnos</option>
                            @foreach ($turnos as $item)
                            <option value="{{ $item->id }}">{{ $item->turno }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button onclick="document.getElementById('courseModal').classList.add('hidden')" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" type="button">
                            Cancelar
                        </button>
                        <button id="botao_submiter" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all" type="submit">
                            Novo Turno
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modal = document.getElementById('courseModal');

    openModalBtn.addEventListener('click', () => {
        $("#data_id").val("")
        modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
    
    $(document).ready(function(){
        load();
    });
     
    $("#designacao_geral").on('input', function () {
        load(1);
    });
    
    $("#paginacao").change(function(){
        load(1);
    });
        
    $("#status_data").change(function(){
        load(1);
    });
    
    function limpar_campos()
    {   
        $("#data_id").val("");
        $("#ano_lectivo_id").val("");    
        $("#vagas").val("");
        $("#turnos_id").val("");
    }

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if ($("#data_id").val() == null || $("#data_id").val() == "") {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + $("#data_id").val();
            }
         
            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    load();
                    modal.classList.add('hidden');
                    limpar_campos();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });
    });

    // Fechar ao clicar no fundo escuro
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    function load(page=1){
        $.ajax({
            type: "GET",
            url: "/ano-lectivo-turnos",
            data: {
                page: page,
                designacao_geral: $("#designacao_geral").val(),
                status_data: $("#status_data").val(),
                paginacao: $("#paginacao").val(),
            },
            dataType: "json",
            beforeSend: function () {
                // opcional: mostrar loader
                // progressBeforeSend("Carregando...");
         
            },
            success: function (res) {
                Swal.close();
                let rows = "";
                res.data.forEach(s => {
                    rows += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        o
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-500 dark:text-white">${s.turno.turno}</p>
                                        <p class="text-xs text-slate-500">${s.turno.turno}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 ${s.turno.status === 'activo' ? 'bg-primary/10 text-primary' : 'bg-red-500/10 text-red-600'} text-[11px] font-bold rounded-full uppercase tracking-tight">${s.turno.status}</span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.total_vagas}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.ano_lectivo.ano}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="Editar" onclick="edit(${s.id})">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all  delete-record" title="Eliminar" data-id="${s.id}">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                $("#tbody").html(rows);
                updateResultsInfo(res);
                paginate(res);
            },
            error: function (xhr) {
                Swal.close();
                console.error(xhr);
            }
        });
    }
    
    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('ano-lectivo-turnos.destroy', ':id') }}`.replace(':id', recordId), 
                    method: 'DELETE', 
                    data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        load();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });
    
    function exportData(documentType) {
        // Reaproveitando os filtros da loadSeries
        const data = {
            designacao_geral: $("#designacao_geral").val(),
            sala_status: $("#sala_status").val(),
            paginacao: $("#paginacao").val(),
            documentType: documentType // "excel" ou "pdf"
        };
    
        $.ajax({
            type: "GET",
            url: `/ano-lectivo/classes-ano-turno-export`, // rota que vai gerar o arquivo
            data: data,
            xhrFields: {
                responseType: 'blob' // importante para receber o arquivo
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // se Laravel
            },
            beforeSend: function() {
                progressBeforeSend("Gerando arquivo...");
            },
            success: function(blob, status, xhr) {
                Swal.close();
    
                // Pegando nome do arquivo do header
                let filename = documentType === 'excel' ? 'classes-ano-lectivo.xlsx' : 'classes-ano-lectivo.pdf';
                const disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    const match = disposition.match(/filename="?(.+)"?/);
                    if (match[1]) filename = match[1];
                }
    
                // Criar link para download
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: function(xhr) {
                Swal.close();
                console.error("Erro ao gerar arquivo", xhr);
            }
        });
    }
        
    function edit(id) {
        $.ajax({
            url: `/ano-lectivo-turnos/${id}/edit`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                progressBeforeSend("Carregando dados...");
            },
            success: function (data) {
                Swal.close();
    
                if (!data || !data.id) {
                    showMessage("Erro", "Dados inválidos recebidos do servidor", "error");
                    return;
                }
    
                $("#data_id").val(data.id);
                $("#vagas").val(data.total_vagas);
                $("#ano_lectivo_id").val(data.ano_lectivos_id);
                $("#turnos_id").val(data.turnos_id).trigger('change');
    
                $("#modalTitle").text("Editar Turno");
                $("#botao_submiter").text("Editar Turno");
    
                // abrir modal (Tailwind / AdminLTE)
                modal.classList.remove('hidden');
            },
            error: function (xhr) {
                Swal.close();
    
                let message = "Erro ao carregar dados da turno";
    
                if (xhr.status === 404) {
                    message = "Turno não encontrada";
                } else if (xhr.status === 403) {
                    message = "Você não tem permissão para esta ação";
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
    
                showMessage("Erro", message, "error");
            }
        });
    }
        
    $("#btnExportExcel").click(() => exportData("excel"));
    $("#btnExportPDF").click(() => exportData("pdf"));

</script>
@endsection









{{-- @extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Turnos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Turnos</li>
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
                    <div class="card-header">
                        @if (Auth::user()->can('create: turno'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalTurno">Novo Turno</a>
                        @endif
                        <a href="{{ route('web.turnos-pdf-ano-lectivo') }}" class="btn-danger btn float-end mx-1" target="_blink"> <i class="fas fa-pdf"></i> Imprimir PDF</a>
                        <a href="{{ route('web.turnos-excel-ano-lectivo') }}" class="btn-success btn float-end mx-1" target="_blink"> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Turno</th>
                                    <th>Totel de Vagas</th>
                                    <th>Horário</th>
                                    <th>Status</th>
                                    <th style="width: 170px;"> Acções </th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @if (count($turnos))
                                @foreach ($turnos as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>{{ $item->total_vagas }}</td>
                                    <td>{{ $item->turno->horario }}</td>
                                    <td>{{ $item->turno->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: turno'))
                                                <a title="Editar Turno" id="{{ $item->id }}" class="editar dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('delete: turno'))
                                                <a href="#" title="Excluir Turno" id="{{ $item->id }}" class="dropdown-item deleteModal"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif
                                                <div class="dropdown-divider"></div>
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

<div class="modal fade" id="modalTurno">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('web.cadastrar-turnos-ano-lectivo') }}" id="formCreate" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cadastrar Turnos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="turnos_id">Turnos <span class="text-danger">*</span></label>
                            <select name="turnos_id[]" class="form-control turnos_id select2" id="turnos_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Turnos" multiple="multiple">
                                <option value="">Selecione Turno</option>
                                @foreach ($lista_turnos as $item)
                                <option value="{{ $item->id }}">{{ $item->turno }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text turnos_id_error"></span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="turnos_id">Número de Vagas</label>
                            <input type="number" value="0" name="total_vagas" class="form-control total_vagas" id="total_vagas" placeholder="Número de vagas para estu turno">
                            <span class="text-danger error-text total_vagas_error"></span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="ano_lectivo_id">Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivo_id" class="form-control ano_lectivo_id" id="ano_lectivo_id">
                                @if ($ano_lectivo)
                                <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option>
                                @endif
                            </select>
                            <span class="text-danger error-text ano_lectivo_id_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalTurnoUpdate">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('web.turnos-update-ano-lectivo') }}" id="formUpdate" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cadastrar Turnos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="turnos_id">Turnos</label>
                            <select name="turnos_id" class="form-control turnos_id turno_id_edit" id="turnos_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Turnos">
                                <option value="">Selecione Turno</option>
                                @foreach ($lista_turnos as $item)
                                <option value="{{ $item->id }}">{{ $item->turno }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text turnos_id_error"></span>
                        </div>

                        <input type="hidden" name="id" class="id">

                        <div class="form-group col-md-4">
                            <label for="turnos_id">Número de Vagas</label>
                            <input type="number" name="total_vagas" class="form-control total_vagas" id="total_vagas" placeholder="Número de vagas para estu turno">
                            <span class="text-danger error-text total_vagas_error"></span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="ano_lectivo_id">Ano Lectivo</label>
                            <select name="ano_lectivo_id" class="form-control ano_lectivo_id" id="ano_lectivo_id">
                                @if ($ano_lectivo)
                                <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option>
                                @endif
                            </select>
                            <span class="text-danger error-text ano_lectivo_id_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function() {
        const tabelas = [
            "#carregarTabela",
        ];
        tabelas.forEach(inicializarTabela);
    
        ajaxFormSubmit('#formCreate');
        ajaxFormSubmit('#formUpdate');
        
        excluirRegistro('.deleteModal', `{{ route('ano-lectivo.excluir-salas-ano-lectivo', ':id') }}`);
    
        $(document).on('click', '.editar', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalTurnoUpdate").modal("show");

            $.ajax({
                type: "GET"
                , url: "turnos-ano-lectivo/" + novo_id + "/editar/"
                , beforeSend: function() {
                    // // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.turno_id_edit').html("");
                    $('.turno_id_edit').append('<option value="' + response.dados.turno.id + '" selected>' + response.dados.turno.turno + '</option>');
                    for (let index = 0; index < response.turnos.length; index++) {
                        $('.turno_id_edit').append('<option value="' + response.turnos[index].id + '">' + response.turnos[index].turno + '</option>');
                    }
                    $('.total_vagas').val(response.dados.total_vagas)
                    $('.id').val(response.dados.id)
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });
    });

</script>
@endsection --}}
