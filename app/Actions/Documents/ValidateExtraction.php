<?php

namespace App\Actions\Documents;

class ValidateExtraction
{
    public function handle(array $data): array
    {
        return [
            'nombre' => $data['nombre'] ?? null,
            'documento' => $data['documento'] ?? null,
            'fecha' => $data['fecha'] ?? null,
            'total' => isset($data['total'])
                ? floatval(str_replace(',', '.', $data['total']))
                : null,
        ];
    }
}
