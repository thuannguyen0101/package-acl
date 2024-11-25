<?php

namespace Workable\Bank\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Models\Account;
use Workable\Support\Traits\FilterBuilderTrait;

class AccountService
{
    use FilterBuilderTrait;

    public function indexAccount(array $searches = []): array
    {
        $filters = $this->getFilterRequest($searches);

        $query = $this->buildQuery($filters);

        if (!is_admin($searches)) {
            $query->where('tenant_id', get_tenant_id());
        }

        $accounts = $query->get();

        if ($accounts->count() == 0) {
            return [
                'status'   => ResponseMessageEnum::CODE_NO_CONTENT,
                'message'  => __('acl::api.no_data'),
                'accounts' => null
            ];
        }

        return [
            'status'   => ResponseMessageEnum::CODE_OK,
            'message'  => __('acl::api.success'),
            'accounts' => $accounts
        ];
    }

    public function createAccount(array $data): array
    {
        $user = get_user();

        $account = Account::query()
            ->where('user_id', $user->id ?? 0)
            ->where('tenant_id', get_tenant_id())
            ->first();

        if ($account) {
            return [
                'status'  => ResponseMessageEnum::CODE_CONFLICT,
                'message' => __('acl::api.conflict.account_exists'),
                'account' => $account
            ];
        }

        $account = Account::query()->create([
            'user_id'        => $user->id ?? 0,
            'tenant_id'      => get_tenant_id($user),
            'account_number' => $this->createAccountNumber(),
            'account_type'   => $data['account_type'],
            'bank_name'      => $data['bank_name'],
            'branch_name'    => $data['branch_name'],
            'status'         => AccountEnum::STATUS_ACTIVE,
            'created_at'     => now()->format("Y-m-d H:i:s"),
            'updated_at'     => now()->format("Y-m-d H:i:s"),
        ]);

        $account->user = $user;

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.created'),
            'account' => $account
        ];
    }

    public function getAccount($id): array
    {
        $account = Account::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $account->load('user');

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.created'),
            'account' => $account
        ];
    }

    public function updateAccount($id, array $data): array
    {
        $account = Account::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $account->update($data);

        $account->load('user');

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.updated'),
            'account' => $account
        ];
    }

    public function destroyAccount($id): array
    {
        $account = Account::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $account->delete();

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.deleted'),
        ];
    }

    private function notFoundResponseDefault(): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
            'message' => __('acl::api.not_found'),
            'account' => null
        ];
    }

//    private function conflictResponseDefault(): array
//    {
//        return [
//            'status'  => ResponseMessageEnum::CODE_CONFLICT,
//            'message' => __('acl::api.account_not_owned'),
//            'account' => null
//        ];
//    }

    private function buildQuery(array $filters = []): Builder
    {
        $query = Account::query();
        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }
        return $query;
    }

    private function createAccountNumber(): string
    {
        $randomString = '';

        for ($i = 0; $i < 14; $i++) {
            $randomString .= rand(0, 9);
        }
        return $randomString;
    }
}
