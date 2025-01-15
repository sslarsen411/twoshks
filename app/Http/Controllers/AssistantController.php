<?php

namespace App\Http\Controllers;

use OpenAI\Laravel\Facades\OpenAI;

class AssistantController extends Controller
{
public function sendMessage($inMessage)
{
    // Start the thread and include the webhook callback URL
    $response = OpenAI::threads()->runs()->create(
        threadId: session('threadID'), 
        parameters: [
            'assistant_id' => config('openai.assistant'),
            'callback_url' => route('webhook.openai'), // Full URL of your webhook
        ],
    );

    return $response['status']; // Typically "queued" at this point
}
}

