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
                            {--update : Sử dụng updateOrInsert để cập nhật hoặc tạo mới}';

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
                $this->error("Tệp nguồn không tồn tại: $file");
                return 1;
            }
        }
        $defaultLanguage = config('translate.languages.source_language');
        $translator      = new GoogleTranslate();

        $translator->setSource($defaultLanguage);

        foreach ($sourceFile as $file) {
            $this->info("Đang xử lý tệp: $file");

            $messages = include $file;

            if (!is_array($messages)) {
                $this->error("Tệp $file phải trả về một mảng.");
                return 1;
            }
            if (empty($messages)) {
                continue;
            }
            if ($this->saveTranslationsToDatabase($messages, $defaultLanguage, $useUpdateOrInsert)) {
                $this->info("Ngôn ngữ $defaultLanguage đã được lưu vào cơ sở dữ liệu.");
            } else {
                $this->error("Tệp $file chứa key đã tồn tại.");
            }

            foreach ($targetLanguages as $language) {
                $this->info("Đang dịch sang: $language");
                $translatedMessages = $this->translateMessages($translator, $messages, $language);

                if ($this->saveTranslationsToDatabase($translatedMessages, $language, $useUpdateOrInsert)) {
                    $this->info("Dịch ngôn ngữ $language đã được lưu vào cơ sở dữ liệu.");
                } else {
                    $this->error("Tệp $file chứa key đã tồn tại.");
                }
            }
        }

        $translationService->reload();
        $this->info("Dịch thuật hoàn tất.");

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
                $this->error("Lỗi khi dịch khóa '$key': " . $e->getMessage());
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
    private function saveTranslationsToDatabase(array $messages, string $languageCode, bool $useUpdateOrInsert): bool
    {
        foreach ($messages as $key => $value) {
            unset($messages[$key]);
            $messages[$key . "_" . $languageCode] = $value;
        }

        if ($useUpdateOrInsert) {
            foreach ($messages as $key => $translation) {
                DB::table('translates')->updateOrInsert(
                    [
                        'key_language' => $key
                    ],
                    [
                        'translation' => $translation,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        } else {
            $dataInsert = [];
            $languages  = Translate::query()
                ->whereIn('key_language', array_keys($messages))
                ->pluck('key_language')->toArray();

            foreach ($messages as $key => $translation) {
                if (in_array($key, $languages)) {
                    $this->error("key:$key lang:$languageCode đã tồn tại và không được ghi đè.");
                    return false;
                }
                $dataInsert[] = [
                    'key_language' => $key,
                    'translation'  => $translation,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            Translate::query()->insert($dataInsert);
        }
        return true;
    }
}
