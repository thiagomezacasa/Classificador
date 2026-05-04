<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'whatsapp_conversation',
        'ai_score',
        'intent_level',
        'ai_analysis'
    ];
}
