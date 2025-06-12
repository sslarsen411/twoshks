<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CombineQandA {
    /**
     * Format paired feedback into a readable text block for the final prompt.
     *
     * @return string
     */
    public function formatFeedbackForPrompt(): string
    {
        $qaPairs = $this->pairQuestionsWithAnswers();

        $output = "Here is the feedback:\n\n";

        foreach ($qaPairs as $pair) {
            $output .= "Q: {$pair['question']}\n";
            $output .= "A: {$pair['answer']}\n\n";
        }

        return trim($output);
    }

    public function pairQuestionsWithAnswers(): array
    {
        $questions = session('questions', []);
        //  $answers = session('answers', []);
        $answers = Cache::get('answers', []);

        $paired = [];

        foreach ($questions as $index => $question) {
            $paired[] = [
                'question' => $question,
                'answer' => $answers[$index] ?? '',
            ];
        }

        return $paired;
    }
}
