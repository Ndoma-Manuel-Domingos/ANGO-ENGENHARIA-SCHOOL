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
            <th style="text-align: center;font-weight: 900;text-transform: uppercase;padding-top: 10px;font-size: 15pt" colspan="7">{{ $titulo }}</th>
        </tr>
        <tr>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="3">INSTITUIÇÃO: {{ $instituicao->nome ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="3">ESTAGIO: {{ $bolsa->nome ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="3">TOTAL DE REGISTRO: {{ count($estagiarios) }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Id</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Nº Processo</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="50">Estudante</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Idade</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Estagio</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Instituição</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Tipo Instituição</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Data Inicio</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Data Final</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Estado</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($estagiarios as $key => $item)
        <tr>          
            <td style="border: 1px solid #858585">{{ $key + 1 }}</td>
            <td style="border: 1px solid #858585">{{ $item->estudante->numero_processo }}</td>
            <td style="border: 1px solid #858585">{{ $item->estudante->nome ?? '' }} {{ $item->estudante->sobre_nome ?? '' }}</td>
            <td style="border: 1px solid #858585">{{ $item->estudante->idade($item->estudante->nascimento)  }} </td>
            <td style="border: 1px solid #858585">{{ $item->bolsa->nome ?? '' }}</td>
            <td style="border: 1px solid #858585">{{ $item->instituicao->nome ?? '' }}</td>
            <td style="border: 1px solid #858585">{{ $item->instituicao->tipo ?? '' }}</td>
            <td style="border: 1px solid #858585">{{ $item->data_inicio ?? '' }}%</td>
            <td style="border: 1px solid #858585">{{ $item->data_final ?? '' }}</td>
            @if ($item->status == "activo")
            <td style="text-align: left;color: green;border: 1px solid #858585">{{ $item->status ?? '' }}</td>
            @else
            <td style="text-align: left;color: red;border: 1px solid #858585">{{ $item->status ?? '' }}</td>
            @endif            
        </tr>    
      @endforeach
    </tbody>
</table>