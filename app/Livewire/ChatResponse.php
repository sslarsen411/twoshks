<?php

namespace App\Livewire;

use App\Traits\AIChat;
use Illuminate\View\View;
use Livewire\Component;

class ChatResponse extends Component {
    use AIChat;

    public array $helpText;
    public string $threadId;
    public $questionNo;
    public ?string $response = null;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->threadId = session('threadID');
        $this->js('$wire.getResponse()');
    }

    /**
     * @return $this
     */
    public function getResponse(): static
    {
        $fname = session('cust.first_name');
        $prompt = <<<PROMPT
        This reviewer named $fname needs help with question number $this->questionNo
        Their request: {$this->helpText['content']}
PROMPT;
        $this->createMessage($this->threadId, $prompt);
        return $this;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.chat-response');
    }
}
