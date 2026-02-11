<?php

namespace App\Actions\Documents;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExtractStructuredData
{
    public function handle(string $prompt): array
    {
        $apiKey = config('services.gemini.key');
        
        if (!$apiKey) {
            // Fallback a método local si no hay API key
            return $this->extractWithRegex($prompt);
        }

        $response = Http::timeout(120)
            ->withOptions(['verify' => false])
            ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'maxOutputTokens' => 2000,
                    'responseMimeType' => 'application/json', // Solicitar JSON directamente
                ]
            ]);

        if ($response->successful()) {
            $content = $response->json('candidates.0.content.parts.0.text');
            
            if (empty($content)) {
                return $this->extractWithRegex($prompt);
            }
            
            $data = $this->extractJson($content);
            
            // Si no se extrajo JSON válido, intentar con regex
            if (empty($data) || isset($data['error'])) {
                return $this->extractWithRegex($prompt);
            }
            
            return $data;
        } else {
            Log::warning("Error en Gemini API, usando regex: " . $response->status());
            return $this->extractWithRegex($prompt);
        }
    }

    protected function extractJson(string $content): array
    {
        // Buscar JSON en la respuesta
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $content, $matches)) {
            $jsonString = $matches[0];
            $data = json_decode($jsonString, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }
        
        return ['error' => 'No se encontró JSON válido'];
    }

    // Método de respaldo con REGEX para extraer datos
    protected function extractWithRegex(string $prompt): array
    {
        // Extraer el texto del prompt (lo que viene después de "TEXTO:")
        $text = $this->extractTextFromPrompt($prompt);
        
        if (empty($text)) {
            return [];
        }

        $data = [];

        // 1. Extraer NOMBRE (busca patrones de nombre)
        if (preg_match('/(?:nombre|cliente|señor|señora|sr|sra|srt?a\.?)\s*[:.]?\s*([A-ZÁÉÍÓÚÑ][a-záéíóúñ]+\s+[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(?:\s+[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*)/ui', $text, $matches)) {
            $data['nombre'] = trim($matches[1]);
        } elseif (preg_match('/^([A-ZÁÉÍÓÚÑ][a-záéíóúñ]+\s+[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)/u', $text, $matches)) {
            $data['nombre'] = trim($matches[1]);
        }

        // 2. Extraer DOCUMENTO (DNI, CUIT, etc.)
        if (preg_match('/(?:dni|documento|identificación|cuit|cuil)\s*[:.]?\s*([0-9]{7,12})/i', $text, $matches)) {
            $data['documento'] = $matches[1];
        } elseif (preg_match('/\b([0-9]{2}[-.]?[0-9]{6,8}[-.]?[0-9]?)\b/', $text, $matches)) {
            $data['documento'] = preg_replace('/[^0-9]/', '', $matches[1]);
        }

        // 3. Extraer FECHA (formato dd/mm/yyyy o dd-mm-yyyy)
        if (preg_match('/(?:fecha|emisión)\s*[:.]?\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i', $text, $matches)) {
            $data['fecha'] = $matches[1];
        } elseif (preg_match('/\b(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})\b/', $text, $matches)) {
            $data['fecha'] = $matches[1];
        }

        // 4. Extraer TOTAL (montos con $, € o números con decimales)
        if (preg_match('/(?:total|importe|monto|suma)\s*[:.]?\s*[$€]?\s*([0-9]+[.,][0-9]{2})/i', $text, $matches)) {
            $data['total'] = str_replace(',', '.', $matches[1]);
        } elseif (preg_match('/[$€]\s*([0-9]+[.,][0-9]{2})/i', $text, $matches)) {
            $data['total'] = str_replace(',', '.', $matches[1]);
        } elseif (preg_match('/\b([0-9]+[.,][0-9]{2})\b/', $text, $matches)) {
            $data['total'] = str_replace(',', '.', $matches[1]);
        }

        return $data;
    }

    protected function extractTextFromPrompt(string $prompt): string
    {
        // Extrae el texto después de "TEXTO:" en el prompt
        if (preg_match('/TEXTO:\s*(.*)/s', $prompt, $matches)) {
            return trim($matches[1]);
        }
        return $prompt;
    }
}