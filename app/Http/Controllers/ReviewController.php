<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Traits\Assistant;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller{
    use Assistant; 

    public function composeReview(){ 
    try {
        // Retrieve the review from the database
        $reviewCollection = Review::find(session('reviewID'));
        if (!$reviewCollection) {
            throw new \Exception("Review not found with ID: " . session('reviewID'));
        }
        // Deserialize the answers
        $answers = @unserialize($reviewCollection->answers);
        if ($answers === false) {
            throw new \Exception("Failed to unserialize answers for review ID: " . session('reviewID'));
        }
        // Generate the review prompt
        $msg = $this->makeReviewPrompt($answers);
        if (empty($msg)) {
            throw new \Exception("Failed to generate review prompt for review ID: " . session('reviewID'));
        }
        // Send the prompt to the assistant and get the response
        $review = $this->createMessage($msg, 'final');
        if (!$review) {
            throw new \Exception("Failed to generate the review from the assistant.");
        }
        // Update the review record in the database
        $updateSuccess = $reviewCollection->update(['review' => $review, 'status' => 'Completed']);
        if (!$updateSuccess) {
            throw new \Exception("Failed to update the review record in the database.");
        }
        // Clean up the review for the final display
        $finalReview = str_replace('"', '', $review);
        // Return the review completion page
        return view('pages.finish', ['review' => $finalReview]);
    } catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error('Error composing review: ' . $e->getMessage());
        // Optionally, return an error view or response
        return view('pages.error', ['error' => $e->getMessage()]);
    }
  }
}
