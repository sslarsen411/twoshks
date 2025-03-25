<?php

namespace App\Livewire;

use App\Traits\AIChat;
use Illuminate\View\View;
use Livewire\Component;

class ChatResponse extends Component
{
    use AIChat;

    public array $helpText;
    public string $threadId;
    public $category;
    public string $question;
    public string $questionNumber;
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
        ray($this);
        $this->category = session('location.category');
        $prompt = <<<PROMPT
        This reviewer needs help with this question: $this->question.

        What they asked "{$this->helpText['content']}"

       Help them answer the question.
PROMPT;
        ray($prompt);
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
