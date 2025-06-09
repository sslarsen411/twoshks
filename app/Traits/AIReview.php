<?php
/**
 * @author Scott Larsen <scott@scottlarsenai.com>
 */

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Throwable;

trait AIReview {
    use ReviewPromptHandler;

    private const string ROLE_USER = 'user';
    private const string PROMPT_TYPE_FIRST = 'first';
    private const string PROMPT_TYPE_FINAL = 'final';
    private const int POLL_INTERVAL = 2;
    private const int TIMEOUT = 30;

    /**
     * @param  array  $parameters
     * @return string
     */
    public function setThread(array $parameters = []): string
    {
        $thread = OpenAI::threads()->create($parameters);
        return $thread->id;
    }

    /**
     * @param $inMessage
     * @param  string  $reviewPromptType
     * @return string
     */
    public function createMessage($inMessage, string $reviewPromptType = ''): string
    { // 'first' or 'final'
        try {
            //$response = OpenAI::threads()->messages()->create(session('threadID'), [
            OpenAI::threads()->messages()->create(session('threadID'), [
                'role' => self::ROLE_USER,
                'content' => $inMessage,
            ]);

            return $this->sendMessage($reviewPromptType);
        } catch (Exception $e) {
            return "Error creating message: ".$e->getMessage();
        }
    }

    /**
     * @param $inPromptType
     * @return string
     */
    public function sendMessage($inPromptType): string
    {
        try {
            $response = OpenAI::threads()->runs()->create(
                threadId: session('threadID'),
                parameters: [
                    'assistant_id' => config('openai.assistant'),
                ],
            );
            // Get the thread ID from the response
            $runId = $response['id'];
            // Poll for completion and retrieve the result
            if ($inPromptType === 'first') {
                $jsonData = array(
                    'status' => 'success',
                    'message' => 'review initialized',
                );
                return json_encode($jsonData);
            }
            return $this->getThreadResult($runId);
        } catch (Exception $e) {
            return "Error sending message: ".$e->getMessage();
        }
    }

    /**
     * @param $runId
     * @param  int  $timeout
     * @param  int  $interval
     * @return string
     */
    public function getThreadResult($runId, int $timeout = self::TIMEOUT, int $interval = self::POLL_INTERVAL): string
    {
        $startTime = time();
        while (true) {
            // Query the thread for status
            $response = OpenAI::threads()->runs()->retrieve(
                threadId: session('threadID'),
                runId: $runId,
            );
            // Check if the thread status is completed
            if ($response['status'] === 'completed') {
                $list = OpenAI::threads()->messages()->list(session('threadID'), [
                    'limit' => 10,
                ]);
                $rev = OpenAI::threads()->messages()->retrieve(threadId: session('threadID'),
                    messageId: $list->firstId);
                return $rev['content'][0]['text']['value'] ?? "No text found in the response.";
            }
            // Check if the timeout has been reached
            if ((time() - $startTime) >= $timeout) {
                return "Error: Timed out while waiting for the thread to complete.";
            }
            // Wait before polling again
            sleep($interval);
        }
    }

    public function sendOpenAiRequest(array $messages): string
    {
        return $this->sendToAssistantSync($messages);
    }

    public function sendToAssistantSync(array $messages): string
    {
        try {

            // $result =  OpenAI::threads()->messages()->create(session('threadID'),[
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => $messages,
                'temperature' => 0.3,
            ]);

            return $result->choices[0]->message->content ?? '';
        } catch (Throwable $e) {
            logger()->error('OpenAI sync request failed: '.$e->getMessage());
            return '';
        }
    }

    private function logMessageStatus(string $reviewId, string $messageStatus): void
    {
        if ($messageStatus === json_encode(['status' => 'success', 'message' => 'review initialized'])) {
            Log::info("Review:$reviewId Initial instructions successfully sent");
        } else {
            Log::error('There was a problem sending the initial instructions');
        }
    }
}
