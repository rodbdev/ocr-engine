<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromptTemplate;


class PromptTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromptTemplate::create([
            'name' => 'Default OCR',
            'fields' => [
                'document_type',
                'date',
                'total_amount',
                'issuer',
            ],
            'rules' => [
                'instruction' => 'Extract the listed fields from the document',
                'output_format' => 'json',
                'strict' => true,
            ],
        ]);

    }
}
