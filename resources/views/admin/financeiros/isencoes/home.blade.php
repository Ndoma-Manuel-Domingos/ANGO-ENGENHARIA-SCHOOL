@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Finenceiros</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">gestão isenções</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestão de Isenções</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            <a href="{{ route('paineis.administrativo') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Voltar</span>
            </a>
        </div>
    </div>
    
    <div class="mb-8 max-w-2xl">
        <label class="flex flex-col w-full h-12">
            <div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="text-gray-400 flex bg-white dark:bg-gray-800 items-center justify-center pl-4 rounded-l-xl">
                    <span class="material-symbols-outlined text-[24px]">person_search</span>
                </div>
                <input id="designacao_geral" class="form-input flex w-full min-w-0 flex-1 border-none bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:ring-0 h-full placeholder:text-gray-500 px-4 rounded-r-xl text-base font-normal"
                    placeholder="Search Student for Exemption (Name, ID or Email)..." />
            </div>
        </label>
    </div>
      
    <div class="flex flex-col lg:flex-row gap-8">
        <aside class="w-full lg:w-1/3 flex flex-col gap-6">
            <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm">
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-40 border-4 border-gray-50 dark:border-gray-800 shadow-lg"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDsgKNxKR2o3wdqTxBtHO2NDQyIKGlt1QFuc_HUpsPfaMpYfVmEQn0EfZx0LsVviggI0zxT2YYoFK87FVeJxWOc5vj_a3Mfvuo-iNzohFwJI3YQOwQIteeHDVejXSp2xLUlY80_BsJdb-HdQKd5gv-GOkQMtBLw61mxEfS62ajJwD7sC9SWF2o36RpekcvzeVNOavysSsp5lWffq1xoX7QVEHmJgrth0_b92lHr0_wUROLvxmKyLbYA0VV-eLxYrbo5ZPtIOYeq_Gl7");'>
                    </div>
                    <div id="Student">
                       {{-- carregar AJAX --}}
                    </div>
                </div>
                <div class="mt-8 space-y-4 pt-8 border-t border-gray-100 dark:border-gray-800">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Currently Exempted Services</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">verified</span>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Sports Facility Fee</span>
                            </div>
                            <span class="text-xs font-bold text-primary">Active</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">verified</span>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Transportation</span>
                            </div>
                            <span class="text-xs font-bold text-primary">Active</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 italic mt-2 text-center">These services are fully discounted for the current period.</p>
                </div>
            </div>
        </aside>
        <div class="w-full lg:w-2/3 flex flex-col gap-6">
        
            <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm overflow-hidden flex flex-col">
                
                <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-bold text-[#111318] dark:text-white">Serviços Elegíveis para Isenção</h3>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select class="form-select text-sm font-medium border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-lg focus:ring-primary focus:border-primary text-gray-600 dark:text-gray-300 pr-10">
                            <option>Todo Serviços</option>
                        </select>
                        <button class="p-2 border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <span class="material-symbols-outlined text-gray-500">filter_list</span>
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Serviço</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Mês</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Valor</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Multa</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Status</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Status Multa</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] text-right"> Action (Isentar)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="tbody">
                            {{-- carregar AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
 
</div>
@endsection

@section('scripts')
    <script>
        
        $("#designacao_geral").on('keydown', function (e) {
            if (e.key === "Enter") {
                let valor = $(this).val().trim();
                if (valor.length >= 2) {
                    load(1);
                }
            }
        });
        
        function exibirTabela(data)
        {
            // verifica se cartoes existe e tem itens
            if (!data.cartoes || data.cartoes.length === 0) {
        
                Swal.fire({
                    icon: 'error',
                    title: 'Estudante não encontrado',
                    text: 'Nenhum estudante corresponde aos critérios da pesquisa.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'bg-primary text-white rounded-lg px-4 py-2 hover:bg-primary/90 transition-all'
                    },
                    background: '#fff',
                });
        
                return;
            }
        
            let student_info = data.cartoes[0];
        
            // verifica se estudante existe
            if (!student_info.estudante) {
        
                Swal.fire({
                    icon: 'error',
                    title: 'Dados incompletos',
                    text: 'O estudante não possui informações associadas.',
                    confirmButtonText: 'OK',
                });
        
                return;
            }

            $("#Student").empty();
            
            $("#Student").html(`
                <div id="Student">
                    <h2 class="text-2xl font-bold text-[#111318] dark:text-white">${student_info.estudante?.nome} ${student_info.estudante?.sobre_nome}</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">ID: ${student_info.estudante?.numero_processo} • ${student_info?.estudante?.matricula?.curso?.curso}</p>
                </div>
            `);
            
            Swal.close();
            let rows = "";
            data.cartoes.forEach(s => {
            
                let stat = "";
                let multa_stat = "";
                
                let btnIsencaoMes = "";
                let btnIsencaoMulta = "";
                
                if(s.status != "Isento") {
                    btnIsencaoMes += `
                        <button 
                            class="p-2 text-green-600 hover:text-white hover:bg-green-600/90 rounded-lg transition-all mudar-record"
                            title="Isentar o mês"
                            data-id="${s.id}"
                            data-acao="isentar-mes"
                        >
                            <span class="material-symbols-outlined text-xl">
                                event_available
                            </span>
                        </button>
                    `;
                }else {
                    btnIsencaoMes += `
                        <button 
                            class="p-2 text-red-600 hover:text-white hover:bg-red-600/90 rounded-lg transition-all mudar-record"
                            title="Remover Isentar do mês"
                            data-id="${s.id}"
                            data-acao="isentar-remover-mes"
                        >
                            <span class="material-symbols-outlined text-xl">
                                event_available
                            </span>
                        </button>
                    `;
                }
                
                if(s.status_multa == "I") {
                    
                    btnIsencaoMulta += `
                        <button 
                            class="p-2 text-red-600 hover:text-white hover:bg-red-600/90 rounded-lg transition-all mudar-record"
                            title="Remover Isentção da multa"
                            data-id="${s.id}"
                            data-acao="remover-isentar-multa"
                        >
                            <span class="material-symbols-outlined text-xl">
                                money_off
                            </span>
                        </button>
                    `;
                
                    multa_stat += `
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-white">Isento</span>
                                <span class="text-xs text-gray-500">Category: ${s.motivo_isencao_multa}</span>
                            </div>
                        </td>
                    `;
                }else {
                
                    btnIsencaoMulta += `
                        <button 
                            class="p-2 text-orange-600 hover:text-white hover:bg-orange-600/90 rounded-lg transition-all mudar-record"
                            title="Isentar a multa"
                            data-id="${s.id}"
                            data-acao="isentar-multa"
                        >
                            <span class="material-symbols-outlined text-xl">
                                money_off
                            </span>
                        </button>
                    `;
                
                    multa_stat += `
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                N/Isento
                            </span>
                        </td>
                    `;
                }
                
                if(s.status == "Pago") {
                    stat += `
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                ${s.status}
                            </span>
                        </td>
                    `;
                } else if(s.status == "divida") {
                    stat += `
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                ${s.status}
                            </span>
                        </td>
                    `;
                } else if(s.status == "Nao Pago") {
                    stat += `
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                ${s.status}
                            </span>
                        </td>
                    `;
                }else{
                    stat += `
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                ${s.status}
                            </span>
                        </td>
                    `;
                }
            
                rows += `
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-white">${s.servico.servico}</span>
                                <span class="text-xs text-gray-500">Category: ${s.servico.contas}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">${descricao_mes(s.month_name)}</td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">${formatar_moeda(s.preco_unitario)}</td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">${formatar_moeda(s.multa)}</td>
                        ${stat}
                        ${multa_stat}
                        <td class="px-6 py-4 text-right">
                            <!-- Isentar o Mês -->
                            ${btnIsencaoMes}
                            
                            <!-- Isentar a Multa -->
                            ${btnIsencaoMulta}
                            
                            <!-- Editar Multa -->
                            <button 
                                class="p-2 text-blue-600 hover:text-white hover:bg-blue-600/90 rounded-lg transition-all mudar-record"
                                title="Editar multa"
                                data-id="${s.id}"
                                data-acao="editar-multa"
                            >
                                <span class="material-symbols-outlined text-xl">
                                    edit
                                </span>
                            </button>
                        </td>
                    </tr>
                `;
            });
         
            $("#tbody").html(rows);
        }
    
        function load(page=1){
            $.ajax({
                type: "GET",
                url: "/isencoes",
                data: {
                    page: page,
                    designacao_geral: $("#designacao_geral").val(),
                },
                dataType: "json",
                beforeSend: function () {
                    // opcional: mostrar loader
                    progressBeforeSend("Carregando...");
                },
                success: function (data) {
                    exibirTabela(data);                    
                },
                error: function (xhr) {
                    Swal.close();
                    console.error(xhr);
                }
            });
        }
        
        $(document).on('click', '.mudar-record', function(e) {

            e.preventDefault();
            let recordId = $(this).data('id');
            let acaoId = $(this).data('acao');
    
            Swal.fire({
                title: 'Você tem certeza?'
                , text: "Esta ação poderá ser desfeita!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#d33'
                , cancelButtonColor: '#3085d6'
                , confirmButtonText: `Sim, ${acaoId}!`
                , cancelButtonText: 'Cancelar'
            , }).then((result) => {
                if (result.isConfirmed) {
                    
                    Swal.fire({
                        title: acaoId == "editar-multa" ? "Informe o novo valor" : "Descreva o motivo",
                        input: acaoId == "editar-multa" ? "number" : "textarea",
                        inputLabel: acaoId == "editar-multa" ? "Valor" : "Motivo da alteração",
                        inputPlaceholder:  acaoId == "editar-multa" ? "Digite o motante" : "Escreva aqui o motivo...",
                        inputAttributes: {
                            autocapitalize: "off",
                            rows: 4
                        },
                        showCancelButton: true,
                        confirmButtonText: "Enviar",
                        cancelButtonText: "Cancelar",
                        customClass: {
                            confirmButton: "bg-primary text-white px-4 py-2 rounded-lg",
                            cancelButton: "bg-gray-300 text-gray-700 px-4 py-2 rounded-lg"
                        },
                        preConfirm: (value) => {
                            if (!value) {
                                Swal.showValidationMessage("O campo é obrigatório");
                            }
                            return value;
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                          if (result.isConfirmed) {
                            // Envia a solicitação AJAX para excluir o registro
                            $.ajax({
                                url: `{{ route('isencoes.update', ':id') }}`.replace(':id', recordId),
                                method: 'PUT',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    action: acaoId,
                                    messegem: result.value
                                },
                                beforeSend: function () {
                                    progressBeforeSend();
                                },
                                success: function (data) {
                                    Swal.close();
                                    exibirTabela(data);
                                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                                },
                                error: function (xhr) {
                                    Swal.close();
                                    showMessage('Erro!', xhr.responseJSON?.message ?? 'Erro inesperado', 'error');
                                }
                            });
                          }
                        });
                    
                
                }
            });
        });
        
    </script>
@endsection 