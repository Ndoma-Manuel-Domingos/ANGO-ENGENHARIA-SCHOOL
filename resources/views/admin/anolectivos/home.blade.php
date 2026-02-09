@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Configurações</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">gestão de anos lectivos</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de Anos Lectivos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            <button id="openModal"
                class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">add</span>
                <span>Criar Novo Ano</span>
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
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input class="w-full h-10 pl-9 pr-4 text-sm bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary" id="pesquisa_por_designacao" placeholder="Search by name or code..." type="text" />
                </div>
                <select id="status" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
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
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Serie</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Designação</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Inicio</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Final</th>
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
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitle">Criar Novo Ano</h3>
                    <button id="closeModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form class="p-6 space-y-5" action="{{ route('anos-lectivos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="data_id" name="data_id">
                    <div>
                        <label for="designacao" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Designação</label>
                        <input id="designacao" name="designacao" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="informe a designação do ano" type="text" />
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Estado</label>
                        <select id="status" name="status" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Todos</option>
                            <option value="activo" selected>Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label for="inicio" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Data Início</label>
                            <input id="inicio" name="inicio" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Data de Início" type="date" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="final" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Data Final</label>
                            <input id="final" name="final" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Data de Final" type="date" />
                        </div>
                    </div>
               
                    <div class="flex items-center justify-end gap-3 pt-10">
                        <button
                            onclick="document.getElementById('courseModal').classList.add('hidden')"
                            class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all"
                            type="button">
                            Cancelar
                        </button>
                        <button id="botao_submiter" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all" type="submit">
                            Novo Ano
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    {{-- Visualizações --}}
    <div id="modalView" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <div class="relative bg-white dark:bg-sidebar-dark w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            
            <div id="headerData"></div>
         
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-8 flex-1">
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span>Cursos
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Abreviação</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Curso</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Tipo</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="tbody_cursos">
                                {{-- carregar Ajax --}}
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span> Classes
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Tipo</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Classe</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Nota de Avaliação</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_classes" class="divide-y divide-slate-100 dark:divide-slate-800">
                                {{-- carregar Ajax --}}
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span> Turnos
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Turno</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Horario</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Estado</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_turnos" class="divide-y divide-slate-100 dark:divide-slate-800">
                                {{--  carregar Ajax --}}
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span> Salas
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Sala</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Tipo</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Estado</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_salas" class="divide-y divide-slate-100 dark:divide-slate-800">
                                {{--  carregar Ajax --}}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        
            <div class="p-6 border-t border-slate-100 dark:border-slate-800 flex justify-end shrink-0">
                <button id="closeModalView" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    Fechar
                </button>
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
    const modalView = document.getElementById('modalView');
    const closeModalView = document.getElementById('closeModalView');

    openModalBtn.addEventListener('click', () => {
        $("#data_id").val("")
        modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => { modal.classList.add('hidden'); });
    closeModalView.addEventListener('click', () => { modalView.classList.add('hidden'); });

    // Fechar ao clicar no fundo escuro
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    $(document).ready(function(){
        load();
    });
            
    $("#pesquisa_por_designacao").on('input', function () {
        load(1);
    });
    
    $("#status").change(function(){
        load(1);
    });
    
    $("#paginacao").change(function(){
        load(1);
    });
    
    function load(page=1){
        $.ajax({
            type: "GET",
            url: "/anos-lectivos",
            data: {
                page: page,
                designacao: $("#pesquisa_por_designacao").val(),
                status: $("#status").val(),
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
                    
                    const ROUTE_CONFIG_ANO_LECTIVO = `{{ route('web.carregamento-configuracao-ano-lectivo', ':id') }}`.replace(':id', s.id);
                    
                    let activarDesactivarBtn = s.status === 'activo'
                        ? `<button class="mudar-status-record p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Desativar" id="${s.id}">
                                <span class="material-symbols-outlined text-xl">cancel</span>
                            </button>`
                        : `<button class="mudar-status-record p-2 text-slate-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Ativar" id="${s.id}">
                                <span class="material-symbols-outlined text-xl">check_circle</span>
                            </button>`;
                
                    rows += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        o
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-500 dark:text-white">${s.serie}</p>
                                        <p class="text-xs text-slate-500">${s.serie}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.ano}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 ${s.status === 'activo' ? 'bg-primary/10 text-primary' : 'bg-red-500/10 text-red-600'} text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.inicio}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.final}</p>
                            </td>
                         
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-1">
                                    ${activarDesactivarBtn}
                                    <a href="${ROUTE_CONFIG_ANO_LECTIVO}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Configurar">
                                        <span class="material-symbols-outlined text-xl">settings</span>
                                    </a>
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.id})">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="Editar" onclick="edit(${s.id})">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all  delete-record" title="Eliminar"  data-id="${s.id}">
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
                    url: `{{ route('anos-lectivos.destroy', ':id') }}`.replace(':id', recordId), 
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
    
    function edit(id) {
        $.ajax({
            url: `/anos-lectivos/${id}/edit`,
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
                $("#designacao").val(data.ano);
                $("#status").val(data.status);
                $("#inicio").val(data.inicio);
                $("#final").val(data.final);
    
                $("#modalTitle").text("Editar Ano");
                $("#botao_submiter").text("Editar Ano");
    
                // abrir modal (Tailwind / AdminLTE)
                modal.classList.remove('hidden');
            },
            error: function (xhr) {
                Swal.close();
    
                let message = "Erro ao carregar dados do Ano";
    
                if (xhr.status === 404) {
                    message = "Ano não encontrada";
                } else if (xhr.status === 403) {
                    message = "Você não tem permissão para esta ação";
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
    
                showMessage("Erro", message, "error");
            }
        });
    }
    
    function show(id)
    {
        $.ajax({
            url: `/anos-lectivos/${id}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                progressBeforeSend("Carregando dados...");
            },
            success: function (data) {
                Swal.close();
                
                modalView.classList.remove('hidden');
                
                let h = `
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">${data.ano.ano}</h3>
                            <p class="text-sm text-slate-500 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full ${data.ano.status === 'activo' ? ' bg-emerald-500 ': ' bg-red-500 '} "></span> ${data.ano.status}
                                </div>
                            </p>
                        </div>
                        <button onclick="document.getElementById('modalView').classList.add('hidden')"
                            class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                `;
                
                $("#headerData").html(h);
                
                let rows_cursos = "";
                data.cursos.forEach(s => {
                    rows_cursos += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-2 text-sm font-bold text-primary">${s.curso.abreviacao}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.curso.curso}</td>
                            <td class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 text-right">${s.curso.tipo}</td>
                            <td class="px-4 py-2 flex justify-end gap-1">
                                <a class="excluir_cursos_ano_lectivo p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" id="${s.id}">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </td>
                        </tr>`;
                    }
                );
                $("#tbody_cursos").html(rows_cursos);
                
                let rows_classes = "";
                data.classes.forEach(s => {
                    rows_classes += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-xs font-medium text-slate-500">${s.classe.tipo}</td>
                            <td class="px-4 py-3 text-sm font-bold">${s.classe.classes}</td>
                            <td class="px-4 py-3 text-sm font-bold">${s.classe.tipo_avaliacao_nota}</td>
                            <td class="px-4 py-2 flex justify-end gap-1">
                                <a class="excluir_classes_ano_lectivo p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" id="${s.id}">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </td>
                        </tr>
                    `;
                })
                $("#tbody_classes").html(rows_classes);
                
                let rows_salas = "";
                data.salas.forEach(s => {
                    rows_salas += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm font-bold">${s.sala.salas}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">${s.sala.tipo}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400 text-right">${s.sala.status}</td>
                            <td class="px-4 py-2 flex justify-end gap-1">
                                <a class="excluir_salas_ano_lectivo p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" id="${s.id}">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </td>
                        </tr>
                    `;
                })
                $("#tbody_salas").html(rows_salas);
                
                let rows_turnos = "";
                data.turnos.forEach(s => {
                    rows_turnos += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-sm font-bold">${s.turno.turno}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">${s.turno.horario}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400 text-right">${s.turno.status}</td>
                            <td class="px-4 py-2 flex justify-end gap-1">
                                <a class="excluir_turnos_ano_lectivo p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" id="${s.id}">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </td>
                        </tr>
                    `;
                })
                $("#tbody_turnos").html(rows_turnos);
    
                // abrir modal (Tailwind / AdminLTE)
            },
            error: function (xhr) {
                Swal.close();
    
                let message = "Erro ao carregar dados do Ano";
    
                if (xhr.status === 404) {
                    message = "Ano não encontrada";
                } else if (xhr.status === 403) {
                    message = "Você não tem permissão para esta ação";
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
    
                showMessage("Erro", message, "error");
            }
        });
    }
 
    function configurar(id) {alert("configurar")}
    
    excluirRegistro('.excluir_classes_ano_lectivo', `{{ route('ano-lectivo.excluir-classes-ano-lectivo', ':id') }}`);
    excluirRegistro('.excluir_cursos_ano_lectivo', `{{ route('ano-lectivo.excluir-cursos-ano-lectivo', ':id') }}`);
    excluirRegistro('.excluir_turnos_ano_lectivo', `{{ route('ano-lectivo.excluir-turnos-ano-lectivo', ':id') }}`);
    excluirRegistro('.excluir_salas_ano_lectivo', `{{ route('ano-lectivo.excluir-salas-ano-lectivo', ':id') }}`);
    
    bindStatusUpdate('.mudar-status-record', `{{ route('web.actualizar-status', ':id') }}`);
    
    function limpar_campos()
    {   
        $("#data_id").val("");
        $("#designacao").val("");    
        $("#status").val("");
        $("#inicio").val("");
        $("#final").val("");
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
            
    function exportData(documentType) {
        // Reaproveitando os filtros da loadSeries
        const data = {
            documentType: documentType // "excel" ou "pdf"
        };
    
        $.ajax({
            type: "GET",
            url: `/anos-lectivos-export`, // rota que vai gerar o arquivo
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
                let filename = documentType === 'excel' ? 'anos-lectivos.xlsx' : 'anos-lectivos.pdf';
                
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
            
    $("#btnExportExcel").click(() => exportData("excel"));
    $("#btnExportPDF").click(() => exportData("pdf"));

</script>
@endsection
