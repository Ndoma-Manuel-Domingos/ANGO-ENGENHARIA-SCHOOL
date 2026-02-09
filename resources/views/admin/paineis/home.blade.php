@extends('layouts.escolas')

@section('content')

<div class="p-4 lg:p-8 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @if (Auth::user()->can('read: ano lectivo'))
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined p-2 bg-primary/10 text-primary rounded-lg">tune</span>
                    <span
                        class="text-[#07883b] text-sm font-medium flex items-center bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">{{ $verAnoLectivoActivo ? 'activo' : 'desactivo' }}</span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Ano lectivo</span></p>
                <p class="text-2xl font-bold mt-1">{{ $verAnoLectivoActivo ? $verAnoLectivoActivo->ano : 'Desconhecido' }}</p>
            </div>
        @endif
      
        @if (Auth::user()->can('read: estudante'))
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined p-2 bg-accent/10 text-accent rounded-lg">groups</span>
                    <span
                        class="text-[#e73908] text-sm font-medium flex items-center bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">-</span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total de Estudantes</p>
                <p class="text-2xl font-bold mt-1"> {{ $totalEstudantesConfirmados ? $totalEstudantesConfirmados : 0 }} / {{ $totalEstudantesNaoConfirmados }} </p>
            </div>
        @endif
        
        @if (Auth::user()->can('read: professores'))
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined p-2 bg-accent/10 text-accent rounded-lg">groups</span>
                    <span
                        class="text-[#e73908] text-sm font-medium flex items-center bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">-</span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total de Professores</p>
                <p class="text-2xl font-bold mt-1">{{ $totalprofessores }}</p>
            </div>
        @endif
        
        @if (Auth::user()->can('read: pagamento'))
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span
                        class="material-symbols-outlined p-2 bg-secondary/10 text-secondary rounded-lg">account_balance_wallet</span>
                    <span
                        class="text-[#07883b] text-sm font-medium flex items-center bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">+02%</span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Facturação diária</p>
                <p class="text-2xl font-bold mt-1">$450,200</p>
            </div>
        @endif 
        
    </div>

    <div class="space-y-8">
        @if (Auth::user()->can('read: pagamento'))
            <div
                class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Pagamentos recentes</h3>
                    <button class="text-primary text-sm font-semibold hover:underline">Download CSV</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Course Name</th>
                                <th class="px-6 py-3 font-semibold text-center">Enrolled</th>
                                <th class="px-6 py-3 font-semibold text-center">Avg. Performance</th>
                                <th class="px-6 py-3 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 bg-blue-100 text-blue-600 rounded flex items-center justify-center font-bold text-xs">
                                            MA</div>
                                        <span class="text-sm font-medium">Mathematics &amp; Algebra</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">342</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-sm font-bold">8.4</span>
                                        <span
                                            class="material-symbols-outlined text-green-500 text-sm">trending_up</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 uppercase">Active</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 bg-purple-100 text-purple-600 rounded flex items-center justify-center font-bold text-xs">
                                            CS</div>
                                        <span class="text-sm font-medium">Computer Science</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">215</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-sm font-bold">9.1</span>
                                        <span
                                            class="material-symbols-outlined text-green-500 text-sm">trending_up</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 uppercase">Active</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 bg-orange-100 text-orange-600 rounded flex items-center justify-center font-bold text-xs">
                                            PH</div>
                                        <span class="text-sm font-medium">Physics &amp; Dynamics</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">189</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-sm font-bold">7.2</span>
                                        <span
                                            class="material-symbols-outlined text-slate-400 text-sm">trending_flat</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 uppercase">Waitlist</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    
</div>

@endsection

@section('scripts')
    <script>
    </script>
@endsection

