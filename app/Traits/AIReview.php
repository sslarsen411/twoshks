<?php
/**
 * @author Scott Larsen <scott@scottlarsenai.com>
 */

namespace App\Traits;

use App\Models\Customer;
use App\Models\Review;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

trait AIReview
{
    use GooglePlaces;

    private const string ROLE_USER = 'user';
    private const string PROMPT_TYPE_FIRST = 'first';
    private const string PROMPT_TYPE_FINAL = 'final';
    private const int POLL_INTERVAL = 2;
    private const int TIMEOUT = 30;

    /**
     * @param array $parameters
     * @return string
     */
    public function setThread(array $parameters = []): string
    {
        $thread = OpenAI::threads()->create($parameters);
        return $thread->id;
    }

    /**
     * @param Customer $customer
     * @param string $status
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
            if ($this->rating > session('location.min_rate')) {
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

    /**
     * @property array place
     * @property array reviews
     */
    public function createInitialPrompt(string $placeId): string
    {
        $customerName = session('cust.first_name');
        $customerRating = session('rating')[0];
        $bizCategory = session('location.category');
        /** @TODO add a description to the user table */
        $description = 'None';
        $place = $this->getPlaces($placeId);
        /** @noinspection PhpUndefinedFieldInspection */
        $reviews = $this->formatReviews(reviews: $place->reviews);
        $placeDetails = $this->formatPlaceDetails($place, $description);
        /** @var string $bizType */
        return <<<PROMPT
        {
            "context": "A customer named $customerName is starting a review for a business. Your role is to assist them
            in providing thoughtful, engaging answers to the review questions through chat.
            The review question are the subset of questions referenced by the category key.
            You will help guide them, offer suggestions, and ensure they feel supported throughout the process.
            A prompt to generate the final review will follow later. Below are key details:",

            "instructions": "Acknowledge the customer by name and confirm that you're ready to assist. If they have
            any questions or need help, respond as instructed above.",

            "customer": {
                "name": "$customerName",
                "overall_rating": $customerRating
            },

            "business": {
                "category": "$bizCategory",
                "details": $placeDetails
            },

            "reviews": $reviews
        }
        PROMPT;
    }

    private function formatReviews(?array $reviews): string
    {
        if (empty($reviews)) {
            return json_encode(['No reviews']);
        }
        //   ray($reviews);
        $formattedReviews = array_filter(
            array_map(fn($review) => $review['text'] ?? '', $reviews),
            fn($text) => strlen($text) > 0
        );
        //   ray($formattedReviews);
        return json_encode(array_values($formattedReviews));
    }

    private function formatPlaceDetails(object $place, string $description): string
    {
        return <<<JSON
        {
            "BusinessName": "$place->name",
            "address": "$place->formatted_address",
            "type": "{$place->types[0]}",
            "average_rating": $place->rating,
            "description": "$description",
            "total_reviews": $place->user_ratings_total
        }
        JSON;
    }

    private function logMessageStatus(string $reviewId, string $messageStatus): void
    {
        if ($messageStatus === json_encode(['status' => 'success', 'message' => 'review initialized'])) {
            Log::info("Review:$reviewId Initial instructions successfully sent");
        } else {
            Log::error('There was a problem sending the initial instructions');
        }
    }

    /**
     * @param $inMessage
     * @param string $reviewPromptType
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
            return "Error creating message: " . $e->getMessage();
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
            return "Error sending message: " . $e->getMessage();
        }
    }

    /**
     * @param $runId
     * @param int $timeout
     * @param int $interval
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

    /**
     * @param $inAnswers
     * @return string
     */
    public function makeReviewPrompt($inAnswers): string
    {
        // ray($inAnswers);
        return <<<PROMPT
            Now, use the following responses from the customer to craft a well-structured, natural-sounding review:

                - **Question 1 Response: "$inAnswers[0]"
                - **Question 2 Response: "$inAnswers[1]"
                - **Question 3 Response: ""$inAnswers[2]"
                - **Question 4 Response: ""$inAnswers[3]"
                - **Question 5 Response: ""$inAnswers[4]}"
                - **Question 6 Response: ""$inAnswers[5]"

             ### Instructions:
            - **Use the provided customer responses and business details to create a detailed review.**
            - **Make the review sound human and natural, NOT like a generic report.**
            - **Incorporate the customer's favorite aspect and overall experience to emphasize what makes the business stand out.**
            - **If the rating is lower than 4 stars, acknowledge concerns professionally.**
            - **Match the tone of the customer based on their answers.**
            - **Focus on clarity, keeping the review concise and impactful.**
            - **Conclude with a strong closing statement that fits the customer's sentiment.**

            Write the review in **plain text** below:
        PROMPT;
    }
}
