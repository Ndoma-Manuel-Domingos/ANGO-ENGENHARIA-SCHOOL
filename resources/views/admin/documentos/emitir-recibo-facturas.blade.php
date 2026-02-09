@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Emitir Recibo Factura <span class="text-danger">{{ $pagamento->next_factura }}</span> </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.documento') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Facturas</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                  <h5>Detalhes da Factura</h5>
                  <hr>
                  <form class="row">
                    <div class="form-group col-12 col-md-3">
                      <label for="" class="form-label">Tipo Factura</label>
                      <input type="text" class="form-control" value="{{ $pagamento->next_factura }}" disabled>
                    </div>
                  </form>
                </div>
                <div class="card-body">
                    <form action="{{ route('web.emitir-recibo-facturas-create') }}" method="GET" class="row" id="formulario">
                        @csrf
                        <div class="form-group col-12 col-md-3">
                          <label for="" class="form-label">Selecione o Tipo de Factura</label>
                          <select type="text" class="form-control" name="tipo_factura">
                              <option value="RG">RECIBO</option>
                          </select>
                        </div>
                        
                        <div class="form-group col-12 col-md-3">
                          <label for="forma_pagamento">Forma de Pagamento</label>
                          <select name="forma_pagamento" id="forma_pagamento" class="form-control forma_pagamento">
                            @foreach ($formas_pagamento as $item)
                              <option value="{{ $item->sigla_tipo_pagamento }}">{{ $item->descricao }}</option>
                            @endforeach
                          </select>
                        </div>
                   
                        <div class="col-12 col-md-3">
                          <label for="preco_factura" class="form-label">Preço da Factura</label>
                          <input type="text" class="form-control" id="preco_factura" name="preco_factura" value="{{ $pagamento->total_incidencia + $pagamento->total_iva }}" disabled>
                        </div> 
                        
                        <div class="col-12 col-md-3">
                          <label for="" class="form-label">Total Multa</label>
                          <input type="text" class="form-control" name="multa_factura" value="{{ $pagamento->multa }}" disabled>
                        </div>
                        
                        <div class="col-12 col-md-3">
                          <label for="" class="form-label">Total A Pagar</label>
                          <input type="text" class="form-control" name="total_a_pagar" value="{{ $pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa }}" disabled>
                          <input type="hidden" class="form-control valor_total_a_pagar" name="valor_guardado" id="valor_guardado" value="{{ $pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa }}">
                        </div>
                        
                        <input type="hidden" name="ficha_factura" value="{{ $pagamento->ficha }}">
                        <input type="hidden" class="form-control" name="preco_factura" value="{{ $pagamento->total_incidencia + $pagamento->total_iva }}">
                        <input type="hidden" class="form-control" name="multa_factura" value="{{ $pagamento->multa }}">
                        <input type="hidden" class="form-control" name="total_a_pagar" value="{{ $pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa }}">
                        
                        <div class="col-12 col-md-3" id="valor_entregue_id">
                          <label for="" class="form-label">Valor Entregue</label>
                          <input type="text" class="form-control valor_entregue" id="valor_entregue" name="valor_entregue" >
                        </div>
                             
                        <div class="form-group col-12 col-md-3" id="valor_entregue_multicaixa_id">
                          <label for="valor_entregue_multicaixa">Digite o Valor Entregue por Multicaixa(TPA)</label>
                          <input type="text" name="valor_entregue_multicaixa" id="valor_entregue_multicaixa" class="form-control valor_entregue_multicaixa" value="0" onchange="calcularTroco()" placeholder="Digite o Valor que o Estudante te Entregou">
                          <span class="text-danger error-text valor_entregue_multicaixa_error"></span>
                        </div>
                        
                        <div class="form-group col-12 col-md-3" id="form_campo_caixas">
                          <label for="caixa_id">Caixas</label>
                          <select name="caixa_id" id="caixa_id" class="form-control caixa_id">
                            @foreach ($caixas as $item)
                            <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->caixa }}</option>
                            @endforeach
                          </select>
                          <span class="text-danger error-text caixa_id_error"></span>
                        </div>
  
                        <div class="form-group col-12 col-md-3" id="form_campo_bancos" style="display: none;">
                          <label for="banco_id">Bancos</label>
                          <select name="banco_id" id="banco_id" class="form-control banco_id">
                            @foreach ($bancos as $item)
                            <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->banco }}</option>
                            @endforeach
                          </select>
                          <span class="text-danger error-text banco_id_error"></span>
                        </div>
                        
                        <div class="col-12 col-md-3" id="numero_borderon">
                          <label for="numero_borderon" class="form-label">Número do Borderon</label>
                          <input type="text" class="form-control" id="numero_borderon" name="numero_borderon" >
                        </div>
                    </form>    
                </div>

                <div class="card-footer">
                  <p class=" float-left" style="font-size: 15pt"><span class="text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span></p>
                  <button form="formulario" class="btn btn-primary float-right" type="submit">
                    Emitir Factura
                  </button>
                </div>
            </div>
        </div>
      </div>

    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection


