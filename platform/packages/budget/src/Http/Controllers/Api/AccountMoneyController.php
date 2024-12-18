<?php

namespace Workable\Budget\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Budget\Enums\ResponseEnum;
use Workable\Budget\Http\Requests\AccountMoneyListRequest;
use Workable\Budget\Http\Requests\AccountMoneyRequest;
use Workable\Budget\Services\AccountMoneyService;
use Workable\Support\Traits\ResponseHelperTrait;

class AccountMoneyController extends Controller
{
    use ResponseHelperTrait;

    protected $accountMoneyService;

    public function __construct(AccountMoneyService $accountMoneyService)
    {
        $this->middleware('acl_permission:account_money_list')->only('index');
        $this->middleware('acl_permission:account_money_create')->only('store');
        $this->middleware('acl_permission:account_money_update')->only('show');
        $this->middleware('acl_permission:account_money_show')->only('update');
        $this->middleware('acl_permission:account_money_delete')->only('destroy');

        $this->accountMoneyService = $accountMoneyService;
    }

    public function index(AccountMoneyListRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'account_monies' => $account_monies,
            ) = $this->accountMoneyService->index($request->all());

        return $this->respondSuccess($message, $account_monies);
    }

    public function store(AccountMoneyRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'account_money' => $account_money,
            ) = $this->accountMoneyService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $account_money);
    }

    public function show($id, AccountMoneyListRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'account_money' => $account_money,
            ) = $this->accountMoneyService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $account_money);
    }

    public function update(int $id, AccountMoneyRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'account_money' => $account_money,
            ) = $this->accountMoneyService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $account_money);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->accountMoneyService->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }
}
