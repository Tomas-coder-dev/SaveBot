<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendTelegramFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;      // Ruta dentro del disco 'public'
    protected $originalName;  // Nombre original del archivo
    protected $telegramToken;
    protected $chatId;

    /**
     * Crea una nueva instancia del Job.
     *
     * @param string $filePath
     * @param string $originalName
     * @param string $telegramToken
     * @param mixed $chatId
     */
    public function __construct($filePath, $originalName, $telegramToken, $chatId)
    {
        $this->filePath = $filePath;
        $this->originalName = $originalName;
        $this->telegramToken = $telegramToken;
        $this->chatId = $chatId;
    }

    /**
     * Ejecuta el Job: envia el archivo a Telegram según su tipo.
     */
    public function handle()
    {
        // Obtener la ruta completa del archivo almacenado en el disco 'public'
        $fullPath = Storage::disk('public')->path($this->filePath);
        // Obtener el MIME type utilizando PHP
        $mimeType = mime_content_type($fullPath);

        $endpoint = 'sendPhoto';
        if (str_starts_with($mimeType, 'video/')) {
            $endpoint = 'sendVideo';
        } elseif (!str_starts_with($mimeType, 'image/')) {
            Log::error("Tipo de archivo no soportado", ['mime' => $mimeType]);
            return;
        }

        $url = "https://api.telegram.org/bot{$this->telegramToken}/";

        $response = Http::attach(
            ($endpoint == 'sendPhoto') ? 'photo' : 'video',
            fopen($fullPath, 'r'),
            $this->originalName
        )->post($url . $endpoint, [
            'chat_id' => $this->chatId,
            'caption' => 'Archivo subido desde Laravel (Job)'
        ]);

        if ($response->failed()) {
            Log::error("Error al enviar archivo a Telegram en el Job", $response->json());
        }
    }
}
