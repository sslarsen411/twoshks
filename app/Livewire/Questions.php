<?php

namespace App\Livewire;

use App\Models\Review;
use App\Traits\AIReview;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Questions extends Component {
    use AIReview;

    public string $random;
    public string $question;
    public array $questions = [];
    public string $answer = '';
    public int $progress = 15;
    public int $currentIndex = 0;
    public string $ask = ''; // Renamed from `$dex`
    public array $aiMessages = [];
    public int $questionNumber = 1; // Renamed from `$aiMsg`
    public string $type;
    protected array $messages = [
        'answer.required' => 'Please type something',
    ];
    private array $banner = array('lp', 'full', 'rp');

    /**
     * @return void
     */
    public function mount(): void
    {
        // $this->questions = Cache::get('questArr');
        //  $this->questions = Cache::get('questions');
        //  ray($this->questions);
        $this->initializeFirstQuestion();
        $this->random = $this->banner[array_rand($this->banner)];
    }

    /**
     * Initializes the first question and message prompt.
     *
     * @return void
     */
    private function initializeFirstQuestion(): void
    {
        $this->type = session('location.type');
        $freq = session('location.engagement_frequency');
        $rate = session('rating')[0];
        $questArr = Cache::get('questions');
        $specific = match ($this->type) {
            'retail' => $questArr[$this->type],
            'service' => $questArr[$this->type][$freq],
        };
        ray($specific);
        //   $initialPrompt = $this->createInitialPrompt(session('location.PID'));
        //   $this->createMessage($initialPrompt, true);
        ray($this->questions);
        // ray($this->questions['service']['ongoing']);
        $this->questions = array_merge($questArr['initial'], $specific,
            $questArr['general']);
        // $string = $this->questions['general'];
        $search = array("NUM_STAR", "COMPANY");
        $replace = array($rate, session('location.company'));
        $this->questions = str_replace($search, $replace, $this->questions);
        ray($this->questions);
        $this->question = $this->questions[$this->currentIndex];

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
        $review = Review::find(session('reviewID'));
        // Save updated answers
        $updatedAnswers = $this->saveUpdatedAnswers($review->answers, $this->currentIndex, strip_tags($this->answer));
        $review->update(['answers' => $updatedAnswers]);
        $this->progress += 12;
        $this->questionNumber++;
        $this->currentIndex++;
        if ($this->currentIndex < count($this->questions)) {
            $this->random = $this->banner[array_rand($this->banner)];
            $this->answer = '';
            $this->question = $this->questions[$this->currentIndex];
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
     * @return View
     */
    public function render(): View
    {
        return view('livewire.questions');
    }

    /**
     * @param  string|null  $inAnsStr
     * @param  string|int  $dex
     * @param  string  $newAns
     * @return null | string
     */
    function updateAnswers(string|null $inAnsStr, string|int $dex, string $newAns): string|null
    {
        if ($inAnsStr) {
            $ansArr = unserialize($inAnsStr);
        } else {
            $ansArr = [];
        }
        $ansArr[$dex] = $newAns;
        return serialize($ansArr);
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
