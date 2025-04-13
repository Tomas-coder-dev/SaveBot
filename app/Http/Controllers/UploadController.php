<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendTelegramFileJob;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    protected $telegramToken;
    protected $chatId;

    public function __construct()
    {
        // Se leen los valores desde el archivo .env
        $this->telegramToken = env('TELEGRAM_BOT_TOKEN');
        $this->chatId = env('TELEGRAM_CHAT_ID');
    }

    /**
     * Muestra el formulario para subir archivos.
     */
    public function showForm()
    {
        return view('upload');
    }

    /**
     * Procesa la subida de múltiples archivos y despacha un Job para cada uno.
     * La respuesta se retorna en JSON para el manejo vía AJAX.
     */
    public function uploadFile(Request $request)
    {
        // Validar que cada archivo sea de tipo imagen o video y menor a 10 MB.
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,jpg,png,mp4,mov,avi|max:10240'
        ]);

        $files = $request->file('files');
        $errors = [];
        $successCount = 0;

        foreach ($files as $file) {
            // Guardar el archivo en el disco 'public'
            $path = $file->store('uploads', 'public');
            $originalName = $file->getClientOriginalName();

            try {
                // Despachar el Job pasando la ruta y el nombre original del archivo
                SendTelegramFileJob::dispatch($path, $originalName, $this->telegramToken, $this->chatId);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = 'Error con ' . $originalName . ': ' . $e->getMessage();
            }
            // Opcional: borrar el archivo temporal si ya no se requiere
            // Storage::disk('public')->delete($path);
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => "$successCount files processed.",
                'errors' => $errors
            ], 500);
        }

        return response()->json([
            'message' => "$successCount archivos cargados y enviados  con éxito."
        ], 200);
    }
}
