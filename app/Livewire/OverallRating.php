<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Question;
use App\Rules\NamePlus;
use App\Traits\AIReview;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OverallRating extends Component
{
    use AIReview;

    private const string CARE_URL = '/care';
    private const string QUESTION_URL = '/question';

    public $rating = 0;
    #[Validate]
    public $first_name;
    public $last_name;
    public $email;
    // UI Flags
    public $isDisabled = true;
    public $showInstr = true;
    protected int $seconds = 3600;

    /**
     * @param $propertyName
     * @return void'
     * Called when a property is updated.
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
        $this->showInstr = false;
        session()->forget('rating');
        session()->push('rating', $this->rating);
    }

    /**
     * @return null
     */
    public function submitForm(): null
    {
        $this->validate();

        $customer = $this->createCustomer();
        session()->put('cust', $customer);

        $review = $this->initReview($customer);
        session()->put('reviewID', $review->id);

        if ($this->rating < session('location.min_rate')) {
            alert()->question('What happened?', 'Please tell us how we can improve your experience');
            return $this->redirect(self::CARE_URL, navigate: true);
        }
        // Initialize question set for business category
        $questions = Question::where('category_id', session("location.category"))->pluck('questions')->toArray();
        Cache::add('questArr', unserialize($questions[0]), $this->seconds);
        alert()->success($this->first_name . ', You\'re ready to start', text: "Here's the first question");
        return $this->redirect(self::QUESTION_URL, navigate: true);
    }

    /**
     * Creates or retrieves an existing customer.
     *
     * @return Customer
     */
    private function createCustomer(): Customer
    {
        return Customer::firstOrCreate([
            'users_id' => session('location.users_id'),
            'location_id' => session('locID'),
            'first_name' => $this->sanitizeInput($this->first_name),
            'last_name' => $this->sanitizeInput($this->last_name),
            'email' => $this->sanitizeInput($this->email),
        ]);
    }

    /**
     * Sanitizes input by stripping HTML tags.
     *
     * @param string $input
     * @return string
     */
    private function sanitizeInput(string $input): string
    {
        return strip_tags($input);
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.overall-rating');
    }

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'min:2 ', 'max:45', new NamePlus],
            'last_name' => ['required', 'min:2 ', 'max:45', new NamePlus],
            'email' => 'required|email',
        ];
    }

}
