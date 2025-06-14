<?php
/**
 * @author Scott Larsen <scott@scottlarsenai.com>
 */

namespace App\Traits;

use Exception;
use OpenAI\Laravel\Facades\OpenAI;
use Throwable;

trait AIReview {
    use ReviewPromptHandler, AILog;

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
    public function createUserMessage($inMessage, string $reviewPromptType = ''): string
    { // 'first' or 'final'
        try {
            //$response = OpenAI::threads()->messages()->create(session('threadID'), [
            OpenAI::threads()->messages()->create(session('threadID'), [
                'role' => self::ROLE_USER,
                'content' => $inMessage,
            ]);

            return $this->runAssistant($reviewPromptType);
        } catch (Exception $e) {
            $this->logAssistantError("AIReview:createUserMessageAndRun", $e);
            return "Error creating message: ".$e->getMessage();
        }
    }

    /**
     * @param $inPromptType
     * @return string
     */
    public function runAssistant($inPromptType): string
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
            $this->logAssistantError("AIReview:runAssistant", $e);
            return "Error sending message: ".$e->getMessage();
        }
    }

    /**
     * @param $runId
     * @return string|null
     */
    public function getThreadResult($runId): ?string
    {
        try {
            $threadId = session('threadID');
            if (!$threadId) {
                throw new Exception("Thread ID not found in session.");
            }
            // $status = 'queued';
            $startTime = time();
            do {
                sleep(self::POLL_INTERVAL);
                $run = OpenAI::threads()->runs()->retrieve($threadId, $runId);
                $status = $run->status;
            } while (!in_array($status, ['completed', 'failed', 'cancelled']) && (time() - $startTime < 15));

            if ($status !== 'completed') {
                return json_encode(['status' => 'error', 'message' => 'Timed out waiting for assistant response']);
            }
            $messages = OpenAI::threads()->messages()->list($threadId);
            $allMessages = $messages->data;
            $assistantReply = collect($allMessages)
                ->firstWhere('role', 'assistant');

            $content = $assistantReply->content[0]->text->value ?? '';
            $this->logAssistantMessage("AIReview:getThreadResult", $threadId, $runId, 'Successful run');
            return $content;
        } catch (Exception $e) {
            $this->logAssistantError("AIReview:getThreadResult", $e);
            return null;
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
}
