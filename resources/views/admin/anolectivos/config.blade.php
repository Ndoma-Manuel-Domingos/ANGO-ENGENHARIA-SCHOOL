@extends('layouts.escolas')

@section('content')
<div class="p-4 lg:p-8 space-y-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <span>Dashboard</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Configurações</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span>Anos Lectivos</span>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">configuração ano lectivo</span>
    </nav>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Configuração do Ano lectivo</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1"></p>
        </div>
        <div>
            <a href="{{ route('web.ano-lectivo') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/30 hover:bg-primary/90 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Voltar</span>
            </a>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row gap-8 mt-6">
    
        <div class="flex-1 flex flex-col gap-6">
            <!-- Section: Personal Data -->
            <form id="cadastrarCursoS" action="{{ route('ano-lectivo-cursos.store') }}" method="post" class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#f0f2f4] dark:border-slate-800 overflow-hidden">
                <h2 class="text-[#111318] dark:text-white text-[18px] font-bold leading-tight px-6 py-4 border-b border-[#f0f2f4] dark:border-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">table</span>
                    Cursos
                </h2>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="flex flex-col gap-1">
                        <label for="cursos_select" class="text-sm font-semibold text-[#111318] dark:text-slate-200">Cursos</label>
                        <select id="cursos_select" name="cursos_id[]" multiple="multiple" class="select2 form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10"
                            placeholder="Selecione um conjunto de cursos">
                            @if ($cursos)
                                @foreach ($cursos as $curso)
                                <option value="{{ $curso->id }}" @if (in_array($curso->id, $_cursos)) selected @endif>{{ $curso->curso }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-[#111318] dark:text-slate-200">Anos Lectivos</label>
                        <select name="ano_lectivo_id" class="form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10">
                            @if ($anoLectivo)
                            <option value="{{ $anoLectivo->id }}">{{ $anoLectivo->ano }}</option>
                            @endif
                        </select>
                    </div>
                    
                </div>
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 py-3 px-6 mt-4 border-t border-[#f0f2f4] dark:border-slate-800">
                    <a href="{{ route('web.ano-lectivo') }}" class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-[#f0f2f4] dark:bg-slate-800 text-[#111318] dark:text-white text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </a>
                    @if (Auth::user()->can('create: curso'))
                        <button type="submit" class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-colors gap-2">
                            <span class="material-symbols-outlined text-lg">add</span> Curso
                        </button>
                    @endif
                </div>
            </form>
        </div>
        
        <div class="flex-1 flex flex-col gap-6">
            <!-- Section: Personal Data -->
            <form id="cadastrarClasseF" action="{{ route('ano-lectivo-classes.store') }}" method="post" class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#f0f2f4] dark:border-slate-800 overflow-hidden">
                <h2 class="text-[#111318] dark:text-white text-[18px] font-bold leading-tight px-6 py-4 border-b border-[#f0f2f4] dark:border-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">table</span>
                    Classes
                </h2>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="flex flex-col gap-1">
                        <label for="classe_select" class="text-sm font-semibold text-[#111318] dark:text-slate-200">Classes</label>
                        <select id="classe_select" name="classes_id[]" multiple="multiple" class="select2 form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10" placeholder="Selecione um conjunto de classes">
                            @if ($classes)
                                @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}" @if (in_array($classe->id, $_classes)) selected @endif>{{ $classe->classes }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-[#111318] dark:text-slate-200">Anos Lectivos</label>
                        <select name="ano_lectivo_id" class="form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10">
                            @if ($anoLectivo)
                            <option value="{{ $anoLectivo->id }}">{{ $anoLectivo->ano }}</option>
                            @endif
                        </select>
                    </div>
                    
                </div>
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 py-3 px-6 mt-4 border-t border-[#f0f2f4] dark:border-slate-800">
                    <a href="{{ route('web.ano-lectivo') }}" class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-[#f0f2f4] dark:bg-slate-800 text-[#111318] dark:text-white text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </a>
                    @if (Auth::user()->can('create: classe'))
                        <button type="submit" class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-colors gap-2">
                            <span class="material-symbols-outlined text-lg">add</span> Classe
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    
    <div class="flex flex-col md:flex-row gap-8 mt-6">
        
        <div class="flex-1 flex flex-col gap-6">
            <!-- Section: Personal Data -->
            <form id="cadastrarSalaT" action="{{ route('web.cadastrar-salas-ano-lectivo') }}" method="post" class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#f0f2f4] dark:border-slate-800 overflow-hidden">
                <h2 class="text-[#111318] dark:text-white text-[18px] font-bold leading-tight px-6 py-4 border-b border-[#f0f2f4] dark:border-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">table</span>
                    Salas
                </h2>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="flex flex-col gap-1">
                        <label for="salas_select" class="text-sm font-semibold text-[#111318] dark:text-slate-200">Salas</label>
                        <select id="salas_select" name="salas_id[]" multiple="multiple" class="select2 form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10" placeholder="Selecione um conjunto de salas">
                            @if ($salas)
                                @foreach ($salas as $sala)
                                <option value="{{ $sala->id }}" @if (in_array($sala->id, $_salas)) selected @endif>{{ $sala->salas }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-[#111318] dark:text-slate-200">Anos Lectivos</label>
                        <select name="ano_lectivo_id" class="form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10">
                            @if ($anoLectivo)
                            <option value="{{ $anoLectivo->id }}">{{ $anoLectivo->ano }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 py-3 px-6 mt-4 border-t border-[#f0f2f4] dark:border-slate-800">
                    <a href="{{ route('web.ano-lectivo') }}" class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-[#f0f2f4] dark:bg-slate-800 text-[#111318] dark:text-white text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </a>
                    @if (Auth::user()->can('create: sala'))
                        <button type="submit" class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-colors gap-2">
                            <span class="material-symbols-outlined text-lg">add</span> Sala
                        </button>
                    @endif
                </div>
            </form>
        </div>
        
        <div class="flex-1 flex flex-col gap-6">
            <!-- Section: Personal Data -->
            <form id="cadastrarTurnoT" action="{{ route('ano-lectivo-turnos.store') }}" method="post" class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#f0f2f4] dark:border-slate-800 overflow-hidden">
                <h2 class="text-[#111318] dark:text-white text-[18px] font-bold leading-tight px-6 py-4 border-b border-[#f0f2f4] dark:border-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">table</span>
                    Turnos
                </h2>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="flex flex-col gap-1">
                        <label for="turnos_select" class="text-sm font-semibold text-[#111318] dark:text-slate-200">Turnos</label>
                        <select id="turnos_select" name="turnos_id[]" multiple="multiple" class="select2 form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10" placeholder="Selecione um conjunto de salas">
                            @if ($turnos)
                                @foreach ($turnos as $turno)
                                <option value="{{ $turno->id }}" @if (in_array($turno->id, $_turnos)) selected @endif>{{ $turno->turno }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-[#111318] dark:text-slate-200">Anos Lectivos</label>
                        <select name="ano_lectivo_id" class="form-select rounded-lg border-[#d1d5db] dark:border-slate-700 bg-transparent focus:border-primary focus:ring-primary h-10">
                            @if ($anoLectivo)
                            <option value="{{ $anoLectivo->id }}">{{ $anoLectivo->ano }}</option>
                            @endif
                        </select>
                    </div>
                    
                </div>
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 py-3 px-6 mt-4 border-t border-[#f0f2f4] dark:border-slate-800">
                    <a href="{{ route('web.ano-lectivo') }}" class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-[#f0f2f4] dark:bg-slate-800 text-[#111318] dark:text-white text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </a>
                    @if (Auth::user()->can('create: turno'))
                        <button type="submit" class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-colors gap-2">
                            <span class="material-symbols-outlined text-lg">add</span> Turno
                        </button>
                    @endif
                </div>
            </form>
        </div>
        
    </div>
    
</div>
    
@endsection

@section('scripts')
    <script>
        // Aplica aos formulários desejados
        ajaxFormSubmit('#cadastrarClasseF');
        ajaxFormSubmit('#cadastrarCursoS');
        ajaxFormSubmit('#cadastrarTurnoT');
        ajaxFormSubmit('#cadastrarSalaT');
    </script>
@endsection

