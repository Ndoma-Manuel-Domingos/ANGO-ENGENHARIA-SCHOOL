<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato do Funcionário | </title>

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
			font-size: 11pt;
			margin-bottom: 10px;
		}
        h2{
            font-size: 9pt;
        }

		.titulo{
			font-size: 9pt;
			text-align: center;
            margin-top: 0;
		}

		p{
			/* margin-bottom: 20px; */
			line-height: 30px;
            font-size: 12pt;
            text-align: justify;
		}
        strong{
            font-size: 12pt;
        }

		table{
			width: 100%;
			text-align: left;
			border-spacing: 0;	
			margin-bottom: 10px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 12pt;
		}
		thead{
			background-color: #fdfdfd;
			border-bottom: 1px solid #006699;
            font-size: 10pt;

		}
		th, td{
			padding: 12px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 8pt;
		}

        strong{
            font-size: 8pt;
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
            /* position: fixed;
            top: 0; */
            /* padding: 40px 20px; */
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            width: 100%;
            float: left;
        }

        .div01{
            width: 50%;
            float: left;
        }

        .section{
            /* margin-top: 90px;
            padding: 20px;  */
        }
	</style>
</head>
<body>

	<div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
		<img src="{{ asset("assets/images/$escola->logotipo") }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
        
        <h1>{{ $escola->nome }}</h1>
		<p>NIF: {{ $escola->documento }}</p> 
		<p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
        <h1>Departamento de Recursos Humanos </h1>
	</div>

    @if (isset($contrato) && $contrato != null)
        <section class="section">
            <h1>Ficha de Contrato Nº {{ $contrato->documento }}</h1>
            
            <h1 style="text-align: center;margin: 30px 0;text-transform: uppercase">CONTRATO DE TRABALHO POR TEMPO {{ $contrato->tempo_contrato }} (n.º 1 do artigo 14.º da Lei n.º 2/00, de 11 de Fevereiro - Lei Geral de Trabalho)</h1>
            
            <p>{{ $escola->nome }} , com sede em Luanda, na
                Rua 2, Casa nº 11, Bairro Areial, {{ $escola->distrito->nome ?? "" }}, Município de {{ $escola->municipio->nome ?? "" }}, Província {{ $escola->provincia->nome ?? ""}} , com o contribuinte fiscaln.º {{ $escola->documento }}, representada pelo Srº  {{ $escola->director }}, na qualidade de
                Procurador da Sócia-Gerenteda referida empresa, adiante designada como EMPREGADOR 
            </p>
                
            <p>E</p>    
            
            <p>{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}, {{ $funcionario->estado_civil }}, maior, natural de Cazengo, residente na Provínciade Luanda, Bairro Cazenga casa nª10 zona 18, portador do Bilhete de Identidadenº
                {{ $funcionario->bilheite }} , emitido pelos serviços de identificação, aos 31/12/2013 , válido até 30/12/2018 , daqui em diante designado de TRABALHADOR</p>
            <p>
                É celebrado o presente CONTRATO DE TRABALHO POR TEMPO INDETERMINADO
                , nostermos e de harmonia com o disposto na Lei Geral do Trabalho e demais legislaçãoaplicável, e pelas cláusulas seguintes:
            </p>
            
            <table>
                <thead>
                    <tr>
                        <td colspan="4" 
                        style="text-align: center;background-color: rgb(221, 221, 221);
                        color: #313131;font-size: 10pt;"
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
                        <td><strong>{{ $contrato->data_at }}</strong></td>
                    </tr> 
                </tbody>
            </table>

            <!-- Table row -->
            <table>
                <thead>
                    <tr>
                        <th colspan="4" 
                        style="text-align: center;background-color: rgb(221, 221, 221);
                        color: #575757;font-size: 10pt;"
                        >Informações sobre o Contrato</th>
                    </tr>
                </thead>


                <tbody>
                    <tr>
                        <td>Data Inicio <strong>{{ $contrato->data_inicio_contrato }}</strong></td>
                        <td>Data Final <strong>{{ $contrato->data_final_contrato }}</strong></td>
                        <td>Hora Entrada <strong>{{ $contrato->hora_entrada_contrato }}</strong></td>
                        <td>Hora Saída <strong>{{ $contrato->hora_saida_contrato }}</strong></td>
                    </tr>

                    <tr style="background-color: #fdfdfd;">                            
                        <td>Conta Bancaria: <strong>{{ $contrato->conta_bancaria }}</strong> </td>
                        <td>IBAN: <strong>{{ $contrato->iban }}</strong></td>
                        <td>NIF: <strong>{{ $contrato->nif }}</strong></td>
                        <td>Cargo: <strong>{{ $contrato->cargo }}</strong></td>
                    </tr>

                    <tr style="background-color: #fdfdfd;">  
                        <td>Status Contrato: <strong>{{ $contrato->status_contrato }}</strong></td>
                        <td>Status: <strong>{{ $contrato->status }}</strong></td>
                        <td>Subcídio Transporte: <strong>{{ number_format(($contrato->subcidio_transporte), '2', ',', '.') }} Kz</strong></td>
                        <td>Subcídio Alimentação: <strong>{{ number_format(($contrato->subcidio_alimentacao), '2', ',', '.') }} Kz</strong></td>
                    </tr>

                    <tr style="background-color: #fdfdfd;">  
                        <td><strong>-----</strong></td>
                        <td>Outros Subcídios: <strong>{{ number_format(($contrato->subcidio), '2', ',', '.') }} Kz</strong></td>
                        <td>Salario Basíco: <strong>{{ number_format(($contrato->salario), '2', ',', '.') }} Kz</strong></td>
                        <td>Salario Geral: <strong>{{ number_format(($contrato->salario + $contrato->subcidio + $contrato->subcidio_alimentacao + $contrato->subcidio_transporte), '2', ',', '.') }} Kz</strong></td>
                    </tr>

                    <tr style="background-color: #eeeeee;">
                        <th colspan="4">Clausula</th>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <p>{{ $contrato->clausula }}</p> </td>
                    </tr>
                </tbody>
            </table>        
        </section>
    @endif

</body>
</html>