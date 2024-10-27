<?php

namespace Workable\ACL\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Workable\ACL\Enums\AccountEnum;
use Workable\ACL\Http\Requests\AccountRequest;
use Workable\ACL\Http\Resources\AccountResource;
use Workable\ACL\Models\Account;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom_permission:create_account')->only('store', 'show');
    }

    public function index()
    {

    }

    public function store(AccountRequest $request)
    {
        $item = $request->validated();

        $account = Account::create([
            'user_id'        => auth()->id(),
            'account_number' => $this->createAccountNumber(),
            'account_type'   => $item['account_type'],
            'bank_name'      => $item['bank_name'],
            'branch_name'    => $item['branch_name'],
            'status'         => AccountEnum::STATUS_ACTIVE,
            'created_at'     => now()->format("Y-m-d H:i:s"),
            'updated_at'     => now()->format("Y-m-d H:i:s"),
        ]);

        $user          = auth()->user();
        $user->account = $account;

        $accountRes = new AccountResource($user);
        $accountRes->setMessage('Tài khoản được tạo thành công.');

        return $accountRes;
    }

    public function show($id)
    {
        $account = Account::find($id);
        if (is_null($account)) {
            return AccountResource::handleError('Không tìm thấy tài khoản.');
        }
        $user          = auth()->user();
        $user->account = $account;

        $accountRes = new AccountResource($user);
        $accountRes->setMessage('Tìm nạp tài khoản thành công.');

        return $accountRes;
    }

    public function save($id, AccountRequest $request)
    {
        $item    = $request->validated();
        $account = Account::find($id)->update($item);

        if (is_null($account)) {
            return AccountResource::handleError('Không tìm thấy tài khoản.');
        }
        $user          = auth()->user();
        $user->account = $account;

        $accountRes = new AccountResource($user);
        $accountRes->setMessage('Tài khoản được cập nhật thành công.');
    }

    public function destroy()
    {
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
