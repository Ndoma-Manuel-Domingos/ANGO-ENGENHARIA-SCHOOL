<?php

namespace App\Http\Controllers;

use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $escola = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->findOrFail($this->escolarLogada());
        
        $head = [
            "escola" => $escola,
            "verAnoLectivoActivo" => AnoLectivo::find($this->anolectivoActivo()),
            "titulo" => "Backup e Restauração do Banco",
            "descricao" => env('APP_NAME'),
        ];

        return view('admin.backups.index', $head);
    }

    public function exportar(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '4096M'); // ou mais se necessário
        
        if($request->banco == "" || $request->banco == null) {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $banco = env('DB_DATABASE');
        }else {
            $filename = $request->banco . '_' . date('Y-m-d_H-i-s') . '.sql';
            $banco = $request->banco;
        }
        
        // $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $filename);

        // Garante que a pasta existe
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            $banco,
            $backupPath
        );

        system($command);

        // Compacta
        $zip = new \ZipArchive();
        $zipPath = storage_path('app/backups/' . str_replace('.sql', '.zip', $filename));
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            $zip->addFile($backupPath, $filename);
            $zip->close();
        }

        // Remove o SQL original
        // unlink($backupPath);

        return response()->download($zipPath)->deleteFileAfterSend(true);

    }


    public function importar(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        if (!$request->hasFile('arquivo')) {
            return response()->json(['message' => 'Nenhum arquivo enviado'], 400);
        }

        $zipPath = $request->file('arquivo')->store('temp');
        $zipFullPath = storage_path('app/' . $zipPath);

        // Extrair
        $zip = new \ZipArchive();
        if ($zip->open($zipFullPath) === true) {
            $zip->extractTo(storage_path('app/temp'));
            $zip->close();
        }

        // Pega o primeiro .sql extraído
        $files = glob(storage_path('app/temp/*.sql'));
        if (count($files) === 0) {
            return response()->json(['message' => 'Nenhum arquivo SQL encontrado'], 400);
        }
        $sqlFile = $files[0];

        // Importar
        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $sqlFile
        );
        system($command);

        // Limpa
        unlink($zipFullPath);
        unlink($sqlFile);

        return response()->json(['success' => true]);
    }
    
    public function store(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        $nomeBanco = preg_replace('/[^a-zA-Z0-9_]/', '', $request->nome_banco); // evitar SQL Injection

        DB::statement("CREATE DATABASE IF NOT EXISTS `$nomeBanco` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
        return response()->json(['success' => true, 'message' => 'Banco criado com sucesso!']);
                
    }
    
    public function listarBancos()
    {
        $bancos = DB::select("SHOW DATABASES");
    
        // Filtrar para evitar bancos do sistema
        $bancos = array_filter($bancos, function($banco) {
            return !in_array($banco->Database, [
                'information_schema', 'mysql', 'performance_schema', 'sys', 'myapp2'
            ]);
        });
    
        return response()->json(array_values($bancos));
    }
    
    public function deleteBancos(Request $request)
    {
        $banco = $request->banco;
        DB::statement("DROP DATABASE `$banco`");
        return response()->json(['success' => true]);
    }  
}
