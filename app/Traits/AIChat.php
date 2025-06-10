<?php

namespace App\Traits;

use Exception;
use Log;
use OpenAI\Laravel\Facades\OpenAI;

trait AIChat {
    use AILog;

    public string $threadId;
    public ?string $response = null;

    public function sendStreamingMessageToAssistant($threadId, $question): void
    {
        $this->threadId = $threadId;
        OpenAI::threads()->messages()->create($this->threadId, [
            'role' => 'user',
            'content' => $question,
        ]);
        // Ensure no output before streaming
        if (ob_get_level()) {
            ob_end_clean();
        }
        $this->streamAssistantReply();
    }

    /**
     * @return void
     * https://github.com/symfony/symfony/issues/60603
     * https://github.com/laravel/framework/issues/55894
     * Started happening after internal symfony components updated to 7.3.0.
     *
     * For now, a quick solution is you can lock the symfony/http-foundation version in composer.json
     * until the symfony team fixes this issue.
     *
     */
    public function streamAssistantReply(): void
    {
        try {
            $stream = OpenAI::threads()->runs()->createStreamed(
                threadId: $this->threadId,
                parameters: [
                    'assistant_id' => config('openai.assistant'),
                ]);

            ob_start();
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
                        ob_flush();
                        flush();
                    } catch (Exception $e) {
                        // Log the streaming error but don't throw
                        Log::error('Streaming error: '.$e->getMessage());
                    }
                }
            }
            ob_end_clean();
        } catch (Exception $e) {
            $this->logAssistantError("AIChat:streamAssistantReply", $e);
            echo "<p class='text-red-600'>An error occurred: {$e->getMessage()}</p>";
        } finally {
            if (ob_get_level()) {
                ob_end_clean();
            }
        }
    }
}
