<?php

namespace Workable\Bank\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Http\Requests\AccountListRequest;
use Workable\Bank\Http\Requests\AccountRequest;
use Workable\Bank\Http\Resources\AccountCollection;
use Workable\Bank\Http\Resources\AccountResource;
use Workable\Bank\Models\Account;
use Workable\Bank\Services\AccountService;

class AccountController extends Controller
{
    use ApiResponseTrait;

    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->middleware('acl_permission:index_account')->only('index');
        $this->middleware('acl_permission:create_account')->only('store');
        $this->middleware('acl_permission:view_account')->only('show');
        $this->middleware('acl_permission:edit_account')->only('update');
        $this->middleware('acl_permission:delete_account')->only('destroy');
        $this->accountService = $accountService;
    }

    public function index(AccountListRequest $request)
    {
        $listAccounts = $this->accountService->indexAccount($request->all());

        $listAccounts = new AccountCollection($listAccounts);

        return $this->successResponse($listAccounts);
    }

    public function store(AccountRequest $request)
    {
        $account = $this->accountService->createAccount($request->all());

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
}
