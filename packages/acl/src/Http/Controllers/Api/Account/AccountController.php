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
        $this->middleware('custom_permission:index_account')->only('index');
        $this->middleware('custom_permission:create_account')->only('store');
        $this->middleware('custom_permission:view_account')->only('show');
        $this->middleware('custom_permission:edit_account')->only('save');
        $this->middleware('custom_permission:delete_account')->only('destroy');
    }

    public function index()
    {
        $listAccounts = Account::query()->with(['user'])->get();
        return AccountResource::collection($listAccounts);
    }

    public function store(AccountRequest $request)
    {
        auth()->user()->can('create_account');

        $user    = auth()->user();
        $item    = $request->validated();
        $account = Account::where('user_id', $user->id)->first();
        if ($account) {
            return AccountResource::handleError("Bạn đã có tại khoản vui lòng xóa hủy tài khoản trước khi tạo mới.");
        }
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

        $accountRes = new AccountResource($account);
        $accountRes->setMessage('Tài khoản được tạo thành công.');

        return $accountRes;
    }

    public function show()
    {
        $user    = auth()->user();
        $account = Account::where('user_id', $user->id)->first();

        if (is_null($account)) {
            return AccountResource::handleError('Không tìm thấy tài khoản.');
        }

        $account->user = $user;

        $accountRes = new AccountResource($account);
        $accountRes->setMessage('Tìm tài khoản thành công.');

        return $accountRes;
    }

    public function save(AccountRequest $request)
    {
        $item    = $request->validated();
        $user    = auth()->user();
        $account = Account::where('user_id', $user->id)->first();

        if (is_null($account)) {
            return AccountResource::handleError('Không tìm thấy tài khoản.');
        }

        $account->update($item);

        $accountRes = new AccountResource($account);
        $accountRes->setMessage('Tài khoản được cập nhật thành công.');
        return $accountRes;
    }

    public function destroy()
    {
        $user    = auth()->user();
        $account = Account::where('user_id', $user->id)->delete();

        if (is_null($account)) {
            return AccountResource::handleError('Không tìm thấy tài khoản.');
        }
        return AccountResource::handleDelete("Tài khoản được xóa thành công.");
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
