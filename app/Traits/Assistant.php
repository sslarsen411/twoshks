<?php
/**
 * @author Scott Larsen <scott@scottlarsen.net>
 */

namespace App\Traits;

use App\Models\Customer;
use App\Models\Review;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

trait Assistant {
    use GooglePlaces;

    /**
     * @param  array  $parameters
     * @return string
     */
    public function setThread(array $parameters = []): string
    {
        //    ray($parameters);
        $thread = OpenAI::threads()->create($parameters);
        return $thread->id;
    }

    /**
     * @param  Customer  $customer
     * @param  string  $status
     * @return array|Review
     */
    public function initReview(Customer $customer, string $status = 'Started'): array|Review
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
            $prompt = $this->createInitialPrompt(session('location.PID'));
            //           ray($prompt);
            $msg = $this->createMessage($prompt, 'first');
            if ($msg) {
                LOG::info('Review:'.$newReview->id.' Initial instructions successfully sent');
            } else {
                Log::error('There was a problem sending the initial instructions');
            }
            //return $newReview;
        } catch (QueryException  $e) {
            Log::error($e->errorInfo);
        }
        return $newReview;
    }

    /**
     * @param $inPID
     * @return string
     */
    public function createInitialPrompt($inPID): string
    {
        $customer_fname = session('cust.first_name');
        $customers_overall_rating = session('rating')[0];
        $place = $this->getPlaces($inPID);
        $desc = 'No description given';
        if (session('desc')) {
            $desc = session('desc');
        }
        $initPrompt = <<<PROMPT
        {
        "context": "A customer wants to initialize a review for a business. A prompt to compose the review will follow later.",
        "instructions":"Output this text: review initialized",
        "customer": {
            "name": "$customer_fname",
            "overall_rating": $customers_overall_rating
        },
        "business": {
            "name": "$place->name",
            "address": "$place->formatted_address",
            "type": "{$place->types[0]}",
            "average_rating": $place->rating,
            "description": "$desc",
            "total_reviews": $place->user_ratings_total,
            "reviews": [
    PROMPT;
        if ($place->reviews) {
            foreach ($place->reviews as $review) {
                if (strlen($review['text']) > 0) {
                    $initPrompt .= '"'.$review['text'].'",';
                }
            }
            $initPrompt .= rtrim($initPrompt, ',');
        } else {
            $initPrompt .= 'No reviews';
        }
        $initPrompt .= <<<PROMPT
                ]
            }
        }
    PROMPT;
        return $initPrompt;
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
                'role' => 'user',
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
    public function getThreadResult($runId, int $timeout = 30, int $interval = 2): string
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

    /**
     * @param $inAnswers
     * @return string
     */
    public function makeReviewPrompt($inAnswers): string
    {
        return <<<PROMPT
            You are an expert at crafting engaging, concise, and compelling reviews for Google Business Profiles. Use the provided business details,
            overall customer rating, and customer feedback to create a cohesive review.

              ### Customer Feedback:

              - Why they rated this way: "$inAnswers[0]"
              - Staff experience: "$inAnswers[1]"
              - Favorite aspect: "$inAnswers[2]"
              - Suggested improvement: "$inAnswers[3]"
              - Likelihood to recommend: "$inAnswers[4]"
              - Additional comments: "$inAnswers[5]"

              ### Instructions:
                1. Use the provided customer feedback and business details to create a detailed review.
                2. Begin each review with a unique, engaging introduction that reflects the customer's sentiment. Examples:
                  -  "My recent visit to [Business Name] was truly delightful!"
                  -  "I decided to try out [Business Name] and was pleasantly surprised by the experience."
                  -  "At [Business Name], I found much more than I expected!"
                  -  "Here’s my latest experience at [Business Name]."
                  -  "My review of [Business name]"
                  - "I found [Business Name] to be [overall_rating] because"
                3. Incorporate the customer's favorite aspect and overall experience to emphasize what makes the business stand out.
                4. Acknowledge the suggested improvements constructively while maintaining a positive tone. Keep this section brief to focus on the strengths
                5. End with an encouraging statement that aligns with the customer's likelihood to recommend. Use this opportunity to reaffirm the business's strengths and leave a lasting impression.
                6. Maintain Tone and Style:
                   - Friendly, conversational, and professional.
                   - Match the tone of the customer based on their answers.
                   -  Focus on clarity, keeping the review concise and impactful.
                7. Output Requirements:
                    - Generate the review in plain text.
                    - Incorporate the provided answers seamlessly while ensuring the review flows naturally.

              ### Example Review:
              "My review of [Business Name]. Overall I had a great experience! The staff were incredibly knowledgeable and helpful,
              answering all my questions and making my visit enjoyable. One of my favorite aspects was [Customer's Favorite Aspect], which truly stood out.

              While [Suggested Improvement] could be addressed, it didn't detract much from the overall experience. With their excellent service and
              [Business reviews 1], I highly recommend [Business Name] to anyone looking for [Business reviews 2].
              I'll definitely be returning soon!"

        PROMPT;
    }
}
