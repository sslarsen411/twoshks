<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Rules\NamePlus;
use App\Traits\AIReview;
use App\Traits\ReviewInitializer;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OverallRating extends Component {
    use AIReview, ReviewInitializer;

    private const string CARE_URL = '/care';
    private const string QUESTION_URL = '/question';

    public float $rating = 0;
    #[Validate]
    public string $first_name;
    public string $last_name;
    public string $email;
    // UI Flags
    public bool $isDisabled = true;
    public bool $showInstr = true;

    /**
     * @param $propertyName
     * @return void'
     * Called when a property is updated.
     * @throws ValidationException
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
        //ray(session()->all());

        if ($this->rating < session('location.min_rate')) {
            alert()->question('What happened?', 'Please tell us how we can improve your experience');
            return $this->redirect(self::CARE_URL, navigate: true);
        }
        alert()->success($this->first_name.', You\'re ready to start', text: "Here's the first question");
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
     * @param  string  $input
     * @return string
     */
    private function sanitizeInput(string $input): string
    {
        return strip_tags($input);
    }

    /**
     * @return object
     */
    public function render(): object
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
