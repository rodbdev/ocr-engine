<?php

namespace App\Actions\Documents;

use App\Models\Document;

class BuildPrompt
{
    public function handle(Document $document, string $text): string
    {
        return <<<PROMPT
        Sos un extractor de información.

        Extraé los siguientes campos del texto:
        - nombre
        - documento
        - fecha
        - total

        Devolvé SOLO JSON válido.

        TEXTO:
        {$text}
        PROMPT;
    }
}
