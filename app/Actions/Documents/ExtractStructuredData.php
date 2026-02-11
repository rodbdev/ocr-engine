<?php

namespace App\Actions\Documents;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExtractStructuredData
{
    public function handle(string $prompt): array
    {
        // Solo usar REGEX - opción 100% gratis
        return $this->extractWithRegex($prompt);
    }

    protected function extractWithRegex(string $prompt): array
    {
        // Extraer el texto del prompt
        $text = $this->extractTextFromPrompt($prompt);
        
        if (empty($text)) {
            return [];
        }

        $data = [];

        // 1. Extraer NOMBRE
        if (preg_match('/(?:nombre|cliente)[:\s]+([A-Z][a-z]+\s+[A-Z][a-z]+)/i', $text, $matches)) {
            $data['nombre'] = trim($matches[1]);
        }

        // 2. Extraer DOCUMENTO
        if (preg_match('/(?:dni|documento)[:\s]+(\d{7,9})/i', $text, $matches)) {
            $data['documento'] = $matches[1];
        } elseif (preg_match('/\b(\d{7,9})\b/', $text, $matches)) {
            $data['documento'] = $matches[1];
        }

        // 3. Extraer FECHA
        if (preg_match('/(?:fecha)[:\s]+(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i', $text, $matches)) {
            $data['fecha'] = $matches[1];
        } elseif (preg_match('/\b(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})\b/', $text, $matches)) {
            $data['fecha'] = $matches[1];
        }

        // 4. Extraer TOTAL
        if (preg_match('/(?:total|importe)[:\s]+[\$€]?\s*(\d+[.,]\d{2})/i', $text, $matches)) {
            $data['total'] = str_replace(',', '.', $matches[1]);
        } elseif (preg_match('/[\$€]\s*(\d+[.,]\d{2})/i', $text, $matches)) {
            $data['total'] = str_replace(',', '.', $matches[1]);
        } elseif (preg_match('/\b(\d+[.,]\d{2})\b/', $text, $matches)) {
            $data['total'] = str_replace(',', '.', $matches[1]);
        }

        return $data;
    }

    protected function extractTextFromPrompt(string $prompt): string
    {
        if (preg_match('/TEXTO:\s*(.*)/s', $prompt, $matches)) {
            return trim($matches[1]);
        }
        return $prompt;
    }
}