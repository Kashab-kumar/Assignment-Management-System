<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Unit;
use App\Models\Assignment;
use App\Models\Exam;
use App\Models\ExamQuestion;

class GradingService
{
    protected $apiKey;
    protected $useAI = true;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Grade assignment submission using hybrid approach
     */
    public function gradeAssignment($submission, $assignment, $unitOutlines = [])
    {
        // Try AI grading first if enabled
        if ($this->useAI && $this->apiKey) {
            try {
                $aiResult = $this->gradeWithAI($submission, $assignment, $unitOutlines);
                if ($aiResult) {
                    return $aiResult;
                }
            } catch (\Exception $e) {
                // Fallback to rule-based if AI fails
                \Log::error('AI grading failed: ' . $e->getMessage());
            }
        }

        // Fallback to rule-based grading
        return $this->gradeWithRules($submission, $assignment, $unitOutlines);
    }

    /**
     * Grade exam submission using hybrid approach
     */
    public function gradeExam($examAnswers, $exam, $unitOutlines = [])
    {
        $totalScore = 0;
        $feedback = [];

        foreach ($examAnswers as $answer) {
            $question = $answer->question;

            // Try AI grading for each question
            if ($this->useAI && $this->apiKey) {
                try {
                    $aiResult = $this->gradeExamQuestionWithAI($answer, $question, $unitOutlines);
                    if ($aiResult) {
                        $totalScore += $aiResult['score'];
                        $feedback[] = $aiResult['feedback'];
                        continue;
                    }
                } catch (\Exception $e) {
                    \Log::error('AI exam grading failed: ' . $e->getMessage());
                }
            }

            // Fallback to rule-based (exact match with answer key)
            $ruleResult = $this->gradeExamQuestionWithRules($answer, $question);
            $totalScore += $ruleResult['score'];
            $feedback[] = $ruleResult['feedback'];
        }

        return [
            'score' => $totalScore,
            'max_score' => $exam->max_score,
            'feedback' => implode("\n\n", $feedback),
            'method' => $this->useAI ? 'hybrid' : 'rule-based'
        ];
    }