@section('scripts')
  <script>
    $(function () {
      $('#valor_entregue_multicaixa_id').css("display", "none");
      $('#numero_borderon').css("display", "none");
      // selecionar tipo pagamento
      $(document).on('change', '.forma_pagamento', function(e) {
        e.preventDefault();
        let id = $(this).val();
        
        if(id == "OU"){
          $('#valor_entregue_multicaixa_id').css("display", "block");
          $('#numero_borderon').css("display", "block");
           
          $('#form_campo_caixas').css("display", "block");
          $('#form_campo_bancos').css("display", "block");
          
        }else if(id == "MB"){
          $('#valor_entregue_multicaixa_id').css("display", "block");
          $('#valor_entregue_id').css("display", "none");
          $('#numero_borderon').css("display", "none");
          
          $('#form_campo_caixas').css("display", "none");
          $('#form_campo_bancos').css("display", "block");

          
        }else if(id == "NU"){
          $('#valor_entregue_multicaixa_id').css("display", "none");
          $('#valor_entregue_id').css("display", "block");
          $('#numero_borderon').css("display", "none");
          
          
          $('#form_campo_caixas').css("display", "block");
          $('#form_campo_bancos').css("display", "none");
          
        }else{
        
          $('#valor_entregue_id').css("display", "block");
          $('.valor_entregue_multicaixa').val(0);
          $('#valor_entregue_multicaixa_id').css("display", "none");
          $('#numero_borderon').css("display", "block");
          
          $('#form_campo_caixas').css("display", "none");
          $('#form_campo_bancos').css("display", "block");
        }
      });
      
      $('.valor_entregue').on('input', function(e) {
        e.preventDefault();
        
        if ($(this).val() > 0) {
          // valor total a pagar
          var valor_total = parseFloat($('.valor_total_a_pagar').val()) || 0;
          var valor_entregue = parseFloat($(this).val()) || 0;
          var valor_entregue_multicaixa = parseFloat($('.valor_entregue_multicaixa').val()) || 0;
          var valor_descontar_saldo = parseFloat($('.saldo_a_descontar_do_estudante_id').val()) || 0;

          // Calcular o troco
          var troco = (valor_entregue + valor_entregue_multicaixa + valor_descontar_saldo) - valor_total;

          var f2 = troco.toLocaleString('pt-br', {
              minimumFractionDigits: 2
          });

          $("#valor_troco_apresenta").html("")
          $("#valor_troco_apresenta").append(f2)

        } else {
            console.log("false")
        }
      })

      $('.valor_entregue_multicaixa').on('input', function(e) {
        e.preventDefault();
        if ($(this).val() > 0) {
          // valor total a pagar
          var valor_total = parseFloat($('.valor_total_a_pagar').val()) || 0;
          var valor_entregue_multicaixa = parseFloat($(this).val()) || 0;
          var valor_entregue = parseFloat($('.valor_entregue').val()) || 0;
          var valor_descontar_saldo = parseFloat($('.saldo_a_descontar_do_estudante_id').val()) || 0;

          // Calcular o troco
          var troco = (valor_entregue + valor_entregue_multicaixa + valor_descontar_saldo) - valor_total;

          // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
          var f2 = troco.toLocaleString('pt-br', {
            minimumFractionDigits: 2
          });

          $("#valor_troco_apresenta").html("")
          $("#valor_troco_apresenta").append(f2)

        } else {
          console.log("false")
        }
      })
    });
  </script>
@endsection