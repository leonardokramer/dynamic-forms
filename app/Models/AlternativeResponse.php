<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternativeResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_response_id',
        'alternative_text',
        'alternative_order',
        'alternative_is_correct',
    ];

    protected $casts = [
        'alternative_order' => 'integer',
        'alternative_is_correct' => 'boolean',
    ];

    public function questionResponse(): BelongsTo
    {
        return $this->belongsTo(QuestionResponse::class);
    }
}
