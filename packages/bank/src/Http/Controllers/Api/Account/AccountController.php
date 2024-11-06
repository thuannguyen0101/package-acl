<?php

namespace Workable\Bank\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Http\Requests\AccountRequest;
use Workable\Bank\Http\Resources\AccountCollection;
use Workable\Bank\Http\Resources\AccountResource;
use Workable\Bank\Models\Account;

class AccountController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('acl_permission:index_account')->only('index');
        $this->middleware('acl_permission:create_account')->only('store');
        $this->middleware('acl_permission:view_account')->only('show');
        $this->middleware('acl_permission:edit_account')->only('update');
        $this->middleware('acl_permission:delete_account')->only('destroy');
    }

    public function index()
    {
        $listAccounts = Account::query()->with(['user'])->get();
        $listAccounts = new AccountCollection($listAccounts);

        return $this->successResponse($listAccounts);
    }

    public function store(AccountRequest $request)
    {
        $user    = auth()->user();
        $item    = $request->validated();
        $account = Account::where('user_id', $user->id)->first();
        if ($account) {
            return $this->errorResponse("Bạn đã có tại khoản vui lòng xóa hủy tài khoản trước khi tạo mới.", ResponseMessageEnum::CODE_CONFLICT);
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

        return $this->createdResponse(new AccountResource($account));
    }

    public function show($id)
    {
        $user = auth()->user();

        $account = Account::query()->find($id);

        if (is_null($account)) {
            return $this->notFoundResponse("Không tìm thấy tài khoản.");
        }
        $account->user = $user;
        $accountRes    = new AccountResource($account);

        return $this->successResponse($accountRes, 'Tìm tài khoản thành công.');
    }

    public function update(int $id, AccountRequest $request)
    {
        $item    = $request->validated();
        $account = Account::query()->find($id);

        if (is_null($account)) {
            return $this->notFoundResponse("Không tìm thấy tài khoản.");
        }

        $account->update($item);

        $accountRes = new AccountResource($account);

        return $this->successResponse($accountRes, 'Tài khoản được cập nhật thành công.');
    }

    public function destroy(int $id)
    {
        $account = Account::query()->find($id);
        if (!$account) {
            return $this->notFoundResponse("Không tìm thấy tài khoản.");
        }
        $account->delete();

        return $this->deletedResponse("Tài khoản được xóa thành công.");
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
