<table style="border: 1px solid #000">
    <thead>
        <tr>
            <th style="text-align: center;text-transform: uppercase;" colspan="30">REPÚBLICA DE ANGOLA</th>
        </tr>
        <tr>
            <th style="text-align: center;text-transform: uppercase;" colspan="30">GOVERNO PROVINCIAL DE LUANDA</th>
        </tr>
        <tr>
            <th style="text-align: center;text-transform: uppercase;font-size: 15pt" colspan="30">{{ $escola->nome }}</th>
        </tr>

        <tr>
            @if ( isset($classe) && $classe->tipo == 'Transição')
                <th style="color: red;text-align: center;font-size: 15pt" colspan="30">MINI PAUTA PARA CLASSE DE TRANSIÇÃO</th>
            @else
                @if (isset($classe) && $classe->tipo == 'Exame')
                    <th style="color: red;text-align: center;font-size: 15pt" colspan="30">MINI PAUTA PARA CLASSE DE EXAMES</th>
                @else
                    <th style="color: red;text-align: center;font-size: 15pt" colspan="30">MINI PAUTA</th>
                @endif
            @endif
        </tr>
    </thead>
</table>

@if ( isset($classe) && $classe->tipo == 'Transição')
    @include('admin.require.classe-transicao')
@else
    @if (isset($classe) && $classe->tipo == 'Exame')
        @include('admin.require.classe-exames')
    @endif
@endif

<table style="border: 1px solid #000">
    <tbody>
        <tr>
            <td colspan="30"></td>
        </tr>
        <tr>
            <td colspan="15" style="text-align: center;font-size: 12pt">O (A) PROFESSOR DA CLASSE</td>
        </tr>
        <tr>
            <td colspan="15" style="text-align: center;font-size: 12pt">____________________________________</td>
        </tr>
        <tr>
            <td colspan="15" style="text-align: center;font-size: 12pt">O SUBDIRECTOR PEDAGOGICO</td>
        </tr>
        <tr>
            <td colspan="15" style="text-align: center;font-size: 12pt">____________________________________</td>
        </tr>
    </tbody>
</table>
