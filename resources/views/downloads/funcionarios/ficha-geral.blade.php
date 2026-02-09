<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extrato do Funcionário | </title>

    <style type="text/css">
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
            text-align: left;
		}

        body{
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

		h1{
			font-size: 10pt;
			margin-bottom: 10px;
		}
        h2{
            font-size: 12pt;
        }

		.titulo{
			font-size: 12pt;
			text-align: center;
            margin-top: 0;
		}

		p{
			/* margin-bottom: 20px; */
			line-height: 30px;
            font-size: 9pt;
            text-align: justify;
		}
        strong{
            font-size: 9pt;
        }

		table{
			width: 100%;
			text-align: left;
			border-spacing: 0;	
			margin-bottom: 10px;
			border: 1px solid rgb(0, 0, 0);
            font-size:9pt;
		}
		thead{
			background-color: #fdfdfd;
			border-bottom: 1px solid #006699;
            font-size: 10pt;

		}
		th, td{
			padding: 12px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 9pt;
		}

		.border{
			border: 1px solid #fdfdfd;
		}



		.logo{
			height: 70px;
			width: 70px;
			/*border-radius: 300px;*/
			/* padding: 30px;  */
            border: 1px solid #000;
		}
		.ml{
			margin-left: 80px;
		}
		.text-center{
			text-align: left;
		}

        /* ----------------------------------------- */
        .header{
            position: fixed;
            top: 0;
            padding: 40px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            width: 100%;
            padding-bottom: 60px;
            z-index: 2;
        }

        .div01{
            width: 50%;
            float: left;
        }

        .section{
            /* padding: 20px; 
            width: 100%; */
        }
	</style>
</head>
<body>

    <div style="height: 970px">
        <div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
            <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
            <h1>{{ $escola->nome }}</h1>
            <p>NIF: {{ $escola->documento }}</p> 
            <p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
            <h1>Departamento de Recursos Humanos</h1>
        </div>

        <section class="section">
            <h1 style="text-align: center;background-color: rgb(65, 64, 64); color: #eeeeee;font-size: 10pt;padding: 4px;">
                Informações gerais do Funcionário</h1>
            <br>
            <h1>Ficha de Contrato Nº {{ $contratos->documento }}</h1>
            <table>
                <thead>
                    <tr>
                        <td colspan="4" 
                        style="text-align: center;background-color: rgb(65, 64, 64);
                        color: #eeeeee;font-size: 10pt;"
                        >Dados Pessoais Funcionário</td>
                    </tr>
                </thead>
                
                <tbody>
                    
                    <tr>
                        <td>Nome</td>
                        <td>Nascimento</td>
                        <td>Genero</td>
                        <td>Estado Civil</td>
                    </tr> 
                    <tr>    
                        <td><strong>{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}</strong></td>
                        <td><strong>{{ $funcionario->nascimento }}</strong></td>
                        <td><strong>{{ $funcionario->genero }}</strong></td>
                        <td><strong>{{ $funcionario->estado_civil }}</strong></td>
                    </tr> 

                    <tr>
                        <td>Bilhete</td>
                        <td>Telefone</td>
                        <td>Endereco</td>
                        <td>Data Assinatura</td>
                    </tr>   
                    
                    <tr>
                        <td><strong>{{ $funcionario->bilheite }}</strong></td>
                        <td><strong>{{ $funcionario->telefone }}</strong></td>
                        <td><strong>{{ $funcionario->endereco }}</strong></td>
                        <td><strong>{{ $contratos->data_at }}</strong></td>
                    </tr> 
                </tbody>
            </table>

        </section>
    </div>



    <div style="height: 970px">
        <div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
            <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
            <h1>{{ $escola->nome }}</h1>
            {{-- <p>{{ $escola->natureza }}</p>  --}}
            <p>NIF: {{ $escola->documento }}</p> 
            <p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
            <h1>Departamento de Recursos Humanos</h1>
        </div>

        <section class="section">
            
            <h1>Ficha de Contrato Nº {{ $contratos->documento }}</h1>
            <table>
                <thead>
                    <tr>
                        <th colspan="4" 
                        style="text-align: center;background-color: rgb(65, 64, 64);
                        color: #eeeeee;font-size: 10pt;"
                        >Informações sobre o Contrato</th>
                    </tr>
                </thead>
            
            
                <tbody>
                    <tr>
                        <td>Data Inicio <strong>{{ $contratos->data_inicio_contrato }}</strong></td>
                        <td>Data Final <strong>{{ $contratos->data_final_contrato }}</strong></td>
                        <td>Hora Entrada <strong>{{ $contratos->hora_entrada_contrato }}</strong></td>
                        <td>Hora Saída <strong>{{ $contratos->hora_saida_contrato }}</strong></td>
                    </tr>
            
                    <tr style="background-color: #fdfdfd;">                            
                        <td>Conta Bancaria: <strong>{{ $contratos->conta_bancaria }}</strong> </td>
                        <td>IBAN: <strong>{{ $contratos->iban }}</strong></td>
                        <td>NIF: <strong>{{ $contratos->nif }}</strong></td>
                        <td>Cargo: <strong>{{ $contratos->cargo }}</strong></td>
                    </tr>
            
                    <tr style="background-color: #fdfdfd;">  
                        <td>Status Contrato: <strong>{{ $contratos->status_contrato }}</strong></td>
                        <td>Status: <strong>{{ $contratos->status }}</strong></td>
                        <td>Subcídio Transporte: <strong>{{ number_format(($contratos->subcidio_transporte), '2', ',', '.') }} Kz</strong></td>
                        <td>Subcídio Alimentação: <strong>{{ number_format(($contratos->subcidio_alimentacao), '2', ',', '.') }} Kz</strong></td>
                    </tr>
            
                    <tr style="background-color: #fdfdfd;">  
                        <td><strong>-----</strong></td>
                        <td>Outros Subcídios: <strong>{{ number_format(($contratos->subcidio), '2', ',', '.') }} Kz</strong></td>
                        <td>Salario Basíco: <strong>{{ number_format(($contratos->salario), '2', ',', '.') }} Kz</strong></td>
                        <td>Salario Geral: <strong>{{ number_format(($contratos->salario + $contratos->subcidio + $contratos->subcidio_alimentacao + $contratos->subcidio_transporte), '2', ',', '.') }} Kz</strong></td>
                    </tr>
            
                    <tr style="background-color: #eeeeee;">
                        <th colspan="4">Clausula</th>
                    </tr>
            
                    <tr>
                        <td colspan="4">
                            <p>{{ $contratos->clausula }}</p> </td>
                    </tr>
                </tbody>
            </table>    

        </section>
    </div>




    <div style="height: 970px">
        <div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
            <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
            <h1>{{ $escola->nome }}</h1>
            <p>NIF: {{ $escola->documento }}</p> 
            <p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
            <h1>Departamento de Recursos Humanos</h1>
        </div>

        <section class="section">
           
            @if ($extratoFinaceiro)
                <table  style="width: 100%" class="table table-stripeds fs-6 text-center">
                    <thead>
                        <tr>
                            <td colspan="3" 
                                style="text-align: center;background-color: rgb(65, 64, 64);
                                color: #eeeeee;font-size: 10pt;"
                                >Situação dos Meses</td>
                            </tr>
                        <tr>
                            <th>Meses Pago</th>
                            <th>Abreviação</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: rgba(244, 244, 244, .5); font-size: 12pt;">
                        @foreach ($extratoFinaceiro as $item)
                            <tr>
                                <td class="bg-info">{{ $item->meses }}</td>
                                <td class="bg-dark">{{ $item->abreviacao }}</td>
                                <td class="bg-info">{{ $item->status }}</td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                    <tfoot style="background-color: rgba(244, 244, 244, 1.0);">
                        <tr>
                            <td class="bg-success">
                                Meses Pagos <strong> {{ $mesesPagos }}</strong>
                            </td>

                            <td class="bg-danger">
                                Meses Não Pagos <strong>{{ $mesesNPagos }}</strong>
                            </td>

                            <td class="bg-warning">
                                Meses Devendo <strong>{{ $dividas }}</strong>
                            </td>
                        </tr>

                        <tr>
                            <td class="bg-success">
                                Valor a Pagar <strong>{{ number_format(($mesesPagos * ($contratos->salario + $contratos->subcidio_transporte + $contratos->subcidio_alimentacao)) , '2', ',', '.') }} Kz</strong>
                            </td>

                            <td class="bg-danger">
                                Valor a Pagar <strong>{{ number_format(($mesesNPagos * ($contratos->salario + $contratos->subcidio_transporte + $contratos->subcidio_alimentacao)), '2', ',', '.')  }} Kz</strong>
                            </td>

                            <td class="bg-warning">
                                Valor a Pagar <strong>{{ number_format(($dividas * ($contratos->salario + $contratos->subcidio_transporte + $contratos->subcidio_alimentacao)), '2', ',', '.')  }} Kz</strong>
                            </td>
                        </tr>

                    </tfoot>
                </table>
            @endif    

        </section>
    </div>


    <div style="height: 970px">
        <div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
            <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
            <h1>{{ $escola->nome }}</h1>
            <p>NIF: {{ $escola->documento }}</p> 
            <p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
            <h1>Departamento de Recursos Humanos</h1>
        </div>

        <section class="section">
           
            @if ($turmas)
                <table>
                    @foreach ($turmas as $item)
                        <thead>
                            <tr>
                                <th colspan="5" 
                            style="background-color: rgb(220, 220, 220);
                            color: #545454;font-size: 10pt;"
                            >Horário na Turma {{ $item->turma }}</th>
                            </tr>

                            <tr>
                                <th>Disciplina</th>
                                <th>Dia de Semana</th>
                                <th>Tempos</th>
                                <th>Hora Entrada</th>
                                <th>Hora Saída</th>
                            </tr>

                            
                        </thead>

                        <tbody>
                            @php
                                $dias = (new App\Models\web\turmas\Horario)::where([
                                    ['turmas_id','=', $item->id],
                                ])
                                ->join('tb_disciplinas', 'tb_horario_turmas.disciplinas_id', '=', 'tb_disciplinas.id')
                                ->get();
                            @endphp

                            @if ($dias)
                                @foreach ($dias as $items)
                                    <tr>
                                        <td>{{ $items->disciplina }}</td>
                                        <td>{{ $items->semana->nome }}</td>
                                        <td>{{ $items->tempo->nome }} º</td>
                                        <td>{{ $items->hora_inicio }}</td>
                                        <td>{{ $items->hora_final }}</td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>                            
                    @endforeach

                </table>                    
            @endif   

        </section>
    </div>
</body>
</html>
