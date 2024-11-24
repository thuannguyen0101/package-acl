<?php

namespace Workable\Budget\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Budget\Enums\ResponseEnum;
use Workable\Budget\Http\Requests\AccountMoneyListRequest;
use Workable\Budget\Http\Requests\AccountMoneyRequest;
use Workable\Budget\Http\Requests\ExpenseCategoryRequest;
use Workable\Budget\Services\ExpenseCategoryService;
use Workable\Support\Traits\ResponseHelperTrait;

class ExpenseCategoryController extends Controller
{
    use ResponseHelperTrait;

    protected $expenseCategoryService;

    public function __construct(
        ExpenseCategoryService $expenseCategoryService
    )
    {
//        $this->middleware('acl_permission:index_account')->only('index');
//        $this->middleware('acl_permission:create_account')->only('store');
//        $this->middleware('acl_permission:view_account')->only('show');
//        $this->middleware('acl_permission:edit_account')->only('update');
//        $this->middleware('acl_permission:delete_account')->only('destroy');

        $this->expenseCategoryService = $expenseCategoryService;
    }

    public function index(ExpenseCategoryRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'expense_categories' => $expense_categories,
            ) = $this->expenseCategoryService->index($request->all());

        return $this->respondSuccess($message, $expense_categories);
    }

    public function store(ExpenseCategoryRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'expense_category' => $expense_category,
            ) = $this->expenseCategoryService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $expense_category);
    }

    public function show($id, ExpenseCategoryRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'expense_category' => $expense_category,
            ) = $this->expenseCategoryService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $expense_category);
    }

    public function update(int $id, ExpenseCategoryRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'expense_category' => $expense_category,
            ) = $this->expenseCategoryService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $expense_category);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->expenseCategoryService->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }
}
