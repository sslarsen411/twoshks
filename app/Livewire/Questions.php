<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\Review;
use App\Traits\AIReview;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Questions extends Component {
    use AIReview;

    public Question $question;
    public string $answer = '';
    public int $currentIndex = 0; // Renamed from `$dex`
    public string $ask = '';
    public array $aiMessages = []; // Renamed from `$aiMsg`
    public int $key = 1;

    protected array $messages = [
        'answer.required' => 'Please type something',
    ];

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->initializeFirstQuestion();
    }

    /**
     * Initializes the first question and message prompt.
     *
     * @return void
     */
    private function initializeFirstQuestion(): void
    {
        $initialPrompt = $this->createInitialPrompt(session('location.PID'));
        $this->createMessage($initialPrompt, true);
        $cachedQuestions = Cache::get('questions');
        $this->question = $cachedQuestions[$this->currentIndex];
    }


    /**
     * @return void
     */
    public function handleHelp(): void
    {
        $this->aiMessages[] = ['role' => 'user', 'content' => $this->ask];
        $this->aiMessages[] = ['role' => 'assistant', 'content' => ''];
        $this->ask = '';
    }

    /**
     * @return null | string
     */
    public function handleFormSubmission(): null|string
    {
        $this->validate();
        $cachedQuestions = Cache::get('questions');
        $review = Review::find(session('reviewID'));

        // Save updated answers
        $updatedAnswers = $this->saveUpdatedAnswers($review->answers, $this->currentIndex, strip_tags($this->answer));
        $review->update(['answers' => $updatedAnswers]);

        $this->currentIndex++;
        if ($this->currentIndex <= 5) {
            $this->answer = '';
            $this->question = $cachedQuestions[$this->currentIndex];
        } else {
            $review->update(['status' => Review::COMPLETED]);
            alert()->success(
                'Done! '.session('cust.first_name').' You\'ve completed the questions.',
                'Now we\'ll compose a review'
            );
            return $this->redirect('/review', navigate: true);
        }
        return null;
    }

    /**
     * Saves the updated answers to the review object.
     *
     * @param  string|null  $savedAnswers
     * @param  int|string  $index
     * @param  string  $newAnswer
     * @return string|null
     */
    private function saveUpdatedAnswers(string|null $savedAnswers, int|string $index, string $newAnswer): string|null
    {
        $answersArray = $savedAnswers ? unserialize($savedAnswers) : [];
        $answersArray[$index] = $newAnswer;

        return serialize($answersArray);
    }

    /**
     * @param  string|null  $inAnsStr
     * @param  string|int  $dex
     * @param  string  $newAns
     * @return null | string
     */
//    function updateAnswers(string|null $inAnsStr, string|int $dex, string $newAns): string|null
//    {
//        if ($inAnsStr) {
//            $ansArr = unserialize($inAnsStr);
//        } else {
//            $ansArr = [];
//        }
//        $ansArr[$dex] = $newAns;
//        return serialize($ansArr);
//    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.questions');
    }

    /**
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'answer' => 'required|string|min:6',
        ];
    }
}
