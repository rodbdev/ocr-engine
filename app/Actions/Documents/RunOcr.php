<?php

namespace App\Actions\Documents;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunOcr
{
    public function handle(array $images): string
    {
        $fullText = '';
        $apiKey = config('services.ocrspace.key');
        
        if (!$apiKey) {
            throw new \Exception("API key de OCR.space no configurada. Añade OCRSPACE_KEY en .env");
        }

        foreach ($images as $index => $imagePath) {
            try {
                // Verificar que el archivo existe
                if (!file_exists($imagePath)) {
                    Log::warning("Imagen no encontrada: " . $imagePath);
                    continue;
                }

                // Para debugging: tamaño de la imagen
                $fileSize = filesize($imagePath);
                Log::info("Procesando imagen {$index}: {$imagePath} ({$fileSize} bytes)");

                // OCR.space API - extracción simple de texto
                $response = Http::timeout(120)
                    ->withOptions([
                        'verify' => false,
                        'connect_timeout' => 30,
                    ])
                    ->attach('file', file_get_contents($imagePath), basename($imagePath))
                    ->post('https://api.ocr.space/parse/image', [
                        'apikey' => $apiKey,
                        'language' => 'spa', // Español
                        'isOverlayRequired' => false, // Solo queremos texto
                        'filetype' => 'PNG',
                        'OCREngine' => 2, // Engine 2 es más preciso
                        'scale' => true,
                        'detectOrientation' => true,
                        'isTable' => false,
                        'isCreateSearchablePdf' => false,
                        'isSearchablePdfHideTextLayer' => false,
                    ]);

                Log::info("Respuesta de OCR.space: " . $response->status());

                if ($response->successful()) {
                    $result = $response->json();
                    
                    // Verificar estructura de respuesta
                    if (isset($result['ParsedResults'][0]['ParsedText'])) {
                        $pageText = $result['ParsedResults'][0]['ParsedText'];
                        $fullText .= "\n--- Página " . ($index + 1) . " ---\n" . $pageText;
                        
                        Log::info("Texto extraído de página {$index}: " . strlen($pageText) . " caracteres");
                    } else {
                        Log::warning("Estructura inesperada en respuesta OCR.space: " . json_encode($result));
                        $fullText .= "\n[No se pudo extraer texto de esta página]";
                    }
                } else {
                    $errorBody = $response->body();
                    Log::error("Error en OCR.space API - Status: " . $response->status() . " - Body: " . $errorBody);
                    
                    // Intentar parsear el error
                    $errorData = json_decode($errorBody, true);
                    if (isset($errorData['ErrorMessage'])) {
                        throw new \Exception("Error OCR.space: " . $errorData['ErrorMessage']);
                    }
                    
                    $fullText .= "\n[Error en OCR para esta página]";
                }
                
            } catch (\Exception $e) {
                Log::error("Error procesando imagen {$imagePath}: " . $e->getMessage());
                $fullText .= "\n[Error procesando página: " . $e->getMessage() . "]";
                throw $e; // Relanzar para manejo en el Job
            }
        }

        $finalText = trim($fullText);
        Log::info("Total de texto extraído: " . strlen($finalText) . " caracteres");
        
        return $finalText;
    }
}