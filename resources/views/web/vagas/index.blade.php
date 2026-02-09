@include('web.headers')
	<!-- Carossel BANNER -->
<style>
    a{
      text-decoration: none;
    }

    .waves {
        position: relative;
        width: 100%;
        height: 10vh;
        min-height: 80px;
        max-height: 150px;
        margin-bottom: -7px;
    }
    .parallax>use {
        -webkit-animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
        animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
    }
    .parallax>use:first-child {
        -webkit-animation-delay: -2s;
        animation-delay: -2s;
        -webkit-animation-duration: 7s;
        animation-duration: 7s;
    }
    .parallax>use:nth-child(2) {
        -webkit-animation-delay: -3s;
        animation-delay: -3s;
        -webkit-animation-duration: 10s;
        animation-duration: 10s;
    }
    .parallax>use:nth-child(3) {
        -webkit-animation-delay: -4s;
        animation-delay: -4s;
        -webkit-animation-duration: 13s;
        animation-duration: 13s;
    }
    .parallax>use:nth-child(4) {
        -webkit-animation-delay: -5s;
        animation-delay: -5s;
        -webkit-animation-duration: 20s;
        animation-duration: 20s;
    }
    @keyframes move-forever {
      0% {
          -webkit-transform: translate3d(-90px, 0, 0);
          -ms-transform: translate3d(-90px, 0, 0);
          transform: translate3d(-90px, 0, 0);
      }
      100% {
          -webkit-transform: translate3d(85px, 0, 0);
          -ms-transform: translate3d(85px, 0, 0);
          transform: translate3d(85px, 0, 0);
      }
    }
</style>

<main>

	<section class="container" style="margin-top: 140px">
		<div class="row">
			<div class="col-12 text-center">
				<div data-aos="fade-up">
					<h3 class="title">Solicitações de Vagas</h3>
					<p class="text mb-3 mt-3" style="font-size: 15pt">É um sistema que ajuda as escolas a terem as informações contabilísticas, pedagógicos e administrativas em um só lugar, e acessar as mesmas de uma forma simplificada, a fim de obter dados para melhores tomadas de decisão.</p>
				</div>
			</div>
		</div>

        <div class="row mb-5">

            <div class="col-md-8 offset-md-2 mt-3 mb-5">
              @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
              @endif
            </div>

            <div class="col-md-8 offset-md-2">
              <div class="card card-primary">
                <div class="card-header px-3">
                    <form action="{{ route('web.vagas') }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group mb-3 col-md-12 col-12 my-4">
                            <label for="escola_ids py-2">Selecione a Escola</label>
                            <select name="escola_ids" id="escola_ids" class="form-control select2 mt-2">
                                <option value="">Selecione Escola</option>
                                @if ($escolas)
                                    @foreach ($escolas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                @else
                                    <option value="">Sem Nenhum Curso cadastrado</option>
                                @endif
                            </select>
                            <span class="text-danger error-text"></span>
                        </div>
                    </div>
                    </form>
                </div>
              </div>
              <!-- /.card -->
            </div>


            <div class="col-md-8 offset-md-2 mt-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Área de Formação</th>
                            <th>Total Vagas</th>
                            <th>Acções</th>
                        </tr>
                    </thead>
                    <tbody class="table_cursos"></tbody>
                </table>
            </div>

          </div>
	</section>

</main>

@include('web.footers')


<script>
    $(function(){
  
      $("#escola_ids").change(()=>{
        let country_id = $("#escola_ids").val();
        $.get('get_dados_escolas/'+country_id, function(data){
            $('.table_cursos').html("")
            for (let index = 0; index < data.cursos.length; index++) {

                if(data.cursos[index].total_vagas > 0 || data.cursos[index].total_vagas == 0){
                    var status = "d-none";
                }else{
                    var status = 'd-block';
                }

                $('.table_cursos').append('<tr><td>'+ data.cursos[index].curso.curso +'</td><td>'+ data.cursos[index].curso.area_formacao +'</td><td>'+ data.cursos[index].total_vagas +'</td><td> <a href="" class="btn btn-primary '+status+'">Solicitar</a> </td></tr>');
            }
        })
        document.getElementById("cursos_id").disabled = false;
      })
    
    }); 
</script>
  