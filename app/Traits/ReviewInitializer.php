<?php

namespace App\Traits;

use App\Models\Customer;
use App\Models\Review;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait ReviewInitializer {
    use ReviewPromptHandler, AIReview;

    /**
     * @param  Customer  $customer
     * @param  string  $status
     * @return array|Review|null
     */
    public function initReview(Customer $customer, string $status = Review::STARTED): array|Review|null
    {
        $newReview = [];
        try {
            $newReview = Review::create([
                'users_id' => $customer->users_id,
                'customer_id' => $customer->id,
                'location_id' => $customer->location_id,
                'rate' => session('rating')[0],
                'status' => $status,
            ]);


            if (session('rating')[0] > session('location.min_rate')) {
                $prompt = $this->createInitialPrompt(session('location.PID'));
                $this->logMessageStatus(
                    $newReview->id,
                    $this->createMessage($prompt, self::PROMPT_TYPE_FIRST)
                );
            }
        } catch (QueryException  $e) {
            Log::error($e->errorInfo);
        }
        return $newReview;
    }
}
