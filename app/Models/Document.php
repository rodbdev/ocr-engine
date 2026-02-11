<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'original_name',
        'mime',
        'path',
        'prompt_template_id',
        'extracted_json',
    ];
}
