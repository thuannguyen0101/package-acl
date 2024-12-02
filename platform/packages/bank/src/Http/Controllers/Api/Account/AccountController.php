<?php

namespace Workable\Bank\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Workable\Bank\Enums\ResponseEnum;
use Workable\Bank\Http\Requests\AccountListRequest;
use Workable\Bank\Http\Requests\AccountRequest;
use Workable\Bank\Services\AccountService;
use Workable\Support\Traits\ResponseHelperTrait;

class AccountController extends Controller
{
    use ResponseHelperTrait;

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
        list(
            'status' => $status,
            'message' => $message,
            'accounts' => $accounts,
            ) = $this->accountService->indexAccount($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondSuccess(
                $message,
                $accounts
            );
        }

        return $this->respondSuccess($message, $accounts);
    }

    public function store(AccountRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'account' => $account,
            ) = $this->accountService->createAccount($request->all());

        return $this->respondBase($status, $message, $account);
    }

    public function show($id, AccountListRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'account' => $account,
            ) = $this->accountService->getAccount($id, $request->all());
        
        return $this->respondBase($status, $message, $account);
    }

    public function update(int $id, AccountRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'account' => $account,
            ) = $this->accountService->updateAccount($id, $data);

        return $this->respondBase($status, $message, $account);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->accountService->destroyAccount($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }

    private function respondBase(int $status, string $message, array $account)
    {
        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $account);
    }
}
