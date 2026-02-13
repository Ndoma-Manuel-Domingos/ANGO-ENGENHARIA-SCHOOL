@extends('layouts.escolas')

@section('content')

<div class="p-4 lg:p-8 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 dark:bg-blue-900/30 p-2.5 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 !fill-1">trending_up</span>
                </div>
                <div class="flex items-center gap-1 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2.5 py-1 rounded-full text-xs font-bold">
                    <span class="material-symbols-outlined text-xs">arrow_upward</span>
                    <span>{{ $restante_percentagem + $saida_percentagem }}%</span>
                </div>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Entradas</p>
            <p class="text-3xl font-bold mt-1 text-slate-900 dark:text-white">{{ number_format($pagamentosValoresReceber, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">vs. {{ number_format($pagValReceberUltimoMes, 2, ',', '.') }} no ultimo mês</p>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-red-50 dark:bg-red-900/30 p-2.5 rounded-lg">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 !fill-1">trending_down</span>
                </div>
                <div class="flex items-center gap-1 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2.5 py-1 rounded-full text-xs font-bold">
                    <span class="material-symbols-outlined text-xs">arrow_upward</span>
                    <span>{{ $saida_percentagem }}%</span>
                </div>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Saídas</p>
            <p class="text-3xl font-bold mt-1 text-slate-900 dark:text-white">{{ number_format($pagamentosValoresPagar, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">vs. {{ number_format($pagValPagarUltimoMes, 2, ',', '.') }} no ultimo mês</p>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-emerald-50 dark:bg-emerald-900/30 p-2.5 rounded-lg">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 !fill-1">account_balance</span>
                </div>
                <div class="flex items-center gap-1 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2.5 py-1 rounded-full text-xs font-bold">
                    <span class="material-symbols-outlined text-xs">arrow_upward</span>
                    <span>{{ $restante_percentagem }}%</span>
                </div>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Saldo Líquido</p>
            <p class="text-3xl font-bold mt-1 text-slate-900 dark:text-white">{{ number_format($pagamentosValoresReceber - $pagamentosValoresPagar, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">Growth performance: Healthy</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-lg font-bold">Grafico de controle de pagamentos de propinas mensal</h3>
                </div>
            </div>
    
            <div class="flex-1 min-h-[180px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    
        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold mb-6">Grafico de controle de percentagem de propinas anual</h3>
            <div class="relative w-56 h-56 mx-auto">
                <canvas id="servicesChart"  height="273"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold">Revenue by Course/Year</h3>
                <div class="flex items-center gap-4 text-[10px] uppercase font-bold tracking-wider">
                    <span class="flex items-center gap-1.5">
                        <div class="size-2 bg-indigo-600 rounded-full"></div> High School
                    </span>
                    <span class="flex items-center gap-1.5">
                        <div class="size-2 bg-indigo-400 rounded-full"></div> Middle School
                    </span>
                    <span class="flex items-center gap-1.5">
                        <div class="size-2 bg-indigo-200 rounded-full"></div> Elementary
                    </span>
                </div>
            </div>
            <div class="h-64 flex flex-col justify-between pt-4">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-medium">
                            <span>2024 Academic Year</span>
                            <span class="text-indigo-600 font-bold">$245,000</span>
                        </div>
                        <div
                            class="w-full h-8 flex rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800">
                            <div class="bg-indigo-600 h-full w-[45%]" title="High School"></div>
                            <div class="bg-indigo-400 h-full w-[35%]" title="Middle School"></div>
                            <div class="bg-indigo-200 h-full w-[20%]" title="Elementary"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-medium">
                            <span>2023 Academic Year</span>
                            <span class="text-indigo-600 font-bold">$210,000</span>
                        </div>
                        <div
                            class="w-full h-8 flex rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800">
                            <div class="bg-indigo-600 h-full w-[40%]" title="High School"></div>
                            <div class="bg-indigo-400 h-full w-[40%]" title="Middle School"></div>
                            <div class="bg-indigo-200 h-full w-[20%]" title="Elementary"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-medium">
                            <span>2022 Academic Year (Target)</span>
                            <span class="text-indigo-600 font-bold">$195,000</span>
                        </div>
                        <div
                            class="w-full h-8 flex rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 opacity-60">
                            <div class="bg-indigo-600 h-full w-[35%]" title="High School"></div>
                            <div class="bg-indigo-400 h-full w-[40%]" title="Middle School"></div>
                            <div class="bg-indigo-200 h-full w-[25%]" title="Elementary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="lg:col-span-4 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold mb-6">Financial Health</h3>
            <div class="space-y-4">
                <div
                    class="p-4 bg-green-50 dark:bg-green-900/10 rounded-xl border border-green-100 dark:border-green-900/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-green-600 font-bold">check_circle</span>
                        <p class="text-sm font-bold text-green-800 dark:text-green-400">Stable Operations</p>
                    </div>
                    <p class="text-xs text-green-700 dark:text-green-500/80 leading-relaxed">Liquidity is 15%
                        above the quarterly safety margin. Operational costs remain optimized.</p>
                </div>
                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Priority Alerts</p>
                    <div
                        class="flex items-start gap-3 p-3 bg-red-50 dark:bg-red-900/10 rounded-lg border border-red-100 dark:border-red-900/30">
                        <span class="material-symbols-outlined text-red-600 text-xl">error</span>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">12 Overdue Payments</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pending since Oct 15th
                                (Total: $4,200)</p>
                            <button class="text-[10px] font-bold text-red-600 hover:underline mt-2">SEND
                                REMINDERS</button>
                        </div>
                    </div>
                    <div
                        class="flex items-start gap-3 p-3 bg-amber-50 dark:bg-amber-900/10 rounded-lg border border-amber-100 dark:border-amber-900/30">
                        <span class="material-symbols-outlined text-amber-600 text-xl">warning</span>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Contract Renewal</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Cleaning services
                                contract expires in 12 days.</p>
                            <button class="text-[10px] font-bold text-amber-600 hover:underline mt-2">VIEW
                                CONTRACT</button>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-50 dark:border-slate-800">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-medium text-slate-500">Burn Rate</span>
                        <span class="text-xs font-bold">$10.2k / mo</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="bg-primary h-full w-[42%]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="text-lg font-bold">Profitability by Course</h3>
            <button class="text-primary text-sm font-semibold hover:underline flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">download</span>
                Full Audit Report
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Course Module</th>
                        <th class="px-6 py-4 font-semibold text-center">Gross Revenue</th>
                        <th class="px-6 py-4 font-semibold text-center">Profit Margin</th>
                        <th class="px-6 py-4 font-semibold text-right">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-9 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center font-bold text-xs">
                                    MA</div>
                                <div>
                                    <span class="text-sm font-bold block">Mathematics &amp; Algebra</span>
                                    <span class="text-[10px] text-slate-400">342 active enrollments</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-semibold">$124,500</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-sm font-bold text-green-600">32%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="material-symbols-outlined text-green-500">trending_up</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-9 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center font-bold text-xs">
                                    CS</div>
                                <div>
                                    <span class="text-sm font-bold block">Computer Science</span>
                                    <span class="text-[10px] text-slate-400">215 active enrollments</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-semibold">$88,200</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-sm font-bold text-green-600">45%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="material-symbols-outlined text-green-500">trending_up</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-9 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg flex items-center justify-center font-bold text-xs">
                                    PH</div>
                                <div>
                                    <span class="text-sm font-bold block">Physics &amp; Dynamics</span>
                                    <span class="text-[10px] text-slate-400">189 active enrollments</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-semibold">$62,400</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-sm font-bold text-slate-500">22%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="material-symbols-outlined text-slate-400">trending_flat</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

    fetch('{{ route("financeiros.carregar-mensalidades-por-mes") }}') 
        .then(response => response.json())
        .then(data => {
            const mensal = data.mensalidades;
            const percentuais = data.percentuais;

            // Extrair meses
            const meses = mensal.map(m => m.month_name);

            // Gráfico de barras por mês
            const ctxMensal = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxMensal, {
                type: 'bar', 
                data: {
                    labels: meses, 
                    datasets: [
                        {
                            label: 'Qtd Pago', 
                            data: mensal.map(m => m.total_pago), 
                            backgroundColor: '#22C55E',
                            borderWidth: 2,
                            borderRadius: 30,
                            borderColor: '#15803D',
                            order: 0
                        }, 
                        {
                            label: 'Qtd Dívida', 
                            data: mensal.map(m => m.total_divida), 
                            backgroundColor: '#F59E0B',
                            borderWidth: 2,
                            borderRadius: 30,
                            borderColor: '#B45309',
                            order: 0
                        }, 
                        {
                            label: 'Qtd Não Pago', 
                            data: mensal.map(m => m.total_nao_pago), 
                            backgroundColor: '#EF4444',
                            borderWidth: 2,
                            borderRadius: 30,
                            borderColor: '#B91C1C',
                            order: 0
                        }, 
                        {
                            label: 'Qtd Isento', 
                            data: mensal.map(m => m.total_isento), 
                            backgroundColor: '#3B82F6',
                            borderWidth: 2,
                            borderRadius: 30,
                            borderColor: '#1D4ED8',
                            order: 0
                        }
                    ]
                }, 
                options: {
                    responsive: true, 
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return `${context.dataset.label}: ${value.toLocaleString()}`;
                                }
                            }
                        }
                    }, 
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('pt-AO', {
                                        style: 'currency', 
                                        currency: 'AOA'
                                    })
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de pizza com percentuais
            const ctxPercentual = document.getElementById('servicesChart').getContext('2d');
            new Chart(ctxPercentual, {
                type: 'doughnut', 
                data: {
                    labels: ['Pago', 'Dívida', 'Não Pago', 'Isento'], 
                    datasets: [{
                        data: [
                            percentuais.pago, 
                            percentuais.divida, 
                            percentuais.nao_pago, 
                            percentuais.isento
                        ], 
                        backgroundColor: ['#22C55E', '#F59E0B', '#EF4444', '#3B82F6'],
                        borderColor: ['#15803D','#F59E0B','#B91C1C','#1D4ED8']
                    }]
                }, 
                options: {
                    responsive: true, 
                    plugins: {
                        title: {
                            display: true, 
                            text: 'Percentual dos Valores no Ano (%)'
                        }, 
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${context.raw}%`
                            }
                        }
                    }
                }
            });
    })
    .catch(error => {
        console.error('Erro ao carregar dados do gráfico:', error);
    });
    
    
    function formatarValor(valor) {
        return parseFloat(valor).toLocaleString('pt-AO', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
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
                <h1 class="m-0 text-dark">Painel Financeiro</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Painel Financeiro</a></li>
                    <li class="breadcrumb-item active">Financeiro</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>Grafico de controle de pagamentos de propinas mensal</h5>
                    </div>
                    <div class="card-body">
                        <!-- Gráfico por mês -->
                        <canvas id="graficoMensal" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Grafico de controle de percentagem de propinas anual</h5>
                    </div>
                    <div class="card-body">
                        <!-- Gráfico percentual total -->
                        <canvas id="graficoPercentual" height="273" class="mt-5"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($pagamentosValoresReceber, 2, ',', '.') }} <sub>Kzs</sub> </h3>

                        <p>Contas da Receber Geral [ENTRADAS]</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-contas-receber') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($pagamentosValoresPagar, 2, ',', '.') }} <sub>Kzs</sub></h3>

                        <p>Contas a Pagar Geral [SAÍDAS]</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-contas-pagar') }}" class="small-box-footer bg-danger">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($pagamentosValoresReceber - $pagamentosValoresPagar, 2, ',', '.') }} <sub>Kzs</sub></h3>
                        <p>SALDO ACTUAL NA CONTA</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="" class="small-box-footer bg-success">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif


            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3> {{ number_format($multaAcumuladasPagas, 2, ',', '.') }} <sub>Kzs</sub> </h3>

                        <p>MULTAS ACUMULADAS PAGAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($multaAcumuladasNaoPagas, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>

                        <p>MULTAS ACUMULADAS NÃO PAGAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-danger">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($multaAcumuladasPagas - $multaAcumuladasNaoPagas, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>
                        <p>MULTAS ACUMULADAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-success">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('painel financeiro'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>
                            {{ number_format($dividaAcumuladas, 2, ',', '.') }} <sub>Kzs</sub>
                        </h3>

                        <p>DIVÍDAS GERAIS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.financeiro-gestao-dividas') }}" class="small-box-footer bg-danger">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif


            @if (Auth::user()->can('read: pagamento'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>:</h3>

                        <p>Relatório de Pagamentos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('web.financeiro-buscas-gerais') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: pagamento'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>:</h3>
                        <p>Buscas Mensais</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('web.financeiro-outras-buascas') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif

            @if (Auth::user()->can('read: estudante'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>Estudantes</h3>
                        <p>Listagem dos estudantes com valores da propina</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('financeiros.estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endif
        </div>

    </div><!-- /.container-fluid -->
</div>

@endsection

@section('scripts')
<script>

    fetch('{{ route("financeiros.carregar-mensalidades-por-mes") }}') 
        .then(response => response.json())
        .then(data => {
            const mensal = data.mensalidades;
            const percentuais = data.percentuais;

            // Extrair meses
            const meses = mensal.map(m => m.month_name);

            // Gráfico de barras por mês
            const ctxMensal = document.getElementById('graficoMensal').getContext('2d');
            new Chart(ctxMensal, {
                type: 'bar'
                , data: {
                    labels: meses
                    , datasets: [{
                            label: 'Qtd Pago'
                            , data: mensal.map(m => m.total_pago)
                            , backgroundColor: '#4CAF50'
                        }
                        , {
                            label: 'Qtd Dívida'
                            , data: mensal.map(m => m.total_divida)
                            , backgroundColor: '#F44336'
                        }
                        , {
                            label: 'Qtd Não Pago'
                            , data: mensal.map(m => m.total_nao_pago)
                            , backgroundColor: '#FF9800'
                        }
                        , {
                            label: 'Qtd Isento'
                            , data: mensal.map(m => m.total_isento)
                            , backgroundColor: '#2196F3'
                        }
                    ]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return `${context.dataset.label}: ${value.toLocaleString()}`;
                                }
                            }
                        }
                    }
                    , scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('pt-BR', {
                                        style: 'currency'
                                        , currency: 'BRL'
                                    })
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de pizza com percentuais
            const ctxPercentual = document.getElementById('graficoPercentual').getContext('2d');
            new Chart(ctxPercentual, {
                type: 'doughnut'
                , data: {
                    labels: ['Pago', 'Dívida', 'Não Pago', 'Isento']
                    , datasets: [{
                        data: [
                            percentuais.pago
                            , percentuais.divida
                            , percentuais.nao_pago
                            , percentuais.isento
                        ]
                        , backgroundColor: ['#4CAF50', '#F44336', '#FF9800', '#2196F3']
                    }]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        title: {
                            display: true
                            , text: 'Percentual dos Valores no Ano (%)'
                        }
                        , tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${context.raw}%`
                            }
                        }
                    }
                }
            });
    })
    .catch(error => {
        console.error('Erro ao carregar dados do gráfico:', error);
    });

    function formatarValor(valor) {
        return parseFloat(valor).toLocaleString('pt-AO', {
            minimumFractionDigits: 2
            , maximumFractionDigits: 2
        });
    }
</script>
@endsection --}}
