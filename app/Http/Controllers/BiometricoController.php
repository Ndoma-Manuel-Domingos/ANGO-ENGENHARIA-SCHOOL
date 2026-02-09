<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Rats\Zkteco\Lib\ZKTeco;
use RealRashid\SweetAlert\Facades\Alert;

class BiometricoController extends Controller
{
 
    use TraitHelpers;
    use TraitChavesSaft;
    #Ndoma
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // if(!$user->can('create: factura')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        

        $headers = [ 
            "escola" => Shcool::with('ensino')->findOrFail($this->escolarLogada()),
            
            "titulo" => "Gerar Assiuidades",
            "descricao" => env('APP_NAME'),
            "usuario" => User::findOrFail(Auth::user()->id),
        ];

        return view('admin.recurso-humano.biometrico.index', $headers);
    }
    

    public function store(Request $request)
    {
        // $user = auth()->user();
        
        // if(!$user->can('create: factura')){
        //     Alert::error('Acesso restrito', 'Você não possui permissão para esta operação, por favor, contacte o administrador!');
        //     return redirect()->back();
        // }
        
        set_time_limit(0);
        ini_set('memory_limit', '4096M');
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $zk = new ZKTeco('10.10.50.16');
        
        if ($zk->connect()) {
            
            $users = $zk->getUser(); 
            $attendances = $zk->getAttendance();

            // // Filtrar os registros pelo intervalo de datas
            // $filteredAttendances = collect($attendances)->filter(function ($attendance) use ($startDate, $endDate) {
            //     $timestamp = date('Y-m-d', strtotime($attendance['timestamp']));
            //     return $timestamp >= $startDate && $timestamp <= $endDate;
            // });

            $zk->disconnect();
            
            dd($attendances);
            
        } else {
            Alert::error('Alerta', 'Falha na conexão com o dispositivo.!');
            return redirect()->back();
        }

    }
    
      
}
