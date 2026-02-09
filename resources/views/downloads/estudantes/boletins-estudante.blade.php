<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: calibri;
        }

        body {
            padding: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            border: 1px solid black;
            /* Adiciona borda à tabela */
        }

        td {
            border: none;
            /* Remove bordas internas das células */
            padding: 2px;
            /* Adiciona um espaço interno às células para melhorar a aparência */
        }

        tr:last-child td {
            border-bottom: 1px solid black;
            /* Adiciona borda inferior nas células da última linha */
        }

        tr td:first-child {
            border-left: 1px solid black;
            /* Adiciona borda esquerda na primeira célula de cada linha */
        }

        tr td:last-child {
            border-right: 1px solid black;
            /* Adiciona borda direita na última célula de cada linha */
        }

    </style>
</head>
<body>

    <div style="width: 100%; text-align: center;">
        @foreach ($estudantes as $item)
        <div style="display: inline-block; width: 350px; height: 500px;vertical-align: top; border: 1px solid #000000; padding: 10px; box-sizing: border-box;">
            <header>
                <img src="{{ $logotipo?? 'assets/images/insigna.png' }}" style="height: 60px; width: 60px; margin-bottom: 10px; margin-top: 11px;">

                <h6 style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif; font-size: 9px;">REPÚBLICA DE ANGOLA</h6>
                <h6 style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif; font-size: 9px;">MINISTÉRIO DE EDUCAÇÃO</h6>
                <h6 style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif; font-size: 9px;">GOVERNO PROVINCIAL DE LUANDA</h6>
                <h6 style="text-transform: uppercase; font-family: Arial, Helvetica, sans-serif; font-size: 9px;">{{ $escola->nome }}</h6>
            </header>

            <main>
                <div style="text-align: center;margin: 10px 0">
                    <h1 style="font-size: 9pt;font-family: Arial, Helvetica, sans-serif">BOLETIN DE NOTAS DO ESTUDANTE</h1>
                    <p style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;text-align: left">
                        Nome: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-size: 10pt;font-family: Arial, Helvetica, sans-serif;color: red;">{{ $item->estudante->nome}} {{ $item->estudante->sobre_nome }}</strong>.
                    </p>

                    <p style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;text-align: left">
                        Turma: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-family: Arial, Helvetica, sans-serif;font-size: 8pt">{{ $turma->turma }}</strong>.
                        classe: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-family: Arial, Helvetica, sans-serif;font-size: 8pt">{{ $classe->classes }}</strong>.
                        Sala <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-family: Arial, Helvetica, sans-serif;font-size: 8pt">{{ $sala->salas }}</strong>.
                        Turno: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-family: Arial, Helvetica, sans-serif;font-size: 8pt">{{ $turno->turno }} </strong>.
                        Período: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-family: Arial, Helvetica, sans-serif;font-size: 8pt">{{ $trimestre->trimestre }} </strong>.
                        Ano Lectivo: <strong style="border-bottom: 1px solid rgba(0,0,0,1);font-family: Arial, Helvetica, sans-serif;font-size: 8pt">{{ $anoLectivo->ano }}</strong>.
                    </p>
                </div>

                @if ($turma->notas($item->estudante->id, $anoLectivo->id, $trimestre->id))
                <table style="width: 100%;margin: 0 auto;border: 1px solid #949494;border-collapse: collapse">
                    <thead>
                        <tr>
                            <th style="text-align: left;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">Disciplinas</th>
                            
                            @if ($turma->curso->tipo === "Técnico")
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MAC</th>
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MPP</th>
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">NPT</th>
                            @endif
                            @if ($turma->curso->tipo === "Punível")
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MAC</th>
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MPP</th>
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">NPT</th>
                            @endif
                            @if ($turma->curso->tipo === "Outros")
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MAC</th>
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MPP</th>
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">NPT</th>
                            @endif
                            
                            @if ($trimestre->trimestre === "Iª Trimestre")
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MT1</th>
                            @endif
                            @if ($trimestre->trimestre === "IIª Trimestre")
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MT2</th>
                            @endif
                            @if ($trimestre->trimestre === "IIIª Trimestre")
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">MT3</th>
                            @endif
                            
                            <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;padding: 5px">Observação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($turma->notas($item->estudante->id, $anoLectivo->id, $trimestre->id) as $item)
                        <tr>
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;">{{ $item->disciplina->disciplina }}</td>
                            
                            @if ($turma->curso->tipo === "Técnico")
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mac) }}</td>
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->npp) }}</td>
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->npt) }}</td>
                            @endif
                            @if ($turma->curso->tipo === "Punível")
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mac) }}</td>
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->npp) }}</td>
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->npt) }}</td>
                            @endif
                            @if ($turma->curso->tipo === "Outros")
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mac) }}</td>
                            <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->npt) }}</td>
                            @endif
                            
                            
                            @if ($trimestre->trimestre === "Iª Trimestre")
                                <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt1) }}</td>
                                @if ($item->mt1 >= ($classe->tipo_avaliacao_nota / 2))
                                <td style="color: blue;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt1) }}</td>
                                @else
                                <td style="color: red;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt1) }}</td>
                                @endif
                            @endif
                            
                            @if ($trimestre->trimestre === "IIª Trimestre")
                                <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt2) }}</td>
                                @if ($item->mt2 >= ($classe->tipo_avaliacao_nota / 2))
                                <td style="color: blue;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt2) }}</td>
                                @else
                                <td style="color: red;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt2) }}</td>
                                @endif
                            @endif
                            
                            @if ($trimestre->trimestre === "IIIª Trimestre")
                                <td style="border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt3) }}</td>
                                @if ($item->mt3 >= ($classe->tipo_avaliacao_nota / 2))
                                <td style="color: blue;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt3) }}</td>
                                @else
                                <td style="color: red;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 6pt;text-align: center;">{{ round($item->mt3) }}</td>
                                @endif
                            @endif
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                
                <div style="text-align: center;margin: 10px 0">
                    <p style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;text-align: center">Coordenador do Curso</p>
                    <p style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;text-align: center">_____________________________</p>
                </div>

            </main>
        </div>
        @endforeach
    </div>

</body>
</html>
