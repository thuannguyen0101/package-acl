<?php

namespace Workable\ACL\Core\Traits;

trait FilterApiTrait
{
    protected function getFilterRelationsApi(array $filters = []): array
    {
        $with   = isset($filters['with']) ? explode(",", $filters['with']) : [];
        $fields = $filters['fields'] ?? [];
        if (!empty($fields)) {
            foreach ($fields as $entity => $field) {
                $fields[$entity] = explode(",", $field);
            }
        }

        return [
            'fields' => $fields,
            'with'   => $with
        ];
    }
}
