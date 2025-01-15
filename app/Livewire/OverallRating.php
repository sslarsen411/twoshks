<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Rules\NamePlus;
use App\Traits\Assistant;
use Livewire\Attributes\Validate;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class OverallRating extends Component {
    use Assistant;

    public $rating = 0;
    #[Validate]
    public $first_name;
    public $last_name;
    public $email;
    public $isDisabled = true;
    public $showInstr = true;

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
        $this->showInstr = false;
        session()->forget('rating');
        session()->push('rating', $this->rating);
        ray(session()->all());
    }

    public function submitForm(): null
    {
        $this->validate();
        /* Create new customer */
        $newCustomer = Customer::firstOrCreate([
            'users_id' => session('location.users_id'),
            'location_id' => session('locID'),
            'first_name' => strip_tags($this->first_name),
            'last_name' => strip_tags($this->last_name),
            'email' => strip_tags($this->email),
        ]);
        session()->put('cust', $newCustomer);
        $newReview = $this->initReview($newCustomer);
        session()->put('reviewID', $newReview->id);
        if ($this->rating < session('location.min_rate')) {
            Alert::question('What happened?', 'Please tell us how we can improve your experience');
            return $this->redirect('/care', navigate: true);
        }
        Alert::success($this->first_name.', You\'re ready to start', text: "Here's the first question");
        return $this->redirect('/question', navigate: true);
    }

    public function render()
    {
        return view('livewire.overall-rating');
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'min:2 ', 'max:45', new NamePlus],
            'last_name' => ['required', 'min:2 ', 'max:45', new NamePlus],
            'email' => 'required|email',
        ];
    }
}
