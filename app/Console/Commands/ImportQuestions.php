<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Question;

class ImportQuestions extends Command
{
    protected $signature = 'questions:import {path} {--dry-run}';

    protected $description = 'Import questions from a JSON or CSV question bank file';

    public function handle()
    {
        $path = $this->argument('path');
        $dry = $this->option('dry-run');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return 1;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $rows = [];

        if (in_array($ext, ['json'])) {
            $content = file_get_contents($path);
            $data = json_decode($content, true);
            if (!is_array($data)) {
                $this->error('Invalid JSON file');
                return 1;
            }
            $rows = $data;
        } elseif (in_array($ext, ['csv'])) {
            if (($handle = fopen($path, 'r')) !== false) {
                $headers = null;
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    if (!$headers) {
                        $headers = $data;
                        continue;
                    }
                    $rows[] = array_combine($headers, $data);
                }
                fclose($handle);
            }
        } else {
            $this->error('Unsupported file type. Provide JSON or CSV.');
            return 1;
        }

        $this->info('Loaded ' . count($rows) . ' questions from file');

        $inserted = 0;
        $updated = 0;
        foreach ($rows as $i => $r) {
            $text = trim($r['question_text'] ?? ($r['question'] ?? ''));
            if (empty($text)) continue;

            $attributes = [
                'module_id' => isset($r['module_id']) ? intval($r['module_id']) : null,
                'unit_id' => isset($r['unit_id']) ? intval($r['unit_id']) : null,
                'topic' => isset($r['topic']) ? trim($r['topic']) : null,
                'question_type' => isset($r['question_type']) ? trim($r['question_type']) : null,
                'question_text' => $text,
                'options' => isset($r['options']) ? $this->parseJsonIfPossible($r['options']) : null,
                'answer' => isset($r['answer']) ? $r['answer'] : null,
                'marks' => isset($r['marks']) ? floatval($r['marks']) : 0,
                'difficulty' => isset($r['difficulty']) ? $r['difficulty'] : null,
                'tags' => isset($r['tags']) ? $this->splitTags($r['tags']) : null,
                'attachments' => null,
                'created_by' => null,
            ];

            if ($dry) {
                $this->line("[DRY] Would import: " . Str::limit($text, 80));
                continue;
            }

            $existing = Question::where('question_text', $text)
                ->where('unit_id', $attributes['unit_id'])
                ->first();

            if ($existing) {
                $existing->update($attributes);
                $updated++;
            } else {
                Question::create($attributes);
                $inserted++;
            }
        }

        $this->info("Inserted: {$inserted}, Updated: {$updated}");
        return 0;
    }

    private function parseJsonIfPossible($value)
    {
        if (is_array($value)) return $value;
        $trim = trim($value);
        if ($trim === '') return null;
        $decoded = json_decode($trim, true);
        if (json_last_error() === JSON_ERROR_NONE) return $decoded;
        // try semicolon or pipe separated
        if (strpos($trim, '|') !== false) return array_map('trim', explode('|', $trim));
        if (strpos($trim, ';') !== false) return array_map('trim', explode(';', $trim));
        return [$trim];
    }

    private function splitTags($value)
    {
        if (is_array($value)) return $value;
        $trim = trim($value);
        if ($trim === '') return null;
        return array_filter(array_map('trim', preg_split('/[,;|]+/', $trim)));
    }
}
