<?php

namespace App\Traits;

use Exception;
use Log;
use OpenAI\Laravel\Facades\OpenAI;

trait AIChat {
    public string $threadId;
    public ?string $response = null;

    public function createMessage($inThreadId, $inMessage): void
    {
        $this->threadId = $inThreadId;

        OpenAI::threads()->messages()->create($this->threadId, [
            'role' => 'user',
            'content' => $inMessage,
        ]);
        // Ensure no output before streaming
        if (ob_get_level()) {
            ob_end_clean();
        }

        $this->streamAiResponse();
    }

    /**
     * @return void
     * https://github.com/symfony/symfony/issues/60603
     * https://github.com/laravel/framework/issues/55894
     * Started happening after internal symfony components updated to 7.3.0.
     *
     * For now, quick solution is you can lock symfony/http-foundation version in composer.json until symfony team fix this issue.
     *

     */
    public function streamAiResponse(): void
    {
        try {
            ob_start();

            $stream = OpenAI::threads()->runs()->createStreamed(
                threadId: $this->threadId,
                parameters: [
                    'assistant_id' => config('openai.assistant'),
                ]);

            foreach ($stream as $content) {
                if ($content->event == 'thread.message.delta') {
                    // Clean any existing output
                    if (ob_get_length()) {
                        ob_clean();
                    }

                    try {
                        $this->stream(
                            to: 'stream-'.$this->getId(),
                            content: $content->response->delta->content[0]->text->value,
                        );
                        $this->response .= $content->response->delta->content[0]->text->value;
                    } catch (Exception $e) {
                        // Log the streaming error but don't throw
                        Log::error('Streaming error: '.$e->getMessage());
                    }
                }
            }
        } finally {
            if (ob_get_level()) {
                ob_end_clean();
            }
        }

    }
}
