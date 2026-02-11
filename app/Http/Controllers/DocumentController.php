<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\PromptTemplate;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessDocumentJob; 
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function create()
    {
        return Inertia::render('Documents/Create', [
            'prompts' => PromptTemplate::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx',
            'prompt_id' => 'required|exists:prompt_templates,id'
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        // Generar nombre único CON extensión
        $fileName = Str::random(40) . '.' . $extension;
        
        // Guardar archivo
        $path = $file->storeAs('documents', $fileName, 'documents');

        $document = Document::create([
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'path' => $path, // Esto ya incluye la extensión
            'prompt_template_id' => $request->prompt_id,
            'status' => 'pending'
        ]);

        ProcessDocumentJob::dispatch($document);

        return redirect()->route('documents.show', $document);
    }

    public function show(Document $document)
    {
        return Inertia::render('Documents/Show', [
            'document' => $document
        ]);
    }
}
