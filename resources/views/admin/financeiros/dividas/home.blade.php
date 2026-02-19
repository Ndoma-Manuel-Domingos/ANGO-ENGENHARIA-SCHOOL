@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Finenceiros</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">gestão dívidas</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de dívidas</h1>
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
                
                <select id="condicao" class="select2 h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[150px]">
                    <option value="">Todos Estados</option>
                    <option value="Nao Pago">Não Pagos</option>
                    <option value="Pago">Pagos</option>
                    <option value="divida">Divida</option>
                </select>
                
                <select id="servicoId" class="select2 h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[150px]">
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
                
                <select id="mes" name="mes[]" multiple class="select2 h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[170px]">
                    <option value="">Todos</option>
                    <option value="Jan">Janeiro</option>
                    <option value="Feb">Fevereiro</option>
                    <option value="Mar">Março</option>
                    <option value="Apr">Abril</option>
                    <option value="May">Maio</option>
                    <option value="Jun">Junho</option>
                    <option value="Jul">Julho</option>
                    <option value="Aug">Agosto</option>
                    <option value="Sep">Setembro</option>
                    <option value="Oct">Outrobro</option>
                    <option value="Nov">Novembro</option>
                    <option value="Dec">Deszembro</option>
                </select>
                
                <select id="paginacao" class="custom-input h-10 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-primary min-w-[120px]">
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
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Nome</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Mês</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Multa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Preço</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Total</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Serviço</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Curso</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Classe</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Turno</th>
                
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
        
        <div class="relative bg-white dark:bg-sidebar-dark w-full max-w-7xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            
            <div id="headerData"></div>
         
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-8 flex-1">
                <section>
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800" id="idTH">
                                    
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
                <button onclick="document.getElementById('modalView').classList.add('hidden')" class="mx-2 h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    Fechar
                </button>
            </div>
            
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
    
    $("#condicao").change(function(){
        load(1);
    });
    
    $("#mes").change(function(){
        load(1);
    });
    
    $("#paginacao").change(function(){
        load(1);
    }); 
    
    function load(page=1){
        $.ajax({
            type: "GET",
            url: "/dividas",
            data: {
                page: page,
                designacao_geral: $("#designacao_geral").val(),
                condicao: $("#condicao").val(),
                servico_id: $("#servicoId").val(),
                ano_lectivo_id: $("#anosId").val(),
                mes: $("#mes").val(),
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
                    let stat = "";
                    
                    if(s.status == "Pago") {
                        stat += `
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 bg-green-500/10 text-green-600 text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                            </td>
                        `;
                    } else if(s.status == "divida") {
                        stat += `
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 bg-amber-500/10 text-amber-600 text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                            </td>
                        `;
                    } else if(s.status == "Nao Pago") {
                        stat += `
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 bg-red-500/10 text-red-600 text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                            </td>
                        `;
                    }else{
                        stat += `
                            <td class="px-6 py-5">
                                <span class="inline-flex px-2.5 py-1 bg-blue-500/10 text-blue-600 text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                            </td>
                        `;
                    }
                
                    rows += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">
                                    <a href="#">${s.estudante.nome} ${s.estudante.sobre_nome}</a>
                                </p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.month_name}</p>
                            </td>
                            ${stat}
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatar_moeda(s.multa)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatar_moeda(s.preco_unitario)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatar_moeda(s.preco_unitario + s.multa)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.servico?.servico ?? ''}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.estudante?.matricula?.curso?.curso ?? ''}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.estudante?.matricula?.classe?.classes ?? ''}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.estudante?.matricula?.turno?.turno ?? ''}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.estudante.id})">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
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
    
    function exibir_cartoes(data) {
                        
        let rows_items = "";
        let idHT = "";
        
        if(data.bolseiro) {
            idHT += `
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Meses</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider" title="Data Final Pagamento">Final Pagamento</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider" title="Valor Unitário">Preço</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Multa</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Estado</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Desconto</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Valor_Pagar</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Periodo_1</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Periodo_2</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Pag_Feito</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Acções</th>
            `;
        }else {
            idHT += `
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Meses</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider" title="Data Final Pagamento">Data Final</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider" title="Valor Unitário">Preço</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Multa</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider">Estado</th>
                <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Acções</th>
            `;
        }
        
        $("#idTH").html(idHT);
        
        data.cartoes.forEach(s => {
           
            let status = "";
            let idDT = "";
            
            if(data.bolseiro) {

                if(data.bolseiro.afectacao == "mensalidade") {
                    idDT += `
                        <td class="px-4 py-2 text-sm font-medium">${formatar_moeda((s.preco_unitario - (s.preco_unitario * (data.bolseiro.instituicao_bolsa.desconto) / 100)))}</td>
                        <td class="px-4 py-2 text-sm font-medium">${formatar_moeda((s.preco_unitario - (s.preco_unitario - (s.preco_unitario * (data.bolseiro.instituicao_bolsa.desconto) / 100) )))}</td>
                    `;
                }else {
                    idDT += `
                        <td class="px-4 py-2 text-sm font-medium">${formatar_moeda((s.preco_unitario - (s.preco_unitario * (data.bolseiro.instituicao_bolsa.desconto) / 100)))}</td>
                        <td class="px-4 py-2 text-sm font-medium">${formatar_moeda((s.preco_unitario - (s.preco_unitario - (s.preco_unitario * (data.bolseiro.instituicao_bolsa.desconto) / 100) )))}</td>
                    `;
                }
                
                idDT += `
                    <td class="px-4 py-2 text-sm font-medium">${s.trimestral}</td>
                    <td class="px-4 py-2 text-sm font-medium">${s.semestral}</td>
                    <td class="px-4 py-2 text-sm font-medium">${s.status_2}</td>
                `;
            }
            
            if (s.status == "Pago") {
                status += `
                    <button class="p-2 text-red-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como não pago" data-id="${s.id}" data-status="Nao Pago">
                        <span class="material-symbols-outlined text-xl">check_circle</span>
                    </button>
                    <button class="p-2 text-blue-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como isento" data-id="${s.id}" data-status="Isento">
                        <span class="material-symbols-outlined text-xl">warning</span>
                    </button>
                    <button class="p-2 text-amber-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como divida" data-id="${s.id}" data-status="divida">
                        <span class="material-symbols-outlined text-xl">verified</span>
                    </button>
                `;
            }
            
            if (s.status == "divida") {
                status += `
                    <button class="p-2 text-red-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como não pago" data-id="${s.id}" data-status="Nao Pago">
                        <span class="material-symbols-outlined text-xl">check_circle</span>
                    </button>
                    <button class="p-2 text-green-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como pago" data-id="${s.id}" data-status="Pago">
                        <span class="material-symbols-outlined text-xl">cancel</span>
                    </button>
                    <button class="p-2 text-blue-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como isento" data-id="${s.id}" data-status="Isento">
                        <span class="material-symbols-outlined text-xl">warning</span>
                    </button>
                `;
            }
            
            if (s.status == "Nao Pago") {
                status += `
                    <button class="p-2 text-green-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como pago" data-id="${s.id}" data-status="Pago">
                        <span class="material-symbols-outlined text-xl">cancel</span>
                    </button>
                    <button class="p-2 text-blue-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como isento" data-id="${s.id}" data-status="Isento">
                        <span class="material-symbols-outlined text-xl">warning</span>
                    </button>
                    <button class="p-2 text-amber-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como divida" data-id="${s.id}" data-status="divida">
                        <span class="material-symbols-outlined text-xl">verified</span>
                    </button>
                `;
            }
            
            if (s.status == "Isento") {
                status += `
                    <button class="p-2 text-amber-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como divída" data-id="${s.id}" data-status="divida">
                        <span class="material-symbols-outlined text-xl">verified</span>
                    </button>
                    <button class="p-2 text-green-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como pago" data-id="${s.id}" data-status="Pago">
                        <span class="material-symbols-outlined text-xl">cancel</span>
                    </button>
                    <button class="p-2 text-red-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all mudar-record" title="Definir como não pago" data-id="${s.id}" data-status="Nao Pago">
                        <span class="material-symbols-outlined text-xl">check_circle</span>
                    </button>
                `;
            }
        
            rows_items += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                <td class="px-4 py-2 text-sm font-medium">${descricao_mes(s.month_name)}</td>
                <td class="px-4 py-2 text-sm font-medium">${s.data_exp}</td>
                <td class="px-4 py-2 text-sm font-medium">${formatar_moeda(s.preco_unitario)}</td>
                <td class="px-4 py-2 text-sm font-medium">${formatar_moeda(s.multa)}</td>
                <td class="px-4 py-2 text-sm font-medium">
                    <span class="inline-flex px-2.5 py-1 ${s.status == "Pago" ? 'bg-green-500/10 text-green-600' : (s.status == "divida" ? 'bg-amber-500/10 text-amber-600' : (s.status == "Nao Pago" ? 'bg-red-500/10 text-red-600' : 'bg-blue-500/10 text-blue-600'))} text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
                </td>
                ${idDT}
                <td class="px-4 py-2">
                    <div class="flex justify-end gap-1">
                        ${status}
                    </div>
                </td>
            </tr>`;
            
            }
        );
        $("#tbody_cursos").html(rows_items);
    
    }
    
    function show(id)
    {
        $.ajax({
            url: `/dividas/${id}`,
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
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Situação Financeira</h3>
                        </div>
                        <button onclick="document.getElementById('modalView').classList.add('hidden')"
                            class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-2 flex-1">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white uppercase">${data.estudante.nome} ${data.estudante.sobre_nome} 
                            <span class="text-gray-300"><span class="material-symbols-outlined text-xl">${data.bolseiro ? 'verified': 'person'}</span> ${data.bolseiro ? ' - Bolseiro': ' - Não Bolseiro'}</span>
                        </h3>
                        <p class="text-sm text-slate-500 font-medium">Extrato financeiro do estudante, meses pagos e não pagos.</p>
                    </div>
                `;
                
                $("#headerData").html(h);
                
                exibir_cartoes(data);
               
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
        
    function exportData(documentType) {
        // Reaproveitando os filtros da loadSeries
        const data = {
            condicao: $("#condicao").val(),
            servico_id: $("#servicoId").val(),
            ano_lectivo_id: $("#anosId").val(),
            mes: $("#mes").val(),
            documentType: documentType // "excel" ou "pdf"
        };
    
        $.ajax({
            type: "GET",
            url: `/dividas-export`, // rota que vai gerar o arquivo
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
                let filename = documentType === 'excel' ? 'dividas.xlsx' : 'dividas.pdf';
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
        
    $(document).on('click', '.mudar-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id');
        let statusId = $(this).data('status');

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: `Sim, actualizar para ${statusId}!`
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('web.dividas-mudar-estado', [':id', ':status']) }}`.replace(':id', recordId).replace(':status', statusId), 
                    method: 'GET', 
                    data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(data) {
                        Swal.close();
                         
                        $("#tbody_cursos").empty();
                        exibir_cartoes(data);
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                       
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }, 
                });
            }
        });
    });
    
    $("#btnExportPDF").click(() => exportData("pdf"));
    
</script>
@endsection 
