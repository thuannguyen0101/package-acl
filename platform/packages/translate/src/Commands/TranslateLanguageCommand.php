<?php

namespace Workable\Translate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Workable\Translate\Services\TranslationService;

class TranslateLanguageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:translate
                            {sourceFile? : Path to the source file (optional)}
                            {--languages= : Comma-separated list of target languages (optional)}';

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
        $sourceFile         = $this->argument('sourceFile')
            ?? config('translate.languages.translation_files');

        if (!is_array($sourceFile)) {
            $sourceFile = [$sourceFile];
        }

        $targetLanguages = $this->option('languages')
            ? explode(',', $this->option('languages'))
            : config('translate.languages.target_languages');

        foreach ($sourceFile as $file) {
            if (!file_exists($file)) {
                $this->error("Source file does not exist: $file");
                return 1;
            }
        }

        $translator = new GoogleTranslate();
        $translator->setSource(config('translate.languages.source_language'));

        foreach ($sourceFile as $file) {
            $this->info("Processing file: $file");

            $messages = include $file;

            if (!is_array($messages)) {
                $this->error("File $file must return an array.");
                return 1;
            }
            $this->saveTranslationsToDatabase($messages, config('translate.languages.source_language'));
            foreach ($targetLanguages as $language) {
                $this->info("Translating into: $language");

                $translatedMessages = $this->translateMessages($translator, $messages, $language);
                $this->saveTranslationsToDatabase($translatedMessages, $language);

                $this->info("Translations for $language saved to database.");
            }


        }
        $translationService->reload();
        $this->info("All translations completed.");
        return 0;
    }

    /**
     * Translate messages into target language.
     *
     * @param GoogleTranslate $translator
     * @param array $messages
     * @param string $targetLanguage
     * @return array
     */
    private function translateMessages(GoogleTranslate $translator, array $messages, string $targetLanguage): array
    {
        $translatedMessages = [];
        $translator->setTarget($targetLanguage);

        foreach ($messages as $key => $message) {
            try {
                $translatedMessages[$key] = $translator->translate($message);
            } catch (\Exception $e) {
                $this->error("Error translating key '$key': " . $e->getMessage());
                $translatedMessages[$key] = $message;
            }
        }

        return $translatedMessages;
    }

    /**
     * Save translated messages to the database.
     *
     * @param array $messages
     * @param string $languageCode
     */
    private function saveTranslationsToDatabase(array $messages, string $languageCode): void
    {
        foreach ($messages as $key => $translation) {
            DB::table('translates')->updateOrInsert(
                [
                    'key'           => $key,
                    'language_code' => $languageCode,
                ],
                [
                    'translation' => $translation,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}
