<?php

use Workable\UserTenant\Models\Tenant;

function get_user()
{
    return auth()->user();
}
function get_user_id()
{
    return auth()->user()->id;
}

function get_tenant_id()
{
    return auth()->user()->tenant_id ?? null;
}

function check_tenant_owner()
{
    $user = get_user();
    $tenant = Tenant::query()->where('id', $user->tenant_id)->first();

    return $tenant->id === $user->tenant_id;
}
function get_data_tenant_id()
{
    return [
        "tenant_id" => auth()->user()->tenant_id
    ];
}

