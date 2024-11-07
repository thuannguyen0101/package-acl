<?php

namespace Workable\ACL\Services;

use Illuminate\Database\Query\Builder;

abstract class BaseService
{
    protected function applyBaseRelationsWithFields($query, array $filters)
    {
        $relations = $filters['with'] ?? [];
        $fields    = $filters['fields'] ?? [];

        if (empty($relations)) {
            return $query;
        }

        foreach ($relations as $relation) {
            if (isset($fields[$relation])) {
                $relationFields = $fields[$relation];
                $query->with([$relation => function ($q) use ($relationFields) {
                    if (!in_array('id', $relationFields)) {
                        $relationFields[] = 'id';
                    }
                    $q->select($relationFields);
                }]);
            } else {
                $query->with($relation);
            }
        }

        return $query;
    }
}
