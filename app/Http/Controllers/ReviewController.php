<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Traits\AIReview;
use Exception;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use AIReview;

    private const string MESSAGE_TYPE_FINAL = 'final';
    private const string ERROR_PAGE_VIEW = 'pages.error';
    private const string FINISH_PAGE_VIEW = 'pages.finish';

    public function composeReview()
    {
        try {
            // Step 1: Retrieve the review record
            $reviewCollection = $this->retrieveReview(session('reviewID'));
            ray($reviewCollection);
            // Step 2: Deserialize the answers
            $answers = $this->deserializeAnswers($reviewCollection->answers, session('reviewID'));
            ray($answers);
            // Step 3: Generate the review prompt
            $msg = $this->makeReviewPrompt($answers);
            if (empty($msg)) {
                throw new Exception("Failed to generate review prompt for review ID: " . session('reviewID'));
            }
            // Step 4: Send the prompt to the assistant and get the response
            $review = $this->createMessage($msg, self::MESSAGE_TYPE_FINAL);
            if (!$review) {
                throw new Exception("Failed to generate the review from the assistant.");
            }
            // Step 5: Update review record in the database
            $this->updateReview($reviewCollection, $review);
            // Step 6: Clean up the review for the final display
            $finalReview = str_replace('"', '', $review);
            ray($finalReview);
            // Step 7: Return the page view
            return view(self::FINISH_PAGE_VIEW, ['review' => $finalReview]);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error composing review: ' . $e->getMessage());
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
    private function deserializeAnswers(string $serializedAnswers, string $reviewID): array
    {
        $answers = unserialize($serializedAnswers);
        if ($answers === false) {
            throw new Exception("Failed to unserialize answers for review ID: $reviewID");
        }
        return $answers;
    }

    /**
     * @throws Exception
     */
    private function updateReview(Review $reviewCollection, string $review): void
    {
        $updateSuccess = $reviewCollection->update(['review' => $review, 'status' => Review::COMPLETED]);
        if (!$updateSuccess) {
            throw new Exception("Failed to update the review record in the database.");
        }
    }
}
