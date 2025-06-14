<?php

namespace App\Traits;

/**
 *
 */
trait ReviewPromptHandler {
    use GooglePlaces;

    /**
     * @property array place
     * @property array currentReviewsArray
     */
    public function createInitialPrompt(string $placeId): string
    {
        $customerName = session('cust.first_name');
        $customerRating = session('rating')[0];
        $place = $this->getPlaces($placeId);
        /** @noinspection PhpUndefinedFieldInspection */
        $currentReviews = $this->formatReviews(reviews: $place->reviews);
        $placeDetails = $this->formatPlaceDetails($place);

        return <<<PROMPT
        {
        The following is information about the business this reviewer is rating and the reviewer. You’ll use this
        context later when composing their review and a reply.

        Reviewer information:
        - Name: $customerName
        - Overall rating: $customerRating stars

        Business summary:
        $placeDetails

        The most relevant reviews currently posted on Google
        $currentReviews

        Next, the reviewer will answer a series of feedback questions. Once you receive their answers, you’ll write a short, polished review that:
        - Reflects their voice and tone
        - Matches the sentiment of their rating
        - Incorporates the experience they describe
        - Helps other customers evaluating this business

        Wait for the reviewer’s answers before writing the review.
        }
        PROMPT;
    }

    /**
     * Extracts reviews from an array and formats them as text
     * @param  array|null  $reviews
     * @return string
     */
    private function formatReviews(?array $reviews): string
    {
        if (empty($reviews)) {
            return 'No reviews';
        }
        $reviewStr = '';
        $i = 1;
        foreach ($reviews as $review) {
            $reviewStr .= "$i: {$review['rating']} stars - {$review['text']}  - date posted: {$review['relative_time_description']}\n";
            $i++;
        }
        return $reviewStr;
    }

    /**
     * Extracts data from a Google Places object and formats them as text
     * @param  object  $place
     * @return string
     */
    private function formatPlaceDetails(object $place): string
    {
        $bizCategory = session('location.type');
        $bizFrequency = session('location.engagement_frequency');
        $description = $place->editorial_summary ?: "none given";
        return <<<DATA
          - Business name: $place->name
          - Category: $bizCategory
          - Customer frequency: $bizFrequency
          - Google business type: {$place->types()[0]}
          - Google editorial summary: $description
          - Current Google ratings: $place->rating
          - Current Google ratings total: $place->user_ratings_total
        DATA;
    }

    /**
     * Create a prompt to have the assistant validate a user's answer
     * @return string
     */
    public function getValidationPrompt(): string
    {
        return <<<PROMPT
            You are a helpful assistant evaluating customer responses to review questions. Your job is to decide if the answer is
            clear and specific enough to be turned into a helpful review.

            You will be given:
            - The review question the customer is responding to
            - The customer’s answer

            If the answer is meaningful and specific, return:
            {
              "status": "okay"
            }

            If the answer is vague, too short, or unclear, return:
            {
              "status": "not_okay",
              "message": "A short, friendly prompt encouraging the customer to expand their answer, tailored to the question"
            }

            Make your message sound natural and supportive. Offer gentle suggestions or ask for examples to help them
            elaborate. Do not use the phrase “vague” or “incomplete” in the message. Keep the tone positive and conversational.

            **Important:** Respond ONLY with a valid JSON object. No extra explanation.
PROMPT;
    }

    /**
     * @return string
     */
    public function makeReviewPrompt(): string
    {
        $feedback = $this->pairQuestionsWithAnswers();

        return <<<PROMPT
        The reviewer has now answered the feedback questions. Use their answers—along with the previously provided business information and tone—to write a short, helpful review in the reviewer's voice.

        Here is the feedback:

        $feedback

        Instructions for writing the review:

        The review should sound like something a real person would post on a public platform—helpful, authentic, and easy to read.

          ### Output Instructions:
            - Return both the customer review and the business reply in a valid JSON object using this format:
            {
              "review": "...",
              "reply": "..."
            }

            Formatting Rules:
            - The "review" field must be plain text with no HTML or markdown.
            - The "reply" field must use clean, basic HTML such as <p>, <strong>, and <br> for formatting. Do not include styling, scripts, or external links.

        PROMPT;
    }

    /**
     * Combine the question set with the answer set for the makeReviewPrompt function
     * @return string
     */
    private function pairQuestionsWithAnswers(): string
    {
        $questions = session('question_set', []);
        $answers = session('answers', []); // Or wherever you're storing the reviewer's inputs

        $paired = [];

        foreach ($questions as $index => $question) {
            $paired[] = [
                'question' => $question,
                'answer' => $answers[$index] ?? '',
            ];
        }
        return $this->formatForPrompt($paired);
    }

    /**
     * Format the question/answer pairs as a string for the makeReviewPrompt function
     * @param  array  $qaPairs
     * @return string
     */
    function formatForPrompt(array $qaPairs): string
    {
        $output = "Here is the feedback:\n\n";
        foreach ($qaPairs as $pair) {
            $output .= "Q: {$pair['question']}\n";
            $output .= "A: {$pair['answer']}\n\n";
        }
        return trim($output);
    }
}
