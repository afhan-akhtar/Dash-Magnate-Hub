<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalQuestionOption extends Model
{
    protected $table = 'professional_question_options';

    protected $fillable = [
        'professional_question_id',
        'label',
        'value',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'professional_question_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(ProfessionalQuestion::class, 'professional_question_id');
    }
}
