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
                    
                    let rota = "{{ route('web.sistuacao-financeiro', ':id') }}";
                    rota = rota.replace(':id', s.estudante.id);
                
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
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.id})">
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
                
                let rota = "{{ route('ficha-pagamento-propina', ':id') }}";
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
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">${data.estudante.nome} ${data.estudante.sobre_nome}</h3>
                        
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
                <h1 class="m-0 text-dark">Painel Financeiro Gestão de dívidas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Divídas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Painel financeiro para gestão de dívidas, imprimir lista de
                        estudantes devedores por turma, individual e geral.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('financeiros.financeiro-gestao-dividas') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                
                                <div class="form-group col-12 col-md-4">
                                    <label for="input_estudante" class="form-label">Pesquisar por Estudante</label>
                                    <input type="text" name="input_estudante" value="{{ old('inpu_estudante') ?? $requests['input_estudante'] }}" placeholder="Informe o número do bilheite ou cedula" class="form-control input_estudante" id="input_estudante">
                                </div>
                            
                                <div class="form-group col-12 col-md-2">
                                    <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                    <select name="ano_lectivos_id" class="form-control ano_lectivos_id select2" id="ano_lectivos_id">
                                        <option value="">Todos</option>
                                        @foreach ($anos_lectivos as $item)
                                            <option value="{{ $item->id }}" {{ $requests['ano_lectivos_id'] == $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                </div>
            
                                <div class="form-group col-12 col-md-2">
                                    <label for="servico" class="form-label">Serviços</label>
                                    <select name="servico" id="servico" class="form-control servico select2">
                                        <option value="">Todos</option>
                                        @if (count($servicos) != 0)
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['servico'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                @php
                                    // Verifica se o campo 'mes' existe e se é um array
                                    $mesesSelecionados = isset($requests['mes']) && is_array($requests['mes']) ? $requests['mes'] : [];
                                @endphp
                                
                               
                                <div class="form-group col-12 col-md-4">
                                    <label for="mes" class="form-label">Meses </label>
                                    <select name="mes[]" id="mes" multiple="multiple" class="form-control mes select2">
                                        <option value="">Todos</option>
                                        <option value="Jan" {{ in_array("Jan", $mesesSelecionados) ? 'selected' : '' }}>Janeiro</option>
                                        <option value="Feb" {{ in_array("Feb", $mesesSelecionados) ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="Mar" {{ in_array("Mar", $mesesSelecionados) ? 'selected' : '' }}>Março</option>
                                        <option value="Apr" {{ in_array("Apr", $mesesSelecionados) ? 'selected' : '' }}>Abril</option>
                                        <option value="May" {{ in_array("May", $mesesSelecionados) ? 'selected' : '' }}>Maio</option>
                                        <option value="Jun" {{ in_array("Jun", $mesesSelecionados) ? 'selected' : '' }}>Junho</option>
                                        <option value="Jul" {{ in_array("Jul", $mesesSelecionados) ? 'selected' : '' }}>Julho</option>
                                        <option value="Aug" {{ in_array("Aug", $mesesSelecionados) ? 'selected' : '' }}>Agosto</option>
                                        <option value="Sep" {{ in_array("Sep", $mesesSelecionados) ? 'selected' : '' }}>Setembro</option>
                                        <option value="Oct" {{ in_array("Oct", $mesesSelecionados) ? 'selected' : '' }}>Outrobro</option>
                                        <option value="Nov" {{ in_array("Nov", $mesesSelecionados) ? 'selected' : '' }}>Novembro</option>
                                        <option value="Dec" {{ in_array("Dec", $mesesSelecionados) ? 'selected' : '' }}>Deszembro</option>
                                    </select>
                                </div>
            
                                <div class="form-group col-12 col-md-2">
                                    <label for="condicao" class="form-label">Estado</label>
                                    <select name="condicao" id="condicao" class="form-control condicao select2">
                                        <option value="">Todos</option>
                                        <option value="Nao Pago" {{ $requests['condicao'] == "Nao Pago" ? 'selected' : '' }}>Não Pagos</option>
                                        <option value="Pago" {{ $requests['condicao'] == "Pago" ? 'selected' : '' }}>Pagos</option>
                                        <option value="divida" {{ $requests['condicao'] == "divida" ? 'selected' : '' }}>Divida</option>
                                    </select>
                                </div>
                                
                                
                                <div class="form-group col-12 col-md-2">
                                    <label for="cursos_id" class="form-label">Cursos</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control cursos_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->curso->id }}" {{ $requests['cursos_id'] == $item->curso->id ? 'selected' : '' }}>{{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-12 col-md-2">
                                    <label for="classes_id" class="form-label">Classes</label>
                                    <select name="classes_id" id="classes_id" class="form-control classes_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->classe->id }}" {{ $requests['classes_id'] == $item->classe->id ? 'selected' : '' }}>{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-12 col-md-2">
                                    <label for="turnos_id" class="form-label">Turnos</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control turnos_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($turnos as $item)
                                        <option value="{{ $item->turno->id }}" {{ $requests['turnos_id'] == $item->turno->id ? 'selected' : '' }}>{{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary imprimir_lista"><i class="fas fa-filter"></i> Filtra</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        @if ($cartoes)
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Listagem estudantes com Extratos</h3>
                            <a href="{{ route('estudantes-devedores-imprmir', ['input_estudante' => $requests['input_estudante'] ?? "", 'ano_lectivos_id' => $requests['ano_lectivos_id'] ?? "", 'servico' => $requests['servico'] ?? "", 'mes' => $requests['mes'] ?? "", 'condicao' => $requests['condicao'] ?? "", 'cursos_id' => $requests['cursos_id'] ?? "", 'classes_id' => $requests['classes_id'] ?? "", 'turnos_id' => $requests['turnos_id'] ?? ""]) }}" target="_blink" class="btn btn-primary float-right"><i class="fas fa-print"></i> Imprimir</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                        <table id="carregarTabelaEstudantes" style="width: 100%" class="table  table-bordered table-striped  ">
                            <thead>
                                <tr>
                                  <th>Nº</th>
                                  <th>Nome</th>
                                  <th>Bilhete</th>
                                  <th>Mês</th>
                                  <th>Estado</th>
                                  <th>Multa</th>
                                  <th>Preço</th>
                                  <th>Total</th>
                                  <th>Serviço</th>
                                  <th>Curso</th>
                                  <th>Classe</th>
                                  <th>Turno</th>
                                  <th style="width: 100px">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $multas = 0;
                                    $preco = 0;
                                    $total = 0;
                                @endphp
                                @foreach ($cartoes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    <td>{{ $item->mes($item->month_name) }}</td>
                                    @if ($item->status == "divida")
                                     <td class="text-warning">{{ $item->status }}</td> 
                                    @else
                                        @if ($item->status == "Pago")
                                        <td class="text-success">{{ $item->status }}</td> 
                                        @else
                                            @if ($item->status == "Nao Pago")
                                            <td class="text-danger">{{ $item->status }}</td>  
                                            @else
                                            <td class="text-info">{{ $item->status }}</td>   
                                            @endif
                                        @endif
                                    @endif
                                    <td>{{ number_format($item->multa ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->preco_unitario ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ number_format((($item->preco_unitario ?? 0) + ($item->multa ?? 0)), 2, ',', '.') }}</td>
                                    <td>{{ $item->servico->servico ?? "" }}</td>
                                    <td>{{ $item->estudante->matricula->curso->curso ?? '' }}</td>
                                    <td>{{ $item->estudante->matricula->classe->classes ?? '' }}</td>
                                    <td>{{ $item->estudante->matricula->turno->turno ?? '' }}</td>
                                    <td>
                                        <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-info"><i class="fas fa-plus"></i> Detalhe</a>
                                    </td>
                                    
                                    @php
                                        $multas += $item->multa ?? 0;
                                        $preco += $item->preco_unitario ?? 0;
                                        $total += (($item->preco_unitario ?? 0) + ($item->multa ?? 0)); 
                                    @endphp
                                </tr>
                                @endforeach
                                
                                <tfoot>
                                    <tr>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>{{ number_format($multas ?? 0, 2, ',', '.') }}</th>
                                        <th>{{ number_format($preco ?? 0, 2, ',', '.') }}</th>
                                        <th>{{ number_format($total ?? 0, 2, ',', '.') }}</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                        <th>----</th>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                        </div>
                        <!-- /.card-body -->
                  </div>
                  
                   <!-- /.card -->
                </div>
            </div>
        @endif

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection


@section('scripts')

  <script>
    $(function () {
      $("#carregarTabelaEstudantes").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

    });
    
  </script>
  
@endsection --}}