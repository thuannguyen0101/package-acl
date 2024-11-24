<?php

namespace Workable\Budget\Http\DTO;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Workable\Budget\Enums\AccountMoneyEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class AccountMoneyDTO extends BaseDTO implements DtoInterface
{
    public static function transform($data, array $relations = []): ?array
    {
        if (!isset($data)) {
            return null;
        }

        if ($data instanceof Collection) {
            return [
                'account_monies' => self::processMultiple($data, $relations),
            ];
        } elseif ($data instanceof LengthAwarePaginator) {
            $links = [
                "first" => $data->path(),
                "last"  => $data->path() . '?page=' . $data->lastPage(),
                "prev"  => $data->previousPageUrl(),
                "next"  => $data->nextPageUrl()
            ];

            return [
                'account_monies' => self::processMultiple($data, $relations),
                'links'          => $links,
                'meta'           => [
                    'current_page' => $data->currentPage(),
                    'from'         => 1,
                    'last_page'    => $data->lastPage(),
                    'links'        => $links,
                    'path'         => $data->path(),
                    'per_page'     => $data->perPage(),
                    'to'           => $data->lastPage(),
                    'total'        => $data->total(),
                ]
            ];
        }

        return [
            'account_money' => self::processSinger($data, $relations)
        ];
    }

    public static function processMultiple($items, array $relations = []): array
    {
        if (empty($items)) {
            return [];
        }
        $listItem = [];

        foreach ($items as $item) {
            $listItem[] = self::processSinger($item, $relations);
        }

        return $listItem;
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'          => $item->id ?? null,
            'tenant_id'   => $item->tenant_id ?? null,
            'name'        => $item->name ?? null,
            'description' => $item->description ?? null,
            'created_at'  => AccountMoneyEnum::convertDate($item->created_at),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
