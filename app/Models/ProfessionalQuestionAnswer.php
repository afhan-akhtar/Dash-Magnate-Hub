<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalQuestionAnswer extends Model
{
    protected $table = 'professional_question_answers';

    protected $fillable = [
        'professional_question_id',
        'user_id',
        'answer_text',
        'selected_option_ids',
    ];

    protected $casts = [
        'professional_question_id' => 'integer',
        'user_id' => 'integer',
        'selected_option_ids' => 'array',
    ];

    public function question()
    {
        return $this->belongsTo(ProfessionalQuestion::class, 'professional_question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
