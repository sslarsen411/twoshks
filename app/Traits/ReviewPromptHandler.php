<?php

namespace App\Traits;

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
        $bizCategory = session('location.type');
        /** @TODO add a description to the user table */
        $description = 'None';
        $place = $this->getPlaces($placeId);
        /** @noinspection PhpUndefinedFieldInspection */
        $currentReviewsArray = $this->formatReviews(reviews: $place->reviews);
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

            "reviews": $currentReviewsArray
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

        // ray($inAnswers);
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
    }
}