    /**
     * AI-based grading for assignments
     */
    protected function gradeWithAI($submission, $assignment, $unitOutlines)
    {
        $context = $this->buildGradingContext($assignment, $unitOutlines);

        $prompt = "You are an educational grader. Grade the following student submission based on the unit outline criteria.\n\n" .
                  "Unit Outline Criteria:\n" . $context . "\n\n" .
                  "Assignment: " . $assignment->title . "\n" .
                  "Description: " . ($assignment->description ?? 'N/A') . "\n" .
                  "Max Score: " . $assignment->max_score . "\n\n" .
                  "Student Submission:\n" . $submission->content . "\n\n" .
                  "Provide a JSON response with:\n" .
                  "- score: integer (0 to max_score)\n" .
                  "- feedback: string (detailed feedback for the student)\n" .
                  "- strengths: array of strings (what the student did well)\n" .
                  "- improvements: array of strings (areas for improvement)";

        try {
            $response = Http::post($this->apiUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if (!$response->successful()) {
                \Log::error('Gemini API error: ' . $response->body());
                return null;
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $result = json_decode($content, true);

            return [
                'score' => $result['score'] ?? 0,
                'max_score' => $assignment->max_score,
                'feedback' => $result['feedback'] ?? '',
                'strengths' => $result['strengths'] ?? [],
                'improvements' => $result['improvements'] ?? [],
                'method' => 'ai'
            ];
        } catch (\Exception $e) {
            \Log::error('AI grading error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * AI-based grading for exam questions
     */
    protected function gradeExamQuestionWithAI($answer, $question, $unitOutlines)
    {
        $context = $this->buildGradingContext($question->exam, $unitOutlines);

        $prompt = "Grade the following student answer based on the unit outline and answer key.\n\n" .
                  "Unit Outline Context:\n" . $context . "\n\n" .
                  "Question: " . $question->question_text . "\n" .
                  "Answer Key: " . ($question->answer_key ?? 'N/A') . "\n" .
                  "Points: " . $question->points . "\n\n" .
                  "Student Answer: " . $answer->answer_text . "\n\n" .
                  "Provide a JSON response with:\n" .
                  "- score: integer (0 to points)\n" .
                  "- feedback: string (brief feedback)";

        try {
            $response = Http::post($this->apiUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if (!$response->successful()) {
                \Log::error('Gemini API error: ' . $response->body());
                return null;
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $result = json_decode($content, true);

            return [
                'score' => $result['score'] ?? 0,
                'feedback' => $result['feedback'] ?? ''
            ];
        } catch (\Exception $e) {
            \Log::error('AI exam grading error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Rule-based grading for assignments
     */
    protected function gradeWithRules($submission, $assignment, $unitOutlines)
    {
        $score = 0;
        $feedback = [];
        $submissionText = strtolower($submission->content);

        // Extract keywords from unit outlines
        $keywords = $this->extractKeywords($unitOutlines);

        // Count keyword matches
        $matchedKeywords = 0;
        foreach ($keywords as $keyword) {
            if (strpos($submissionText, strtolower($keyword)) !== false) {
                $matchedKeywords++;
            }
        }

        // Calculate score based on keyword coverage
        if (count($keywords) > 0) {
            $coverage = $matchedKeywords / count($keywords);
            $score = round($assignment->max_score * $coverage);
        }

        // Generate feedback
        $feedback[] = "Keyword coverage: " . $matchedKeywords . "/" . count($keywords);
        if ($matchedKeywords > 0) {
            $feedback[] = "Good coverage of unit outline topics.";
        } else {
            $feedback[] = "Consider including more topics from the unit outline.";
        }

        return [
            'score' => $score,
            'max_score' => $assignment->max_score,
            'feedback' => implode("\n", $feedback),
            'method' => 'rule-based'
        ];
    }

    /**
     * Rule-based grading for exam questions
     */
    protected function gradeExamQuestionWithRules($answer, $question)
    {
        $studentAnswer = trim(strtolower($answer->answer_text));
        $correctAnswer = trim(strtolower($question->answer_key ?? ''));

        $score = 0;
        $feedback = '';

        if ($correctAnswer !== '' && $studentAnswer === $correctAnswer) {
            $score = $question->points;
            $feedback = 'Correct answer.';
        } elseif ($correctAnswer !== '') {
            // Partial credit for similar answers
            $similarity = $this->calculateSimilarity($studentAnswer, $correctAnswer);
            if ($similarity > 0.7) {
                $score = round($question->points * 0.5);
                $feedback = 'Partially correct (50% credit).';
            } else {
                $feedback = 'Incorrect answer.';
            }
        } else {
            $feedback = 'No answer key provided for grading.';
        }

        return [
            'score' => $score,
            'feedback' => $feedback
        ];
    }

    /**
     * Build grading context from unit outlines
     */
    protected function buildGradingContext($item, $unitOutlines)
    {
        $context = '';

        foreach ($unitOutlines as $unit) {
            $context .= "Unit " . $unit->order . ": " . $unit->title . "\n";
            if ($unit->description) {
                $context .= "Description: " . $unit->description . "\n";
            }
            if ($unit->extracted_content) {
                $context .= "Content: " . substr($unit->extracted_content, 0, 500) . "...\n";
            }
            $context .= "\n";
        }

        return $context ?: 'No unit outline provided.';
    }

    /**
     * Extract keywords from unit outlines
     */
    protected function extractKeywords($unitOutlines)
    {
        $keywords = [];

        foreach ($unitOutlines as $unit) {
            // Extract from title
            $titleWords = preg_split('/[\s,]+/', $unit->title);
            $keywords = array_merge($keywords, array_filter($titleWords, function($word) {
                return strlen($word) > 3;
            }));

            // Extract from description
            if ($unit->description) {
                $descWords = preg_split('/[\s,]+/', $unit->description);
                $keywords = array_merge($keywords, array_filter($descWords, function($word) {
                    return strlen($word) > 3;
                }));
            }

            // Extract from extracted content
            if ($unit->extracted_content) {
                $contentWords = preg_split('/[\s,]+/', $unit->extracted_content);
                $keywords = array_merge($keywords, array_filter($contentWords, function($word) {
                    return strlen($word) > 3;
                }));
            }
        }

        return array_unique($keywords);
    }

    /**
     * Calculate similarity between two strings (simple Levenshtein-based)
     */
    protected function calculateSimilarity($str1, $str2)
    {
        $len1 = strlen($str1);
        $len2 = strlen($str2);

        if ($len1 === 0 || $len2 === 0) {
            return 0;
        }

        $distance = levenshtein($str1, $str2);
        $maxLen = max($len1, $len2);

        return 1 - ($distance / $maxLen);
    }

    /**
     * Enable or disable AI grading
     */
    public function setUseAI($useAI)
    {
        $this->useAI = $useAI;
        return $this;
    }
}
