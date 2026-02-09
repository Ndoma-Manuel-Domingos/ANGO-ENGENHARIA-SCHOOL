@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Abertura do Caixa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Abertura Caixas</li>
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
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i>Deve efetuar a abertura do caixa para processar a faturação</h5>
            </div>
        </div>
    </div>

      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <!-- form start -->
            <form action="{{ route('operacoes-caixas.abertura-caixas') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="valor_inicial">Valor Inicial</label>
                  <input type="number" class="form-control valor_inicial @error('valor_inicial') is-invalid @enderror" id="valor_inicial" name="valor_inicial" placeholder="Introduz um Valor Inicial">
                </div>
                <div class="form-group">
                  <label>Selecionar o Caixa</label>
                  <select class="form-control caixa_id @error('valor_inicial') is-invalid @enderror" name="caixa_id">
                  @if ($caixas)
                    @foreach ($caixas as $item)
                      <option value="{{ $item->id }}">{{ $item->conta }} {{ $item->caixa }}</option>
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                @if (Auth::user()->can('abertura caixa'))
                <button type="submit" class="btn btn-primary">Abrir o Caixa</button>
                @endif
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário
     
            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                  Swal.close();
                  // Exibe uma mensagem de sucesso
                  showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                  window.location.href = response.redirect;
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });
    });
</script>
@endsection
