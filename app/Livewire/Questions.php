<?php

namespace App\Livewire;

use App\Models\Review;
use App\Traits\AIReview;
use App\Traits\ReviewQuestionSet;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Questions extends Component {
    use ReviewQuestionSet, AIReview;

    public string $random;
    public string $question;
    public array $questionArray = [];
    public string $answer = '';
    public int $progress = 15;
    public int $currentIndex = 0;
    public string $ask = ''; // Renamed from `$dex`
    public array $aiMessages = [];
    public int $questionNumber = 1; // Renamed from `$aiMsg`
    public string $type;
    public bool $validationPassed = false;
    public string $validationMessage;
    protected array $messages = [
        'answer.required' => 'Please type something',
    ];
    private array $banner = array('lp', 'full', 'rp');

    /**
     * Prepare the questions
     * @return void
     */
    public function mount(): void
    {
        $this->questionArray = $this->initializeFirstQuestion(inRate: session('rating')[0],
            inBiz: session('location.company'));
        $this->question = $this->questionArray[$this->currentIndex];
        $this->random = $this->banner[array_rand($this->banner)];
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
        //AI Assistant validates the answer
        $this->checkAnswer($this->question, $this->answer);
        ray($this->validationPassed);
        if (!$this->validationPassed && $this->validationMessage) {
            return back()->withErrors($this->validationMessage);
        }
        $review = Review::find(session('reviewID'));
        // Save updated answers
        $updatedAnswers = $this->saveUpdatedAnswers($review->answers, $this->currentIndex, strip_tags($this->answer));
        $review->update(['answers' => $updatedAnswers]);
        $this->progress += 12;
        $this->questionNumber++;
        $this->currentIndex++;
        if ($this->currentIndex < session('question_num')) {
            $this->random = $this->banner[array_rand($this->banner)];
            $this->answer = '';
            $this->question = $this->questionArray[$this->currentIndex];
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

    private function checkAnswer($question, $answer): void
    {
        $prompt = <<<PROMPT
            You are a helpful assistant evaluating customer responses to review questions. Your job is to decide if the answer is
            clear and specific enough to be turned into a helpful review.

            You will be given:
            - The review question the customer is responding to
            - The customer’s answer

            If the answer is meaningful and specific, return:
            {
              "status": "okay"
            }

            If the answer is vague, too short, or unclear, return:
            {
              "status": "not_okay",
              "message": "A short, friendly prompt encouraging the customer to expand their answer, tailored to the question"
            }

            Make your message sound natural and supportive. Offer gentle suggestions or ask for examples to help them
            elaborate. Do not use the phrase “vague” or “incomplete” in the message. Keep the tone positive and conversational.

            **Important:** Respond ONLY with a valid JSON object. No extra explanation.
PROMPT;

        $ValidationMessages = [
            [
                'role' => 'system',
                'content' => $prompt
            ],
            [
                'role' => 'user',
                'content' => "Question: $question\nAnswer: $answer"
            ]
        ];
        //ray($ValidationMessages);
        $response = $this->sendOpenAiRequest($ValidationMessages);

        $json = json_decode($response, true);
        // ray($json);

        if (isset($json['status']) && $json['status'] === 'okay') {
            $this->validationPassed = true;
            $this->validationMessage = '';
        } elseif (isset($json['status']) && $json['status'] === 'not_okay') {
            $this->validationPassed = false;
            $this->validationMessage = $json['message'].' If you need help, you can chat with me below.';

        } else {
            $this->validationPassed = false;
            $this->validationMessage = "You're off to a good start! Try adding a few details about what stood out
            or made the experience what it was — that helps others get the full picture.";
        }
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
     * @return string[]
     */
//    protected function rules(): array
//    {
//        return [
//            'answer' => 'required|string|min:6',
//        ];
//    }
}
