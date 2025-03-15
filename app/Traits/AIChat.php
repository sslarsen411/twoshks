<?php

namespace App\Traits;

use OpenAI\Laravel\Facades\OpenAI;

trait AIChat
{
    public string $threadId;
    public ?string $response = null;

    public function createMessage($inThreadId, $inMessage): void
    {
        $this->threadId = $inThreadId;

        OpenAI::threads()->messages()->create($this->threadId, [
            'role' => 'user',
            'content' => $inMessage,
        ]);
        $this->streamAiResponse();
    }

    /**
     * @return void
     */
    public function streamAiResponse(): void
    {
        $stream = OpenAI::threads()->runs()->createStreamed(
            threadId: $this->threadId,
            parameters: [
                'assistant_id' => config('openai.assistant'),
            ]);

        foreach ($stream as $content) {
            if ($content->event == 'thread.message.delta') {
                $this->stream(
                    to: 'stream-' . $this->getId(),
                    content: $content->response->delta->content[0]->text->value,
                );
                $this->response .= $content->response->delta->content[0]->text->value;
            }
        }
    }
}
