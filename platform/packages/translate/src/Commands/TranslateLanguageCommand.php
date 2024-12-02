<?php

namespace Workable\Translate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Workable\Translate\Models\Translate;
use Workable\Translate\Services\TranslationService;

class TranslateLanguageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:translate
                            {--path= : Path to the source file (optional)}
                            {--languages= : Comma-separated list of target languages (optional)}
                            {--update : Use updateOrInsert to update or create records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a language file into multiple languages and save to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $translationService = app(TranslationService::class);
        $sourceFile         = $this->option('path')
            ?? config('translate.languages.translation_files');

        if (!is_array($sourceFile)) {
            $sourceFile = [$sourceFile];
        }

        $targetLanguages = $this->option('languages')
            ? explode(',', $this->option('languages'))
            : config('translate.languages.target_languages');

        $useUpdateOrInsert = $this->option('update');

        foreach ($sourceFile as $file) {
            if (!file_exists($file)) {
                $this->error("Source file not found: $file");
                return 1;
            }
        }

        $defaultLanguage = config('translate.languages.source_language');
        $translator      = new GoogleTranslate();
        $translator->setSource($defaultLanguage);

        foreach ($sourceFile as $file) {
            $this->info("Processing file: $file");

            $messages = include $file;

            if (!is_array($messages)) {
                $this->error("File $file must return an array.");
                return 1;
            }

            if (empty($messages)) {
                continue;
            }
            $translatedMessages = $this->translateMessages($translator, $messages, $targetLanguages);

            if ($this->saveTranslationsToDatabase($translatedMessages, $useUpdateOrInsert)) {
                $this->info("Translations have been saved successfully.");
            } else {
                $this->error("An error occurred while saving translations.");
            }
        }

        $translationService->reload();
        $this->info("Translation process completed.");

        return 0;
    }

    /**
     * Translate messages into multiple target languages.
     *
     * @param GoogleTranslate $translator
     * @param array $messages
     * @param array $targetLanguages
     * @return array
     */
    private function translateMessages(GoogleTranslate $translator, array $messages, array $targetLanguages): array
    {
        $translatedMessages = [];
        $defaultLanguage    = config('translate.languages.source_language');
        foreach ($messages as $key => $message) {
            $translations["translation_" . $defaultLanguage] = $message;
            foreach ($targetLanguages as $language) {
                try {
                    $translator->setTarget($language);
                    $translations['translation_' . $language] = $translator->translate($message);
                } catch (\Exception $e) {
                    $this->error("Error translating key '$key' to language '$language': " . $e->getMessage());
                    $translations[$language] = $message; // Fallback to original message
                }
            }
            $translatedMessages[$key] = $translations;
        }
        return $translatedMessages;
    }

    /**
     * Save translated messages to the database.
     *
     * @param array $messages
     * @param bool $useUpdateOrInsert
     * @return bool
     */
    private function saveTranslationsToDatabase(array $messages, bool $useUpdateOrInsert): bool
    {
        if (!$useUpdateOrInsert) {
            $listKey      = array_keys($messages);
            $listKeyExist = Translate::query()->select('key')->whereIn('key', $listKey)->pluck('key')->toArray();
            $commonItems  = array_intersect($listKey, $listKeyExist);

            if (count($commonItems)) {
                $this->error("Keys: " . implode(', ', $commonItems) . " already exists and was not overwritten.");
                return false;
            }
        }

        $itemsInsert = [];

        foreach ($messages as $key => $translations) {
            $data = array_merge([
                'key'        => $key,
                'updated_at' => now(),
            ], $translations);
            if ($useUpdateOrInsert) {
                DB::table('translates')->updateOrInsert(
                    ['key' => $key],
                    array_merge($data, ['created_at' => now()])
                );
            } else {
                $data['created_at'] = now();
                $itemsInsert[]      = $data;
            }
        }

        if (!$useUpdateOrInsert) {
            DB::table('translates')->insert($itemsInsert);
        }

        return true;
    }
}
