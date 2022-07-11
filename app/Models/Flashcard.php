<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;
    protected $table = "flashcards";

    public static $rules = array(
        'question_text' => 'required|unique:flashcards,question_text',
        'answer_text' => 'required',
    );

    protected $fillable = ['question_text', 'answer_text'];

    public static function byQuestionText($question_text)
    {
        return self::where('question_text', '=', $question_text)->get();
    }

    public function flashcard_answer($user_id = null)
    {
        return $this->hasMany(FlashcardAnswer::class);
    }

}
