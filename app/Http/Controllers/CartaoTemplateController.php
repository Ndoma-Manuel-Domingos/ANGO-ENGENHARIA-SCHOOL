<?php

namespace App\Http\Controllers;

use App\Models\CartaoTemplate;
use App\Models\User;
use App\Models\Shcool;
use App\Models\web\estudantes\Estudante;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // usando simple-qrcode
use Illuminate\Support\Facades\File;

class CartaoTemplateController extends Controller
{
    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    // Listar bancos
    public function index()
    {
        $escola = Shcool::with(['pais', 'provincia', 'municipio', 'ensino'])->findOrFail($this->escolarLogada());
          
        $cartao = CartaoTemplate::where('shcools_id', $escola->id)->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'shcools_id' => $escola->id,
            'user_id' => Auth::user()->id,
        ]);
        
        // Caminho da imagem
        $logotipoPath = public_path("uploads/logos/{$escola->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            "titulo" => "Configuração de Cartões",
            "descricao" => env('APP_NAME'),
            "template" => $cartao,
            "escola" => $escola,
            "logotipo" => $temLogotipo ? $logotipoPath : null,
        ];

        return view('admin.informacoes-escolares.cartoes.index', $head);
    }
    
    // rota para gerar QR code do funcionário
    public function create(Estudante $estudante)
    {
        $payload = route('employee.scan', ['id' => $estudante->id]); // ou qualquer payload
        // Opcional: salva no campo qr_code
        $estudante->qr_code = $payload;
        $estudante->update();

        $svg = QrCode::format('svg')->size(200)->generate($payload);
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }


    // salvar template
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'nullable|string',
            'width'=>'required|integer',
            'height'=>'required|integer',
            'height_logo'=>'required|integer',
            'line_height'=>'required|string',
            'opacity'=>'required|string',
            'filter'=>'required|string',
            'orientation'=>'required|in:horizontal,vertical',
            'rotacao_fundo'=>'required|integer',
            'border_radius'=>'required|integer',
            'border_top_space'=>'required|integer',
            'border_top_color'=>'required|string',
            'border_bottom_space'=>'required|integer',
            'border_bottom_color'=>'required|string',
            'border_logo'=>'required|integer',
            'border_logo_color'=>'required|string',
            'border_logo_radius'=>'required|integer',
            'font_family'=>'nullable|string',
            'font_size_title'=>'nullable|string',
            'font_size_subtitle'=>'nullable|string',
            'font_size'=>'nullable|string',
            'text_color'=>'nullable|string',
            'date_validade'=>'nullable|string',
            'background_color'=>'nullable|string',
            'background_color_segunda'=>'nullable|string',
            'background_color_terceira'=>'nullable|string',
            'photo_position'=>'nullable|in:left,right,top,bottom',
            'logo_position'=>'nullable|in:left,right,top,bottom',
        ]);
       
        if ($request->hasFile('background_image') && $request->file('background_image')->isValid()) {
         
            $image = $request->file('background_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images'), $imageName);
        } else {
            $imageName = null;
        }
        
        try {
            DB::beginTransaction();

            $template = CartaoTemplate::first() ?? new CartaoTemplate();
            $template->fill($data);
            $template->background_image = $imageName ?? $template->background_image;
            $template->save();
        
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        Alert::success('Bom Trabalho', 'Configuração salva com sucesso!');
        return redirect()->back();

    }
    
    
    // Listar bancos
    public function show($id)
    {
        $estudante = Estudante::with(['contrato.cargo', 'estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
    
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $cartao = CartaoTemplate::where('entidade_id', $entidade->empresa->id)->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'entidade_id' => $entidade->empresa->id,
            'user_id' => Auth::user()->id,
        ]);
        
        $head = [
            "titulo" => "Configuração do cartão do Estudante",
            "descricao" => env('APP_NAME'),
            "template" => $cartao,
            "estudante" => $estudante,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.informacoes-escolares.cartoes.index', $head);
    }

    
}
