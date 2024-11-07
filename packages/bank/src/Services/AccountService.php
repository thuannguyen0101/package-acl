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
        return $this->buildQuery($filters)->get();
    }

    public function createAccount(array $data)
    {
        $user    = auth()->user();
        $account = Account::where('user_id', $user->id)->first();

        if ($account) {
            return [
                'status'  => ResponseMessageEnum::CODE_CONFLICT,
                'message' => 'Bạn đã có tài khoản.',
                'account'    => $account
            ];
        }

        $account = Account::query()->create([
            'user_id'        => auth()->id(),
            'account_number' => $this->createAccountNumber(),
            'account_type'   => $data['account_type'],
            'bank_name'      => $data['bank_name'],
            'branch_name'    => $data['branch_name'],
            'status'         => AccountEnum::STATUS_ACTIVE,
            'created_at'     => now()->format("Y-m-d H:i:s"),
            'updated_at'     => now()->format("Y-m-d H:i:s"),
        ]);

        dd($account);
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
