<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\calendarios\Pagamento;
use App\Models\web\calendarios\PagamentoNotaCredito;
use App\Models\web\calendarios\PagamentoOriginal;
use App\Models\web\calendarios\PagamentoRecibo;
use App\Models\web\calendarios\Servico;
use App\Models\web\estudantes\Estudante;
use Carbon\Carbon;
use Illuminate\Http\Request;


use phpseclib\Crypt\RSA;

use DateTime;
use DOMDocument;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class GerarSaftController extends Controller
{
 
    use TraitHelpers;
    use TraitChavesSaft;
    #Ndoma
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = auth()->user();
        
        if(!$user->can('create: factura')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Gerar Documento SAFT",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.ficheiros-safts.saft', $headers);
    }

    public function store(Request $request)
    {
    
        $user = auth()->user();
        
        if(!$user->can('gerar saft')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        $StartDate = str_replace("T", " ", $request->data_inicio) . " 00:00";;
        $EndDate = str_replace("T", " ", $request->data_final) . " 00:59";

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $root = $dom->createElement('AuditFile');
        $root->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $root->setAttribute('xsi:schemaLocation', "urn:OECD:StandardAuditFile-Tax:AO_1.01_01 SAFTAO1.01_01.xsd");
        $root->setAttribute('xmlns', "urn:OECD:StandardAuditFile-Tax:AO_1.01_01");
        $dom->appendChild($root);
        
        $header = $dom->createElement('Header');
        $header->appendChild($dom->createElement('AuditFileVersion', '1.01_01'));
        
        $entidade = Shcool::with(['ensino','distrito','pais','provincia','municipio'])->findOrFail($this->escolarLogada());
        
        $header->appendChild($dom->createElement('CompanyID', $entidade->documento));
        $header->appendChild($dom->createElement('TaxRegistrationNumber', $entidade->documento));
        $header->appendChild($dom->createElement('TaxAccountingBasis', 'F'));
        $header->appendChild($dom->createElement('CompanyName', $entidade->nome ?? "Desconhecido"));
        $header->appendChild($dom->createElement('BusinessName', $entidade->nome ?? "Desconhecido"));
        //create companyAddress
                
        $companyAddress = $dom->createElement('CompanyAddress');
        $companyAddress->appendChild($dom->createElement('AddressDetail', $entidade->endereco ?? "Desconhecido"));
        $companyAddress->appendChild($dom->createElement('City', $entidade->provincia->nome ?? "Desconhecido"));
        $companyAddress->appendChild($dom->createElement('Province', $entidade->provincia->nome ?? "Desconhecido"));
        $companyAddress->appendChild($dom->createElement('Country', 'AO'));
        $header->appendChild($companyAddress);
        $header->appendChild($dom->createElement('FiscalYear', Carbon::parse(Carbon::now())->format('Y')));
        $header->appendChild($dom->createElement('StartDate', date_format(date_create($StartDate), "Y-m-d")));
        $header->appendChild($dom->createElement('EndDate', date_format(date_create($EndDate), "Y-m-d")));
        $header->appendChild($dom->createElement('CurrencyCode', 'AOA'));
        
        $dateNow = date_format(new DateTime(Carbon::now()->addHour()->toDateTimeString()), 'Y-m-d');
        $header->appendChild($dom->createElement('DateCreated', $dateNow));
        $header->appendChild($dom->createElement('TaxEntity', 'Global'));
        $header->appendChild($dom->createElement('ProductCompanyTaxID', $entidade->documento ?? "Desconhecido"));
        
        $header->appendChild($dom->createElement('SoftwareValidationNumber', '469/AGT/2024'));
        $header->appendChild($dom->createElement('ProductID', 'EA VIEGAS/EA VIEGAS - COMERCIO GERAL E PRESTAÇAO DE SERVIÇOS , LDA'));
        $header->appendChild($dom->createElement('ProductVersion', '1.0.0'));
       
        $header->appendChild($dom->createElement('Telephone', $entidade->telefone1 ?? "Desconhecido"));
        $header->appendChild($dom->createElement('Email', $entidade->site ?? "Desconhecido"));
        $header->appendChild($dom->createElement('Website', $entidade->site ?? "Desconhecido"));
        $root->appendChild($header);
        
        //MasterFiles
        $masterFiles = $dom->createElement('MasterFiles');
    
        $consumidor_final = Estudante::where('shcools_id', $entidade->id)->where('nome', 'CONSUMIDOR FINAL')->first();
        $clientes = Estudante::with(['provincia'])->where('shcools_id', $entidade->id)->get();
           
        foreach ($clientes as $key => $cliente) {
            if ($cliente->nif == '999999999') {
                $CustomerID = $consumidor_final->id;
                $AccountID = "Desconhecido";
                $CustomerTaxID = $consumidor_final->nif;
                $CompanyName = "Consumidor Final";
                $AddressDetail = "Desconhecido";
                $City = "Desconhecido";
                $PostalCode = "Desconhecido";
                $Country = "Desconhecido";
                ++$key;
                if ($key > 1) {
                    continue;
                }
            } else {
                $CustomerID = $cliente->id;
                $AccountID =  $cliente->conta_corrente;
                $CustomerTaxID = "999999999"; // $cliente->bilheite;
                $CompanyName =  $cliente->nome ?? "Desconhecido";
                $AddressDetail = $cliente->endereco ?? "Desconhecido";
                $City = $cliente->provincia->nome ??  "Desconhecido";
                $PostalCode = "Desconhecido"; // "*";
                $Country = "AO";
            }

            $customer = $dom->createElement('Customer');
            $customer->appendChild($dom->createElement('CustomerID', $CustomerID));
            $customer->appendChild($dom->createElement('AccountID', $AccountID));
            $customer->appendChild($dom->createElement('CustomerTaxID', $CustomerTaxID));
            $customer->appendChild($dom->createElement('CompanyName', $CompanyName));
            //BillingAddress
            $billingAddress = $dom->createElement('BillingAddress');
            $billingAddress->appendChild($dom->createElement('AddressDetail', $AddressDetail));
            $billingAddress->appendChild($dom->createElement('City', $City));
            $billingAddress->appendChild($dom->createElement('PostalCode', $PostalCode));
            $billingAddress->appendChild($dom->createElement('Country', $Country));
            $customer->appendChild($billingAddress);
            $customer->appendChild($dom->createElement('SelfBillingIndicator', 0));
            $masterFiles->appendChild($customer);
        }
        $root->appendChild($masterFiles);
        
        $produtos = Servico::where('shcools_id', $entidade->id)->where('shcools_id', $this->escolarLogada())->get();
        
        foreach ($produtos as $key => $produto) {
            $product = $dom->createElement('Product');
            $product->appendChild($dom->createElement('ProductType', $produto->tipo));
            $product->appendChild($dom->createElement('ProductCode', $produto->id));
            $product->appendChild($dom->createElement('ProductGroup', 'N/A'));
            $product->appendChild($dom->createElement('ProductDescription', $produto->servico));
            $product->appendChild($dom->createElement('ProductNumberCode', $produto->id));
            $masterFiles->appendChild($product);
        }
        
        $taxas = $this->listarTaxas($produtos);
            
        $taxTable = $dom->createElement('TaxTable');
       
        foreach ($taxas as $tipoTaxa) {
            $TaxType = $tipoTaxa->taxa ? "IVA" : "NS";
            // $TaxCode = $tipoTaxa->taxa ? "NOR" : "ISE";
            $TaxCode = $tipoTaxa->taxa ? "NOR" : "NS";
            //$Description = $tipoTaxa->designacao ? "Taxa Normal" : "Isenta";
            $Description = $tipoTaxa->taxa ? "Taxa Normal" : "Isenta";
            $TaxPercentage = $tipoTaxa->taxa;
            $taxTableEntry = $dom->createElement('TaxTableEntry');
            $taxTableEntry->appendChild($dom->createElement('TaxType', $TaxType));
            $taxTableEntry->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
            $taxTableEntry->appendChild($dom->createElement('TaxCode', $TaxCode));
            $taxTableEntry->appendChild($dom->createElement('Description', $Description));
            $taxTableEntry->appendChild($dom->createElement('TaxPercentage', $TaxPercentage));
            $taxTable->appendChild($taxTableEntry);
        }
        
        $masterFiles->appendChild($taxTable);

        // QDT FT E FR (STARTDATE E ENDDATE)
        $quantFtFr = Pagamento::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->where(function ($query) {
                $query->whereIn('tipo_factura', ['FT', 'FR']);
            })
            ->count();
            
        //OBS: adicionar aqui Qtds notas de creditos (facturas e facturas recibos anulados ou retificados)

        $TotalCredit = Pagamento::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('anulado', 'N')
            ->where('retificado', 'N')
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->where(function ($query) {
                $query->whereIn('tipo_factura', ['FT', 'FR']);
            })
            ->sum(DB::raw('total_incidencia'));
         
        $TotalCreditRetificada = PagamentoOriginal::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('anulado', 'N')
            ->where('retificado', 'N')
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->where(function ($query) {
                $query->whereIn('tipo_factura', ['FT', 'FR']);
            })
            ->sum(DB::raw('total_incidencia'));

            
        $TotalCredit = $TotalCredit + $TotalCreditRetificada;
        
        $TotalCredit_ = 0;

        $sourceDocuments = $dom->createElement('SourceDocuments');
        
        
        //LISTAR FACTURAS E FACTURAS RECIBOS
        $facturas = Pagamento::with(['items', 'items.servico', 'items.servico', 'items.servico.motivo'])
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->where(function ($query) {
                $query->whereIn('tipo_factura', ['FT', 'FR']);
            })->get();
            
        ## 
            
        if(count($facturas) > 0){
            $salesInvoices = $dom->createElement('SalesInvoices');
        }
        
        // $clienteDiverso = Estudante::where('entidade_id', $entidade->empresa->id)->first();
        
        $notaCreditoIDs = [];

        $invoiceFTEFRs = [];
        
         
            $total_extra = 0;
            
    
        foreach ($facturas as $key => $factura) {
         
            if ($factura->anulado == 'Y') {
                array_push($notaCreditoIDs, $factura->id);
            }

            if ($factura->retificado == 'Y') {
                $factura = PagamentoOriginal::with(['items', 'items.servico', 'items.servico', 'items.servico.motivo'])
                    ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
                    ->where('shcools_id', $this->escolarLogada())
                    ->where('ano_lectivos_id', $this->anolectivoActivo())
                    ->where('status', 'Confirmado')
                    ->where('caixa_at', 'receita')
                    ->where('id', $factura->id)->first();
                    
                array_push($notaCreditoIDs, $factura->id);
            }

            $InvoiceNo = $factura->next_factura;
            $InvoiceStatusDate = $factura->created_at;
            $SourceID = $factura->funcionarios_id ?? NULL;
            $Hash = $factura->hash;
            $InvoiceDate = Carbon::parse($factura->created_at)->format('Y-m-d');
            $InvoiceType = $factura->tipo_factura;
            $SystemEntryDate = str_replace(' ', 'T', $factura->created_at);
            $CustomerID = $factura->estudantes_id; 

            $invoice = $dom->createElement('Invoice');
            $invoice->appendChild($dom->createElement('InvoiceNo', $InvoiceNo));
            $documentStatus = $dom->createElement('DocumentStatus');
            $InvoiceStatus = $factura->anulado == 'Y' ? "A" : "N";
            $documentStatus->appendChild($dom->createElement('InvoiceStatus', $InvoiceStatus));
            $documentStatus->appendChild($dom->createElement('InvoiceStatusDate', (Carbon::parse($InvoiceStatusDate)->format('Y-m-d') . "T" . Carbon::parse($InvoiceStatusDate)->format("H:i:s"))));
            $documentStatus->appendChild($dom->createElement('SourceID', $SourceID));
            $documentStatus->appendChild($dom->createElement('SourceBilling', 'P'));
            $invoice->appendChild($documentStatus); //Add documentStatus no Invoice
            $invoice->appendChild($dom->createElement('Hash', $Hash));
            $invoice->appendChild($dom->createElement('HashControl', '1'));
            $invoice->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
            $invoice->appendChild($dom->createElement('InvoiceDate', $InvoiceDate));
            $invoice->appendChild($dom->createElement('InvoiceType', $InvoiceType));
            $specialRegimes = $dom->createElement('SpecialRegimes');
            $specialRegimes->appendChild($dom->createElement('SelfBillingIndicator', 0));
            $specialRegimes->appendChild($dom->createElement('CashVATSchemeIndicator', 0));
            $specialRegimes->appendChild($dom->createElement('ThirdPartiesBillingIndicator', 0));
            $invoice->appendChild($specialRegimes); //add specialRegimes no Invoice
            $invoice->appendChild($dom->createElement('SourceID', $SourceID));
            $invoice->appendChild($dom->createElement('SystemEntryDate', $SystemEntryDate));
            $invoice->appendChild($dom->createElement('CustomerID', $CustomerID));
            //Criar Line de Invoice foreach
       
            foreach ($factura['items'] as $key => $Item) {
             
                $Item = (object) $Item;
                $line = $dom->createElement('Line');
                $line->appendChild($dom->createElement('LineNumber', $key + 1));
                $line->appendChild($dom->createElement('ProductCode', $Item->servicos_id));
                $line->appendChild($dom->createElement('ProductDescription', $Item->servico->servico));
                $line->appendChild($dom->createElement('Quantity', number_format($Item->quantidade, 1, ".", "")));
                $line->appendChild($dom->createElement('UnitOfMeasure', ($Item->servico->unidade ? $Item->servico->unidade : "un")));
                $line->appendChild($dom->createElement('UnitPrice', number_format($Item->preco + $Item->desconto_valor, 2, ".", "")));
                $line->appendChild($dom->createElement('TaxPointDate', Carbon::parse($factura->created_at)->format('Y-m-d')));
                $Description = $factura->observacao ? $factura->observacao : 'FACTURA ' . $factura->next_factura;
                $line->appendChild($dom->createElement('Description', $Description));
                // $valor_base1 = ($Item->preco - $Item->desconto_valor);
                $valor_base = (($Item->preco - $Item->desconto_valor) * $Item->quantidade);
                
                $total_extra += $valor_base; 
                
                $line->appendChild($dom->createElement('CreditAmount', number_format($valor_base, 2, ".", "")));
               
                $TotalCredit_ = $TotalCredit_ + $valor_base;
               
                $taxa = DB::table('tb_taxas')->where('id', $Item->servico->taxa_id)->first();
                // $taxa = Taxa::findOrFail($Item->servico->taxa_id);
                
                $TaxExemptionReason = $Item->servico->motivo->descricao;
                $TaxExemptionCode = $Item->servico->motivo->codigo;
   
                //Criar Taixa e seus filhos
                $tax = $dom->createElement('Tax');
                $tax->appendChild($dom->createElement('TaxType', "IVA"));
                $tax->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
                $tax->appendChild($dom->createElement('TaxCode', $taxa->sigla));
                $tax->appendChild($dom->createElement('TaxPercentage', $taxa->taxa));
                $line->appendChild($tax); //Add Fax na Line
                $line->appendChild($dom->createElement('TaxExemptionReason', $TaxExemptionReason));
                $line->appendChild($dom->createElement('TaxExemptionCode', $TaxExemptionCode));
                $line->appendChild($dom->createElement('SettlementAmount', $Item->desconto_valor));
                $invoice->appendChild($line);
            }
           
            $documentTotals = $dom->createElement('DocumentTotals');
            $documentTotals->appendChild($dom->createElement('TaxPayable', number_format($factura->total_iva, 2, ".", "")));
            $documentTotals->appendChild($dom->createElement('NetTotal', number_format($factura->total_incidencia, 2, ".", "")));

            //$GrossTotal = $factura->valor_total;
            // $GrossTotal = $factura->total_incidencia;
            $GrossTotal = $factura->total_iva + $factura->total_incidencia;
            // $GrossTotal = $factura->valor2;
            
            $documentTotals->appendChild($dom->createElement('GrossTotal', number_format($GrossTotal, 2, ".", "")));
            $invoice->appendChild($documentTotals);
            $payment = $dom->createElement('Payment');
            $PaymentMechanism = $factura->tipo_pagamento ? $factura->tipo_pagamento : "OU";
    
            $payment->appendChild($dom->createElement('PaymentMechanism', $PaymentMechanism));
            $payment->appendChild($dom->createElement('PaymentAmount', number_format($GrossTotal, 2, ".", "")));
            $payment->appendChild($dom->createElement('PaymentDate', Carbon::parse($factura->created_at)->format('Y-m-d')));
            $documentTotals->appendChild($payment);
            // $salesInvoices->appendChild($invoice);
            $invoiceFTEFRs[] = $invoice;
            
        }
      
           
        //Notas de crédito
        $notaCreditos = PagamentoNotaCredito::with([
            'items',
            'pagamento', 'items.servico',
            'items.servico.motivo'
        ])
        ->where('shcools_id', $this->escolarLogada())
        ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->where('status', 'Confirmado')
        ->where('caixa_at', 'receita')
        ->whereIn('pagamento_id', $notaCreditoIDs)->get();
        
        $TotalDebit = 0;

        if ($notaCreditos) {
            $invoiceNCs = [];
            $quantNotaCredito = 0;
            foreach ($notaCreditos as $key => $notaCredito) {

                $quantNotaCredito++;

                $InvoiceNo = $notaCredito->next_factura;
                $InvoiceStatusDate = $notaCredito->created_at;
                $SourceID = $notaCredito->funcionarios_id;
                $Hash = $notaCredito->hash;
                $InvoiceDate = Carbon::parse($notaCredito->pagamento->created_at)->format('Y-m-d');
                $SystemEntryDate = str_replace(' ', 'T', $notaCredito->created_at);
                $CustomerID = $notaCredito->estudantes_id;

                $invoice = $dom->createElement('Invoice');
                $invoice->appendChild($dom->createElement('InvoiceNo', $InvoiceNo));
                $documentStatus = $dom->createElement('DocumentStatus');
                $documentStatus->appendChild($dom->createElement('InvoiceStatus', "N"));
                $documentStatus->appendChild($dom->createElement('InvoiceStatusDate', (Carbon::parse($InvoiceStatusDate)->format('Y-m-d') . "T" . Carbon::parse($InvoiceStatusDate)->format("H:i:s"))));
                $documentStatus->appendChild($dom->createElement('SourceID', $SourceID));
                $documentStatus->appendChild($dom->createElement('SourceBilling', 'P'));
                $invoice->appendChild($documentStatus); //Add documentStatus no Invoice
                $invoice->appendChild($dom->createElement('Hash', $Hash));
                $invoice->appendChild($dom->createElement('HashControl', '1'));
                $invoice->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
                $invoice->appendChild($dom->createElement('InvoiceDate', $InvoiceDate));
                $invoice->appendChild($dom->createElement('InvoiceType', 'NC'));
                $specialRegimes = $dom->createElement('SpecialRegimes');
                $specialRegimes->appendChild($dom->createElement('SelfBillingIndicator', 0));
                $specialRegimes->appendChild($dom->createElement('CashVATSchemeIndicator', 0));
                $specialRegimes->appendChild($dom->createElement('ThirdPartiesBillingIndicator', 0));
                $invoice->appendChild($specialRegimes); //add specialRegimes no Invoice
                $invoice->appendChild($dom->createElement('SourceID', $SourceID));
                $invoice->appendChild($dom->createElement('SystemEntryDate', $SystemEntryDate));
                $invoice->appendChild($dom->createElement('CustomerID', $CustomerID));
                //Criar Line de Invoice foreach

                foreach ($notaCredito['items'] as $key => $Item) {

                    $Item = (object) $Item;
                    $line = $dom->createElement('Line');
                    $line->appendChild($dom->createElement('LineNumber', $key + 1));
                    $line->appendChild($dom->createElement('ProductCode', $Item->servicos_id));
                    $line->appendChild($dom->createElement('ProductDescription', $Item->servico->nome));
                    $line->appendChild($dom->createElement('Quantity', number_format($Item->quantidade, 1, ".", "")));
                    $line->appendChild($dom->createElement('UnitOfMeasure', ($Item->servico->unidade ? $Item->servico->unidade : "un")));
                    $line->appendChild($dom->createElement('UnitPrice', number_format($Item->preco + $Item->desconto_valor, 2, ".", "")));
                    $line->appendChild($dom->createElement('TaxPointDate', Carbon::parse($notaCredito->created_at)->format('Y-m-d')));
                    $Description = $notaCredito->observacao ? $notaCredito->observacao : 'FACTURA ANULADA OU RETIFICADA ' . $notaCredito->pagamento->next_factura;
                    $References = $dom->createElement('References');
                    $References->appendChild($dom->createElement('Reference', $notaCredito->pagamento->next_factura));
                    $References->appendChild($dom->createElement('Reason', $Description));
                    $line->appendChild($References);
                    $line->appendChild($dom->createElement('Description', $Description));
                    
                    $valor_base = $Item->preco + $Item->desconto_valor;
                    // $valor_base = (($Item->preco + $Item->desconto_valor) * $Item->quantidade) - $Item->valor_iva;
                    $line->appendChild($dom->createElement('DebitAmount', number_format($valor_base, 2, ".", "")));
                    
                    
                    $taxa_ = DB::table('tb_taxas')->where('id', $Item->servico->taxa_id)->first();


                    if ($taxa_->taxa > 0) {
                        $TaxType = "IVA";
                        //se foi aplicado IVA seta vazio
                        $TaxExemptionReason = "#";
                        $TaxExemptionCode = "#";
                    } else {
                        $TaxType = "NS";
                        //se não foi aplicado iva seta valores
                        $TaxExemptionReason = $Item->servico->motivo->descricao;
                        $TaxExemptionCode = $Item->servico->motivo->codigo;
                    }
                    
                    // $TaxCode = $Item->servico->tipoTaxa->taxa ? "NOR" : ($TaxType == "NS" ? "NS" : "ISE");
                    $TaxCode = $taxa_->taxa ? "NOR" : ($TaxType == "NS" ? "NS" : "NS");
                    $Description = $taxa_->taxa ? "Taxa Normal" : "Isenta";
                    $TaxPercentage = $taxa_->taxa;

                    $TaxExemptionCode = $TaxExemptionCode ?? 'M02';

                    //Criar Taixa e seus filhos
                    $tax = $dom->createElement('Tax');
                    $tax->appendChild($dom->createElement('TaxType', $TaxType));
                    $tax->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
                    $tax->appendChild($dom->createElement('TaxCode', $TaxCode));
                    $tax->appendChild($dom->createElement('TaxPercentage', number_format($TaxPercentage, 1, ".", "")));
                    $line->appendChild($tax); //Add Fax na Line
                    $line->appendChild($dom->createElement('TaxExemptionReason', $TaxExemptionReason));
                    $line->appendChild($dom->createElement('TaxExemptionCode', $TaxExemptionCode));
                    $line->appendChild($dom->createElement('SettlementAmount', $Item->desconto_valor));
                    $invoice->appendChild($line);
                }

                $TotalDebit += $notaCredito->total_incidencia;

                // if ($notaCredito->anulado == 1) { //Não anulado
                //     $TotalCredit += $notaCredito->total_incidencia;
                // }

                //criar  DocumentTotals e seus filhos
                $documentTotals = $dom->createElement('DocumentTotals');
                $documentTotals->appendChild($dom->createElement('TaxPayable', number_format($notaCredito->total_iva, 2, ".", "")));
                $documentTotals->appendChild($dom->createElement('NetTotal', number_format($notaCredito->total_incidencia, 2, ".", "")));

                $GrossTotal = $notaCredito->total_iva + $notaCredito->total_incidencia;
                // $GrossTotal = $notaCredito->valor2;

                $documentTotals->appendChild($dom->createElement('GrossTotal', number_format($GrossTotal, 2, ".", "")));
                $invoice->appendChild($documentTotals);
                $payment = $dom->createElement('Payment');
                $PaymentMechanism = $factura->tipo_pagamento ? $factura->tipo_pagamento : "OU";

                $payment->appendChild($dom->createElement('PaymentMechanism', $PaymentMechanism));
                $payment->appendChild($dom->createElement('PaymentAmount', number_format($GrossTotal, 2, ".", "")));
                $payment->appendChild($dom->createElement('PaymentDate', Carbon::parse($notaCredito->created_at)->format('Y-m-d')));
                $documentTotals->appendChild($payment);

                $invoiceNCs[] = $invoice;
            }
        }
      
        if(count($facturas) > 0){
            $NumberOfEntries =  $quantFtFr + $quantNotaCredito;
            $salesInvoices->appendChild($dom->createElement('NumberOfEntries', $NumberOfEntries));
            $salesInvoices->appendChild($dom->createElement('TotalDebit', number_format($TotalDebit, 2, ".", "")));
            $salesInvoices->appendChild($dom->createElement('TotalCredit', number_format($TotalCredit, 2, ".", "")));
            $sourceDocuments->appendChild($salesInvoices); 
        }
        

        //faz o array para colocar os invoices enbaixo das NumberOfEntries
        foreach ($invoiceFTEFRs as $invoiceFTEFR) {
            $salesInvoices->appendChild($invoiceFTEFR);
        }
        
        foreach ($invoiceNCs as $invoiceNC) {
            $salesInvoices->appendChild($invoiceNC);
        }
        
        //MovementOfGoods
        $movementOfGoods = $dom->createElement('MovementOfGoods');
        $movementOfGoods->appendChild($dom->createElement('NumberOfMovementLines', 0));
        $movementOfGoods->appendChild($dom->createElement('TotalQuantityIssued', 0.00));
        $sourceDocuments->appendChild($movementOfGoods);
        //fim MovementOfGoods
        
        //Lista apenas facturas proformas
        $countFtProforma = Pagamento::where('shcools_id', $this->escolarLogada())
        ->where('ano_lectivos_id', $this->anolectivoActivo())
        ->where('status', 'Confirmado')
        ->where('caixa_at', 'receita')
        ->where('tipo_factura', 'FP')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->count();
          
        $TotalDebit = 0;
        
        $facturas_proforma = Pagamento::with(['items', 'items.servico', 'items.servico.motivo'])
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->where('tipo_factura', 'FP')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->get();
            
        $TotalCredit = Pagamento::with(['items','items.servico', 'items.servico.motivo'])
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->where('tipo_factura', 'FP')
            ->where('anulado', 'N') //Nao anulado
            ->where('retificado','N')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->sum(DB::raw('total_incidencia'));
                
        $TotalCreditRetificada = PagamentoOriginal::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->where('tipo_factura', 'FP')
            ->where('anulado', 'N') //Nao anulado
            ->where('retificado','N')
            ->sum(DB::raw('total_incidencia'));
            
        $TotalCredit = $TotalCredit + $TotalCreditRetificada;
        
        $workingDocuments = $dom->createElement('WorkingDocuments');
        
        $workingDocuments->appendChild($dom->createElement('NumberOfEntries', $countFtProforma));
        $workingDocuments->appendChild($dom->createElement('TotalDebit', $TotalDebit));
        $workingDocuments->appendChild($dom->createElement('TotalCredit', number_format($TotalCredit, 2, ".", "")));
        $sourceDocuments->appendChild($workingDocuments);
        
        foreach ($facturas_proforma as $key => $facturaProforma) {

            $WorkDocument = $dom->createElement('WorkDocument');
            $DocumentNumber = $facturaProforma->next_factura;
            $WorkDocument->appendChild($dom->createElement('DocumentNumber', $DocumentNumber));
            $DocumentStatus = $dom->createElement('DocumentStatus');

            $DocumentStatus->appendChild($dom->createElement('WorkStatus', 'N'));
            $DocumentStatus->appendChild($dom->createElement('WorkStatusDate', (Carbon::parse($facturaProforma->updated_at)->format('Y-m-d') . "T" . Carbon::parse($facturaProforma->updated_at)->format("H:i:s"))));
            $DocumentStatus->appendChild($dom->createElement('Reason', '#'));
            $DocumentStatus->appendChild($dom->createElement('SourceID', $facturaProforma->funcionarios_id));
            $DocumentStatus->appendChild($dom->createElement('SourceBilling', 'P'));
            $WorkDocument->appendChild($DocumentStatus);
            $WorkDocument->appendChild($dom->createElement('Hash', $facturaProforma->hash));
            $WorkDocument->appendChild($dom->createElement('HashControl', '1'));
            $WorkDocument->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
            $WorkDocument->appendChild($dom->createElement('WorkDate', Carbon::parse($facturaProforma->created_at)->format('Y-m-d')));
            $WorkDocument->appendChild($dom->createElement('WorkType', $facturaProforma->tipo_factura));
            $WorkDocument->appendChild($dom->createElement('SourceID', $facturaProforma->funcionarios_id));
            $WorkDocument->appendChild($dom->createElement('SystemEntryDate', (Carbon::parse($facturaProforma->created_at)->format('Y-m-d') . "T" . Carbon::parse($facturaProforma->created_at)->format("H:i:s"))));
            $WorkDocument->appendChild($dom->createElement('TransactionID', '#'));
            $WorkDocument->appendChild($dom->createElement('CustomerID', $facturaProforma->estudantes_id));


            foreach ($facturaProforma["items"] as $key => $ftProformaItem) {

                $line = $dom->createElement('Line');
                $line->appendChild($dom->createElement('LineNumber', $key + 1));
                $line->appendChild($dom->createElement('ProductCode', $ftProformaItem->servicos_id));
                $line->appendChild($dom->createElement('ProductDescription', $ftProformaItem->servico->nome));
                $line->appendChild($dom->createElement('Quantity', number_format($ftProformaItem->quantidade, 1, ".", "")));
                $line->appendChild($dom->createElement('UnitOfMeasure', ($ftProformaItem->servico->unidade ? $ftProformaItem->servico->unidade : "un")));
                $line->appendChild($dom->createElement('UnitPrice', number_format($ftProformaItem->preco + $ftProformaItem->desconto_valor, 2, ".", "")));
                $line->appendChild($dom->createElement('TaxPointDate', Carbon::parse($facturaProforma->created_at)->format('Y-m-d')));
                $line->appendChild($dom->createElement('Description', $facturaProforma->observacao ? $facturaProforma->observacao : 'FACTURA ' . $facturaProforma->next_factura));
                $valor_base = $Item->preco + $Item->desconto_valor;
                // $valor_base = (($Item->preco + $Item->desconto_valor) * $Item->quantidade) - $Item->valor_iva;
                $line->appendChild($dom->createElement('CreditAmount', number_format($valor_base, 2, ".", "")));

                $taxa_ = DB::table('tb_taxas')->where('id', $ftProformaItem->servico->taxa_id)->first();

                if ($taxa_->taxa > 0) {
                    $TaxType = "IVA";
                    //se foi aplicado IVA seta vazio
                    $TaxExemptionReason = "#";
                    $TaxExemptionCode = "#";
                } else {
                    $TaxType = "NS";
                    //se não foi aplicado iva seta valores
                    $TaxExemptionReason = $ftProformaItem->servico->motivo->descricao;
                    $TaxExemptionCode = $ftProformaItem->servico->motivo->codigo;
                }
                // $TaxCode = $ftProformaItem->servico->tipoTaxa->taxa ? "NOR" : ($TaxType == "NS" ? "NS" : "ISE");
                //$TaxCode = $taxa_->sigla ? "NOR" : ($TaxType == "NS" ? "NS" : "NS");
                $TaxCode = $taxa_->taxa ? "NOR" : ($TaxType == "NS" ? "NS" : "NS");
                //$Description = $taxa_->designacao ? "Taxa Normal" : "Isenta";
                 $Description = $taxa_->taxa ? "Taxa Normal" : "Isenta";
                $TaxPercentage = $taxa_->taxa;
               

                //Criar Taxa e seus filhos
                $tax = $dom->createElement('Tax');
                $tax->appendChild($dom->createElement('TaxType', $TaxType));
                $tax->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
                $tax->appendChild($dom->createElement('TaxCode', $TaxCode));
                $tax->appendChild($dom->createElement('TaxPercentage', $TaxPercentage));
                $line->appendChild($tax); //Add Fax na Line
                $line->appendChild($dom->createElement('TaxExemptionReason', $TaxExemptionReason));
                $line->appendChild($dom->createElement('TaxExemptionCode', $TaxExemptionCode));
                $line->appendChild($dom->createElement('SettlementAmount', $ftProformaItem->desconto_valor));
                $WorkDocument->appendChild($line);
            }
            $workingDocuments->appendChild($WorkDocument);

            //criar  DocumentTotals e seus filhos
            $documentTotals = $dom->createElement('DocumentTotals');
            $documentTotals->appendChild($dom->createElement('TaxPayable', number_format($facturaProforma->total_iva, 2, ".", "")));
            $documentTotals->appendChild($dom->createElement('NetTotal', number_format($facturaProforma->total_incidencia, 2, ".", "")));

            //$GrossTotal = $facturaProforma->valor_total;
            $GrossTotal = $facturaProforma->total_iva + $facturaProforma->total_incidencia;
            // $GrossTotal = $facturaProforma->valor2; // + $facturaProforma->total_incidencia;

            $documentTotals->appendChild($dom->createElement('GrossTotal', number_format($GrossTotal, 2, ".", "")));
            $WorkDocument->appendChild($documentTotals);
        }
        
        // Preenche SourceDocuments->Payments
        // Qtd de recibos(incluindo os anulados)
        $quantRecibos = PagamentoRecibo::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->count();
     
        $TotalCredit = PagamentoRecibo::where('anulado', 'N') //recibo não anulado
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('shcools_id', $this->escolarLogada())
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->sum(DB::raw('total_incidencia'));

        $TotalDebit = 0;
        $TotalDebit = number_format($TotalDebit, 2, ".", "");
        $TotalCredit = number_format($TotalCredit, 2, ".", "");
        
        // Preenche SourceDocuments->Payments->Payment
        $recibos = PagamentoRecibo::with(['estudante', 'pagamento'])
            ->where('shcools_id', $this->escolarLogada())
            ->where('ano_lectivos_id', $this->anolectivoActivo())
            ->where('status', 'Confirmado')
            ->where('caixa_at', 'receita')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->get();
            
        //Payments
        $payments = $dom->createElement('Payments');
        $payments->appendChild($dom->createElement('NumberOfEntries', $quantRecibos));
        $payments->appendChild($dom->createElement('TotalDebit', $TotalDebit));
        $payments->appendChild($dom->createElement('TotalCredit', $TotalCredit));
        $sourceDocuments->appendChild($payments);
        
        foreach ($recibos as $key => $recibo) {

            $Payment = $dom->createElement('Payment');
            $Payment->appendChild($dom->createElement('PaymentRefNo', $recibo->next_factura));
            $Payment->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
            $Payment->appendChild($dom->createElement('TransactionDate', Carbon::parse($recibo->created_at)->format('Y-m-d')));
            $Payment->appendChild($dom->createElement('PaymentType', 'RG'));

            $Description = $recibo->observacao ? $recibo->observacao : 'Liquidação da factura ' . $recibo->pagamento->next_factura;

            $Payment->appendChild($dom->createElement('Description', $Description));
            $Payment->appendChild($dom->createElement('SystemID', $recibo->id));
            $payments->appendChild($Payment);

            // Preenche SourceDocuments->Payments->Payment->DocumentStatus

            $PaymentStatus = $recibo->anulado == 'N' ? "N" : "A";

            $DocumentStatus = $dom->createElement('DocumentStatus');
            $DocumentStatus->appendChild($dom->createElement('PaymentStatus', $PaymentStatus));
            $DocumentStatus->appendChild($dom->createElement('PaymentStatusDate', Carbon::parse($recibo->updated_at)->format('Y-m-d') . "T" . Carbon::parse($recibo->updated_at)->format("H:i:s")));
            $DocumentStatus->appendChild($dom->createElement('SourceID', $recibo->funcionarios_id));
            $DocumentStatus->appendChild($dom->createElement('SourcePayment', 'P'));
            $Payment->appendChild($DocumentStatus);

            // Preenche SourceDocuments->Payments->Payment->PaymentMethod

            $PaymentMethod = $dom->createElement('PaymentMethod');
            $PaymentMethod->appendChild($dom->createElement('PaymentMechanism', $recibo->tipo_pagamento ? $recibo->tipo_pagamento : "OU"));
            $PaymentMethod->appendChild($dom->createElement('PaymentAmount', $TotalDebit = number_format($recibo->valor2, 2, ".", "")));
            $PaymentMethod->appendChild($dom->createElement('PaymentDate', Carbon::parse($recibo->created_at)->format('Y-m-d')));
            $Payment->appendChild($PaymentMethod);
            $Payment->appendChild($dom->createElement('SourceID', $recibo->funcionarios_id));
            $Payment->appendChild($dom->createElement('SystemEntryDate', Carbon::parse($recibo->created_at)->format('Y-m-d') . "T" . Carbon::parse($recibo->updated_at)->format("H:i:s")));
            
            $CustomerID = $recibo->estudantes_id;
           
            $Payment->appendChild($dom->createElement('CustomerID', $CustomerID));
            $Line = $dom->createElement('Line');
            $Line->appendChild($dom->createElement('LineNumber', ++$key));
            $SourceDocumentID = $dom->createElement('SourceDocumentID');

            $SourceDocumentID->appendChild($dom->createElement('OriginatingON', $recibo->pagamento->factura_next));
            $SourceDocumentID->appendChild($dom->createElement('InvoiceDate', Carbon::parse($recibo->pagamento->created_at)->format('Y-m-d')));
            $SourceDocumentID->appendChild($dom->createElement('Description', $Description));
            $Line->appendChild($SourceDocumentID);
            $Line->appendChild($dom->createElement('CreditAmount', number_format($recibo->valor2, 2, ".", "")));
            $Payment->appendChild($Line);

            // Preenche SourceDocuments->Payments->Payment->DocumentTotals
            
            $TaxPayable = 0;

            $DocumentTotals = $dom->createElement('DocumentTotals');
            $DocumentTotals->appendChild($dom->createElement('TaxPayable', number_format($TaxPayable, 2, ".", "")));
            $DocumentTotals->appendChild($dom->createElement('NetTotal', number_format($recibo->total_incidencia , 2, ".", "")));
            $DocumentTotals->appendChild($dom->createElement('GrossTotal', number_format($recibo->total_incidencia , 2, ".", "")));
            $Payment->appendChild($DocumentTotals);
        }

        $purchaseInvoices = $dom->createElement('PurchaseInvoices');
        $purchaseInvoices->appendChild($dom->createElement('NumberOfEntries', 0));
        //add PurchaseInvoices em sourceDocuments
        $sourceDocuments->appendChild($purchaseInvoices);
        $root->appendChild($sourceDocuments);
     
        $dom = $dom->saveXML();
        $dom = str_replace("<TransactionID>#</TransactionID>", "", $dom);
        $dom = str_replace("<TaxExemptionReason>#</TaxExemptionReason>", "", $dom);
        $dom = str_replace("<TaxExemptionCode>#</TaxExemptionCode>", "", $dom);
        $dom = str_replace("<Reason>#</Reason>", "", $dom);
        
        $filename = "saft_" . date("d") . '_' . date("m") . '_' . date("Y") . ' _' . $request->data_inicio . ' - ' .$request->data_final;
        // $filename = "saft_" . date("d") . '_' . date("m") . '_' . date("Y") . ;

        return response()->streamDownload(function () use ($dom) {
            echo $dom;
        }, $filename . '.xml');
    
    }

    public function refazer()
    {
        $user = auth()->user();
        
        if(!$user->can('create: factura')){
            Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
            return redirect()->back();
        }
        
        ini_set('memory_limit', '4096M');  // Ajuste para 1024 MB ou outro valor
        
        $ano_lectivo_activo = AnoLectivo::findOrFail($this->anolectivoActivo());
       
        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Refazer o SAFT",
            "anos_lectivos" => AnoLectivo::where([
                ['shcools_id', $this->escolarLogada()]
            ])->get(),
            "descricao" => "Documentos",
            "ano_lectivo_activo" => $ano_lectivo_activo,
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.ficheiros-safts.saft-refazer', $headers);
    }
    
    public function refazer_saft(Request $request)
    {
        ini_set('max_execution_time', 300); // 5 minutos
        ini_set('memory_limit', '2048M');   // 2GB, se necessário

        try {
            DB::beginTransaction();
            
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
            // 
            $rsa = new RSA(); //Algoritimo RSA
            // Lendo a private key
            $rsa->loadKey($privatekey);
            $codigo_designacao_factura = "EAV";
                        
            $ano_lectivo_activo = AnoLectivo::findOrFail($request->ano_lectivo_id);
            
            $pagamentos = Pagamento::where('shcools_id', $this->escolarLogada())
                ->where('ano_lectivos_id', $ano_lectivo_activo->id)
                ->where('caixa_at', 'receita')
                ->where('status', 'Confirmado')
                ->select('id', 'next_factura', 'numero_factura', 'hash', 'total_iva', 'total_incidencia', 'created_at', 'tipo_pagamento', 'ano_lectivos_id')
                ->where('tipo_factura', $request->tipo_documento)
                ->orderBy('id', 'asc')
                ->orderByRaw('TIME(created_at) ASC')
                ->get();
            
            $previousHash = null; // Para armazenar o hash do pagamento anterior
            
            foreach ($pagamentos as $index => $pagamento) {
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $pagamento->created_at);
                
                $n = $index + 1;
             
                if ($n === 1) {
                
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                
                    $total_a_pagar = $pagamento->total_iva +  $pagamento->total_incidencia;
                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->tipo_documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$n}" . ';' . number_format($total_a_pagar, 2, ".", "") . ';' . "";
                   
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)                
    
                    // Para o primeiro registro, o update será normal
                    $pagamento->update([
                        'created_at' => $pagamento->created_at,
                        'numero_factura' => $n,
                        'tipo_pagamento' => $pagamento->tipo_pagamento == "TT" ? "TB" : ($pagamento->tipo_pagamento == "DD" ? "DE" : $pagamento->tipo_pagamento),
                        'factura_ano' => $ano_lectivo_activo->serie,
                        'next_factura' => "{$request->tipo_documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$n}",
                        'hash' => base64_encode($signaturePlaintext),
                        'texto_hash' => $plaintext, // Exemplo de hash para o primeiro registro
                    ]);
            
                    // Armazena o hash do primeiro pagamento
                    $previousHash = $pagamento->hash;
                }
                if($n > 1) {
                                
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                
                    $total_a_pagar = $pagamento->total_iva +  $pagamento->total_incidencia;
                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->tipo_documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$n}" . ';' . number_format($total_a_pagar, 2, ".", "") . ';' . $previousHash;
                                        
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)                
                
                    // Para os registros a partir do segundo, usamos o hash do anterior
                    $pagamento->update([
                        'created_at' => $pagamento->created_at,
                        'numero_factura' => $n,
                        'tipo_pagamento' => $pagamento->tipo_pagamento == "TT" ? "TB" : ($pagamento->tipo_pagamento == "DD" ? "DE" : $pagamento->tipo_pagamento),
                        'factura_ano' => $ano_lectivo_activo->serie,
                        'next_factura' => "{$request->tipo_documento} {$codigo_designacao_factura}{$ano_lectivo_activo->serie}/{$n}",
                        'hash' => base64_encode($signaturePlaintext), // Usa o hash do pagamento anterior
                        'texto_hash' => $plaintext,
                    ]);
            
                    // Armazena o hash atualizado para o próximo pagamento
                    $previousHash = $pagamento->hash;
                }
            }
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        Alert::success('Bom Trabalho', 'Operação realizado com sucesso!');
        return redirect()->back();
              
    }
   
    public function listarTaxas($produtos)
    {
        $taxas = array();
        foreach ($produtos as $key => $produto) {
            $taxa = DB::table('tb_taxas')->where('id', $produto->taxa_id)->first();
            array_push($taxas, $taxa);
        }
        $collection = collect($taxas);
        $array = $collection->unique();
        return $array;
    }
}
