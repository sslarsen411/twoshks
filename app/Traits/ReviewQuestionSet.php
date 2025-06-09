<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use NumberFormatter;
use RuntimeException;

trait ReviewQuestionSet {
    /**
     * Reads a JSON file containing questions and prepares them for display.
     *
     * @param $inType
     * @param $inFreq
     * @return bool
     *
     */
    public function prepQuestions($inType, $inFreq): bool
    {
        try {
            $jsonPath = public_path('questions.json');
            if (!file_exists($jsonPath)) {
                throw new RuntimeException('Questions file not found');
            }

            $json = file_get_contents($jsonPath);
            if ($json === false) {
                throw new RuntimeException('Failed to read questions file');
            }

            $questArr = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid JSON format: '.json_last_error_msg());
            }

            //    $type = session('location.type');
            //    $freq = session('location.customer_frequency');

            if (!in_array($inType, ['retail', 'service'])) {
                throw new InvalidArgumentException('Invalid location type');
            }

            if ($inType === 'service' && !$inFreq) {
                throw new InvalidArgumentException('Customer frequency required for service type');
            }
            /** @var string $inType $InFreq */
            $specific = match ($inType) {
                'retail' => $questArr[$inType] ?? [],
                default => $questArr[$inType][$inFreq] ?? [],
            };

            if (!isset($questArr['initial'], $questArr['general'])) {
                throw new RuntimeException('Missing required question sections');
            }

            $questions = array_merge($questArr['initial'], $specific, $questArr['general']);
            session()->put('questions', $questions);
            $n = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            session()->put('question_num', count($questions));
            session()->put('question_num_txt', $n->format(count($questions)));
            return true;
        } catch (Exception $e) {
            Log::error('Error preparing questions: '.$e->getMessage());
            //throw $e;
            return false;
        }
    }

    /**
     * Adds the company's name and review's rating to the question set.
     *
     * @param $inRate
     * @param $inBiz
     * @return array
     */
    public function initializeFirstQuestion($inRate, $inBiz): array
    {
        return str_replace(array("NUM_STAR", "COMPANY"), array($inRate, $inBiz), session('questions'));
    }
}
