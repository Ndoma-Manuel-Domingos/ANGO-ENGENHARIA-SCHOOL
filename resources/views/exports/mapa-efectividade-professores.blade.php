<table style="border: 1px solid #000">
    <thead>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;" colspan="7">República de Angola</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;" colspan="7">Governo Provincial de Luanda</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;" colspan="7">Gabinete Provincial da Educação</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 15pt" colspan="7">{{ $escola->nome }}</th>
        </tr>
        <tr>
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 15pt" colspan="7">MAPA DE EFECTIVIDADE DOS PROFESSORES</th>
        </tr>
        
        <tr>
            <th colspan="7"></th>
        </tr>
                        

        <tr>
            <th colspan="7"></th>
        </tr>

    </thead>
    <tbody>
        
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 12pt">Assinatura</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 12pt">____________________________________</td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead>
        <tr>
            <th style="text-align: left;background-color: #000000;color: #ffffff">Nº</th>
            <th style="text-align: left;background-color: #000000;color: #ffffff;" colspan="31">Nome Completo</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($professores as $key => $item)
            @php
                $mapas = App\Models\web\calendarios\MapaEfectividade::where('funcionarios_id', $item->funcionario->id)->when($requests['data_inicio'], function($query, $value){
                    $query->where('created_at', '>=', Carbon\Carbon::createFromDate($value));
                })->when($requests['data_final'], function($query, $value){
                    $query->where('created_at', '<=', Carbon\Carbon::createFromDate($value));
                })->get();
            @endphp
            
            <tr>
                @php
                    $total_presenca = 0;
                    $total_ausencia = 0;
                    $total_justificada = 0;
                    $total_indefinida = 0;
                @endphp
            
                <td style="width: 60px;border: 1px solid #000">{{ $key + 1 }}</td> 
                <td style="width: 200px;border: 1px solid #000">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</td>
                @foreach ($mapas as $map)
                    
                    @if ($map->status == 'Presente')
                        @php
                            ++$total_presenca;
                        @endphp                         
                    @endif
                    @if ($map->status == 'Ausente')
                        @php
                            ++$total_ausencia;
                        @endphp                         
                    @endif
                    @if ($map->status == 'Justitificado')
                        @php
                            ++$total_justificada;
                        @endphp                         
                    @endif
                    @if ($map->status == 'Indefinido')
                        @php
                            ++$total_indefinida;
                        @endphp                         
                    @endif
                
                    @if ($map->status == 'Presente' )
                    <th style="background-color: green;color: #0f0f0f;width: 90px;border: 1px solid #000"> 
                        <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                        <small>{{ $map->dia_semana }}</small>
                    </th>
                    @endif
                    @if ($map->status == 'Ausente' )
                    <th style="background-color: red;color: #0f0f0f;width: 90px;border: 1px solid #000"> 
                        <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                        <small>{{ $map->dia_semana }}</small>
                    </th>
                    @endif
                    @if ($map->status == 'Justitificado' )
                    <th style="background-color: blue;color: #0f0f0f;width: 90px;border: 1px solid #000"> 
                        <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                        <small>{{ $map->dia_semana }}</small>
                    </th>
                    @endif
                    @if ($map->status == 'Indefinido' )
                    <th style="background-color: yellow;color: #0f0f0f;width: 90px;border: 1px solid #000"> 
                        <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                        <small>{{ $map->dia_semana }}</small>
                    </th>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
