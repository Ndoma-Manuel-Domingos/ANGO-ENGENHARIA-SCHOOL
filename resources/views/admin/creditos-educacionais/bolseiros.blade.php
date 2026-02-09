@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Credito Educacional</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">gestão de bolseiros</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de bolseiros</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            @if (Auth::user()->can('create: bolseiro'))
            <button id="openModal"
                class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">add</span>
                <span>Atribuir Bolsas</span>
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
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Estudante</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Idade</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Insittuição</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Bolsa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Tipo Instituição</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Desconto</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Período</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 tracking-wider">Estado</th>
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
            <div class="relative bg-white dark:bg-sidebar-dark w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitle">Atribuir Bolsas</h3>
                    <button id="closeModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form class="p-6 space-y-5" id="createInstituicao" action="{{ route('bolseiros.store') }}" method="POST">
                    @csrf
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
                    
                    <input type="hidden" id="data_id" name="data_id">
                    
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button onclick="document.getElementById('courseModal').classList.add('hidden')" class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all"
                            type="button">
                            Cancelar
                        </button>
                        @if (Auth::user()->can('create: bolseiro'))
                        <button id="botao_submiter" form="createInstituicao" class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all" type="submit">
                            Salvar
                        </button>
                        @endif
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
                url: "/bolseiros",
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
                success: function (data) {
                 
                    Swal.close();
                    let rows = "";
                    data.data.forEach(s => {
                      
                        rows += `
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                              
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.estudante.nome} ${s.estudante.sobre_nome}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.estudante.nascimento}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.bolsa.nome}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.instituicao.nome}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.instituicao.tipo}</p>
                                </td>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.instituicao_bolsa.desconto}%</p>
                                </td>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-500 dark:text-white">${s.periodo.trimestre}</p>
                                </td>
                                
                                <td class="px-6 py-5">
                                    <span class="inline-flex px-2.5 py-1 ${s.status === 'activo' ? 'bg-primary/10 text-primary' : 'bg-red-500/10 text-red-600'} text-[11px] font-bold rounded-full uppercase tracking-tight">${s.status}</span>
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
            $("#data_id").val("");
            $("#designacao").val("");    
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
                <h1 class="m-0 text-dark">Listagem Bolseiros</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('creditos-educacionais.instituicao') }}">Listagem</a></li>
                    <li class="breadcrumb-item active">Detalhe</li>
                </ol>
            </div><!-- /.col -->
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
       
        <div class="row">
            
            <div class="col-12 col-md-12">                
                <div class="card">
                    <form action="{{ route('creditos-educacionais.instituicao-listar-bolseiros') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="instituicao_id">Instituição <span class="text-danger">*</span></label>
                                    <select name="instituicao_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Instituição</option>
                                        @foreach ($instituicoes as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('instituicao_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="bolsa_id">Bolsas <span class="text-danger">*</span></label>
                                    <select name="bolsa_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Bolsas</option>
                                        @foreach ($bolsas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('bolsa_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button class="btn btn-primary">Pesquisar</button>
                        </div>
                    </form>
                </div>
            
            </div>
        
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('web.estudante-atribuir-bolsa') }}" class="btn btn-primary">Atribuir Bolsairo</a>
                        
                        <a href="{{ route('estudantes-bolseiros-imprmir', ['instituicao_id' => $filtros['instituicao_id'], 'bolsa_id' => $filtros['bolsa_id']]) }}"
                            class="float-end btn-danger btn mx-1" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                        <a href="{{ route('estudantes-bolseiros-imprmir-excel', ['instituicao_id' => $filtros['instituicao_id'], 'bolsa_id' => $filtros['bolsa_id']]) }}"
                            class="float-end btn-success btn mx-1" target="_blink"><i class="fas fa-file-excel"></i> Imprimir</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="tabelasPermissions" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th>Nº Processo</th>
                                    <th>Estudante</th>
                                    <th>Idade</th>
                                    <th>Bolsa</th>
                                    <th>Instituição</th>
                                    <th>Tipo Instituição</th>
                                    <th>Desconto</th>
                                    <th>Período</th>
                                    <th>Estado</th>
                                    <th width="10%" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bolseiros as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->estudante->numero_processo }}</td>
                                        <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome ?? '' }} {{ $item->estudante->sobre_nome ?? '' }}</a></td>
                                        <td>{{ $item->estudante->idade($item->estudante->nascimento)  }} </td>
                                        <td>{{ $item->bolsa->nome ?? '' }}</td>
                                        <td>{{ $item->instituicao->nome ?? '' }}</td>
                                        <td>{{ $item->instituicao->tipo ?? '' }}</td>
                                        <td>{{ $item->instituicao_bolsa->desconto ?? '' }}%</td>
                                        <td>{{ $item->periodo->trimestre ?? '' }}</td>
                                        @if ($item->status == "activo")
                                        <td class="text-success text-uppercase">{{ $item->status ?? '' }}</td>
                                        @else
                                        <td class="text-danger text-uppercase">{{ $item->status ?? '' }}</td>
                                        @endif
                                        <td class="text-end">
                                            
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Opções</button>
                                                <button type="button"
                                                    class="btn btn-info dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                
                                                    <a href="{{ route('web.estudante-editar-bolseiro-bolsa', Crypt::encrypt($item->id)) }}" title="Editar Bolsa" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                    <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Bolsa" class="dropdown-item"><i class="fa fa-edit"></i> Visualizar</a>
                                                    <a href="{{ route('creditos-educacionais.instituicao-remover-bolsa-bolseiros', Crypt::encrypt($item->id)) }}" title="Eliminar Bolsa" class="dropdown-item text-danger"><i class="fa fa-trash"></i> Remover Bolsa</a>

                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-outdent"></i> Outros</a>
                                                </div>
                                            </div>
                                        
                                        </td>
                                    </tr>   
                                @endforeach                          
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    
    </div>
</section>

<!-- /.content-header -->

@endsection --}}
