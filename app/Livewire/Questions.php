<?php

namespace App\Livewire;

use App\Models\Review;
use App\Traits\AIReview;
use App\Traits\ReviewQuestionSet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

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
        // Randomize which guru image appears for each question
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
        // if ($this->currentIndex < 2) {
        try {
            if ($this->currentIndex < session('question_num') - 1) {
                $this->checkAnswer($this->question, $this->answer);
                if (!$this->validationPassed && $this->validationMessage) {
                    return back()->withErrors($this->validationMessage);
                }
            }

            $reviewId = session('reviewID');
            $cacheKey = "review_answers_$reviewId";

            // Store the current answer atomically using Redis transactions
            // https://chatgpt.com/share/684d8dea-7a54-8009-befd-100cfd0ec561
            Cache::lock("lock_$cacheKey", 10)->block(5, function () use ($cacheKey) {
                $cachedAnswers = Cache::get($cacheKey, []);
                $cachedAnswers[$this->currentIndex] = strip_tags($this->answer);
                Cache::put($cacheKey, $cachedAnswers, now()->addMinutes(20));
            });

            $this->progress += 12;
            $this->questionNumber++;
            $this->currentIndex++;

            if ($this->currentIndex < session('question_num')) {
                $this->random = $this->banner[array_rand($this->banner)]; // shuffle the image
                $this->answer = ''; // Clear answer
                $this->question = $this->questionArray[$this->currentIndex]; // Get the next question
            } else {
                // Final step — persist to DB inside a transaction
                DB::beginTransaction();
                try {
                    $answers = Cache::get($cacheKey, []);

                    Review::where('id', $reviewId)->update([
                        'answers' => serialize($answers),
                        'status' => Review::COMPLETED,
                    ]);

                    Cache::forget($cacheKey); // cleanup
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::error("Review answer save failed", [
                        'review_id' => $reviewId,
                        'error' => $e->getMessage(),
                    ]);
                    return back()->withErrors("Sorry, something went wrong while saving your answers.");
                }

                alert()->success(
                    'Done! '.session('cust.first_name').' You\'ve completed the questions.',
                    'Now I\'ll for your review...'
                );
                return $this->redirect('/review', navigate: true);
            }

            return null;
        } catch (Throwable $e) {
            Log::critical('Form submission failed', [
                'review_id' => session('reviewID'),
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors("An unexpected error occurred. Please try again.");
        }
    }

    private function checkAnswer($question, $answer): void
    {
        $prompt = $this->getValidationPrompt();

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
        $response = $this->sendOpenAiRequest($ValidationMessages);
        $json = json_decode($response, true);
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
     * @return View
     */
    public function render(): View
    {
        return view('livewire.questions');
    }

    /**
     * Saves the updated answers to the review object.
     *
     * @param  string|null  $savedAnswers
     * @param  int|string  $index
     * @param  string  $newAnswer
     * @return string|null
     */
//    private function saveUpdatedAnswers(string|null $savedAnswers, int|string $index, string $newAnswer): string|null
//    {
//        $answersArray = $savedAnswers ? unserialize($savedAnswers) : [];
//        $answersArray[$index] = $newAnswer;
//        return serialize($answersArray);
//    }
}
