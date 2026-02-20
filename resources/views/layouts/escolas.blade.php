<!DOCTYPE html>
<html class="light" lang="pt-pt">

@php
    $notificacaoes = App\Models\Notificacao::with(['user'])->where('status', true)->orderBy('created_at', 'DESC')->where('shcools_id', $escola->id)->limit(5)->get();
    $total_notificacaoes = App\Models\Notificacao::with(['user'])->where('status', false)->orderBy('created_at', 'DESC')->where('shcools_id', $escola->id)->count();
    $verificarAnoLectivoUsuario = App\Models\web\anolectivo\AnoLectivoUsuario::where('usuario_id', Auth::user()->id)->where('sessao', 'usuariologadoAnoLectivo' . Auth::user()->id)->where('status', 'Activo')->where('shcools_id', $escola->id)->first();
@endphp

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>{{ ENV('APP_NAME') }} | {{ $titulo }}</title> --}}
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "secondary": "#0ea5e9",
                        "accent": "#2dd4bf",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply font-display;
            }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .dropdown-toggle:checked ~ .sidebar-dropdown-content {
            max-height: 300px;
            margin-top: 0.25rem;
            margin-bottom: 0.5rem;
        }
        .dropdown-toggle:checked ~ .menu-item .expand-icon {
            transform: rotate(180deg);
        }
        .sidebar-dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out, margin 0.3s ease-in-out;
        }#sidebar-toggle {
            display: none;
        }
        #sidebar-toggle:checked ~ .sidebar-container {
            transform: translateX(-100%);
        }
        #sidebar-toggle:checked ~ main {
            margin-left: 0;
            width: 100%;
        }
        .profile-dropdown-content, .notification-dropdown-content {
            display: none;
        }
        .profile-group:focus-within .profile-dropdown-content {
            display: block;
        }
        .notification-group:focus-within .notification-dropdown-content {
            display: block;
        }
        .sidebar-container {
            transition: transform 0.3s ease-in-out;
        }
        
        /*.select2-container .select2-selection--single {
            height: 44px;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 6px 3px;
            font-size: 14px;
        }*/
        
        /* Estilo base comum */
        .select2-container .select2-selection {
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        
        /* SINGLE */
        .select2-container .select2-selection--single {
            height: 44px;
            padding: 6px 10px;
            display: flex;
            align-items: center;
        }
        
        /* Texto do single */
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            line-height: normal;
        }
        
        /* MULTIPLE */
        .select2-container .select2-selection--multiple {
            padding: 6px 6px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        /* Tags do multiple */
        .select2-container .select2-selection--multiple .select2-selection__choice {
            margin-top: 4px;
            margin-bottom: 4px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
        }
        
        /* Campo de digitação no multiple */
        .select2-container .select2-selection--multiple .select2-search__field {
            margin-top: 4px;
            font-size: 14px;
        }
        
        .custom-input{
            height: 44px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            font-size: 13px;
        }
        
        main {
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen relative">
    <input id="sidebar-toggle" type="checkbox" />
    <aside class="sidebar-container w-64 fixed top-0 left-0 h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 z-50 flex flex-col">
        
        <div class="p-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('paineis.administrativo') }}" class="bg-primary size-10 rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">school</span>
                </a>
                <div>
                    <h1 class="text-slate-900 dark:text-white text-base font-bold leading-none">{{ ENV('APP_NAME') }}</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-normal">Admin Portal</p>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto mt-2">
            <a href="{{ route('paineis.administrativo') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                <span class="material-symbols-outlined">dashboard</span>
                <p class="text-sm font-medium">Dashboard</p>
            </a>
            
            <div class="relative">
                <input class="dropdown-toggle hidden" id="pedagogical-check" type="checkbox" />
                <label
                    class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors"
                    for="pedagogical-check">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">menu_book</span>
                        <p class="text-sm font-medium">Pedagogical</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary"
                        href="#">Curriculum</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary"
                        href="#">Teachers</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary"
                        href="estudantes.html">Estudantes</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary"
                        href="#">Grades</a>
                </div>
            </div>
            
            <div class="relative">
                <input class="dropdown-toggle hidden" id="financial-check" type="checkbox" />
                <label class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors" for="financial-check">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined !fill-1">payments</span>
                        <p class="text-sm font-semibold">Financeiro</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Controle</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="#">Mapa de Pagamentos</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('home.contas-receber') }}">Entradas</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('home.contas-pagar') }}">Saídas</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('home.gestao-dividas') }}">Gestão de dívidas</a>
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('home.isencoes') }}">Isenção de Serviços</a>
                </div>
            </div>
            
            <div class="relative">
                <input class="dropdown-toggle hidden" id="services-check" type="checkbox" />
                <label class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors" for="services-check">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined !fill-1">receipt_long</span>
                        <p class="text-sm font-semibold">Serviços</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="#">Mapa de Pagamentos</a>
                </div>
            </div>
            
            <div
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                <span class="material-symbols-outlined">workspace_premium</span>
                <p class="text-sm font-medium">Scholarships</p>
            </div>
            
            @if ($escola->modulo != 'Basico')
            <div class="relative">
                <input class="dropdown-toggle hidden" id="config-bolseiros" type="checkbox" />
                <label
                    class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors"
                    for="config-bolseiros">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">school</span>
                        <p class="text-sm font-medium">Credito Educacional</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    @if (Auth::user()->can('read: instituicao'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.instituicoes') }}">Instituições</a>
                    @endif
                    @if (Auth::user()->can('read: bolsa'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.bolsas') }}">Bolsas</a>
                    @endif
                    @if (Auth::user()->can('read: bolseiro'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.bolseiros') }}">Bolseiros</a>
                    @endif
                </div>
            </div>
            
            <div class="relative">
                <input class="dropdown-toggle hidden" id="config-desconto" type="checkbox" />
                <label
                    class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors"
                    for="config-desconto">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">price_check</span>
                        <p class="text-sm font-medium">Descontos</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    @if (Auth::user()->can('read: desconto'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.descontos') }}">Tipos de Descontos</a>
                    @endif
                    @if (Auth::user()->can('read: desconto'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.estudantes-descontos') }}">Listar Estudantes</a>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="relative">
                <input class="dropdown-toggle hidden" id="config-check" type="checkbox" />
                <label
                    class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors"
                    for="config-check">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">settings_suggest</span>
                        <p class="text-sm font-medium">Configurações</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    @if (Auth::user()->can('read: ano lectivo'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.ano-lectivo') }}">Anos Lectivos</a>
                    @endif
                    @if (Auth::user()->can('read: sala'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.salas') }}">Salas</a>
                    @endif
                    @if (Auth::user()->can('read: classe'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.ano-lectivo-classes') }}">Classes</a>
                    @endif
                    @if (Auth::user()->can('read: turno'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.ano-lectivo-turnos') }}">Turnos</a>
                    @endif
                    @if (Auth::user()->can('read: curso'))
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.ano-lectivo-cursos') }}">Cursos</a>
                    @endif
                    <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="#">Turmas</a>
                </div>
            </div>
                
            <div class="relative">
                <input class="dropdown-toggle hidden" id="config-table-support" type="checkbox" />
                <label
                    class="menu-item flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors"
                    for="config-table-support">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">table_chart</span>
                        <p class="text-sm font-medium">Tabela de Apoio</p>
                    </div>
                    <span class="material-symbols-outlined text-sm transition-transform expand-icon">expand_more</span>
                </label>
                <div class="sidebar-dropdown-content bg-slate-50/50 dark:bg-slate-800/30 rounded-lg ml-4 space-y-1">
                    @if (Auth::user()->can('read: extensoes'))
                        <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.extensao') }}">Extensões</a>
                    @endif
                
                    @if ($escola->categoria == 'Privado')
                        @if ($escola->modulo != "Basico")
                        
                            @if (Auth::user()->can('read: faculdade'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.faculdades', ['loyout' => 'escolas']) }}">Faculdades</a>
                            @endif
                        
                            @if (Auth::user()->can('read: universidade'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.universidades', ['loyout' => 'escolas']) }}">Universidades</a>
                            @endif
                            
                            @if (Auth::user()->can('read: especialidade'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.especialidades', ['loyout' => 'escolas']) }}">Especialidades</a>
                            @endif
                            
                            @if (Auth::user()->can('read: categoria'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.categorias', ['loyout' => 'escolas']) }}">Categorias</a>
                            @endif
                            
                            {{-- @if (Auth::user()->can('read: canditadura'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.escolaridades') }}">Candidaturas</a>
                            @endif --}}
                            
                            @if (Auth::user()->can('read: escolaridade'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.escolaridades') }}">Escolaridades</a>
                            @endif
                     
                            @if (Auth::user()->can('read: formacao academico'))
                             <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.formacao-academico') }}">Formação Academicas</a>
                            @endif
                 
                            @if (Auth::user()->can('read: banco'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.bancos') }}">Bancos</a>
                            @endif
                
                            @if (Auth::user()->can('read: caixa'))
                            <a class="block px-8 py-2 text-xs text-slate-500 hover:text-primary dark:hover:text-primary" href="{{ route('web.caixas') }}">Caixas</a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            
        </nav>
        
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-800 rounded-xl">
                <img alt="User profile" class="size-10 rounded-full object-cover"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAoNV-d4mdvQG147zUDY4qKO9uRu9YCgusGkulUI-ICn7wc2arG0E6mczw-Z7U2kil6ktsjQ2iizoumydF7O58He5-_yN0TGbaviufNXbrTXRlIBvqrtEuIX_XsvvpKe_7Wpj-caKRXCjD-hSH67Iq0cTgA7ggeQyCNrcHlyRtrwR6-iknqV8FTktBfpBz8wuaL5lav_EF4sUYIYxZ6u8LrofqkzI_PiahcjbGM7CKXxOy6UWKsZyh6Trb53Gx9yT8EMaLDtMiZkUjV" />
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ Auth::user()->nome }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->acesso }}</p>
                </div>
            </div>
        </div>
        
    </aside>
    <main class="ml-64 min-h-screen bg-background-light dark:bg-background-dark">
        
        <header
            class="flex items-center justify-between bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 lg:px-8 py-4 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <label
                    class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg cursor-pointer flex items-center justify-center transition-colors"
                    for="sidebar-toggle">
                    <span class="material-symbols-outlined">menu</span>
                </label>
                <div class="relative w-40 sm:w-64 lg:w-72">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input
                        class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary"
                        placeholder="Search..." type="text" />
                </div>
                
                @if ($escola->fnc_dias_licencas() >= 30)
                    <h2 class="text-lg lg:text-xl font-bold tracking-tight hidden sm:block text-green-500">Faltam {{ $escola->fnc_dias_licencas() }} para expirar a Licença</h2>
                @else
                    <h2 class="text-lg lg:text-xl font-bold tracking-tight hidden sm:block text-red-500">Faltam {{ $escola->fnc_dias_licencas() }} para expirar a Licença</h2>
                @endif
                
                
            </div>
            <div class="flex items-center gap-2 lg:gap-4">
                <button
                    class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    onclick="document.documentElement.classList.toggle('dark')">
                    <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                    <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                </button>
                
                <div class="relative notification-group">
                    <button
                        class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg relative focus:outline-none">
                        <span class="material-symbols-outlined">notifications</span>
                        <span
                            class="absolute top-2.5 right-2.5 size-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                    </button>
                    <div
                        class="notification-dropdown-content absolute right-0 mt-2 w-80 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-bold">Nofiticações</h3>
                        </div>
                        <div class="max-h-[400px] overflow-y-auto">
                            @foreach ($notificacaoes as $item)
                            <div
                                class="p-4 border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                <div class="flex gap-3">
                                    <div
                                        class="flex-shrink-0 size-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-lg">analytics</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $item->user->nome ?? ''}}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ Str::limit($item->notificacao, 35, ' (...)') }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-semibold">{{ date('d-m-Y', strtotime($item->created_at)) }} ás {{ date('H:i:s', strtotime($item->created_at)) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/50 text-center">
                            <a class="text-xs font-semibold text-primary hover:underline" href="{{ route('notificacoes.index', ['notification' => 'all']) }}">Ver todas notificações</a>
                        </div>
                    </div>
                </div>
                
                <div class="relative profile-group">
                    <button class="flex items-center gap-2 p-1 pl-1 pr-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-all focus:outline-none">
                        <img alt="Sarah Jenkins"
                            class="size-8 rounded-full object-cover border border-slate-200 dark:border-slate-700"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAoNV-d4mdvQG147zUDY4qKO9uRu9YCgusGkulUI-ICn7wc2arG0E6mczw-Z7U2kil6ktsjQ2iizoumydF7O58He5-_yN0TGbaviufNXbrTXRlIBvqrtEuIX_XsvvpKe_7Wpj-caKRXCjD-hSH67Iq0cTgA7ggeQyCNrcHlyRtrwR6-iknqV8FTktBfpBz8wuaL5lav_EF4sUYIYxZ6u8LrofqkzI_PiahcjbGM7CKXxOy6UWKsZyh6Trb53Gx9yT8EMaLDtMiZkUjV" />
                        <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
                    </button>
                    <div class="profile-dropdown-content absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl z-50">
                        <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                            <p class="text-sm font-bold">{{ Auth::user()->nome }}</p>
                            <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-2">
                            <a class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg"
                                href="{{ route('informacoes-escolares.editar', Crypt::encrypt($escola->id)) }}">
                                <span class="material-symbols-outlined text-lg">settings</span>
                                Configuração Empresa
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg"
                                href="{{ route('configuracao-carto-funcionario.index') }}">
                                <span class="material-symbols-outlined text-lg">settings_suggest</span>
                                Configuração Cartões
                            </a>
                            {{-- <a class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg"
                                href="{{ route('est.privacidade') }}">
                                <span class="material-symbols-outlined text-lg">person</span>
                                Actualizar Dados
                            </a> --}}
                            <div class="my-1 border-t border-slate-100 dark:border-slate-800"></div>
                            <a href="{{ route('logout') }}"
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                Termissão Sessão
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </header>
        
        @yield('content')

        
    </main>
</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function inicializarTabela(selector) {
            const tabela = $(selector).DataTable({
                language: {
                    url: "{{ asset('plugins/datatables/pt_br.json') }}"
                }
                , responsive: true
                , lengthChange: false
                , autoWidth: false
                , buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            tabela.buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');
        }

        function verificarLicenca() {
            // Não mostrar novamente hoje
            const ignorarHoje = localStorage.getItem("ignorar_alerta_licenca_software");
            const hoje = new Date().toISOString().slice(0, 10);

            if (ignorarHoje === hoje) {
                return; // já foi ignorado hoje
            }

            fetch('/paineis/verificar/licenca-validade')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Informação Importante: Licença do Software!'
                            , text: `A sua licença expirará em ${data.dias_restantes} dias. Renove-a para continuar a usufruir do sistema sem interrupções.`
                            , icon: 'warning'
                            , showDenyButton: true
                            , showCancelButton: true
                            , confirmButtonText: 'Renovar licença agora'
                            , denyButtonText: 'Não mostrar hoje'
                            , cancelButtonText: 'Ignorar por agora'
                        , }).then((result) => {
                            if (result.isConfirmed) {
                                // Mostra os detalhes dos lotes
                                let mensagem = "";
                                mensagem +=
                                    `<div style="text-align: center;"><a href="#" style="font-size: 30px;color: #000000;font-family: arial;">Liga para o terminar <br/>+244 974-50-70-34 <br/> e renova sua licença</a></div>`;
                                Swal.fire({
                                    title: 'Renovar licença agora'
                                    , icon: 'info'
                                    , html: `<pre style="text-align:left">${mensagem}</pre>`
                                , });

                            } else if (result.isDenied) {
                                // Salva para não mostrar novamente hoje
                                localStorage.setItem("ignorar_alerta_licenca_software", hoje);
                            }
                            // Cancelado = ignorar por agora (não faz nada)
                        });
                    }
                })
                .catch(err => console.error("Erro ao validação de licença:", err));
        }

        function carregarDados(opcoes) {
            const id = $(opcoes.origem).val();
            const rota = opcoes.rota.replace(':id', id);

            $.ajax({
                url: rota
                , type: 'GET'
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(data) {
                    Swal.close();
                    showMessage('Sucesso!', opcoes.mensagemSucesso, 'success');
                    $(opcoes.destino).html(data);
                }
                , error: function(xhr) {
                    Swal.close();
                    const erro = xhr.responseJSON ? xhr.responseJSON.message : 'Erro inesperado ao carregar dados.';
                    showMessage('Erro!', erro, 'error');
                }
            });
        }

        function ajaxFormSubmit(formSelector) {
            $(document).on('submit', formSelector, function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = form.serialize();

                $.ajax({
                    url: form.attr('action')
                    , method: form.attr('method')
                    , data: formData
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , beforeSend: function() {
                        progressBeforeSend(); // opcional
                    }
                    , success: function(response) {
                        Swal.close();
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = '';
                            $.each(errors, function(key, value) {
                                messages += `${value}\n *`;
                            });
                            showMessage('Erro de Validação!', messages, 'error');
                        } else {
                            showMessage('Erro!', xhr.responseJSON ? xhr.responseJSON.message : 'Erro inesperado.', 'error');
                        }
                    }
                });
            });
        }

        // Função genérica para exclusão
        function excluirRegistro(selector, routeName) {
            $(document).on('click', selector, function(e) {
                e.preventDefault();
                const recordeID = $(this).attr('id');

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
                            method: 'DELETE'
                            , url: routeName.replace(':id', recordeID)
                            , data: {
                                _token: '{{ csrf_token() }}'
                            }
                            , beforeSend: function() {
                                progressBeforeSend();
                            }
                            , success: function(response) {
                                Swal.close();
                                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                                window.location.reload();
                            }
                            , error: function(xhr) {
                                Swal.close();
                                showMessage('Erro!', xhr.responseJSON ? xhr.responseJSON.message : 'Erro ao excluir.', 'error');
                            }
                        });
                    }
                });
            });
        }

        function bindStatusUpdate(selector, routeName) {
            $(document).on('click', selector, function(e) {
                e.preventDefault();
                const recordId = $(this).attr('id');

                Swal.fire({
                    title: 'Você tem certeza?'
                    , text: "Esta ação não poderá ser desfeita!"
                    , icon: 'warning'
                    , showCancelButton: true
                    , confirmButtonColor: '#d33'
                    , cancelButtonColor: '#3085d6'
                    , confirmButtonText: 'Sim, actualizar!'
                    , cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: routeName.replace(':id', recordId)
                            , method: 'GET'
                            , data: {
                                _token: '{{ csrf_token() }}'
                            }
                            , beforeSend: function() {
                                progressBeforeSend(); // Se estiver a usar um loader
                            }
                            , success: function(response) {
                                Swal.close();
                                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                                window.location.reload();
                            }
                            , error: function(xhr) {
                                Swal.close();
                                showMessage('Erro!', xhr.responseJSON ? xhr.responseJSON.message : 'Erro ao processar.', 'error');
                            }
                        });
                    }
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const toggleThemeBtn = document.getElementById("toggle-theme");
            const icon = toggleThemeBtn.querySelector("i");
            const text = toggleThemeBtn.querySelector("span");

            // Verifica se o usuário já tem um tema salvo
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark-mode");
                icon.classList.replace("fa-moon", "fa-sun");
                // text.textContent = "Modo Claro";
            }

            toggleThemeBtn.addEventListener("click", function(event) {
                event.preventDefault();

                if (document.body.classList.contains("dark-mode")) {
                    document.body.classList.remove("dark-mode");
                    localStorage.setItem("theme", "light");
                    icon.classList.replace("fa-sun", "fa-moon");
                    // text.textContent = "Modo Escuro";
                } else {
                    document.body.classList.add("dark-mode");
                    localStorage.setItem("theme", "dark");
                    icon.classList.replace("fa-moon", "fa-sun");
                    // text.textContent = "Modo Claro";
                }
            });
        })

        const rotas = {
            carregarMunicipios: "{{ route('web.carregar-municipios', ':id') }}"
            , carregarDistritos: "{{ route('web.carregar-distritos', ':id') }}"
            , carregarCargos: "{{ route('web.carregar-cargos-departamentos', ':id') }}"
            , carregarDestinoFuncionario: "{{ route('web.carregar-destino-funcionarios', ':id') }}"
        };

        // Verifica agora e repete a cada 5 minutos
        verificarLicenca();
        setInterval(verificarLicenca, 500000); // 5 minutos
        // setInterval(verificarLicenca, 100000); // 5 minutos


        function progressBeforeSend(title = "Processando...", text = "Por favor, aguarde.", icon = 'info') {
            Swal.fire({
                title: title
                , text: text
                , icon: icon
                , allowOutsideClick: false
                , showConfirmButton: false
                , didOpen: () => {
                    Swal.showLoading();
                }
            , });
        }

        function showMessage(title, text, icon) {
            Swal.fire({
                icon: icon
                , title: title
                , text: text
                , toast: true
                , position: 'top-end'
                , showConfirmButton: false
                , timer: 5000
            , });
        }

        var timerID = null;
        var timerRunning = false;

        function stopclock() {
            if (timerRunning)
                clearTimeout(timerID);
            timerRunning = false;
        }

        function showtime() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds()

            var timeValue = "" + ((hours > 24) ? hours - 24 : hours)

            if (timeValue == "0") timeValue = 24;
            timeValue += ((minutes < 10) ? ":0" : ":") + minutes
            timeValue += ((seconds < 10) ? ":0" : ":") + seconds
            timeValue += (hours >= 24) ? "" : ""
            document.clock.face.value = timeValue;
            timerID = setTimeout("showtime()", 1000);
            timerRunning = true;
        }

        function startclock() {
            stopclock();
            showtime();
        }
        window.onload = startclock;
        // End -->

        $(function() {
            $('.select2').select2()
        });

        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
        
        // INICIO - PAGINAÇÃO AJAX GLOBAL 
        function updateResultsInfo(res) {
            $("#from").text(res.from ?? 0);
            $("#to").text(res.to ?? 0);
            $("#total").text(res.total ?? 0);
        }
        
        function paginate(res) {
            let currentPage = res.current_page;
            let lastPage = res.last_page;
        
            let html = `<div class="flex items-center gap-1">`;
        
            // ⬅ Botão Anterior
            if (currentPage > 1) {
                html += `
                    <button 
                        class="p-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        onclick="load(${currentPage - 1})"
                    >
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                `;
            } else {
                html += `
                    <button class="p-2 rounded-lg text-slate-400 transition-colors disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                `;
            }
        
            // 📄 Cálculo de páginas visíveis
            let start = Math.max(1, currentPage - 2);
            let end   = Math.min(lastPage, currentPage + 2);
        
            // Primeira página
            if (start > 1) {
                html += pageButton(1, currentPage);
                if (start > 2) {
                    html += `<span class="px-2 text-slate-400 text-xs">...</span>`;
                }
            }
        
            // Páginas centrais
            for (let i = start; i <= end; i++) {
                html += pageButton(i, currentPage);
            }
        
            // Última página
            if (end < lastPage) {
                if (end < lastPage - 1) {
                    html += `<span class="px-2 text-slate-400 text-xs">...</span>`;
                }
                html += pageButton(lastPage, currentPage);
            }
        
            // ➡ Botão Próximo
            if (currentPage < lastPage) {
                html += `
                    <button 
                        class="p-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        onclick="load(${currentPage + 1})"
                    >
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                `;
            } else {
                html += `
                    <button class="p-2 rounded-lg text-slate-400 transition-colors disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                `;
            }
        
            html += `</div>`;
        
            $("#pagination").html(html);
        }

        function pageButton(page, currentPage) {
            if (page === currentPage) {
                return `
                    <button class="w-8 h-8 rounded-lg bg-primary text-white text-xs font-bold">
                        ${page}
                    </button>
                `;
            }
        
            return `
                <button 
                    class="w-8 h-8 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold transition-colors"
                    onclick="load(${page})"
                >
                    ${page}
                </button>
            `;
        }
        // FINAL - PAGINAÇÃO AJAX GLOBAL 
        
        function formatar_moeda(value) {
            return value.toLocaleString('pt-AO', {
                style: 'currency',
                currency: 'AOA'
            });
        }
        
        function formatarData(isoString) {
          const data = new Date(isoString);
        
          const horas = String(data.getUTCHours()).padStart(2, '0');
          const minutos = String(data.getUTCMinutes()).padStart(2, '0');
          const segundos = String(data.getUTCSeconds()).padStart(2, '0');
        
          const dia = String(data.getUTCDate()).padStart(2, '0');
          const mes = String(data.getUTCMonth() + 1).padStart(2, '0');
          const ano = data.getUTCFullYear();
        
          return `${horas}:${minutos}:${segundos} - ${dia}/${mes}/${ano}`;
        }
        
        function descricao_mes($string)
        {
            if ($string == "Nov") {
                return "Novembro";
            }
            if ($string == "Dec") {
                return "Dezembro";
            }
            if ($string == "Jan") {
                return "Janeiro";
            }
            if ($string == "Feb") {
                return "Fevereiro";
            }
            if ($string == "Mar") {
                return "Março";
            }
            if ($string == "Apr") {
                return "Abril";
            }
            if ($string == "May") {
                return "Maio";
            }
            if ($string == "Jun") {
                return "Junho";
            }
            if ($string == "Jul") {
                return "Julho";
            }
            if ($string == "Aug") {
                return "Agosto";
            }
            if ($string == "Sep") {
                return "Setembro";
            }
            if ($string == "Oct") {
                return "Outumbro";
            }
        }
        
    </script>
    @yield('scripts')
</html>