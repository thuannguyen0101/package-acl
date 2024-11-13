<?php

namespace Workable\UserTenant\Traits;

trait MessageValidateTrait
{
    protected function getMessage(array $validates = [], string $langConfig = null): array
    {
        if (!$langConfig) {
            return [];
        }

        $messages = [];

        foreach ($validates as $key => $rules) {
            foreach ($rules as $v) {
                $rule                      = explode(":", ($v ?? ''));
                $messages["$key.$rule[0]"] = __($langConfig . '.field_validates.' . $rule[0], [
                    'attribute' => __($langConfig . '.fields.' . $key),
                    'type'      => __($langConfig . '.fields.' . $v),
                    'max'       => $rule[1] ?? 0,
                    'min'       => $rule[1] ?? 0
                ]);
            }
        }
        return $messages;
    }
}
