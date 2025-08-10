<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'text',
        'order',
        'is_correct',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_correct' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
