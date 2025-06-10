<?php

namespace App\Livewire;

use App\Traits\AIChat;
use Livewire\Component;

class ChatResponse extends Component {
    use AIChat;

    public array $helpText;
    public string $threadId;
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
        $prompt = <<<PROMPT
        This reviewer needs help with this question: $this->question.

        What they asked "{$this->helpText['content']}"

       Help them answer the question.
    PROMPT;
        $this->sendStreamingMessageToAssistant($this->threadId, $prompt);
        return $this;
    }

    /**
     * @return object
     */
    public function render(): object
    {
        return view('livewire.chat-response');
    }
}
