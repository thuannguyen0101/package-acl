<?php

namespace Workable\Bank\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Http\DTO\AccountDTO;
use Workable\Bank\Models\Account;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class AccountService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function indexAccount(array $searches = []): array
    {
        $filters = $this->getFilterRequest($searches);

        $query = $this->buildQuery($filters, is_admin($searches));

        $accounts = $query->get();

        if ($accounts->count() == 0) {
            return [
                'status'   => ResponseMessageEnum::CODE_NO_CONTENT,
                'message'  => __('bank::api.no_data'),
                'accounts' => null
            ];
        }

        return [
            'status'   => ResponseMessageEnum::CODE_OK,
            'message'  => __('bank::api.success'),
            'accounts' => AccountDTO::transform($accounts, $filters)
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
                'message' => __('bank::api.conflict.account_exists'),
                'account' => AccountDTO::transform($account)
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

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('bank::api.created'),
            'account' => AccountDTO::transform($account)
        ];
    }

    public function getAccount(int $id, array $searches = []): array
    {
        $filters = $this->getFilterRequest($searches);
        $query   = $this->buildQuery($filters, is_admin($searches));
        $account = $query->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('bank::api.created'),
            'account' => AccountDTO::transform($account)
        ];
    }

    public function updateAccount($id, array $data): array
    {
        $account = $this->findOne($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $account->update($data);

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('bank::api.updated'),
            'account' => AccountDTO::transform($account)
        ];
    }

    public function destroyAccount($id): array
    {
        $account = $this->findOne($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $account->delete();

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('bank::api.deleted'),
        ];
    }

    private function notFoundResponseDefault(): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
            'message' => __('bank::api.not_found'),
            'account' => null
        ];
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = Account::query();

        if (!$isAdmin) {
            $query->where('tenant_id', get_tenant_id());
        }

        $this->scopeFilter($query, $filters['filters']);

        $this->scopeSort($query, $filters['orders']);

        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }
        return $query;
    }

    public function findOne($id): ?Account
    {
        return Account::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);
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
