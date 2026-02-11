<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\Documents\NormalizeFile;
use App\Actions\Documents\RunOcr;
use App\Actions\Documents\BuildPrompt;
use App\Actions\Documents\ExtractStructuredData;
use App\Actions\Documents\ValidateExtraction;

class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle()
    {
        try {
            $this->document->update(['status' => 'processing']);

            // 1. Normalizar archivo
            $images = (new NormalizeFile())->handle($this->document);
            
            // 2. Ejecutar OCR
            $extractedText = (new RunOcr())->handle($images);
            
            // 3. Construir el prompt
            $prompt = (new BuildPrompt())->handle($this->document, $extractedText);
            
            // 4. Extraer datos estructurados
            $extractedData = (new ExtractStructuredData())->handle($prompt);
            
            // 5. Validar la extracción
            $validatedData = (new ValidateExtraction())->handle($extractedData);

            $this->document->update([
                'extracted_text' => $extractedText,
                'extracted_json' => json_encode($validatedData),
                'status' => 'completed',
                'processed_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->document->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processed_at' => now(),
            ]);
            throw $e;
        }
    }
}