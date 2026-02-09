@extends('layouts.site')

@section('content')

<div class="container content mt-5">
    
    <h2 class="text-left">⚠️ Atenção!</h2>
    <h5>Antes de submeter o seu comprovativo de pagamento, certifique-se de que:</h5>
    
    <ul>
        <li>O pagamento foi realizado para o número de conta ou referência correta indicada pela instituição;</li>
        <li>O comprovativo está legível, completo e em formato PDF, JPEG ou PNG;</li>
        <li>O valor pago corresponde exatamente ao indicado no processo de candidatura ou matrícula;</li>
        <li>O nome do estudante está claramente visível no comprovativo.</li>
    </ul>
    
    <h4>📌 Comprovativos inválidos, ilegíveis ou com informações incorretas poderão resultar na rejeição do processo.</h4>
    
    
    <form action="{{ route('enviar-comprovativo') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-5">
            <div class="form-group col-12 col-md-6 mb-3">
                <label for="referencia" class="form-label">Referência da Factura</label>
                <input type="text" name="referencia" value="{{ old('referencia') }}" placeholder="informe a referência da factura" class="form-control @error('referencia') is-invalid @enderror" >
                @error('referencia')
                    <span class=" text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-12 col-md-6 mb-3">
                <label for="comprovativo" class="form-label">Carregar o compravativo</label>
                <input type="file" name="comprovativo" value="{{ old('comprovativo') }}" class="form-control @error('comprovativo') is-invalid @enderror" >
                @error('comprovativo')
                    <span class=" text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-4">Enviar Comprovativo</button>
    </form>
    
</div>

@endsection


@section('scripts')
<script>
</script>
@endsection
