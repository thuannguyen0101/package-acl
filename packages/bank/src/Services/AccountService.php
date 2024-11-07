<?php

namespace Workable\Bank\Services;

use Workable\ACL\Core\Traits\FilterApiTrait;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Services\BaseService;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Models\Account;

class AccountService extends BaseService
{
    use FilterApiTrait;

    public function indexAccount(array $searches = [])
    {
        $filters = $this->getFilterRelationsApi($searches);

        $accounts = $this->buildQuery($filters)->get();

        if ($accounts->count() == 0) {
            return [
                'status'  => ResponseMessageEnum::CODE_NO_CONTENT,
                'message' => __('acl:api.no_data'),
                'accounts' => null
            ];
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl:api.success'),
            'accounts' => $accounts
        ];
    }

    public function createAccount(array $data)
    {
        $user    = auth()->user();

        $account = Account::where('user_id', $user->id)->first();

        if ($account) {
            return [
                'status'  => ResponseMessageEnum::CODE_CONFLICT,
                'message' => __('acl::api.conflict.account_exists'),
                'account' => $account
            ];
        }

        $account       = Account::query()->create([
            'user_id'        => auth()->id(),
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

    public function getAccount($id)
    {
        $account = Account::query()->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $user = auth()->user();

        if ($account->user_id != $user->id) {
            return $this->conflictResponseDefault();
        }

        $account->user = $user;

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl:api.created'),
            'account' => $account
        ];
    }

    public function updateAccount($id, array $data)
    {
        $account = Account::query()->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $user = auth()->user();

        if ($account->user_id != $user->id) {
            return $this->conflictResponseDefault();
        }

        $account->update($data);
        $account->user = $user;

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl:api.updated'),
            'account' => $account
        ];
    }

    public function destroyAccount($id)
    {
        $account = Account::query()->find($id);

        if (!$account) {
            return $this->notFoundResponseDefault();
        }

        $user = auth()->user();

        if ($account->user_id != $user->id) {
            return $this->conflictResponseDefault();
        }

        $account->delete();

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl:api.deleted'),
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

    private function conflictResponseDefault(): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_CONFLICT,
            'message' => __('acl::api.account_not_owned'),
            'account' => null
        ];
    }

    private function buildQuery(array $filters = [])
    {
        $query = Account::query();
        $query = $this->applyBaseRelationsWithFields($query, $filters);
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
