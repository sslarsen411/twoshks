<?php

namespace App\Livewire;

use App\Models\Review;
use App\Traits\Assistant;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class Questions extends Component {
    use Assistant;

    public $question;
    public $answer = '';
    public $dex = 0;
    public string $ask = '';
    public array $aiMsg = [];
    public $key = 1;
    protected $messages = [
        'answer.required' => 'Please type something',
    ];

    /**
     * @return void
     */
    public function mount(): void
    {
        $initMsg = $this->createInitialPrompt(session('location.PID'));
        $this->createMessage($initMsg, true);
        $questions = Cache::get('questions');
        $this->question = $questions[$this->dex];
    }

    /**
     * @return void
     */
    public function handleHelp(): void
    {
        $this->aiMsg[] = ['role' => 'user', 'content' => $this->ask];
        $this->aiMsg[] = ['role' => 'assistant', 'content' => ''];
        $this->ask = '';
    }

    /**
     * @return null | string
     */
    public function submitForm(): null|string
    {
        $this->validate();
        $questions = Cache::get('questions');
        $review = Review::find(session('reviewID'));
        $currAns = $this->updateAnswers($review->answers, $this->dex, strip_tags($this->answer));
        $review->update(['answers' => $currAns]);

        $this->dex++;
        if (($this->dex) <= 5):
            $this->answer = '';
            $this->question = $questions[$this->dex];
        else:
            Alert::success('Congratulations! You&apos;re finished with the questions.',
                'Now we\'ll compose your review');
            return $this->redirect('/review', navigate: true);
        endif;
        return null;
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
