<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CodeProcessingService
{
    /**
     * Save code as a file.
     */
    public function saveCodeAsFile(string $code, string $language, string $taskId, string $userId, string $contestId, ?string $submissionNumber): string
    {
        $extension = $this->getExtensionFromLanguage($language);
        $submissionNumber = $submissionNumber ?: Str::random(8);
        $filename = sprintf('task_%s_%s_%s.%s', $taskId, $userId, $submissionNumber, $extension);

        // Save the code to a file
        Storage::disk('data')->put("contests/{$contestId}/task_{$taskId}/$filename", $code);

        return $filename;
    }

    /**
     * Save an uploaded file and determine its language.
     */
    public function saveUploadedFile($file, string $taskId, string $userId, ?string $submissionNumber, ?string $language = null, $contestId): array
    {
        $extension = $file->getClientOriginalExtension() ?: $this->getExtensionFromLanguage($language);
        $submissionNumber = $submissionNumber ?: Str::random(8);
        $filename = sprintf('task_%s_%s_%s.%s', $taskId, $userId, $submissionNumber, $extension);

        $file->storeAs("contests/{$contestId}/task_{$taskId}", $filename, 'data');

        return [
            'filename' => $filename,
            'language' => $extension,
        ];
    }

    /**
     * Map programming languages to file extensions.
     */
    private function getExtensionFromLanguage(string $language): string
    {
        $mapping = [
            'php' => 'php',
            'html' => 'html',
            'python' => 'py',
            'javascript' => 'js',
            'c++' => 'cpp',
            'java' => 'java',
            // Add more mappings as needed
        ];

        return $mapping[strtolower($language)] ?? 'txt';
    }
}
