<?php

namespace App\Actions\Documents;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunOcr
{
    public function handle(array $images): string
    {
        $fullText = '';
        $apiKey = config('services.ocrspace.key') ?: env('OCRSPACE_KEY');
        
        if (!$apiKey) {
            throw new \Exception("API key de OCR.space no configurada. Añade OCRSPACE_KEY en .env");
        }

        foreach ($images as $imagePath) {
            try {
                if (!file_exists($imagePath)) {
                    Log::warning("Imagen no encontrada: " . $imagePath);
                    continue;
                }

                // Parámetros mínimos para evitar errores
                $response = Http::timeout(120)
                    ->withOptions([
                        'verify' => false,
                        'connect_timeout' => 30,
                    ])
                    ->attach('file', file_get_contents($imagePath), basename($imagePath))
                    ->post('https://api.ocr.space/parse/image', [
                        'apikey' => $apiKey,
                        'language' => 'spa',
                    ]);

                if ($response->successful()) {
                    $result = $response->json();
                    
                    if (isset($result['ParsedResults'][0]['ParsedText'])) {
                        $fullText .= "\n" . $result['ParsedResults'][0]['ParsedText'];
                    } else {
                        Log::warning("No se pudo extraer texto de la imagen: " . $imagePath);
                        $fullText .= "\n[Error en OCR]";
                    }
                } else {
                    Log::error("Error en OCR.space API: " . $response->body());
                    $fullText .= "\n[Error en OCR]";
                }
                
            } catch (\Exception $e) {
                Log::error("Error procesando imagen {$imagePath}: " . $e->getMessage());
                $fullText .= "\n[Error procesando página]";
            }
        }

        return trim($fullText);
    }
}