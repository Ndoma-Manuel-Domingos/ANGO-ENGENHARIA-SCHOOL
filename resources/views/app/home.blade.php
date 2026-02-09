@extends('layouts.escolas')

@section('content')
    @php
        date_default_timezone_set('Africa/Luanda');
        /*sistema de datas*/
        $dia = @date("d");
        $mes = @date("m");
        $ano = @date("Y");
        $dataFinal = $ano."-".$mes."-".$dia;

        $admin = App\Models\User::findOrFail(Auth::user()->id);
        $escola = App\Models\Shcool::findOrFail($admin->shcools_id);

        $controlo = App\Models\web\seguranca\ControloSistema::where([
            ['shcools_id', '=', $escola->id],
            ['level', '=', '4'],
        ])->first();

        $date1 = date_create($controlo->final);
        $date2 = date_create($dataFinal);
        // $date2 = date_create($controlo->inicio);
        $diff = date_diff($date1,$date2);
        $diasRestantes = $diff->format("%a");
    @endphp 


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Painel administrativo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo')}}">Painel Administrativo</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
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
                <div class="col-12 col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-kiss-wink-heart"></i> Holla Srº(ª) {{ $usuario->nome }}, seja Bem-vindo ao 
                            software {{ env('APP_NAME') }}. <span class="float-right text-warning">Módulo {{ $escola->modulo }}</span></h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="callout callout-info">
                        @if ($diasRestantes > 30)
                            <p class="text-success h5">Faltam {{ $diasRestantes }} para expirar a Licença</p>
                        @else
                            <p class="text-danger h5">Faltam {{ $diasRestantes }} para expirar a Licença</p>
                        @endif
                        <form name="clock" class="horas float-right bg-dark p-1">
                            Hora: <input type="submit" name="face" value="">
                        </form>
                        <h5><i class="fas fa-calendar"></i> Hoje 
                        @if (getdate()['weekday'] == 'Friday')
                            Sexta-Feira
                        @endif

                        @if (getdate()['weekday'] == 'Monday')
                            Segunda-Feira
                        @endif

                        @if (getdate()['weekday'] == 'Tuesday')
                            Terça-Feira
                        @endif

                        @if (getdate()['weekday'] == 'Wednesday')
                            Quarta-Feira
                        @endif

                        @if (getdate()['weekday'] == 'Thursday')
                            Quinta-Feira
                        @endif

                        @if (getdate()['weekday'] == 'Saturday')
                            Sábado
                        @endif

                        @if (getdate()['weekday'] == 'Sunday')
                            Domingo
                        @endif
                            {{ getdate()['wday'] }}º dia da semana, {{ getdate()['mday'] }}
                            de 
                        @switch(getdate()['month'])
                            @case("January")
                            Janeiro
                            @break

                            @case("February")
                            Fevereiro
                            @break

                            @case("March")
                            Março
                            @break

                            @case("April")
                            Abril
                            @break

                            @case("May")
                            Maio
                            @break

                            @case("July")
                            Julho
                            @break

                            @case("June")
                            Junho
                            @break

                            @case("August")
                            Agosto
                            @break

                            @case("June")
                            Junho
                            @break

                            @case("September")
                            Setembro
                            @break

                            @case("October")
                            Outubro
                            @break

                            @case("November")
                            Novembro
                            @break

                            @case("December")
                            Dezembro
                            @break
                        @endswitch
                        de {{ getdate()['year'] }}</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                
                @if (Auth::user()->can('read: ano lectivo'))
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- small box -->
                    <div class="small-box text-white" style="background-color: #3498db">
                        <div class="inner">
                            @php
                                if (!empty($verAnoLectivoActivo->ano)) {
                                    $anoLectivoCadastrado = $verAnoLectivoActivo->ano;
                                } else {
                                    $anoLectivoCadastrado = 'Desconhecido';
                                }
                            @endphp

                            <h4>{{ $anoLectivoCadastrado }} <span
                                    class="fs-1">({{ $totalanolectivos }})</span></h4>

                            <p>Ano lectivo</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('web.ano-lectivo') }}" class="small-box-footer">Mais Informação 
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                @endif

                @if (Auth::user()->can('read: estudante'))
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- small box -->
                    <div class="small-box text-white" style="background-color: #3498db">
                        <div class="inner">
                            <h3>
                                @if ($totalEstudantesConfirmados)
                                    {{ $totalEstudantesConfirmados }} <small style="font-size: 13pt"> de {{ $totalEstudantesNaoConfirmados }}</small>
                                @else
                                    0
                                @endif
                            </h3>

                            <p>Total Estudantes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('web.estudantes-listagem-geral') }}" class="small-box-footer">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
                
                @if (Auth::user()->can('read: funcionario'))
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- small box -->
                    <div class="small-box text-white" style="background-color: #3498db">
                        <div class="inner">
                            <h3> {{ $totalfuncionarios }} </h3>

                            <p>Funcionários (Administivos, Limpezas, Segurança)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <a href="{{ route('web.outro-funcionarios') }}" class="small-box-footer">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
                
                @if (Auth::user()->can('read: professores'))
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- small box -->
                    <div class="small-box text-white" style="background-color: #3498db">
                        <div class="inner">
                            <h3> {{ $totalprofessores }} </h3>

                            <p>Professores</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <a href="{{ route('web.funcionarios') }}" class="small-box-footer">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @endif
            </div>
            
            @if (Auth::user()->can('read: estatistica'))
            <div class="row">
                <div class="col-12 col-md-10 mb-4">

                    <div id="poll_div"></div>
    
                    @if($filtros['tipo_graficos'] == 'ColumnChart')
                    {!! $lava->render('ColumnChart', 'Estudante', 'poll_div') !!}
                    @else
    
                    @if($filtros['tipo_graficos'] == 'BarChart')
                    {!! $lava->render('BarChart', 'Estudante', 'poll_div') !!}
                    @else
    
                    @if($filtros['tipo_graficos'] == 'AreaChart')
                    {!! $lava->render('AreaChart', 'Estudante', 'poll_div') !!}
                    @else
    
                    @if($filtros['tipo_graficos'] == 'DonutChart')
                    {!! $lava->render('DonutChart', 'Estudante', 'poll_div') !!}
                    @else
    
                    @if($filtros['tipo_graficos'] == 'PieChart')
                    {!! $lava->render('PieChart', 'Estudante', 'poll_div') !!}
                    @else
    
                    @if($filtros['tipo_graficos'] == 'LineChart')
                    {!! $lava->render('LineChart', 'Estudante', 'poll_div') !!}
                    @else
    
                    {!! $lava->render('ColumnChart', 'Estudante', 'poll_div') !!}
    
                    @endif
                    @endif
                    @endif
                    @endif
                    @endif
                    @endif
    
    
                </div>
    
                <div class="col-12 col-md-2 mb-4">
    
                    <form action="{{ route('paineis.administrativo') }}" method="get">
                        @csrf
    
                        <div class="card">
    
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6" style="width: 70px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
    
                                <div class="form-group pt-4 col-md-12 col-12">
                                    <label for="tipo_graficos" class="form-label">Tipos Gráficos</label>
                                    <select name="tipo_graficos" id="tipo_graficos"
                                        class="form-control tipo_graficos select2">
                                        <option value="">Selecione Tipo Grafico</option>
                                        <option value="ColumnChart" {{ $filtros['tipo_graficos']=='ColumnChart' ? 'selected'
                                            : '' }}>Gráfico de Colunas</option>
                                        <option value="BarChart" {{ $filtros['tipo_graficos']=='BarChart' ? 'selected' : ''
                                            }}>Gráfico de barras</option>
                                        <option value="AreaChart" {{ $filtros['tipo_graficos']=='AreaChart' ? 'selected'
                                            : '' }}>Gráfico de área</option>
                                        <option value="DonutChart" {{ $filtros['tipo_graficos']=='DonutChart' ? 'selected'
                                            : '' }}>Gráfico de Rosca</option>
                                        <option value="PieChart" {{ $filtros['tipo_graficos']=='PieChart' ? 'selected' : ''
                                            }}>Gráfico de Pizza</option>
                                        <option value="LineChart" {{ $filtros['tipo_graficos']=='LineChart' ? 'selected'
                                            : '' }}>Gráfico de Linhas</option>
                                    </select>
                                    @error('tipo_graficos')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                            </div>
    
                            <div class="card-footer pt-4">
    
                                <button type="submit" class="btn btn-primary"> Filtrar</button>
    
                            </div>
    
                        </div>
    
                    </form>
    
                </div>
            </div>
            
            @if($escola->categoria == "Privado")
                <div class="row">
                    <div class="col-12 bg-light mb-2">
                        {!! $chartPagamento->container() !!}
                    </div>
                </div>  
            @endif
            @endif
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
   {!! $chartPagamento->script() !!}
