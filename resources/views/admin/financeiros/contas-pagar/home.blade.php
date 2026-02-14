@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Finenceiros</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">Contas pagar</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de Contas a pagar</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            <a href="{{ route('paineis.administrativo') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Voltar</span>
            </a>
            <button id="btnExportPDF" type="button" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-red-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-success/30 hover:bg-red-500/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">picture_as_pdf</span>
                <span>Imprimir PDF</span>
            </button>
        </div>
    </div>
        
    <div class="bg-white dark:bg-sidebar-dark rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col lg:flex-row gap-4 lg:items-center justify-between">
            <div class="flex flex-wrap gap-3">
                <div class="relative min-w-[240px]">
                    <input class="custom-input w-full h-10 pl-9 pr-4 text-sm bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary" id="designacao_geral" placeholder="Search by name or code..." type="text" />
                </div>
                
                <select id="servicoId" class="select2 h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[220px]">
                    <option value="">Todos Serviços</option>
                    @foreach ($servicos as $item)
                    <option value="{{ $item->id }}">{{ $item->servico }}</option>
                    @endforeach
                </select>
                
                <select id="anosId" class="select2 h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
                    <option value="">Todos Anos</option>
                    @foreach ($anos_lectivos as $item)
                    <option value="{{ $item->id }}">{{ $item->ano }}</option>
                    @endforeach
                </select>
                
                <select id="formaPagamentoId" class="select2 h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[170px]">
                    <option value="">Registros</option>
                    @foreach ($forma_pagamentos as $item)
                    <option value="{{ $item->id }}">{{ $item->descricao }}</option>
                    @endforeach
                </select>
                
                <select id="paginacao" class="custom-input h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
                    <option value="">Registros</option>
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                
                <div class="relative min-w-[150px]">
                    <input class="custom-input w-full h-10 pl-9 pr-4 text-sm bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary" id="dateInicio" placeholder="" type="date" />
                </div>
                
                <div class="relative min-w-[150px]">
                    <input class="custom-input w-full h-10 pl-9 pr-4 text-sm bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary" id="dateFinal" placeholder="" type="date" />
                </div>
                
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Factura</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Serviço</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Total</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Data</th>
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
    
    {{-- Visualizações --}}
    <div id="modalView" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <div class="relative bg-white dark:bg-sidebar-dark w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            
            <div id="headerData"></div>
         
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-8 flex-1">
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">list_alt</span>Detalhes do Pagamento
                        </h4>
                    </div>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Codigo</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Serviço</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Quantidade</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Preço Unitário</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Multa</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Desconto</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="tbody_cursos">
                                {{-- carregar Ajax --}}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            
            <div id="footerData"></div>
            
        </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script>

    const modalView = document.getElementById('modalView');

    $(document).ready(function(){
        load();
    });
    $("#designacao_geral").on('input', function () {
        load(1);
    });
 
    $("#servicoId").change(function(){
        load(1);
    });
    
    $("#anosId").change(function(){
        load(1);
    });
    
    $("#formaPagamentoId").change(function(){
        load(1);
    });
    
    $("#dateInicio").change(function(){
        load(1);
    });
    
    $("#dateFinal").change(function(){
        load(1);
    });
    
    $("#paginacao").change(function(){
        load(1);
    });
    
        
    function exportData(documentType) {
        // Reaproveitando os filtros da loadSeries
        const data = {
            servico_id: $("#servicoId").val(),
            ano_lectivo_id: $("#anosId").val(),
            type:  "receita",
            data_inicio: $("#dateInicio").val(),
            data_final: $("#dateFinal").val(),
            forma_pagamento_id: $("#formaPagamentoId").val(),
            documentType: documentType // "excel" ou "pdf"
        };
    
        $.ajax({
            type: "GET",
            url: `/contas-pagar-export`, // rota que vai gerar o arquivo
            data: data,
            xhrFields: {
                responseType: 'blob' // importante para pagar o arquivo
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
                let filename = documentType === 'excel' ? 'contas-pagar.xlsx' : 'contas-pagar.pdf';
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
        
    $("#btnExportPDF").click(() => exportData("pdf"));
            
    function load(page=1){
        $.ajax({
            type: "GET",
            url: "/contas-pagar",
            data: {
                page: page,
                designacao_geral: $("#designacao_geral").val(),
                servico_id: $("#servicoId").val(),
                ano_lectivo_id: $("#anosId").val(),
                data_inicio: $("#dateInicio").val(),
                data_final: $("#dateFinal").val(),
                forma_pagamento_id: $("#formaPagamentoId").val(),
                paginacao: $("#paginacao").val(),
            },
            dataType: "json",
            beforeSend: function () {
                // opcional: mostrar loader
                progressBeforeSend("Carregando...");
            },
            success: function (data) {
                
                Swal.close();
                let rows = "";
                data.data.forEach(s => {
                    let rota = "{{ route('ficha-pagamento-servico', ':id') }}";
                    rota = rota.replace(':id', s.ficha);
                   
                    rows += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.next_factura}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.servico.servico}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatar_moeda(s.total_iva + s.total_incidencia)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatarData(s.created_at)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.id})">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                    <a href='${rota}' target="_blink" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Imprimir">
                                        <span class="material-symbols-outlined text-xl">print</span>
                                    </a>
                                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all  delete-record" title="Eliminar" data-id="${s.id}">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $("#tbody").html(rows);
                updateResultsInfo(data);
                paginate(data);
                
            },
            error: function (xhr) {
                Swal.close();
                console.error(xhr);
            }
        });
    }
    
    function limpar_campos()
    {   
    }
        
    function show(id)
    {
        $.ajax({
            url: `/contas-receber/${id}`,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                progressBeforeSend("Carregando dados...");
            },
            success: function (data) {
                Swal.close();
                modalView.classList.remove('hidden');
                
                let rota = "{{ route('ficha-pagamento-servico', ':id') }}";
                rota = rota.replace(':id', data.ficha);
                
                let h = `
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">${data.next_factura}</h3>
                        </div>
                        <button onclick="document.getElementById('modalView').classList.add('hidden')"
                            class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-2 flex-1">
                        <p class="text-sm text-slate-500 font-medium">Total: ${formatar_moeda(data.total_incidencia + data.total_iva)}</p>
                        <p class="text-sm text-slate-500 font-medium">Forma de Pagamento: ${data.tipo_pagamento}</p>
                        <p class="text-sm text-slate-500 font-medium">Ano Lectivo: ${data.ano.ano}</p>
                        <p class="text-sm text-slate-500 font-medium">Operador: ${data.operador.nome}</p>
                        <p class="text-sm text-slate-500 font-medium">Data: ${formatarData(data.created_at)}</p>
                        <p class="text-sm text-slate-500 font-medium">Observação: ${data.observacao ?? "Sem descrição"}</p>
                    </div>
                `;
                
                $("#headerData").html(h);
                
                let f = `
                    <div class="p-6 border-t border-slate-100 dark:border-slate-800 flex justify-end shrink-0"> 
                        <a href='${rota}' target="_blank" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Imprimir
                        </a>
                        <button onclick="document.getElementById('modalView').classList.add('hidden')" class="mx-2 h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Fechar
                        </button>
                    </div>
                `;
                
                $("#footerData").html(f);
                
                let rows_items = "";
                data.items.forEach(s => {
                    rows_items += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-2 text-sm font-bold text-primary">${s.id}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.servico.servico}</td>
                            <td class="px-4 py-2 text-sm font-medium">${s.quantidade}</td>
                            <td class="px-4 py-2 text-sm font-medium">${formatar_moeda(s.preco)}</td>
                            <td class="px-4 py-2 text-sm font-medium">${formatar_moeda(s.multa)}</td>
                            <td class="px-4 py-2 text-sm font-medium">${formatar_moeda(s.desconto_valor)}</td>
                            <td class="px-4 py-2 text-sm font-medium">${formatar_moeda(s.total_pagar)}</td>
                        </tr>`;
                    }
                );
                $("#tbody_cursos").html(rows_items);
               
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
                    url: `{{ route('contas-receber.destroy', ':id') }}`.replace(':id', recordId), 
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
    
</script>
@endsection 