<?php

namespace Workable\Navigation\Http\DTO;

use Workable\Budget\Enums\AccountMoneyEnum;
use Workable\Navigation\Enums\CategoryMultiEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class NavigationDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'navigation';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'name'       => $item->name,
            'root_id'    => $item->root_id,
            'parent_id'  => $item->parent_id,
            'url'        => $item->url,
            'type'       => $item->type,
            'icon'       => $item->icon,
            'view_data'  => $item->view_data,
            'label'      => $item->label,
            'layout'     => $item->layout,
            'sort'       => $item->sort,
            'is_auth'    => $item->is_auth,
            'status'     => CategoryMultiEnum::getStatus($item->status),
            'meta'       => json_decode($item->meta, true),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