@endsection

@section('scripts')
    <script>
        $(function() {

            /* initialize the external events
            -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function() {

                    // create an Event Object (https://fullcalendar.io/docs/event-object)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0 //  original position after the drag
                    })

                })
            }

            ini_events($('#external-events div.external-event'))

            /* initialize the calendar
            -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------

            new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText,
                        backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue(
                            'background-color'),
                        borderColor: window.getComputedStyle(eventEl, null).getPropertyValue(
                            'background-color'),
                        textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                    };
                }
            });

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                //Random default events
                events: [{
                        title: 'All Day Event',
                        start: new Date(y, m, 1),
                        backgroundColor: '#f56954', //red
                        borderColor: '#f56954', //red
                        allDay: true
                    },
                    {
                        title: 'Long Event',
                        start: new Date(y, m, d - 5),
                        end: new Date(y, m, d - 2),
                        backgroundColor: '#f39c12', //yellow
                        borderColor: '#f39c12' //yellow
                    },
                    {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false,
                        backgroundColor: '#0073b7', //Blue
                        borderColor: '#0073b7' //Blue
                    },
                    {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        allDay: false,
                        backgroundColor: '#00c0ef', //Info (aqua)
                        borderColor: '#00c0ef' //Info (aqua)
                    },
                    {
                        title: 'Birthday Party',
                        start: new Date(y, m, d + 1, 19, 0),
                        end: new Date(y, m, d + 1, 22, 30),
                        allDay: false,
                        backgroundColor: '#00a65a', //Success (green)
                        borderColor: '#00a65a' //Success (green)
                    },
                    {
                        title: 'Click for Google',
                        start: new Date(y, m, 28),
                        end: new Date(y, m, 29),
                        url: 'https://www.google.com/',
                        backgroundColor: '#3c8dbc', //Primary (light-blue)
                        borderColor: '#3c8dbc' //Primary (light-blue)
                    }
                ],
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                drop: function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                }
            });

            calendar.render();
            // $('#calendar').fullCalendar()

            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            // Color chooser button
            $('#color-chooser > li > a').click(function(e) {
                e.preventDefault()
                // Save color
                currColor = $(this).css('color')
                // Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color': currColor
                })
            })
            $('#add-new-event').click(function(e) {
                e.preventDefault()
                // Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                // Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color': currColor,
                    'color': '#fff'
                }).addClass('external-event')
                event.text(val)
                $('#external-events').prepend(event)

                // Add draggable funtionality
                ini_events(event)

                // Remove event from text input
                $('#new-event').val('')
            })
        })
    </script>
@endsection

