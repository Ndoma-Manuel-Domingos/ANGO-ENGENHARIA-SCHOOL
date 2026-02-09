@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Credito Educacional</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">gestão de instituições</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de instituições</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            @if (Auth::user()->can('create: instituicao'))
            <button id="openModal"
                class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">add</span>
                <span>Criar Nova Instituição</span>
            </button>
            @endif
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
                    <input class="w-full h-10 pl-9 pr-4 text-sm bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary" id="designacao_geral" placeholder="Search by name or code..." type="text" />
                </div>
                <select id="data_status" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
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
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Designação</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">E-mail</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Director</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Endereço</th>
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
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitle">Criar Nova Instituição</h3>
                    <button id="closeModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form class="p-6 space-y-5" id="createInstituicao" action="{{ route('instituicoes.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="designacao" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Designação</label>
                        <input id="designacao" name="designacao" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="informe a designação do bolsa" type="text" />
                    </div>
                    <div>
                        <label for="nif" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">NIF</label>
                        <input id="nif" name="nif" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="informe o codigo" type="number" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">E-mail</label>
                        <input id="email" name="email" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="informe o e-mail" type="text" />
                    </div>
                    <div>
                        <label for="director" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Director</label>
                        <input id="director" name="director" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="informe o director" type="text" />
                    </div>
                    
                    <div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Estado</label>
                            <select id="status" name="status" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Todos</option>
                                <option value="activo" selected>Activo</option>
                                <option value="desactivo">Desactivo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="tipo" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tipo</label>
                            <select id="tipo" name="tipo" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Todos</option>
                                <option value="Interna" selected>Interna</option>
                                <option value="Externa">Externa</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="endereco" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Endereço</label>
                        <textarea name="endereco" id="endereco" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none" placeholder="Informe o endereço" rows="4"></textarea>
                    </div>
                    
                    <input type="hidden" id="data_id" name="data_id">
                    
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button onclick="document.getElementById('courseModal').classList.add('hidden')" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all"
                            type="button">
                            Cancelar
                        </button>
                        @if (Auth::user()->can('create: instituicao'))
                        <button id="botao_submiter" form="createInstituicao" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all" type="submit">
                            Nova Instituição
                        </button>
                        @endif
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
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span> Bolsas Associadas
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Bolsa</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Codigo</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Desconto</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="tbody_instituicao">
                                {{-- carregar Ajax --}}
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
    
    <div id="modalViewAssociacao" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-sidebar-dark w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitleDisciplina">Adicionar Disciplinas</h3>
                    <button id="CloseModalViewAssociacao" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form class="p-6 space-y-5" id="formAssociarBolsa" action="{{ route('web.instituicoes-store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="instituicao_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Institiuição</label>
                        <select id="instituicao_id" name="instituicao_id" style="width: 100%" class="select2 w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Escolher</option>
                            @foreach ($instituicoes as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <div>
                        <label for="bolsa_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Bolsas</label>
                        <select id="bolsa_id" name="bolsa_id" style="width: 100%;" class="select2 w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Escolher as bolsas</option>
                            @foreach ($bolsas as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="desconto" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">TAXA %</label>
                            <input id="desconto" name="desconto" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Informe a taxa" type="number" />
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 pt-10">
                        <button onclick="document.getElementById('modalViewAssociacao').classList.add('hidden')" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" type="button">
                            Cancelar
                        </button>
                        <button type="submit" form="formAssociarBolsa" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all">
                            Salvar
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
        
        const modalView = document.getElementById('modalView');
        const closeModalView = document.getElementById('closeModalView');
        
        const modalViewAssociacao = document.getElementById('modalViewAssociacao');
        const CloseModalViewAssociacao = document.getElementById('CloseModalViewAssociacao');
        
        closeModalView.addEventListener('click', () => { modalView.classList.add('hidden'); });
        CloseModalViewAssociacao.addEventListener('click', () => { modalViewAssociacao.classList.add('hidden'); });
    
        openModalBtn.addEventListener('click', () => {
            $("#data_id").val("")
            modal.classList.remove('hidden');
        });
    
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    
        // Fechar ao clicar no fundo escuro
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
            
        $(document).ready(function(){
            load();
        });
        
        $("#designacao_geral").on('input', function () {
            load(1);
        });
        
        $("#data_status").change(function(){
            load(1);
        });
        
        $("#paginacao").change(function(){
            load(1);
        });
        
        function load(page=1){
            $.ajax({
                type: "GET",
                url: "/instituicoes",
                data: {
                    page: page,
                    designacao_geral: $("#designacao_geral").val(),
                    data_status: $("#data_status").val(),
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
                                            <p class="font-bold text-slate-500 dark:text-white">${s.nome}</p>
                                            <p class="text-xs text-slate-500">${s.nif}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex px-2.5 py-1 ${s.status === 'activo' ? 'bg-primary/10 text-primary' : 'bg-red-500/10 text-red-600'} text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.email}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.director}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.tipo}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.endereco}</p>
                                </td>
                             
                                <td class="px-6 py-5">
                                    <div class="flex justify-end gap-1">
                                        <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="associcao(${s.id})">
                                            <span class="material-symbols-outlined text-xl">handshake</span>
                                        </button>
                                        <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.id})">
                                            <span class="material-symbols-outlined text-xl">visibility</span>
                                        </button>
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
        
        $(document).ready(function() {
            $('#createInstituicao').on('submit', function(e) {
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
        
                
        $(document).ready(function() {
            $('#formAssociarBolsa').on('submit', function(e) {
                e.preventDefault(); // Impede o envio tradicional do formulário
    
                let form = $(this);
                let formData = form.serialize(); // Serializa os dados do formulário
    
                $.ajax({
                    url: form.attr('action'), // URL do endpoint no backend
                    method: 'post', // Método HTTP definido no formulário
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
                        modalViewAssociacao.classList.add('hidden');
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

        function limpar_campos()
        {   
            $("#data_id").val("");
            $("#designacao").val("");    
            $("#email").val("");
            $("#status").val("");
            $("#director").val("");
            $("#nif").val("");
            $("#endereco").val("");
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
                        url: `{{ route('instituicoes.destroy', ':id') }}`.replace(':id', recordId), 
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
            
        // remover disciplina do horario da turma
        $(document).on('click', '.deletar_bolsa', function(e) {
            e.preventDefault();
            let recordId = $(this).data('id'); 
            
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
                        , url: `{{ route('web.instituicoes-delete', ':id') }}`.replace(':id', recordId)
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

        function edit(id) {
            $.ajax({
                url: `/instituicoes/${id}/edit`,
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
                    $("#designacao").val(data.nome);
                    $("#nif").val(data.nif);
                    $("#email").val(data.email);
                    $("#director").val(data.director);
                    $("#status").val(data.status);
                    $("#tipo").val(data.tipo);
                    $("#endereco").val(data.endereco);
                    
                    $("#modalTitle").text("Editar instituição");
                    $("#botao_submiter").text("Editar instituição");
        
                    // abrir modal (Tailwind / AdminLTE)
                    modal.classList.remove('hidden');
                },
                error: function (xhr) {
                    Swal.close();
        
                    let message = "Erro ao carregar dados da instituição";
        
                    if (xhr.status === 404) {
                        message = "Instituição não encontrada";
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
                url: `/instituicoes/${id}`,
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
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">${data.nome}</h3>
                                <p class="text-sm text-slate-500 font-medium">
                                    <div class="flex items-center gap-2">
                                        <span class="size-2 rounded-full ${data.status === 'activo' ? ' bg-emerald-500 ': ' bg-red-500 '} "></span> ${data.status}
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
                    
                    let bolsas = "";
                    data.bolsas.forEach(s => {
                        bolsas += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-2 text-sm font-medium">${s.bolsa.nome}</td>
                                <td class="px-4 py-2 text-sm font-medium">${s.bolsa.codigo}</td>
                                <td class="px-4 py-2 text-sm font-medium">${s.desconto}%</td>
                                <td class="px-4 py-2 flex justify-end gap-1">
                                    <a href="#" class="deletar_bolsa p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" data-id="${s.id}">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </a>
                                </td>
                            </tr>`;
                        }
                    );
                    $("#tbody_instituicao").html(bolsas);
                   
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
    
        function associcao(id)
        {
            $.ajax({
                url: `/instituicoes/${id}`,
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    progressBeforeSend("Carregando dados...");
                },
                success: function (data) {
                    Swal.close();
                        
                    $("#instituicao_id").val(data.id).trigger('change');
                    
                    modalViewAssociacao.classList.remove('hidden');
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
     
    </script>
@endsection
