<!DOCTYPE html>
<html lang="pt-pt">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>MINI PAUTA</title>
        
        <style>
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
    
            body{
    			padding: 30px;
                font-family: Arial, Helvetica, sans-serif;
    		}
        
            table {
                width: 100%;
                text-align: left;
                border-spacing: 0;
                margin-bottom: 10px;
            }
    
            thead {
                text-align: left;
                font-size: 12px;
            }
    
            th,
            td {
                padding: 12px;
                text-align: left;
                font-size: 12px;
            }
        </style>
        
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th colspan="28" style="text-align: center;line-height: 20px">
                        REPÚBLICA DE ANGOLA <br>
                        GOVERNO PROVINCIPAL DE LUANDA <br>
                        INSTITUTO POLITÉCNICO INDUSTRIAL ALDA LARA <br>
                        MINI-PAUTA<br>
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; font-size: 12px;">Disciplina:</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="2">PORT</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="16">Prof.(a):</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="2">Turno: Manhã</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="7">Ano Lectivo: <span>2023/2024</span></th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;color: green;font-size: 16px" rowspan="2" colspan="3">TURMA: INFO10</th>
                    <th style="border: 1px solid #000000;text-align: center;color: blue;font-size: 16px" colspan="25">CLASSIFICAÇÃO</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;" rowspan="3">Idade</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Genero</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="6"> 1 º Trimestre</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="6"> 2 º Trimestre</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="6"> 3 º Trimestre</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="4">Resultados Finais</th>
                    <th style="border: 1px solid #000000" rowspan="3">OBS</th>
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000" rowspan="2">Nº</th>
                    <th style="border: 1px solid #000000" colspan="2" rowspan="2">Nome do(a) aluno(a)</th>
                    
                    <th style="border: 1px solid #000000" rowspan="2">P1</th>
                    <th style="border: 1px solid #000000" rowspan="2">P2</th>
                    <th style="border: 1px solid #000000" rowspan="2">PT</th>
                    <th style="border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="border: 1px solid #000000" colspan="2">Faltas</th>
                    
                    <th style="border: 1px solid #000000" rowspan="2">P1</th>
                    <th style="border: 1px solid #000000" rowspan="2">P2</th>
                    <th style="border: 1px solid #000000" rowspan="2">PT</th>
                    <th style="border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="border: 1px solid #000000" colspan="2">Faltas</th>
                    
                    
                    <th style="border: 1px solid #000000" rowspan="2">P1</th>
                    <th style="border: 1px solid #000000" rowspan="2">P2</th>
                    <th style="border: 1px solid #000000" rowspan="2">PT</th>
                    <th style="border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="border: 1px solid #000000" colspan="2">Faltas</th>
                    
                    
                    <th style="border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="border: 1px solid #000000" rowspan="2">MT2</th>
                    <th style="border: 1px solid #000000" rowspan="2">MT3</th>
                    <th style="border: 1px solid #000000" rowspan="2">MFD</th>
                    
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000">FNJ</th>
                    <th style="border: 1px solid #000000">FJ</th>
                    
                    <th style="border: 1px solid #000000">FNJ</th>
                    <th style="border: 1px solid #000000">FJ</th>
                    
                    
                    <th style="border: 1px solid #000000">FNJ</th>
                    <th style="border: 1px solid #000000">FJ</th>
                </tr>
            </thead>
            
            <tbody>
                @for ($i = 1; $i <= 10; $i++)
                <tr>
                    <td style="border: 1px solid #000000">{{ $i }}</td>
                    <td style="border: 1px solid #000000" colspan="2">Ndoma Manuel Domingos Lewa</td>
                    <td style="text-align: center;border: 1px solid #000000">{{ $i + 4 }}</td>
                    <td style="text-align: center;border: 1px solid #000000">M</td>
                    
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000"></td>
                    <td style="text-align: center;border: 1px solid #000000"></td>
                    
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000"></td>
                    <td style="text-align: center;border: 1px solid #000000"></td>
                    
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000"></td>
                    <td style="text-align: center;border: 1px solid #000000"></td>
                    
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    <td style="text-align: center;border: 1px solid #000000">0</td>
                    
                    <td style="text-align: center;border: 1px solid #000000">
                        @if ($i >= 5)
                            <span style="color: blue;">Transita</span>
                        @else
                            <span style="color: red;">N/Transita</span>
                        @endif
                    </td>
                    
                </tr>
                @endfor
            </tbody>
        </table>
        
        <table style="margin-top: 60px">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; font-size: 12px;" rowspan="2" colspan="2">Matriculados</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">1ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">2ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">3ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;">Assinatura do(a) Prof.</th>
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Desistente</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Avaliados</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">C/Aproveit.</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">S/Aproveit.</th>
              
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Desistente</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Avaliados</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">C/Aproveit.</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">S/Aproveit.</th>
              
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Desistente</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">Avaliados</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">C/Aproveit.</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="2">S/Aproveit.</th>
                    
                    <th style="border: 1px solid #000000;" rowspan="2"></th>
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">MF</th>
                    <th style="border: 1px solid #000000;">F</th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td style="border: 1px solid #000000;">23</td>
                    <td style="border: 1px solid #000000;">23</td>
                    
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    <td style="border: 1px solid #000000;">0</td>
                    
                    <td style="border: 1px solid #000000;" rowspan="2"></td>
                </tr>
                
                <tr>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                    <td style="border: 1px solid #000000;text-align: center;" colspan="2">0%</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
