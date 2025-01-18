<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class ChatResponse extends Component {
    public array $helpText;
    public $threadId;
    public $questionNo;
    //   public array $messages;
    public ?string $response = null;

    public function mount(): void
    {
        $this->threadId = session('threadID');
        $this->js('$wire.getResponse()');
    }

    public function getResponse(): static
    {
        $fname = session('cust.first_name');
        $prompt = <<<PROMPT
        This reviewer named $fname needs help with question number $this->questionNo

        Their request: {$this->helpText['content']}
PROMPT;

        $this->createMessage($prompt);
        return $this;
    }

    public function createMessage($inMessage): void
    {
        OpenAI::threads()->messages()->create($this->threadId, [
            'role' => 'user',
            'content' => $inMessage,
        ]);
        $this->streamAiResponse();
    }

    public function streamAiResponse(): void
    {
        $stream = OpenAI::threads()->runs()->createStreamed(
            threadId: $this->threadId,
            parameters: [
                'assistant_id' => config('openai.assistant'),
            ]);
        // $streamResponse = '';
        foreach ($stream as $content) {
            if ($content->event == 'thread.message.delta') {
                $this->stream(
                    to: 'stream-'.$this->getId(),
                    content: $content->response->delta->content[0]->text->value,
                    replace: false
                );
                $this->response .= $content->response->delta->content[0]->text->value;
            }
        }
    }

    public function render(): View
    {
        return view('livewire.chat-response');
    }
}
