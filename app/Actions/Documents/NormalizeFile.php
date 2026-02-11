<?php

namespace App\Actions\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class NormalizeFile
{
    public function handle(Document $document): array
    {
        // $inputPath = storage_path('app/' . $document->path);
        $filePath = $document->path;

       // Verificar que el archivo existe usando Storage facade
        if (!Storage::disk('documents')->exists($filePath)) {
            throw new \Exception("El archivo no existe en el disco 'documents': " . $filePath);
        }
        
        // Get the full system path for exec commands
        $inputPath = Storage::disk('documents')->path($filePath);
        
        $outputDir = storage_path('app/tmp/' . $document->id);
        
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        
        // Limpiar directorio temporal previo
        array_map('unlink', glob($outputDir . '/*'));

        try {
            // PDF → imágenes
            if (str_contains($document->mime, 'pdf')) {
                // Verificar que pdftoppm está instalado
                exec("which pdftoppm", $output, $returnCode);
                if ($returnCode !== 0) {
                    throw new \Exception("pdftoppm no está instalado. Instala poppler-utils.");
                }
                
                // Convertir PDF a PNG
                $command = "pdftoppm -png -r 300 \"{$inputPath}\" \"{$outputDir}/page\" 2>&1";
                exec($command, $output, $returnCode);
                
                if ($returnCode !== 0) {
                    throw new \Exception("Error al convertir PDF: " . implode("\n", $output));
                }
                
                $images = glob($outputDir . '/page-*.png');
                if (empty($images)) {
                    $images = glob($outputDir . '/page*.png');
                }
                
                return $images;
            }

            // Imagen → imagen normalizada
            // Verificar que convert (ImageMagick) está instalado
            exec("which convert", $output, $returnCode);
            if ($returnCode !== 0) {
                // Si no hay convert, copiar la imagen directamente
                $outputPath = $outputDir . '/page-1.png';
                copy($inputPath, $outputPath);
                return [$outputPath];
            }
            
            // Usar ImageMagick para optimizar la imagen
            $outputPath = $outputDir . '/page-1.png';
            $command = "convert \"{$inputPath}\" -density 300 -colorspace Gray -contrast \"{$outputPath}\" 2>&1";
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0 || !file_exists($outputPath)) {
                // Fallback: copiar la imagen original
                copy($inputPath, $outputPath);
            }
            
            return [$outputPath];
            
        } catch (\Exception $e) {
            Log::error("Error en NormalizeFile: " . $e->getMessage());
            throw $e;
        }
    }
}