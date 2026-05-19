<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalQuestion extends Model
{
    public const ROLES = ['buyer', 'seller', 'capital_raiser', 'broker'];

    public const TYPES = ['text', 'textarea', 'single_choice', 'multi_choice'];

    protected $table = 'professional_questions';

    protected $fillable = [
        'role',
        'question',
        'type',
        'is_required',
        'sort_order',
        'status',
        'code',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'integer',
        'is_required' => 'boolean',
    ];

    /** Human-readable label for admin / UI (stored value is snake_case). */
    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'single_choice' => 'Single Choice',
            'multi_choice' => 'Multiple Choice',
            'text' => 'Text',
            'textarea' => 'Long Text',
            default => \Illuminate\Support\Str::headline(str_replace('_', ' ', $type)),
        };
    }

    public function submittedAnswers()
    {
        return $this->hasMany(ProfessionalQuestionAnswer::class, 'professional_question_id');
    }

    public function options()
    {
        return $this->hasMany(ProfessionalQuestionOption::class, 'professional_question_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
