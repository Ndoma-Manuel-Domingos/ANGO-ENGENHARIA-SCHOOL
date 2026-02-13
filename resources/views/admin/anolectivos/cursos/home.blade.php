@extends('layouts.escolas')

@section('content')

<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Configurações</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">gestão de cursos</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de cursos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            <button id="openModal"
                class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">add</span>
                <span>Criar Novo Curso</span>
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
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Cod</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Curso</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Total de Vagas</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Tipo</th>

                            @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Coordenador</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Faculdade</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Candidatura</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Duração</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Nº Max. Cadeira</th>
                            @endif

                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Área de Formação</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider text-right"> Actions </th>
                        </tr>
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
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitle">Criar Novo Curso</h3>
                    <button id="closeModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form class="p-6 space-y-5" action="{{ route('ano-lectivo-cursos.store') }}" method="POST">
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
                        <label for="cursos_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Cursos</label>
                        <select id="cursos_id" name="cursos_id[]" style="width: 100%" multiple class="select2 w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Selecione Cursos</option>
                            @foreach ($cursos as $item)
                            <option value="{{ $item->id }}">{{ $item->curso }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label for="duracao" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Duração</label>
                            <input id="duracao" name="duracao" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Informe número de vagas" type="number" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="max_cadeira" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">N. Máximo de cadeiras</label>
                            <input id="max_cadeira" name="max_cadeira" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Numero maximo de cadeiras" type="number" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="vagas" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Número de Vagas</label>
                            <input id="vagas" name="vagas" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Informe número de vagas" type="text" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label for="coordenador_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Coordenadores</label>
                            <select id="coordenador_id" name="coordenador_id" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Todos</option>
                                @foreach ($lista_funcionarios as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="faculdade_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Faculdades</label>
                            <select id="faculdade_id" name="faculdade_id" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Todos</option>
                                @foreach ($faculdades as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="candidatura_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Candidaturas</label>
                            <select id="candidatura_id" name="candidatura_id" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Todos</option>
                                @foreach ($candidaturas as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <input type="hidden" id="data_id" name="data_id">
                                        
                    <div>
                        <label for="vantagens" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Vantagens do curso <small class="text-red-400">Separar por ponto e vírgua(;)</small></label>
                        <textarea name="vantagens" id="vantagens" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none" placeholder="Vantagens do curso" rows="4"></textarea>
                    </div>
                    
                    <div>
                        <label for="area_saidas" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Aréa da saídas <small class="text-red-400">Separar por ponto e vírgua(;)</small></label>
                        <textarea name="area_saidas" id="area_saidas" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none" placeholder="Aréa da saídas" rows="4"></textarea>
                    </div>
                   
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button onclick="document.getElementById('courseModal').classList.add('hidden')" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" type="button">
                            Cancelar
                        </button>
                        <button id="botao_submiter" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all" type="submit">
                            Nova Curso
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
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span> Disciplinas
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Codigo</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Disciplina</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Abreviação</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Categoria</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Peso</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="tbody_cursos">
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
    
    {{-- modal disciplina cursos  --}}
    <div id="addCourseModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div
                class="relative bg-white dark:bg-sidebar-dark w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitleDisciplina">Adicionar Disciplinas</h3>
                    <button id="closeAddCourseModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form class="p-6 space-y-5" action="#" method="POST">
                    <div>
                        <label for="categoria_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Componentes de formação</label>
                        <select id="categoria_id" name="categoria_id" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Selecione componentes</option>
                            @foreach ($categorias as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <input type="hidden" id="curso__id" name="curso__id">
                    
                    <div>
                        <label for="disciplina_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Disciplinas</label>
                        <select id="disciplina_id" name="disciplina_id[]" style="width: 100%" multiple class="select2 w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Selecione disciplinas</option>
                            @foreach ($disciplinas as $disciplina)
                            <option value="{{ $disciplina->disciplina->id }}">{{ $disciplina->disciplina->disciplina }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="peso" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Peso</label>
                            <input id="peso" name="peso" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Informe o Peso da disciplina no curso" type="text" />
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all" type="button">
                            Cancelar
                        </button>
                        <button id="update_disciplinas_cursos" class="h-11 px-6 bg-secondary text-white rounded-xl font-bold text-sm shadow-lg shadow-secondary/30 hover:bg-secondary/90 transition-all">
                            Editar Disciplina
                        </button>
                        <button id="cadastrar_disciplinas_cursos" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all">
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

    const escolaTipo = "{{ $escola->ensino->nome }}";
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    
    const closeAddCourseModal = document.getElementById('closeAddCourseModal');
        
    const modal = document.getElementById('courseModal');
    const modalView = document.getElementById('modalView');
    const closeModalView = document.getElementById('closeModalView');
    
    const addCourseModal = document.getElementById('addCourseModal');

    openModalBtn.addEventListener('click', () => {
        $("#data_id").val("")
        modal.classList.remove('hidden');
    });
    
    $("#update_disciplinas_cursos").css({ 'display': 'none' });

    closeAddCourseModal.addEventListener('click', () => { addCourseModal.classList.add('hidden'); });
    
    closeModalBtn.addEventListener('click', () => { modal.classList.add('hidden'); });
    closeModalView.addEventListener('click', () => { modalView.classList.add('hidden'); });

    // Fechar ao clicar no fundo escuro
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
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

    $(document).ready(function(){
        load();
    });

    function load(page=1){
        $.ajax({
            type: "GET",
            url: "/ano-lectivo-cursos",
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
                    let colunasSuperior = '';
                    
                    if (escolaTipo === 'Superior') {
                        colunasSuperior = `
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.coordenador ? s.coordenador.nome : '---'}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.faculdade ? s.faculdade.nome : '---'}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.candidatura ? s.candidatura.nome : '---'}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.duracao??0}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.max_cadeira??0}</p>
                            </td>
                        `;
                    }
                
                    rows += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        o
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-500 dark:text-white">${s.curso.abreviacao}</p>
                                        <p class="text-xs text-slate-500">${s.curso.abreviacao}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.curso.curso}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.total_vagas}</p>
                            </td>
                        
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.curso.tipo}</p>
                            </td>
                            
                            ${colunasSuperior}
                            
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.curso.area_formacao}</p>
                            </td>
                            
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 ${s.curso.status === 'activo' ? 'bg-primary/10 text-primary' : 'bg-red-500/10 text-red-600'} text-[11px] font-bold rounded-full uppercase tracking-tight">${s.curso.status}</span>
                            </td>
                     
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="Adicionar disciplina" onclick="addDisciplina(${s.curso.id})">
                                        <span class="material-symbols-outlined text-xl">table</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.curso.id})">
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
        
    function limpar_campos()
    {   
        $("#data_id").val("");
        $("#ano_lectivo_id").val("");
        $("#cursos_id").val("");
        $("#duracao").val("");
        $("#max_cadeira").val("");
        $("#vagas").val("");
        $("#coordenador_id").val("");
        $("#faculdade_id").val("");
        $("#candidatura_id").val("");
        $("#vantagens").val("");
        $("#area_saidas").val("");
    }
    
    function addDisciplina(id)
    {
        $("#curso__id").val(id)
        $("#cadastrar_disciplinas_cursos").css({ 'display': 'block' });
        $("#update_disciplinas_cursos").css({ 'display': 'none' });
        
        addCourseModal.classList.remove('hidden');
    }
    
    function edit(id) {
        $.ajax({
            url: `/ano-lectivo-cursos/${id}/edit`,
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
                
                $("#ano_lectivo_id").val(data.ano_lectivos_id);
                $("#duracao").val(data.duracao);
                $("#max_cadeira").val(data.max_cadeira);
                $("#vagas").val(data.total_vagas);
                $("#coordenador_id").val(data.coordenador_id);
                $("#faculdade_id").val(data.faculdade_id);
                $("#candidatura_id").val(data.candidatura_id);
                $("#vantagens").val(data.vantagens);
                $("#area_saidas").val(data.area_saidas);
                $("#cursos_id").val(data.cursos_id).trigger('change');
                
    
                $("#modalTitle").text("Editar Curso");
                $("#botao_submiter").text("Editar Curso");
    
                // abrir modal (Tailwind / AdminLTE)
                modal.classList.remove('hidden');
            },
            error: function (xhr) {
                Swal.close();
    
                let message = "Erro ao carregar dados da curso";
    
                if (xhr.status === 404) {
                    message = "Curso não encontrada";
                } else if (xhr.status === 403) {
                    message = "Você não tem permissão para esta ação";
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
    
                showMessage("Erro", message, "error");
            }
        });
    }
    
    // remover disciplina do horario da turma
    $(document).on('click', '.editar_disciplina_curso', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); 
        
        $.ajax({
            type: "GET", 
            url: `../cursos/editar-disciplina-cursos/${recordId}`, 
            beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }, 
            success: function(data) {
                modalView.classList.add('hidden');
                addCourseModal.classList.remove('hidden');
                $("#modalTitleDisciplina").text("Editar Disciplina");
                
                $("#categoria_id").val(data.categoria_id);
                $("#disciplina_id").val(data.disciplinas_id).trigger("change");
                $("#peso").val(data.peso);
                $("#curso__id").val(data.id);
                
                $("#cadastrar_disciplinas_cursos").css({ 'display': 'none' });
                $("#update_disciplinas_cursos").css({ 'display': 'block' });
                
                Swal.close();
            }
        });
    });

    
    function show(id)
    {
        $.ajax({
            url: `../cursos/carregar-disciplinas-cursos/${id}`,
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
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">${data.curso.curso}</h3>
                            <p class="text-sm text-slate-500 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full ${data.curso.status === 'activo' ? ' bg-emerald-500 ': ' bg-red-500 '} "></span> ${data.curso.status}
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
                data.result.forEach(s => {
                    rows_cursos += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-2 text-sm font-bold text-primary">${s.id}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.disciplina.disciplina}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.disciplina.abreviacao}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.categoria.nome}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.peso}</td>
                            <td class="px-4 py-2 flex justify-end gap-1">
                                <a href="#" class="editar_disciplina_curso p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Editar" data-id="${s.id}">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <a href="#" class="deletar_disciplina_curso p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" data-id="${s.id}">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </td>
                        </tr>`;
                    }
                );
                $("#tbody_cursos").html(rows_cursos);
               
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
    
    // cadastrar_disciplinas_cursos
    $(document).on('click', '#cadastrar_disciplinas_cursos', function(e) {
        e.preventDefault();

        var data = {
            'categoria_id': $('#categoria_id').val(), 
            'disciplina_id': $('#disciplina_id').val(), 
            'curso__id': $('#curso__id').val(), 
            'peso': $('#peso').val() || 0, 
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST", 
            url: "{{ route('web.cadastrar-disciplinas-cursos') }}", 
            data: data, 
            dataType: "json", 
            beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }, success: function(response) {
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
    
    // remover disciplina do horario da turma
    $(document).on('click', '.deletar_disciplina_curso', function(e) {
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
                    , url: `{{ route('web.delete-disciplina-cursos', ':id') }}`.replace(':id', recordId)
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
                    url: `{{ route('ano-lectivo-cursos.destroy', ':id') }}`.replace(':id', recordId), 
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
                    }, 
                });
            }
        });
    });
    
    // editar_disciplinas_cursos
    $(document).on('click', '#update_disciplinas_cursos', function(e) {
        e.preventDefault();

        var id = $("#curso__id").val();

        var data = {
            "categoria_id": $("#categoria_id").val(), 
            "disciplina_id": $("#disciplina_id").val(), 
            "peso": $("#peso").val()
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: `../cursos/editar-disciplinas-cursos/${id}`
            , data: data
            , dataType: "json"
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }, success: function(response) {
                Swal.close();
                // Exibe uma mensagem de sucesso
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                window.location.reload();
            }, error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
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
            url: `/ano-lectivo/cursos-ano-lectivo-export`, // rota que vai gerar o arquivo
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
                let filename = documentType === 'excel' ? 'cursos-ano-lectivo.xlsx' : 'cursos-ano-lectivo.pdf';
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
