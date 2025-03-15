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
        $this->category = session('category');
        $prompt = <<<PROMPT
        The reviewer needs help with question:
        category: $this->category
        question: $this->question
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
