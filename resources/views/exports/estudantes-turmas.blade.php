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
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">TURMA: {{ $turma->turma ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">CLASSE: {{ $classe->classes ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">TURNO: {{ $turno->turno ?? "TODOS" }}</th>
        </tr>
        <tr>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">CURSO: {{ $curso->curso ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">SALA: {{ $sala->sala ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">ANO LECTIVO: {{ $ano->ano ?? "TODOS" }}</th>
        </tr>
        <tr>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">TOTAL REGISTRO: {{ count( $estudantes) }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Cod</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20" colspan="2">Nº Processo</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="50">Nome</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Genero</th>
            <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Nº Telefone</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($estudantes as $key => $item)
        <tr>
            <td style="text-align: center;border: 1px solid #858585">00{{ $key + 1 }}</td>
            <td style="border: 1px solid #858585" colspan="2">{{ $item->numero_estudante }}</td>
            <td style="border: 1px solid #858585">{{ $item->nome }} {{ $item->sobre_nome }}</td>
            <td style="border: 1px solid #858585">{{ $item->genero }}</td>
            <td style="border: 1px solid #858585">{{ $item->telefone_estudante }}</td>
        </tr>
      @endforeach
    </tbody>
</table>