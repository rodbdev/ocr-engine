<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptTemplate extends Model
{
   protected $casts = [ 'fields' => 'array', 'rules' => 'array', ];
}
