<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Traits\AIReview;
use Exception;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller {
    use AIReview;

    private const string MESSAGE_TYPE_FINAL = 'final';
    private const string ERROR_PAGE_VIEW = 'pages.error';
    private const string FINISH_PAGE_VIEW = 'pages.finish';

    /**
     * Have the assistant write a review
     * @return object
     */
    public function composeReview(): object
    {
        try {
            // Step 1: Retrieve the review record
            $reviewCollection = $this->retrieveReview(session('reviewID'));
            // Step 2: Generate the review prompt
            $msg = $this->makeReviewPrompt();
            if (empty($msg)) {
                throw new Exception("Failed to generate review prompt for review ID: ".session('reviewID'));
            }
            // Step 3: Have the assistant write a review/reply and handle the response
            $generatedReview = $this->createUserMessage($msg, self::MESSAGE_TYPE_FINAL);
            if (!$generatedReview) {
                throw new Exception("Failed to generate the review from the assistant.");
            }
            $parsed = json_decode($generatedReview, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Assistant response is not valid JSON: ".json_last_error_msg());
            }
            $customerReview = $parsed['review'] ?? null;
            $businessReply = $parsed['reply'] ?? null;
            if (!$customerReview || !$businessReply) {
                throw new Exception("Missing review or reply in assistant response.");
            }
            // Step 4: Update review record in the database
            $this->updateReview($reviewCollection, $customerReview, $businessReply);
            // Step 5: Return the page view
            return view(self::FINISH_PAGE_VIEW, ['review' => $customerReview, 'reply' => $businessReply]);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error composing review: '.$e->getMessage());
            // Optionally, return an error view or response
            return view('pages.error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * @throws Exception
     */
    private function retrieveReview(string $reviewID): Review
    {
        $reviewCollection = Review::find($reviewID);
        if (!$reviewCollection) {
            throw new Exception("Review not found with ID: $reviewID");
        }
        return $reviewCollection;
    }

    /**
     * @throws Exception
     */
    private function updateReview(Review $reviewCollection, string $inReview, string $inReply): void
    {
        $updateSuccess = $reviewCollection->update([
            'review' => $inReview, 'reply' => $inReply, 'status' => Review::COMPLETED
        ]);
        if (!$updateSuccess) {
            throw new Exception("Failed to update the review record in the database.");
        }
    }
}
