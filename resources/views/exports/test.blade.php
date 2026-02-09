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
        <table style="background-color: #ffffff">
            <thead>
                <tr>
                    <th colspan="30" style="text-align: center;line-height: 20px;background-color: rgb(255, 255, 255)">
                        REPÚBLICA DE ANGOLA
                    </th>
                </tr>
                <tr>
                    <th colspan="30" style="text-align: center;line-height: 20px;background-color: rgb(255, 255, 255)">
                        GOVERNO PROVINCIPAL DE LUANDA
                    </th>
                </tr>
                <tr>
                    <th colspan="30" style="text-align: center;line-height: 20px;background-color: rgb(255, 255, 255)">
                        INSTITUTO POLITÉCNICO INDUSTRIAL ALDA LARA
                    </th>
                </tr>
                <tr>
                    <th colspan="30" style="text-align: center;line-height: 20px;background-color: rgb(255, 255, 255)">
                        MINI-PAUTA
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="2">Disciplina:</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="2">PORT</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="16">Prof.(a):</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="2">Turno: Manhã</th>
                    <th style="border: 1px solid #000000; font-size: 12px;" colspan="8">Ano Lectivo: <span>2023/2024</span></th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;color: green;font-size: 16px" rowspan="2" colspan="4">TURMA: INFO10</th>
                    <th style="border: 1px solid #000000;text-align: center;color: blue;font-size: 16px" colspan="26">CLASSIFICAÇÃO</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;" rowspan="3">Idade</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Genero</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="6"> 1 º Trimestre</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="6"> 2 º Trimestre</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="6"> 3 º Trimestre</th>
                    <th style="border: 1px solid #000000;text-align: center;" colspan="4">Resultados Finais</th>
                    <th style="border: 1px solid #000000;text-align: center;" rowspan="3" colspan="2">OBS</th>
                </tr>
                
                <tr>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">Nº</th>
                    <th style="border: 1px solid #000000" colspan="3" rowspan="2">Nome do(a) aluno(a)</th>
                    
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">P1</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">P2</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">PT</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="text-align: center;border: 1px solid #000000" colspan="2">Faltas</th>
                    
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">P1</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">P2</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">PT</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="text-align: center;border: 1px solid #000000" colspan="2">Faltas</th>
                    
                    
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">P1</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">P2</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">PT</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="text-align: center;border: 1px solid #000000" colspan="2">Faltas</th>
                    
                    
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MT1</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MT2</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MT3</th>
                    <th style="text-align: center;border: 1px solid #000000" rowspan="2">MFD</th>
                    
                </tr>
                
                <tr>
                    <th style="text-align: center;border: 1px solid #000000">FNJ</th>
                    <th style="text-align: center;border: 1px solid #000000">FJ</th>
                    
                    <th style="text-align: center;border: 1px solid #000000">FNJ</th>
                    <th style="text-align: center;border: 1px solid #000000">FJ</th>
                    
                    
                    <th style="text-align: center;border: 1px solid #000000">FNJ</th>
                    <th style="text-align: center;border: 1px solid #000000">FJ</th>
                </tr>
            </thead>
            
            <tbody>
                @for ($i = 1; $i <= 10; $i++)
                <tr>
                    <td style="text-align: center;border: 1px solid #000000">{{ $i }}</td>
                    <td style="border: 1px solid #000000" colspan="3">Ndoma Manuel Domingos Lewa</td>
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
                    
                    <td style="text-align: center;border: 1px solid #000000" colspan="2">
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
        
        
        <table style="margin-top: 60px;background-color: #ffffff">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;" rowspan="2" colspan="2">Matriculados</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">1ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">2ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;background-color: aqua" colspan="8">3ª Trimestre</th>
                    <th style="border: 1px solid #000000; font-size: 12px;text-align: center;" colspan="3">Assinatura do(a) Prof.</th>
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
                    
                    <th style="border: 1px solid #000000;" rowspan="2" colspan="3"></th>
                </tr>
                
                <tr>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                    <th style="border: 1px solid #000000;text-align: center;">MF</th>
                    <th style="border: 1px solid #000000;text-align: center;">F</th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td style="border: 1px solid #000000;text-align: center;">23</td>
                    <td style="border: 1px solid #000000;text-align: center;">23</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    <td style="border: 1px solid #000000;text-align: center;">0</td>
                    
                    <td style="border: 1px solid #000000;" rowspan="2" colspan="3"></td>
                </tr>
                
                <tr>
                    <td style="text-align: center;border: 1px solid #000000;" colspan="2">0</td>
                    
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
