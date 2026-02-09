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
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">CLASSE: {{ $classe->classes ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">TURNO: {{ $turno->turno ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">CURSO: {{ $curso->curso ?? "TODOS" }}</th>
            <th style="background-color: #3d3d3d;color: #ffffff;padding: 20px" colspan="2">TOTAL REGISTRO: {{ count( $matriculas) }}</th>
        </tr>
   
    </thead>
</table>

<table>
    <thead>
        <tr>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Nº</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="50">Nome</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="20">Bilhete</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Genero</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Idade</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="30">Curso</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Classe</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Turno</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Status Matricula</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Inscrição</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="25">Serviço</th>
          <th style="border: 1px solid #858585;background-color: #3d3d3d;color: #ffffff;padding: 20px" width="15">Ano Lectivo</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($matriculas as $item)
        <tr>          
            <td style="border: 1px solid #858585">{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
            <td style="border: 1px solid #858585">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
            <td style="border: 1px solid #858585">{{ $item->estudante->bilheite }}</td>
    
            <td style="border: 1px solid #858585">{{ $item->estudante->genero }}</td>
            <td style="border: 1px solid #858585">{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
              
            <td style="border: 1px solid #858585">{{ $item->curso->curso }}</td>
            <td style="border: 1px solid #858585">{{ $item->classe->classes }}</td>
            <td style="border: 1px solid #858585">{{ $item->turno->turno }}</td>
          
          @if ($item->status_matricula == 'inactivo')
            <td style="border: 1px solid #858585">Inactivo</td>
          @endif
          @if ($item->status_matricula == 'confirmado')
            <td style="border: 1px solid #858585">Admitido</td>
          @endif
          @if ($item->status_matricula == 'falecido')
            <td style="border: 1px solid #858585">Falecido</td>
          @endif
          @if ($item->status_matricula == 'desistente')
          <td style="border: 1px solid #858585">Desistente</td>
          @endif
          @if ($item->status_matricula == 'nao_confirmado')
            <td style="border: 1px solid #858585">Não Admitido</td>
          @endif
          @if ($item->status_matricula == 'rejeitado')
            <td style="border: 1px solid #858585">Rejeitada</td>
          @endif
       
          @if ($item->status_inscricao == 'Admitido')
            <td style="border: 1px solid #858585">Admitido</td>
          @endif
       
          @if ($item->status_inscricao == 'Nao Admitido')
            <td style="border: 1px solid #858585">Não Admitido</td>
            @endif
            
          <td style="border: 1px solid #858585">{{ $item->tipo ?? '' }}</td>
          <td style="border: 1px solid #858585">{{ $item->ano_lectivo->ano ?? '' }}</td>
          
        </tr>    
      @endforeach
    </tbody>
</table>