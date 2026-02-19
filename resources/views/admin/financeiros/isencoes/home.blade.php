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
            <button id="btnExportPDF" type="button" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-red-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-success/30 hover:bg-red-500/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">picture_as_pdf</span>
                <span>Imprimir PDF</span>
            </button>
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
                            <option>All Categories</option>
                            <option>Mandatory</option>
                            <option>Optional</option>
                            <option>Extra-curricular</option>
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
                <div class="p-6 bg-gray-50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-500">2 services exempted of 4 total services listed.</p>
                    <button class="w-full sm:w-auto px-8 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 transition-all text-sm font-bold shadow-md shadow-primary/20 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span> Save Exemption Changes
                    </button>
                </div>
            </div>
            
            <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 flex gap-4">
                <span class="material-symbols-outlined text-amber-600">info</span>
                <div>
                    <h5 class="text-sm font-bold text-amber-800 dark:text-amber-400">Audit Trail Note</h5>
                    <p class="text-xs text-amber-700 dark:text-amber-500 mt-1">Exemption changes are logged and
                        will be visible in the student's final financial report. Make sure to provide a
                        justification if requested by the audit committee.</p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="bg-white dark:bg-sidebar-dark rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="w-full lg:w-1/3 flex flex-col gap-6">
                <div
                    class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-40 border-4 border-gray-50 dark:border-gray-800 shadow-lg"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA2h9ukiMynWl8KZYdLm4nvdlwMxyHpv7WBgFndkUV1fAqbAcVz6YPBdkaRNMVF2gx4tBU7pS0ltT5nleewEx0hDZ6dohpRpGzj0AWkxWOsaipO8hRkGST3xEJbjETbFkkSAECPdATjwwCosLFwXcZrMms3_DaYw82PxHAL4IgvsqUb2yeVXAhiBdax-9EyhjSuWMAMHeJfHpem8kYJl5bxqBE8BCAnItxuXALAsP6xRxoGKlS-LQo0o2_cTsnT8zo7BFGeIjJUWkJ-");'>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-[#111318] dark:text-white">Alex Johnson</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">ID: #STU-9942 • Grade 11-B
                            </p>
                        </div>
                    </div>
                    <div class="mt-8 space-y-4 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Currently
                            Exempted Services</h4>
                        <div class="space-y-3">
                            <div
                                class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary text-[20px]">verified</span>
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Sports Facility
                                        Fee</span>
                                </div>
                                <span class="text-xs font-bold text-primary">Active</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary text-[20px]">verified</span>
                                    <span
                                        class="text-sm font-bold text-gray-700 dark:text-gray-300">Transportation</span>
                                </div>
                                <span class="text-xs font-bold text-primary">Active</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500 italic mt-2 text-center">These services are fully
                            discounted for the current period.</p>
                    </div>
                </div>
            </aside>
            <div class="w-full lg:w-2/3 flex flex-col gap-6">
                <div
                    class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <div
                        class="p-6 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white">Services Eligible for Exemption
                        </h3>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <select
                                class="form-select text-sm font-medium border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-lg focus:ring-primary focus:border-primary text-gray-600 dark:text-gray-300 pr-10">
                                <option>All Categories</option>
                                <option>Mandatory</option>
                                <option>Optional</option>
                                <option>Extra-curricular</option>
                            </select>
                            <button
                                class="p-2 border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <span class="material-symbols-outlined text-gray-500">filter_list</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800">
                                <tr>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Service
                                        Detail</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Standard
                                        Amount</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Status</th>
                                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] text-right">
                                        Action (Isentar)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">Tuition Fee -
                                                Annual</span>
                                            <span class="text-xs text-gray-500">Category: Mandatory Academic</span>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 font-bold text-gray-900 dark:text-white line-through opacity-50">
                                        $1,200.00</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            EXEMPTED
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input checked=""
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                id="toggle1" name="toggle" type="checkbox" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                                for="toggle1"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">Computer Lab
                                                Access</span>
                                            <span class="text-xs text-gray-500">Category: Laboratory</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">$150.00</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            PENDING
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                id="toggle2" name="toggle" type="checkbox" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                                for="toggle2"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">Sports Facility
                                                Membership</span>
                                            <span class="text-xs text-gray-500">Category: Extra-curricular</span>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 font-bold text-gray-900 dark:text-white line-through opacity-50">
                                        $85.00</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            EXEMPTED
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input checked=""
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                id="toggle3" name="toggle" type="checkbox" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                                for="toggle3"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">Library Premium
                                                Access</span>
                                            <span class="text-xs text-gray-500">Category: Resources</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">$45.00</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            PENDING
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                id="toggle4" name="toggle" type="checkbox" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                                for="toggle4"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="p-6 bg-gray-50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-xs text-gray-500">2 services exempted of 4 total services listed.</p>
                        <button
                            class="w-full sm:w-auto px-8 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 transition-all text-sm font-bold shadow-md shadow-primary/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Save Exemption Changes
                        </button>
                    </div>
                </div>
                <div
                    class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 flex gap-4">
                    <span class="material-symbols-outlined text-amber-600">info</span>
                    <div>
                        <h5 class="text-sm font-bold text-amber-800 dark:text-amber-400">Audit Trail Note</h5>
                        <p class="text-xs text-amber-700 dark:text-amber-500 mt-1">Exemption changes are logged and
                            will be visible in the student's final financial report. Make sure to provide a
                            justification if requested by the audit committee.</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
       
</div>
@endsection

@section('scripts')
    <script>
        
        $("#designacao_geral").on('input', function () {
            
            let valor = $(this).val().trim();
        
            if (valor.length >= 4) {
                load(1);
            }

        });
    
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
                    
                    let student_info = data.cartoes[0];
                    
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
                        
                        if(s.status_multa == "I") {
                            multa_stat += `
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">Isento</span>
                                        <span class="text-xs text-gray-500">Category: ${s.motivo_isencao_multa}</span>
                                    </div>
                                </td>
                            `;
                        }else {
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
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input checked="" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" id="toggle1" name="toggle" type="checkbox" />
                                        <label class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer" for="toggle1"></label>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                 
                    $("#tbody").html(rows);
                    
                },
                error: function (xhr) {
                    Swal.close();
                    console.error(xhr);
                }
            });
        }
    </script>
@endsection 