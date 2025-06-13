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
        context later when composing their review.

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
          - Address: $place->formatted_address
          - Category: $bizCategory
          - Customer frequency: $bizFrequency
          - Google business type: {$place->types()[0]}
          - Google editorial summary: $description
          - Current Google ratings total: $place->rating
        DATA;
    }

        /**
     * @param $inAnswers
     * @return string
     */
    public function makeReviewPrompt($inAnswers): string
    {
        if (session('rating')[0] >= 4.5) {
            $replyInstructions = <<<INSTRUCTIONS
            - Goal: Express sincere appreciation, reinforce key strengths, and encourage repeat business.
            - Highlight positive aspects the customer mentioned.
            - Reinforce your commitment to excellence.
            - Invite them back warmly and encourage ongoing engagement.
INSTRUCTIONS;
        } else {
            $replyInstructions = <<<INSTRUCTIONS
            - Goal: Show appreciation while professionally addressing any concerns.
            - Acknowledge what they liked.
            - Address any issues they raised and offer a solution or improvement where applicable.
            - Keep the tone positive and forward-looking.
INSTRUCTIONS;
        }
        return <<<PROMPT
            Now, use the following responses from the customer to craft a well-structured, natural-sounding review:
            and a business reply using the following information:
                — **Question 1 Response: "$inAnswers[0]"
                - **Question 2 Response: "$inAnswers[1]"
                - **Question 3 Response: ""$inAnswers[2]"
                - **Question 4 Response: ""$inAnswers[3]"
                - **Question 5 Response: ""$inAnswers[4]}"
                - **Question 6 Response: ""$inAnswers[5]"

             ### Review Instructions:
            - **Use the provided customer responses and business details to create a detailed review.**
            - **Make the review sound human and natural, NOT like a generic report.**
            - **Incorporate the customer's favorite aspect and overall experience to emphasize what makes the business stand out.**
            - **If the rating is lower than 4 stars, acknowledge concerns professionally.**
            - **Match the tone of the customer based on their answers.**
            - **Focus on clarity, keeping the review concise and impactful.**
            - **Conclude with a strong closing statement that fits the customer's sentiment.**

            - Then write a thoughtful business reply thanking them for that review.

            ### Business Reply Instructions:
            - Always thank the reviewer by name.
            - Reference specific positives they mentioned to show appreciation.
            $replyInstructions

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
    }/**/
}
