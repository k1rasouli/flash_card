<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardAnswer extends Model
{
    use HasFactory;
    protected $table = "flashcard_answers";

    public static $rules = array(
        'flashcard_id' => 'required|numeric',
        'user_id' => 'nullable|numeric',
        'provided_answer' => 'required',
        'was_answer_correct' => 'required|boolean',
    );

    protected $fillable = ['flashcard_id', 'user_id', 'provided_answer', 'was_answer_correct'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function flashcards()
    {
        return $this->belongsTo(Flashcard::class);
    }

    public static function allCorrectAnswers()
    {
        return self::where('was_answer_correct', '=', true)->get();
    }

    public static function allCorrectAnswersByUserId($user_id)
    {
        $correct_answer_condition = ['was_answer_correct' => true, 'user_id' => $user_id];
        return self::where($correct_answer_condition)->get();
    }

    public static function allAnsweredQuestionIdArray()
    {
        return self::select('flashcard_id')
            ->distinct()
            ->get()
            ->toArray();
    }

    public static function allAnsweredQuestionIdArrayByUserId($user_id)
    {
        return self::where('user_id', '=', $user_id)
            ->select('flashcard_id')
            ->distinct()
            ->get()
            ->toArray();
    }
}
