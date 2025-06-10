<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait AILog {
    protected function logAssistantMessage($context, $threadId, $runId, $content): void
    {
        Log::info("[{$context}] Assistant response", [
            'thread_id' => $threadId,
            'run_id' => $runId,
            'message' => $content,
        ]);
    }

    protected function logAssistantError($context, $error): void
    {
        Log::error("[{$context}] Error: {$error->getMessage()}", [
            'exception' => $error,
        ]);
    }
}
