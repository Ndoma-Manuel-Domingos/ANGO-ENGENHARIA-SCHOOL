@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Finenceiros</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">Contas receber</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de Contas a receber</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
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
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Factura</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Serviço</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Nome</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Val</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Qtd</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Desc.</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Multa</th>
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
    
    
    
</div>
@endsection

@section('scripts')
<script>
    
    const modalView = document.getElementById('modalView');
    const closeModalView = document.getElementById('closeModalView');
    
    closeModalView.addEventListener('click', () => { modalView.classList.add('hidden'); });

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
    
    $("#paginacao").change(function(){
        load(1);
    });
    
    function formatar_moeda(value) {
        return value.toLocaleString('pt-AO', {
            style: 'currency',
            currency: 'AOA'
        });
    }
        
    function load(page=1){
        $.ajax({
            type: "GET",
            url: "/contas-receber",
            data: {
                page: page,
                designacao_geral: $("#designacao_geral").val(),
                servico_id: $("#servicoId").val(),
                ano_lectivo_id: $("#anosId").val(),
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
                    
                    let rota = "{{ route('ficha-pagamento-propina', ':id') }}";
                    rota = rota.replace(':id', s.pagamento.ficha);
                
                    rows += `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.pagamento.next_factura}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.servico.servico}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.pagamento.estudante.nome} ${s.pagamento.estudante.sobre_nome}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${ formatar_moeda(s.preco)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.quantidade}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatar_moeda(s.desconto)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${formatar_moeda(s.multa)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${ formatar_moeda((s.preco * s.quantidade) - s.desconto + s.multa)}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-500 dark:text-white">${s.date_att}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="View" onclick="show(${s.pagamento.id})">
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
                
                let h = `
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">${data.next_factura}</h3>
                            <p class="text-sm text-slate-500 font-medium">
                                <div class="flex items-center gap-2">
                                    ${data.moeda}
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
                
                // let rows_cursos = "";
                // data.result.forEach(s => {
                //     rows_cursos += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                //             <td class="px-4 py-2 text-sm font-bold text-primary">${s.id}</td>
                //             <td class="px-4 py-2 text-sm font-medium">${s.disciplina.disciplina}</td>
                //             <td class="px-4 py-2 text-sm font-medium">${s.disciplina.abreviacao}</td>
                //             <td class="px-4 py-2 text-sm font-medium">${s.categoria.nome}</td>
                //             <td class="px-4 py-2 text-sm font-medium">${s.peso}</td>
                //             <td class="px-4 py-2 flex justify-end gap-1">
                //                 <a href="#" class="editar_disciplina_curso p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Editar" data-id="${s.id}">
                //                     <span class="material-symbols-outlined text-xl">edit</span>
                //                 </a>
                //                 <a href="#" class="deletar_disciplina_curso p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Eliminar" data-id="${s.id}">
                //                     <span class="material-symbols-outlined text-xl">delete</span>
                //                 </a>
                //             </td>
                //         </tr>`;
                //     }
                // );
                // $("#tbody_cursos").html(rows_cursos);
               
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

{{-- @extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Contas a Receber</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Contas Receber</li>
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
                <form action="{{ route('financeiros.financeiro-contas-receber') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-2">
                                    <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($listasanolectivo) != 0)
                                        @foreach ($listasanolectivo as $item2)
                                        <option value="{{ $item2->id }}" {{ $filtro['ano_lectivo_id'] == $item2->id ? 'selected' : '' }}>{{ $item2->ano }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
    
                                <div class="form-group col-12 col-md-3">
                                    <label for="servico_id" class="form-label">Serviços</label>
                                    <select name="servico_id" id="servico_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($servicos) != 0)
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ $filtro['servico_id'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
    
                                <div class="form-group col-12 col-md-3">
                                    <label for="forma_pagamento_id" class="form-label">Forma Recebimento</label>
                                    <select name="forma_pagamento_id" id="forma_pagamento_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($formas_pagamento) != 0)
                                        @foreach ($formas_pagamento as $item)
                                        <option value="{{ $item->id }}" {{ $filtro['forma_pagamento_id'] == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
    
                                <div class="form-group col-12 col-md-2">
                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                    <input type="date" id="data_inicio" placeholder="Data de Inicio da de Inicial" value="{{ $filtro['data_inicio'] ?? "" }}" name="data_inicio" class="form-control">
                                </div>
    
                                <div class="form-group col-12 col-md-2">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <input type="date" id="data_final" placeholder="Data de Inicio da de FInal" value="{{ $filtro['data_final'] ?? "" }}" name="data_final" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('ficha-pagamentos-receber', [
                            'type' => "receita", 
                            'data_inicio' => $filtro['data_inicio'] ?? "", 
                            'data_final' => $filtro['data_final'] ?? "",
                            'forma_pagamento_id' => $filtro['forma_pagamento_id'] ?? "",
                            'servico_id' => $filtro['servico_id'] ?? "",
                            'ano_lectivo_id' => $filtro['ano_lectivo_id'] ?? ""
                        ]) }}" class="btn btn-danger" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                    </div>
                    @php $totalValorUnitario = 0; $totalQuantidade = 0; $totalValorGeral = 0; $totalValorMulta = 0; @endphp
                    <div class="card-body table-responsive">
                        @if ($pagamentos)
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                      
                            <tbody>
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td>{{ $item->pagamento->next_factura }}</td>
                                    <td>{{ $item->servico->servico ?? "" }}</td>
                                    <td>{{ $item->pagamento->model($item->pagamento->model, $item->pagamento->estudantes_id) }}</td>
                                    <td class="text-right">{{ number_format($item->preco, 2, ',', '.')  }} </td>
                                    <td class="text-right">{{ number_format($item->quantidade, 2, ',', '.') }} </td>
                                    <td class="text-right">{{ number_format($item->desconto, 2, ',', '.') }} </td>
                                    <td class="text-right">{{ number_format($item->multa, 2, ',', '.') }} </td>
                                    <td class="text-right">{{ number_format( ($item->preco * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} </td>
                                    <td>{{  $item->pagamento->operador->nome ?? "" }}</td>
                                    <td>{{ $item->date_att }}</td>
                                    <td class="text-end" style="width: 200px">
                                        @if (Auth::user()->can('delete: pagamento'))
                                        <a href='{{ route('web.financeiro-limpar-pagamento', Crypt::encrypt($item->pagamento->id) ) }}' class="btn-danger btn mx-2">
                                            <i class="fas fa-broom"></i>
                                        </a>
                                        @endif
                                        <a href='{{ route('web.ficha-matricula', Crypt::encrypt($item->pagamento->ficha) ) }}' class="btn btn-primary mx-2">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a href='{{ route('ficha-pagamento-propina', $item->pagamento->ficha) }}' target="_blink" class="btn btn-primary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>

                                @php
                                $totalValorUnitario += $item->preco;
                                $totalQuantidade += $item->quantidade;
                                $totalValorGeral += $item->total_pagar;
                                $totalValorMulta += $item->multa;
                                @endphp

                                @endforeach
                            </tbody>
                            <tfoot>
                                <th class="text-right">-----</th>
                                <th class="text-right">-----</th>
                                <th class="text-right">-----</th>
                                <th class="text-right">{{ number_format($totalValorUnitario, 2, ',', '.') }}</th>
                                <th class="text-right">------</th>
                                <th class="text-right">----------</th>
                                <th class="text-right">{{ number_format($totalValorMulta, 2, ',', '.') }}</th>
                                <th class="text-right">{{ number_format($totalValorGeral, 2, ',', '.') }}</th>
                                <th class="text-right">----------</th>
                                <th class="text-right">------</th>
                                <th class="text-right">------</th>
                                
                            </tfoot>
                        </table>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);
</script>
@endsection --}}
