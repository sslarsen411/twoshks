<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

/**
 * Standardized logging for the Assistant handling
 */
trait AILog {
    /**
     * @param $context
     * @param $threadId
     * @param $runId
     * @param $content
     * @return void
     */
    protected function logAssistantMessage($context, $threadId, $runId, $content): void
    {
        Log::info("[{$context}] Assistant response", [
            'thread_id' => $threadId,
            'run_id' => $runId,
            'message' => $content,
        ]);
    }

    /**
     * @param $context
     * @param $error
     * @return void
     */
    protected function logAssistantError($context, $error): void
    {
        Log::error("[{$context}] Error: {$error->getMessage()}", [
            'exception' => $error,
        ]);
    }
}
